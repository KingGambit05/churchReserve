<?php

require_once "../../classes/user.php";
$userObj = new User();

$user = [];
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user["name"] = trim(htmlspecialchars($_POST["name"]));
    $user["LName"] = trim(htmlspecialchars($_POST["LName"]));
    $user["email"] = trim(htmlspecialchars($_POST["email"]));
    $user["password"] = trim($_POST["password"]);
    $user["confirm_password"] = trim($_POST["confirm_password"]);
    $user["phone"] = trim(htmlspecialchars($_POST["phone"]));

    if (empty($user["name"])) {
        $errors["name"] = "Name is required";
    }

    if (empty($user["LName"])) {
        $errors["LName"] = "Name is required";
    }

    if (empty($user["email"])) {
        $errors["email"] = "Email is required";
    } elseif (!filter_var($user["email"], FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Invalid email format";
    }

    if (empty($user["password"])) {
        $errors["password"] = "Password is required";
    } elseif (strlen($user["password"]) < 6) {
        $errors["password"] = "Password must be at least 6 characters";
    }

    if (empty($user["confirm_password"])) {
        $errors["confirm_password"] = "Please confirm your password";
    } elseif ($user["password"] !== $user["confirm_password"]) {
        $errors["confirm_password"] = "Passwords do not match";
    }

    if (empty($user["phone"])) {
        $errors["phone"] = "Phone number is required";
    } elseif (!preg_match("/^[0-9]{10,11}$/", $user["phone"])) {
        $errors["phone"] = "Invalid phone number format";
    }

    if (empty(array_filter($errors))) {
        $userObj->userName = $user["name"];
        $userObj->lastName = $user["LName"];
        $userObj->email = $user["email"];
        $userObj->password = $user["password"];
        $userObj->phone = $user["phone"];
        $userObj->role = "user";

        if ($userObj->userRegister()) {
            header("Location: login.php");
            exit;
        } else {
            echo "<p class='error'> Registration failed. Try again.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: #f4f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 2rem 3rem;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            width: 400px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-top: 1rem;
            color: #444;
            font-weight: 600;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.6rem;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: 0.3s;
        }
        input:focus {
            border-color: #007bff;
            outline: none;
        }
        p.error {
            color: red;
            font-size: 0.85rem;
            margin: 0.3rem 0 0;
        }
        .submit-btn {
            width: 100%;
            background: #007bff;
            color: white;
            border: none;
            padding: 0.8rem;
            margin-top: 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }
        .submit-btn:hover {
            background: #0056b3;
        }
        .note {
            text-align: center;
            font-size: 0.9rem;
            margin-top: 1rem;
        }
        .note a {
            color: #007bff;
            text-decoration: none;
        }
        .note a:hover {
            text-decoration: underline;
        }
        span {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        <label>Fields with <span>*</span> are required</label>
        <form action="" method="post">
            <label for="name">First Name <span>*</span></label>
            <input type="text" name="name" id="name" value="<?= $user["name"] ?? "" ?>">
            <p class="error"><?= $errors["name"] ?? "" ?></p>

            <label for="Lname">Last Name <span>*</span></label>
            <input type="text" name="LName" id="LName" value="<?= $user["LName"] ?? "" ?>">
            <p class="error"><?= $errors["LName"] ?? "" ?></p>

            <label for="email">Email <span>*</span></label>
            <input type="email" name="email" id="email" value="<?= $user["email"] ?? "" ?>">
            <p class="error"><?= $errors["email"] ?? "" ?></p>

            <label for="password">Password <span>*</span></label>
            <input type="password" name="password" id="password">
            <p class="error"><?= $errors["password"] ?? "" ?></p>

            <label for="confirm_password">Confirm Password <span>*</span></label>
            <input type="password" name="confirm_password" id="confirm_password">
            <p class="error"><?= $errors["confirm_password"] ?? "" ?></p>

            <label for="phone">Phone Number <span>*</span></label>
            <input type="text" name="phone" id="phone" value="<?= $user["phone"] ?? "" ?>">
            <p class="error"><?= $errors["phone"] ?? "" ?></p>

            <input type="submit" value="Create Account" class="submit-btn">
        </form>
        <div class="note">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html>
