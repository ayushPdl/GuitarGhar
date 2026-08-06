<?php
require_once __DIR__ . '/includes/paths.php';
require_once __DIR__ . '/includes/mysqli_compat.php';
session_start();
include 'includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($email === '' || $password === '') {
        $error = 'Please fill in all fields.';
    } else {
        $sql  = 'SELECT id, full_name, password FROM users WHERE email = ?';
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            $error = 'Something went wrong. Please try again.';
        } else {
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            $user = gg_stmt_fetch_one($stmt);
            mysqli_stmt_close($stmt);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email']     = $email;
                header('Location: ' . url('index.php'));
                exit();
            }

            $error = 'Incorrect email or password. Please try again.';
        }
    }
}

$page_title = 'Login | GuitarGhar';
$page_css = 'css/login.css';
include 'includes/navbar.php';
?>

<section id="login-page">

    <div class="login-left">
        <img
            src="<?php echo htmlspecialchars(url('img/guitar.png')); ?>"
            alt="Guitar"
            class="login-guitar"
        >
        <h2>Welcome Back</h2>
        <p>
            Login to access your saved guitar builds,
            lesson progress and personalised recommendations.
        </p>
    </div>

    <div class="login-right">

        <div class="login-box">

            <h3>Login to GuitarGhar</h3>
            <p class="login-sub">
                Don't have an account?
                <a href="<?php echo htmlspecialchars(url('register.php')); ?>">Register free</a>
            </p>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">

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
                            placeholder="Your password"
                            required
                        >
                        <i class="fa-solid fa-eye toggle-pass" onclick="togglePassword('password')"></i>
                    </div>
                </div>

                <button type="submit" class="login-btn">
                    Login
                </button>

            </form>

        </div>

    </div>

</section>
<?php
$page_scripts = <<<'JS'
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
JS;
include 'includes/footer.php';
?>