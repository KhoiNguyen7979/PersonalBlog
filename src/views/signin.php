<link rel="stylesheet" href="public/css/signin.css">

<div class="container">

    <!-- LEFT -->
    <div class="left-panel">
        <h1>Welcome to</h1>
        <a href="index.php"><img src="public/images/bloggerZ.png" class="logo" alt="BloggerZ Logo"></a>
    </div>

    <!-- RIGHT -->
    <div class="right-panel">

        <h2>Sign In</h2>

        <form action="php/login.php" method="POST">

            <input type="text" name="username_email" placeholder="Email / Username" required>

            <input type="password" name="password" placeholder="Password" required>

            <div class="remember">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me</label>
            </div>

            <div class="button">
                <button type="submit" class="signin-btn">Sign In</button>
                <a href="?page=signup" class="signup">Sign Up →</a>
            </div>

            <div class="forgot-password">
                <a href="#">Forgot Password?</a>
            </div>

        </form>

    </div>

</div>
