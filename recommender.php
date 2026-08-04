<?php include 'includes/navbar.php'; ?>
<link rel="stylesheet" href="/guitarghar/css/recommender.css">


    <div class="rec-header">
        <h1>AI Guitar Recommender</h1>
        <p>
            Answer 4 quick questions and our AI will suggest
            the perfect guitar matched exactly to your budget.
        </p>
    </div>

    <div class="rec-layout">

        <div class="rec-form-wrap">
            <div class="rec-form-box">

                <h4>Tell us about yourself</h4>

                <div class="form-group">
                    <label for="r-skill">Your Skill Level</label>
                    <select id="r-skill">
                        <option value="">Select your level</option>
                        <option value="Complete Beginner">Complete Beginner</option>
                        <option value="Beginner">Beginner</option>
                        <option value="Intermediate">Intermediate</option>
                        <option value="Advanced">Advanced</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="r-genre">Favourite Music Genre</label>
                    <select id="r-genre">
                        <option value="">Select genre</option>
                        <option value="Rock / Metal">Rock / Metal</option>
                        <option value="Pop / Folk">Pop / Folk</option>
                        <option value="Classical">Classical</option>
                        <option value="Jazz / Blues">Jazz / Blues</option>
                        <option value="Nepali Folk / Lok Dohori">Nepali Folk / Lok Dohori</option>
                        <option value="Bollywood / Pop">Bollywood / Pop</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="r-type">Acoustic or Electric?</label>
                    <select id="r-type">
                        <option value="">Select preference</option>
                        <option value="Acoustic">Acoustic</option>
                        <option value="Electric">Electric</option>
                        <option value="No preference">No preference</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="r-budget">Your Budget (NPR)</label>
                    <select id="r-budget">
                        <option value="">Select budget</option>
                        <option value="Under NPR 10,000">Under NPR 10,000</option>
                        <option value="NPR 10,000 to 25,000">NPR 10,000 to 25,000</option>
                        <option value="NPR 25,000 to 50,000">NPR 25,000 to 50,000</option>
                        <option value="Above NPR 50,000">Above NPR 50,000</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="r-extra">Anything else? (optional)</label>
                    <textarea
                        id="r-extra"
                        placeholder="e.g. I want to play like Pragyan Gyawali, or I have small hands..."
                    ></textarea>
                </div>

                <button class="rec-submit-btn" onclick="getRecommendation()">
                    Get My Recommendation
                </button>

            </div>
        </div>

        <div class="rec-result-wrap">
            <div class="rec-placeholder" id="rec-placeholder">
                <i class="fa-solid fa-robot"></i>
                <h4>Your recommendation will appear here</h4>
                <p>Fill in the form and click the button.</p>
            </div>
            <div class="rec-loading" id="rec-loading">
                <div class="loading-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <p>Our AI is thinking...</p>
            </div>

<!-- Login prompt shown when not logged in and user clicks button -->
<div class="rec-login-prompt" id="rec-login-prompt">
    <i class="fa-solid fa-lock"></i>
    <h4>Login Required</h4>
    <p>You need a GuitarGhar account to get AI recommendations.</p>
    <div class="prompt-btns">
        <a href="/guitarghar/login.php" class="btn-red btn-lg">Login</a>
        <a href="/guitarghar/register.php" class="btn-outline btn-lg">Register Free</a>
    </div>
</div>
            
            <div class="rec-result-box" id="rec-result-box">
                <div class="rec-result-label">
                    <i class="fa-solid fa-robot"></i>
                    Your Personalised Recommendation
                </div>
                <div class="rec-result-text" id="rec-result-text"></div>
                <button class="rec-btn-outline" onclick="resetRecommender()">
                    <i class="fa-solid fa-rotate-left"></i>
                    Try Again
                </button>
            </div>
        </div>


    </div>



<script>
function getRecommendation() {

    <?php if (!isset($_SESSION['user_id'])): ?>
    showLoginPrompt();
    return;
    <?php endif; ?>

    var skill  = document.getElementById('r-skill').value;
    var genre  = document.getElementById('r-genre').value;
    var type   = document.getElementById('r-type').value;
    var budget = document.getElementById('r-budget').value;
    var extra  = document.getElementById('r-extra').value;

    if (!skill || !genre || !type || !budget) {
        alert('Please answer all four questions.');
        return;
    }

    // Show loading
    document.getElementById('rec-placeholder').style.display = 'none';
    document.getElementById('rec-result-box').style.display  = 'none';
    document.getElementById('rec-loading').style.display     = 'flex';

    // Send to PHP
    var formData = new FormData();
    formData.append('skill',  skill);
    formData.append('genre',  genre);
    formData.append('type',   type);
    formData.append('budget', budget);
    formData.append('extra',  extra);

    fetch('/guitarghar/recommender_api.php', {
        method: 'POST',
        body:   formData
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        document.getElementById('rec-loading').style.display    = 'none';
        
        if (data.error) {
            // Show error message
            document.getElementById('rec-result-box').style.display = 'block';
            document.getElementById('rec-result-text').innerHTML = 
                '<div style="color: #721c24; background: #f8d7da; padding: 15px; border-radius: 8px;">' +
                '<strong>âš ï¸ Sorry!</strong><br><br>' +
                data.error + '<br><br>' +
                'Please try again in a moment. The AI might be busy right now.' +
                '</div>';
        } else {
            document.getElementById('rec-result-box').style.display = 'block';
            // Convert newlines to <br> tags for better display
            var formattedResult = data.result.replace(/\n/g, '<br>');
            document.getElementById('rec-result-text').innerHTML = formattedResult;
        }
    })
    .catch(function(error) {
        document.getElementById('rec-loading').style.display    = 'none';
        document.getElementById('rec-placeholder').style.display = 'flex';
        alert('Network error: ' + error.message + '. Please check your connection and try again.');
    });
}

function resetRecommender() {
    document.getElementById('rec-result-box').style.display  = 'none';
    document.getElementById('rec-placeholder').style.display = 'flex';
    document.getElementById('r-skill').value  = '';
    document.getElementById('r-genre').value  = '';
    document.getElementById('r-type').value   = '';
    document.getElementById('r-budget').value = '';
    document.getElementById('r-extra').value  = '';
}

function showLoginPrompt() {
    document.getElementById('rec-placeholder').style.display = 'none';
    document.getElementById('rec-loading').style.display     = 'none';
    document.getElementById('rec-result-box').style.display  = 'none';
    document.getElementById('rec-login-prompt').style.display = 'flex';
}

</script>

<?php include 'includes/footer.php'; ?>
