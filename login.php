<!DOCTYPE html>
<html lang="en">

<?php include './layout/head.html'; ?>

<body>
    <?php include './layout/auth-navbar.html'; ?>
    <div class="container w-50 mt-5">
        <h2 class="mb-4">Login</h2>
        <form id="registerForm" action="./controller/login-api.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</body>

</html>