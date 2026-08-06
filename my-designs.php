<?php
require_once __DIR__ . '/includes/paths.php';
require_once __DIR__ . '/includes/mysqli_compat.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: " . url("login.php"));
    exit();
}

include "includes/db.php";

$user_id = $_SESSION["user_id"];

$sql = "SELECT * FROM guitar_builds
        WHERE user_id = ?
        ORDER BY saved_at DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$builds = gg_stmt_fetch_all($stmt);
mysqli_stmt_close($stmt);

$SHAPE_LABELS = [
    'strat'    => 'Stratocaster',
    'lespaul'  => 'Les Paul',
    'sg'       => 'SG',
    'acoustic' => 'Acoustic',
];
$page_title = 'My Designs | GuitarGhar';
$page_css = 'css/my-designs.css';
include 'includes/navbar.php';
?>

<section class="designs-page">

    <div class="page-header designs-header">
        <h1>My Guitar Designs</h1>
        <p>All your saved custom guitar builds.</p>
    </div>

    <div class="container">

        <div class="design-grid" id="design-grid">

        <?php if (count($builds) > 0): ?>

            <?php foreach ($builds as $build): ?>

            <div class="design-card" id="card-<?php echo $build['id']; ?>">

                <div class="card-header">
                    <h2>
                        <?php
                        $shapeKey = $build["shape"];
                        echo htmlspecialchars(
                            isset($SHAPE_LABELS[$shapeKey]) ? $SHAPE_LABELS[$shapeKey] : ucfirst($shapeKey),
                            ENT_QUOTES,
                            'UTF-8'
                        );
                    ?>
                    </h2>
                    <div class="header-right">
                        <span
                            class="color-dot"
                            style="background:<?php echo htmlspecialchars($build["color"]); ?>;">
                        </span>
                        <button 
                            class="delete-btn" 
                            onclick="deleteBuild(<?php echo $build['id']; ?>)"
                            title="Delete Design">
                            &times;
                        </button>
                    </div>
                </div>

                <!-- Guitar Preview Canvas -->
                <div class="card-preview">
                    <canvas 
                        class="design-canvas" 
                        width="300" 
                        height="380"
                        data-shape="<?php echo htmlspecialchars($build["shape"]); ?>"
                        data-color="<?php echo htmlspecialchars($build["color"]); ?>"
                    ></canvas>
                </div>

                <div class="spec">
                    <strong>Finish</strong>
                    <?php echo htmlspecialchars($build["color"]); ?>
                </div>

                <div class="spec">
                    <strong>Body Wood</strong>
                    <?php echo htmlspecialchars($build["body_wood"]); ?>
                </div>

                <div class="spec">
                    <strong>Neck Wood</strong>
                    <?php echo htmlspecialchars($build["neck_wood"]); ?>
                </div>

                <div class="spec">
                    <strong>Fingerboard</strong>
                    <?php echo htmlspecialchars($build["fingerboard"]); ?>
                </div>

                <div class="spec">
                    <strong>Pickups</strong>
                    <?php echo htmlspecialchars($build["pickups"]); ?>
                </div>

                <div class="spec">
                    <strong>Bridge</strong>
                    <?php echo htmlspecialchars($build["bridge"]); ?>
                </div>

                <div class="spec">
                    <strong>Hardware</strong>
                    <?php echo htmlspecialchars($build["hardware"]); ?>
                </div>

                <div class="card-footer">
                    <span class="saved-date">
                        Saved on <?php echo date("d M Y", strtotime($build["saved_at"])); ?>
                    </span>
                    <button class="delete-link-btn" onclick="deleteBuild(<?php echo $build['id']; ?>)">
                        Delete
                    </button>
                </div>

            </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="empty-card">
                <h2>No saved designs yet.</h2>
                <p>
                    Build your first custom guitar and click
                    <strong>Save My Build</strong>.
                </p>
                <a href="<?php echo htmlspecialchars(url('builder.php')); ?>" class="build-btn">
                    Go to Builder
                </a>
            </div>

        <?php endif; ?>

        </div>

    </div>

</section>

<!-- Canvas Rendering Script -->
<script>
var IMAGE_PATHS = {
    strat: appUrl('img/strat.png'),
    lespaul: appUrl('img/lespaul.png'),
    sg: appUrl('img/sg.png'),
    acoustic: appUrl('img/acoustic.png')
};

var loadedImages = {};

function renderAllDesigns() {
    var canvases = document.querySelectorAll('.design-canvas');
    canvases.forEach(function(canvas) {
        var shape = canvas.getAttribute('data-shape');
        var color = canvas.getAttribute('data-color');
        drawDesignOnCanvas(canvas, shape, color);
    });
}

function preloadAndRender() {
    var shapes = Object.keys(IMAGE_PATHS);
    var loaded = 0;

    shapes.forEach(function(shape) {
        var img = new Image();
        img.crossOrigin = 'anonymous';
        img.onload = function() {
            loadedImages[shape] = img;
            loaded++;
            if (loaded === shapes.length) renderAllDesigns();
        };
        img.onerror = function() {
            loaded++;
            if (loaded === shapes.length) renderAllDesigns();
        };
        img.src = IMAGE_PATHS[shape];
    });
}

function drawDesignOnCanvas(canvas, shape, currentColor) {
    var ctx = canvas.getContext('2d');
    var W = canvas.width;
    var H = canvas.height;

    ctx.clearRect(0, 0, W, H);

    var img = loadedImages[shape];
    if (!img) return;

    var imgRatio = img.naturalWidth / img.naturalHeight;
    var canvasRatio = W / H;
    var drawW, drawH, drawX, drawY;

    if (imgRatio > canvasRatio) {
        drawW = W * 0.90;
        drawH = drawW / imgRatio;
    } else {
        drawH = H * 0.90;
        drawW = drawH * imgRatio;
    }

    drawX = (W - drawW) / 2;
    drawY = (H - drawH) / 2;

    ctx.drawImage(img, drawX, drawY, drawW, drawH);

    var imageData = ctx.getImageData(0, 0, W, H);
    var data = imageData.data;

    var origR = 170;
    var newR = parseInt(currentColor.slice(1, 3), 16);
    var newG = parseInt(currentColor.slice(3, 5), 16);
    var newB = parseInt(currentColor.slice(5, 7), 16);
    var intensity = 0.85;

    var bodyTopCutoff = drawY + (drawH * 0.40);

    for (var i = 0; i < data.length; i += 4) {
        var pixelIndex = i / 4;
        var y = Math.floor(pixelIndex / W);

        if (y < bodyTopCutoff) continue;

        var pr = data[i];
        var pg = data[i + 1];
        var pb = data[i + 2];
        var pa = data[i + 3];

        if (pa < 10) continue;

        if (pr > 200 && pg > 200 && pb > 200) continue;
        var brightness = (pr + pg + pb) / 3;
        if (brightness < 25) continue;

        var isBodyPixel = (
            pr > 80 &&
            pr > pg * 1.2 &&
            pr > pb * 1.2 &&
            (pr - Math.max(pg, pb)) > 20
        );

        if (!isBodyPixel) continue;

        var bodyBrightness = pr / origR;
        bodyBrightness = Math.max(0.15, Math.min(1.8, bodyBrightness));

        var tintR = Math.min(255, newR * bodyBrightness);
        var tintG = Math.min(255, newG * bodyBrightness);
        var tintB = Math.min(255, newB * bodyBrightness);

        data[i]     = Math.round(pr * (1 - intensity) + tintR * intensity);
        data[i + 1] = Math.round(pg * (1 - intensity) + tintG * intensity);
        data[i + 2] = Math.round(pb * (1 - intensity) + tintB * intensity);
    }

    ctx.putImageData(imageData, 0, 0);
}

// Delete Build AJAX Function
function deleteBuild(buildId) {
    if (!confirm("Are you sure you want to delete this custom guitar design?")) {
        return;
    }

    var formData = new FormData();
    formData.append("build_id", buildId);

    fetch(appUrl("delete_build.php"), {
        method: "POST",
        body: formData
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        if (data.success) {
            var card = document.getElementById("card-" + buildId);
            if (card) {
                card.style.opacity = "0";
                card.style.transform = "scale(0.9)";
                card.style.transition = "all 0.3s ease";
                
                setTimeout(function() {
                    card.remove();
                    
                    // Show empty state if no designs left
                    var remainingCards = document.querySelectorAll(".design-card");
                    if (remainingCards.length === 0) {
                        document.getElementById("design-grid").innerHTML = `
                            <div class="empty-card">
                                <h2>No saved designs yet.</h2>
                                <p>Build your first custom guitar and click <strong>Save My Build</strong>.</p>
                                <a href="<?php echo htmlspecialchars(url('builder.php')); ?>" class="build-btn">Go to Builder</a>
                            </div>
                        `;
                    }
                }, 300);
            }
        } else {
            alert("Error: " + (data.message || "Failed to delete design."));
        }
    })
    .catch(function(err) {
        console.error(err);
        alert("An error occurred while deleting.");
    });
}

document.addEventListener('DOMContentLoaded', preloadAndRender);
</script>

<?php include "includes/footer.php"; ?>
