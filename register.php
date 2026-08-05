<?php
require_once __DIR__ . '/includes/paths.php';
session_start();
include 'includes/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    $email     = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password  = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm   = isset($_POST['confirm']) ? $_POST['confirm'] : '';

    if ($full_name === '' || $email === '' || $password === '' || $confirm === '') {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $check_sql  = 'SELECT id FROM users WHERE email = ?';
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, 's', $email);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            $error = 'An account with this email already exists.';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $sql    = 'INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)';
            $stmt   = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'sss', $full_name, $email, $hashed);

            if (mysqli_stmt_execute($stmt)) {
                $success = 'Account created successfully! You can now login.';
            } else {
                $error = 'Something went wrong. Please try again.';
            }
        }
    }
}

$page_title = 'Register | GuitarGhar';
$page_css = 'css/register.css';
include 'includes/navbar.php';
?>

<section id="register-page">

    <div class="register-left">
        <img
            src="<?php echo htmlspecialchars(url('img/guitar.png')); ?>"
            alt="Guitar"
            class="register-guitar"
        >
        <h2>Join GuitarGhar</h2>
        <p>
            Create a free account to save your guitar builds,
            track your lesson progress and get personalised
            AI recommendations.
        </p>
        <ul class="register-perks">
            <li>
                <i class="fa-solid fa-check"></i>
                Save your custom guitar builds
            </li>
            <li>
                <i class="fa-solid fa-check"></i>
                Track your lesson progress
            </li>
            <li>
                <i class="fa-solid fa-check"></i>
                Get AI guitar recommendations
            </li>
            <li>
                <i class="fa-solid fa-check"></i>
                Use the real-time guitar tuner
            </li>
        </ul>
    </div>

    <div class="register-right">

        <div class="register-box">

            <h3>Create Your Account</h3>
            <p class="register-sub">
                Already have an account?
                <a href="<?php echo htmlspecialchars(url('login.php')); ?>">Login here</a>
            </p>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">

                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input
                        type="text"
                        id="full_name"
                        name="full_name"
                        placeholder="Your full name"
                        value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="you@email.com"
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-wrap">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Minimum 6 characters"
                            required
                        >
                        <i class="fa-solid fa-eye toggle-pass" onclick="togglePassword('password')"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm">Confirm Password</label>
                    <div class="password-wrap">
                        <input
                            type="password"
                            id="confirm"
                            name="confirm"
                            placeholder="Repeat your password"
                            required
                        >
                        <i class="fa-solid fa-eye toggle-pass" onclick="togglePassword('confirm')"></i>
                    </div>
                </div>

                <button type="submit" class="register-btn">
                    Create My Account
                </button>

            </form>

        </div>

    </div>

</section>

<?php include 'includes/footer.php'; ?>

<script>
function togglePassword(fieldId) {
    var field = document.getElementById(fieldId);
    if (field.type === 'password') {
        field.type = 'text';
    } else {
        field.type = 'password';
    }
}
</script>
