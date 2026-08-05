<?php
session_start();

include 'includes/db.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$lessons_query = "SELECT * FROM lessons ORDER BY FIELD(level, 'beginner', 'intermediate', 'advanced'), sort_order ASC";
$lessons_result = $conn->query($lessons_query);

$lessons_by_level = [
    'beginner' => [],
    'intermediate' => [],
    'advanced' => []
];

if ($lessons_result && $lessons_result->num_rows > 0) {
    while ($row = $lessons_result->fetch_assoc()) {
        $lessons_by_level[$row['level']][] = $row;
    }
}

$completed_lessons = [];
if ($user_id) {
    $progress_query = "SELECT lesson_id FROM lesson_progress WHERE user_id = ? AND completed = 1";
    $stmt = $conn->prepare($progress_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $completed_lessons[] = $row['lesson_id'];
    }
    $stmt->close();
}

$page_title = 'Guitar Lessons | GuitarGhar';
$page_css = '/guitarghar/css/lessons.css';
include 'includes/navbar.php';
?>

<div class="page-header lessons-header">
    <h1>Structured Guitar Lessons</h1>
    <p>Follow our step-by-step path from picking up the guitar to playing advanced solos.</p>
</div>

<div class="level-tabs">
    <button type="button" class="tab-btn active" onclick="showTab('beginner', this)">
        Beginner (<?php echo count($lessons_by_level['beginner']); ?>)
    </button>
    <button type="button" class="tab-btn" onclick="showTab('intermediate', this)">
        Intermediate (<?php echo count($lessons_by_level['intermediate']); ?>)
    </button>
    <button type="button" class="tab-btn" onclick="showTab('advanced', this)">
        Advanced (<?php echo count($lessons_by_level['advanced']); ?>)
    </button>
</div>

<div class="lessons-container">
    <?php foreach (['beginner', 'intermediate', 'advanced'] as $index => $level): ?>
        <div id="<?php echo $level; ?>" class="lesson-tab-content <?php echo $index === 0 ? 'active' : ''; ?>">
            <div class="lesson-grid">
                <?php
                $total_slots = 10;
                for ($i = 0; $i < $total_slots; $i++):
                    $lesson_num = $i + 1;
                    $title = '';
                    $desc = '';
                    $video_url = '';
                    $lesson_code = '';
                    $is_completed = false;
                    $has_data = false;

                    if (isset($lessons_by_level[$level][$i])) {
                        $lesson = $lessons_by_level[$level][$i];
                        $title = $lesson['title'];
                        $desc = $lesson['description'];
                        $video_url = $lesson['video_url'];
                        $lesson_code = $lesson['lesson_id'];
                        $is_completed = in_array($lesson_code, $completed_lessons, true);
                        $has_data = true;
                    }
                ?>
                <div class="lesson-card" <?php echo $has_data ? 'data-lesson-id="' . htmlspecialchars($lesson_code) . '"' : ''; ?>>
                    <div class="video-container">
                        <?php if ($user_id && $has_data && !empty($video_url)): ?>
                            <iframe src="<?php echo htmlspecialchars($video_url); ?>" allowfullscreen></iframe>
                        <?php elseif ($has_data && !empty($video_url)): ?>
                            <?php
                            $yt_id = preg_replace('/.*\/([\w-]+)(\?.*)?$/', '$1', $video_url);
                            ?>
                            <img src="https://img.youtube.com/vi/<?php echo htmlspecialchars($yt_id); ?>/hqdefault.jpg"
                                 alt="Lesson thumbnail"
                                 style="position:absolute; top:0; left:0; width:100%; height:100%; object-fit:cover;">
                            <div class="login-lock-overlay">
                                <span class="lock-icon">&#128274;</span>
                                <a href="/guitarghar/login.php" class="login-to-watch">Login to Watch</a>
                            </div>
                        <?php else: ?>
                            <div class="video-placeholder">Video coming soon</div>
                        <?php endif; ?>
                    </div>
                    <div class="card-content">
                        <div class="lesson-num">Lesson <?php echo $lesson_num; ?></div>
                        <h3 class="lesson-title"><?php echo htmlspecialchars($title); ?></h3>
                        <p class="lesson-desc"><?php echo nl2br(htmlspecialchars($desc)); ?></p>

                        <div class="progress-controls" id="controls-<?php echo htmlspecialchars($lesson_code ?: ('empty-' . $level . '-' . $i)); ?>">
                            <?php if (!$has_data): ?>
                                <button type="button" class="btn-mark-done" disabled>Mark as Done</button>
                            <?php elseif (!$user_id): ?>
                                <a href="/guitarghar/login.php" class="btn-mark-done">Mark as Done</a>
                            <?php else: ?>
                                <?php if ($is_completed): ?>
                                    <span class="badge-completed">Completed</span>
                                    <button type="button" class="btn-undo" onclick="toggleProgress('<?php echo htmlspecialchars($lesson_code); ?>', 0)">Undo</button>
                                <?php else: ?>
                                    <button type="button" class="btn-mark-done" onclick="toggleProgress('<?php echo htmlspecialchars($lesson_code); ?>', 1)">Mark as Done</button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
function showTab(level, btn) {
    document.querySelectorAll('.lesson-tab-content').forEach(function(tab) {
        tab.classList.remove('active');
    });
    document.querySelectorAll('.tab-btn').forEach(function(b) {
        b.classList.remove('active');
    });
    document.getElementById(level).classList.add('active');
    if (btn) btn.classList.add('active');
}

function toggleProgress(lessonId, status) {
    if (!lessonId) return;

    var formData = new FormData();
    formData.append('lesson_id', lessonId);
    formData.append('completed', status);

    fetch('/guitarghar/save_progress.php', {
        method: 'POST',
        body: formData
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            updateCardUI(lessonId, status);
        } else {
            alert(data.error || 'Could not save progress.');
        }
    })
    .catch(function() {
        alert('Network error. Please try again.');
    });
}

function updateCardUI(lessonId, status) {
    var controlsDiv = document.getElementById('controls-' + lessonId);
    if (!controlsDiv) return;
    controlsDiv.innerHTML = '';

    if (status === 1) {
        var badge = document.createElement('span');
        badge.className = 'badge-completed';
        badge.textContent = 'Completed';
        var undo = document.createElement('button');
        undo.type = 'button';
        undo.className = 'btn-undo';
        undo.textContent = 'Undo';
        undo.addEventListener('click', function() { toggleProgress(lessonId, 0); });
        controlsDiv.appendChild(badge);
        controlsDiv.appendChild(undo);
    } else {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn-mark-done';
        btn.textContent = 'Mark as Done';
        btn.addEventListener('click', function() { toggleProgress(lessonId, 1); });
        controlsDiv.appendChild(btn);
    }
}
</script>

<?php include 'includes/footer.php'; ?>
