<?php include 'header.php'; ?> 
<link rel="stylesheet" href="styles.css">
<div class="signin-container">
    <h1>Sign In</h1>
    <form action="login.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Sign In</button>
    </form>
    <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
</div>

<script>
document.querySelector('form').addEventListener('submit', function(event) {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    if (!email || !password) {
        alert('Please fill out all fields.');
        event.preventDefault();
    }
});
</script>

<?php include 'footer.php'; ?>  
