<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <?php
    if (isset($_POST["Submit"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];
        require_once "database.php";

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_stmt_init($conn);

        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);

            if ($user) {
                if (password_verify($password, $user["password"])) {
                    header("Location: index2.php");
                    exit();
                } else {
                    echo "<div class='alert alert-danger'>Password does not match</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Email does not match</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Database query failed</div>";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
    ?>
    <div class="heading">
        <h1>Login</h1>
    </div>
    <form action="login.php" method="post">
        <div class="form-group">
            <input type="text" name="email" placeholder="E-Mail:" required>
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Password:" required>
        </div>
        <div class="form-group">
            <input type="submit" value="Login" name="Submit">
        </div>
    </form>
</div>
</body>
</html>
