<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login Results</title>
    <?php include "inc/head.inc.php"; ?>
</head>
<body>
    <?php include "inc/nav.inc.php"; ?>

    <main class="container text-center">
        <?php
        $email = $pwd = "";
        $errorMsg = "";
        $success = true;

        if (empty($_POST["email"])) {
            $errorMsg .= "Email is required.<br>";
            $success = false;
        } else {
            $email = sanitize_input($_POST["email"]);
        }

        if (empty($_POST["pwd"])) {
            $errorMsg .= "Password is required.<br>";
            $success = false;
        } else {
            $pwd = $_POST["pwd"];
        }

        if ($success) {
            authenticateUser();
        }

        function authenticateUser() {
            global $email, $pwd, $errorMsg, $success, $fname, $lname;

            $config = parse_ini_file('/var/www/private/db-config.ini');
            if (!$config) {
                $errorMsg = "Failed to read database config file.";
                $success = false;
            } else {
                $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
                
                if ($conn->connect_error) {
                    $errorMsg = "Connection failed: " . $conn->connect_error;
                    $success = false;
                } else {
                    $stmt = $conn->prepare("SELECT fname, lname, password FROM world_of_pets_members WHERE email=?");
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $fname = $row["fname"];
                        $lname = $row["lname"];
                        $pwd_hashed = $row["password"];

                        if (!password_verify($pwd, $pwd_hashed)) {
                            $errorMsg = "Email not found or password doesn't match...";
                            $success = false;
                        }
                    } else {
                        $errorMsg = "Email not found or password doesn't match...";
                        $success = false;
                    }

                    $stmt->close();
                }
                $conn->close();
            }
        }

        function sanitize_input($data) {
            return htmlspecialchars(stripslashes(trim($data)));
        }
        ?>

        <?php if ($success): ?>
            <h1 class="text-success">Login Successful!</h1>
            <h3>Welcome, <?= htmlspecialchars($fname) . " " . htmlspecialchars($lname) ?>.</h3>
        <?php else: ?>
            <h1 class="text-danger">Oops!</h1>
            <h3>The following errors were detected:</h3>
            <div class="alert alert-danger">
                <?= htmlspecialchars_decode(nl2br(htmlspecialchars($errorMsg))) ?>
            </div>
            <a href="login.php" class="btn btn-warning">Return to Login</a>
        <?php endif; ?>
    </main>

    <?php include "inc/footer.inc.php"; ?>
</body>
</html>
