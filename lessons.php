<?php
session_start();

include 'includes/db.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Fetch all lessons ordered by level and sort_order
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

// Fetch user's completed lessons if logged in
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guitar Lessons - GuitarGhar</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* --- Red and Black Theme Styling --- */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0f0f0f; /* Deep black background */
            color: #e0e0e0;
            margin: 0;
            padding: 0;
        }

        /* Hero / Header Section */
        .lessons-header {
            background-color: #111111;
            padding: 60px 40px;
            border-bottom: 1px solid #2a2a2a;
        }

        .header-container {
            max-width: 1100px;
            margin: 0 auto;
        }

        .lessons-header h1 {
            margin: 0 0 15px 0;
            color: #ffffff;
            font-size: 2.8rem;
            font-weight: bold;
        }

        .lessons-header p {
            color: #888888;
            font-size: 1.2rem;
            margin: 0;
        }

        /* --- Level Tabs (Red Accent) --- */
        .level-tabs {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 40px 0 30px 0;
            overflow-x: auto;
            white-space: nowrap;
            padding-bottom: 10px;
        }

        .tab-btn {
            padding: 12px 30px;
            border: 2px solid #e53935; /* Red theme color */
            background: transparent;
            color: #e53935;
            cursor: pointer;
            border-radius: 30px;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .tab-btn.active, .tab-btn:hover {
            background: #e53935;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(229, 57, 53, 0.3);
        }

        .lesson-tab-content {
            display: none;
            animation: fadeIn 0.4s ease-in-out;
        }

        .lesson-tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* --- Lesson Cards & Grid (Dark Mode) --- */
        .lessons-container {
            padding-bottom: 80px;
        }

        .lesson-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .lesson-card {
            background: #1a1a1a; /* Dark grey card */
            border: 1px solid #333333;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.4);
            display: flex;
            flex-direction: column;
            transition: transform 0.2s ease, border-color 0.2s ease;
        }

        .lesson-card:hover {
            transform: translateY(-5px);
            border-color: #e53935; /* Subtle red border on hover */
        }

        .video-container {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 aspect ratio */
            height: 0;
            background: #000;
            border-bottom: 1px solid #333;
        }

.video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        /* --- Locked video overlay for logged-out users --- */
        .login-lock-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 15, 15, 0.72);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            z-index: 2;
        }

        .lock-icon {
            font-size: 32px;
            line-height: 1;
        }

        .login-to-watch {
            background: #e53935;
            color: #ffffff;
            padding: 10px 22px;
            border-radius: 6px;
            font-weight: bold;
            text-decoration: none;
            font-size: 15px;
            transition: background 0.2s;
        }

        .login-to-watch:hover {
            background: #c62828;
        }

        .card-content {
            padding: 25px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .lesson-num {
            font-size: 13px;
            color: #e53935; /* Red theme color */
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 10px;
        }

        .lesson-title {
            font-size: 20px;
            color: #ffffff;
            margin: 0 0 12px 0;
            line-height: 1.4;
            min-height: 28px; /* Maintain structure even when empty */
        }

        .lesson-desc {
            font-size: 15px;
            color: #a0a0a0;
            line-height: 1.6;
            margin-bottom: 25px;
            flex-grow: 1;
        }

        /* --- Progress Controls (Green/Red Buttons) --- */
        .progress-controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid #333333;
        }

        .btn-mark-done {
            background: #28a745; /* Success Green */
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            font-weight: bold;
            text-decoration: none;
            text-align: center;
            transition: background 0.2s;
            width: 100%;
            display: block;
            box-sizing: border-box;
        }

        .btn-mark-done:hover {
            background: #218838;
        }

        .badge-completed {
            color: #28a745;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(40, 167, 69, 0.15);
            padding: 10px 15px;
            border-radius: 6px;
            font-size: 15px;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .btn-undo {
            background: none;
            border: none;
            color: #e53935; /* Red theme color for undo */
            cursor: pointer;
            text-decoration: underline;
            font-size: 14px;
            padding: 8px 12px;
            font-weight: bold;
        }

        .btn-undo:hover {
            color: #ff5252;
        }

        /* --- Responsive Layout --- */
        @media (max-width: 900px) {
            .lesson-grid {
                grid-template-columns: 1fr;
                max-width: 600px;
            }
            .lessons-header {
                padding: 40px 20px;
            }
        }

        @media (max-width: 600px) {
            .level-tabs {
                justify-content: flex-start;
                padding: 0 20px;
            }
            .tab-btn {
                padding: 10px 20px;
                font-size: 15px;
            }
            .lessons-header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

    <?php include 'includes/navbar.php'; ?>

    <div class="lessons-header">
        <div class="header-container">
            <h1>Structured Guitar Lessons</h1>
            <p>Follow our step-by-step path from picking up the guitar to playing advanced solos.</p>
        </div>
    </div>

    <div class="level-tabs">
        <button type="button" class="tab-btn active" onclick="showTab('beginner')">
            Beginner (10)
        </button>
        <button type="button" class="tab-btn" onclick="showTab('intermediate')">
            Intermediate (10)
        </button>
        <button type="button" class="tab-btn" onclick="showTab('advanced')">
            Advanced (10)
        </button>
    </div>

    <div class="lessons-container">
        <?php foreach (['beginner', 'intermediate', 'advanced'] as $index => $level): ?>
            <div id="<?= $level ?>" class="lesson-tab-content <?= $index === 0 ? 'active' : '' ?>">
                <div class="lesson-grid">
                    <?php 
                    $total_slots = 10;
                    for ($i = 0; $i < $total_slots; $i++): 
                        $lesson_num = $i + 1;
                        
                        // Default empty values for the invalid place
                        $title = "";
                        $desc = "";
                        $video_url = "";
                        $lesson_id = 0;
                        $is_completed = false;
                        $has_data = false;

                        // Check if lesson exists in the database for this slot
                        if (isset($lessons_by_level[$level][$i])) {
                            $lesson = $lessons_by_level[$level][$i];
                            $title = $lesson['title'];
                            $desc = $lesson['description'];
                            $video_url = $lesson['video_url'];
                            $lesson_id = $lesson['id'];
                            $is_completed = in_array($lesson['id'], $completed_lessons);
                            $has_data = true;
                        }
                    ?>
<div class="lesson-card" <?= $has_data ? 'data-lesson-id="'.$lesson_id.'"' : '' ?>>
<div class="video-container">
                                <?php if ($user_id && $has_data && !empty($video_url)): ?>
                                    <!-- Logged-in users see the embedded video -->
                                    <iframe src="<?= htmlspecialchars($video_url) ?>" allowfullscreen></iframe>
                                <?php elseif ($has_data && !empty($video_url)): ?>
                                    <!-- Logged-out users see a locked thumbnail -->
                                    <img src="https://img.youtube.com/vi/<?= htmlspecialchars(preg_replace('/.*\/([\w-]+)(\?.*)?$/', '$1', $video_url)) ?>/hqdefault.jpg" 
                                         alt="Lesson thumbnail" 
                                         style="position:absolute; top:0; left:0; width:100%; height:100%; object-fit:cover;">
                                    <div class="login-lock-overlay">
                                        <span class="lock-icon">🔒</span>
                                        <a href="login.php" class="login-to-watch">Login to Watch</a>
                                    </div>
                                <?php else: ?>
                                    <!-- No video assigned: show a clean placeholder -->
                                    <div style="position:absolute; top:0; left:0; width:100%; height:100%; background:linear-gradient(135deg,#1a1a1a,#111111); display:flex; align-items:center; justify-content:center; color:#666; font-size:15px; text-align:center; padding:20px; box-sizing:border-box;">
                                        🎸 Video coming soon
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-content">
                                <div class="lesson-num">Lesson <?= $lesson_num ?></div>
                                <h3 class="lesson-title"><?= htmlspecialchars($title) ?></h3>
                                <p class="lesson-desc"><?= nl2br(htmlspecialchars($desc)) ?></p>
                                
                                <div class="progress-controls" id="controls-<?= $lesson_id ?>">
                                    <?php if (!$has_data): ?>
                                        <button type="button" class="btn-mark-done" style="background: #333333; color: #666666; cursor: not-allowed;" disabled>Mark as Done ✔️</button>
                                    <?php elseif (!$user_id): ?>
                                        <a href="login.php" class="btn-mark-done">Mark as Done ✔️</a>
                                    <?php else: ?>
                                        <?php if ($is_completed): ?>
                                            <span class="badge-completed">✔️ Completed</span>
                                            <button type="button" class="btn-undo" onclick="toggleProgress(<?= $lesson_id ?>, 0)">Undo</button>
                                        <?php else: ?>
                                            <button type="button" class="btn-mark-done" onclick="toggleProgress(<?= $lesson_id ?>, 1)">Mark as Done ✔️</button>
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
        // Tab switching logic
        function showTab(level) {
            document.querySelectorAll('.lesson-tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            document.getElementById(level).classList.add('active');
            event.currentTarget.classList.add('active');
        }

// Progress toggle logic (client-side only)
        function toggleProgress(lessonId, status) {
            if(lessonId === 0) return; // Prevent clicking on invalid place items

            // Update UI instantly
            updateCardUI(lessonId, status);
        }

        // Update Button UI without reloading
        function updateCardUI(lessonId, status) {
            const controlsDiv = document.getElementById('controls-' + lessonId);
            if (!controlsDiv) return;
            
            if (status === 1) {
                controlsDiv.innerHTML = `
                    <span class="badge-completed">✔️ Completed</span>
                    <button type="button" class="btn-undo" onclick="toggleProgress(${lessonId}, 0)">Undo</button>
                `;
            } else {
                controlsDiv.innerHTML = `
                    <button type="button" class="btn-mark-done" onclick="toggleProgress(${lessonId}, 1)">Mark as Done ✔️</button>
                `;
            }
        }
    </script>
</body>
</html>