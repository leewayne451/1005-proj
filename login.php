<!DOCTYPE html>
<html lang="en">
    <head>
        <title>World of Pets - Login</title>

        <?php
            include "inc/head.inc.php"; // Includes your existing styles and metadata
        ?>
    </head>
    <body>       
        <?php
            include "inc/nav.inc.php"; // Includes your existing navigation bar
        ?>

        <main class="container">
            <h1>Member Login</h1>
            <p>
                Existing members log in here. For new members, please go to the
                <a href="register.php">Member Registration page</a>.
            </p>

            <form action="process_login.php" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input maxlength="45" type="email" id="email" name="email" class="form-control"
                        placeholder="Enter email" required>
                </div>
                <div class="mb-3">
                    <label for="pwd" class="form-label">Password:</label>
                    <input maxlength="45" type="password" id="pwd" name="pwd" class="form-control"
                        placeholder="Enter password" required>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </main>

        <?php
            include "inc/footer.inc.php"; // Includes your existing footer
        ?>
    </body>
</html>
