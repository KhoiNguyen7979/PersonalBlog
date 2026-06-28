<link rel="stylesheet" href="public/css/signup.css">

<div class="container">

    <!-- LEFT -->
    <div class="left-panel">
        <h1>Welcome to</h1>
        <a href="index.php"><img src="public/images/bloggerZ.png" class="logo" alt="BloggerZ Logo"></a>
    </div>

    <!-- RIGHT -->
    <div class="right-panel">

        <h2>Sign Up</h2>

        <form action="php/register.php" method="POST">

            <input type="text" name="fullname" placeholder="Full name" required>

            <input type="email" name="email" placeholder="Email" required>

            <input type="text" name="username" placeholder="Username" required>

            <input type="password" name="password" placeholder="Password" required>

            <input type="password" name="confirm_password" placeholder="Repeat Password" required>

            <div class="agree">
                <input type="checkbox" name="agree" required>
                <span>
                    I agree to the
                    <a href="#">Terms of Use</a>
                </span>
            </div>

            <div class="buttons">
                <button type="submit" class="signup-btn">Sign Up</button>
                <a href="?page=signin" class="signin">Sign In →</a>
            </div>

        </form>

    </div>

</div>
