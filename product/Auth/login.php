<?php
require_once '../../classes/user.php';
$userObj = new User();

$errors = [];
$user = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user["email"] = trim(htmlspecialchars($_POST["email"]));
    $user["password"] = trim(htmlspecialchars($_POST["password"]));

    if (empty($user["email"])) {
        $errors["email"] = "Email is required.";
    }

    if (empty($user["password"])) {
        $errors["password"] = "Password is required.";
    }

    if (empty(array_filter($errors))) {
        $userObj->email = $user["email"];
        $userObj->password = $user["password"];

        if ($userObj->userLogin()) {
            if ($_SESSION["role"] === "admin") {
                header("Location: ../admin/admin_dashboard.php");
            } else {
                header("Location: ../user/user_dashboard.php");
            }
            exit;
        } else {
            $errors["login"] = "Invalid email or password.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Login</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f7f9fc;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    form {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        width: 350px;
    }
    h2 { 
        text-align: center; 
        margin-bottom: 1rem; 
        color: #333; 
    }
    label { 
        display: block; 
        margin-top: 1rem; 
    }
    input[type="email"], 
    input[type="password"] {
        width: 100%;
        padding: .5rem;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-top: .3rem;
    }
    input[type="submit"] {
        margin-top: 1.5rem;
        width: 100%;
        padding: .7rem;
        background: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
    }
    input[type="submit"]:hover {
        background: #0056b3;
    }
    p.error { 
        color: red; 
        margin: .3rem 0; 
        font-size: .9rem; 
    }
    .nav {
        text-align: center;
        margin-top: 1rem;
    }
    .nav a {
        color: #007bff;
        text-decoration: none;
        font-size: .95rem;
    }
    .nav a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>
    <form action="" method="post">
        <h2>Login</h2>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?= $user["email"] ?? "" ?>">
        <p class="error"><?= $errors["email"] ?? "" ?></p>

        <label for="password">Password</label>
        <input type="password" name="password" id="password">
        <p class="error"><?= $errors["password"] ?? "" ?></p>

        <p class="error"><?= $errors["login"] ?? "" ?></p>

        <input type="submit" value="Login">

        <div class="nav">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </form>
</body>
</html>
