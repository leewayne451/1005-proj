<?php 
include "inc/head.inc.php"; 
include "inc/nav.inc.php"; 

$fname = $lname = $email = $pwd = $pwd_hashed = "";
$errorMsg = "";
$success = true;

// Input validation
if (empty($_POST["fname"])) {
    $errorMsg .= "First name is required.<br>";
    $success = false;
} else {
    $fname = sanitize_input($_POST["fname"]);
}

if (empty($_POST["lname"])) {
    $errorMsg .= "Last name is required.<br>";
    $success = false;
} else {
    $lname = sanitize_input($_POST["lname"]);
}

if (empty($_POST["email"])) {
    $errorMsg .= "Email is required.<br>";
    $success = false;
} else {
    $email = sanitize_input($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg .= "Invalid email format.<br>";
        $success = false;
    }
}

if (empty($_POST["pwd"])) {
    $errorMsg .= "Password is required.<br>";
    $success = false;
} elseif ($_POST["pwd"] !== $_POST["pwd_confirm"]) {
    $errorMsg .= "Passwords do not match.<br>";
    $success = false;
} else {
    $pwd_hashed = password_hash($_POST["pwd"], PASSWORD_DEFAULT);
}

if ($success) {
    saveMemberToDB();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Registration Results</title>
</head>
<body>
    <main class="container text-center">
        <?php if ($success): ?>
            <h1 class="text-success">Your registration is successful!</h1>
            <h3>Thank you for signing up, <?= htmlspecialchars($fname) . " " . htmlspecialchars($lname) ?>.</h3>
            <a href="login.php" class="btn btn-success">Log-in</a>
        <?php else: ?>
            <h1 class="text-danger">Oops!</h1>
            <h3>The following errors were detected:</h3>
            <div class="alert alert-danger">
                <?= htmlspecialchars_decode(nl2br(htmlspecialchars($errorMsg))) ?>
            </div>
            <a href="register.php" class="btn btn-danger">Return to Sign Up</a>
        <?php endif; ?>
    </main>

    <?php include "inc/footer.inc.php"; ?>
</body>
</html>

<?php
// Sanitize input function
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to save member data to the database
function saveMemberToDB() {
    global $fname, $lname, $email, $pwd_hashed, $errorMsg, $success;

    // Read database config
    $config = parse_ini_file('/var/www/private/db-config.ini'); 
    if (!$config) {
        $errorMsg = "Failed to read database config file.";
        $success = false;
        return;
    }

    // Create database connection
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
        return;
    }

    // Prepare the statement
    $stmt = $conn->prepare("INSERT INTO world_of_pets_members (fname, lname, email, password) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        $errorMsg = "Statement preparation failed: " . $conn->error;
        $success = false;
        return;
    }

    // Bind & execute the query
    $stmt->bind_param("ssss", $fname, $lname, $email, $pwd_hashed);
    if (!$stmt->execute()) {
        $errorMsg = "Execution failed: (" . $stmt->errno . ") " . $stmt->error;
        $success = false;
    }

    $stmt->close();
    $conn->close();
}
?>
