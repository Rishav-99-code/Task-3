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
        $fullName = $_POST["fullname"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $passwordRepeat = $_POST["repeat_password"];
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $errors = array();

        if (empty($fullName) || empty($email) || empty($password) || empty($passwordRepeat)) {
            array_push($errors, "All fields are required");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email is not valid");
        }
        if (strlen($password) < 8) {
            array_push($errors, "Password must have 8 or more characters");
        }
        if ($password !== $passwordRepeat) {
            array_push($errors, "Password does not match");
        }
        require_once 'database.php';
        $sql = "SELECT *FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        $rowCount = mysqli_num_rows($result);
        if($rowCount>0){
            array_push($errors,"Email already exist!");
        }
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
        } else {
            require_once 'database.php';
            $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt, $sql);

            if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt, "sss", $fullName, $email, $password_hash);
                mysqli_stmt_execute($stmt);
                echo "<div class='alert alert-success'>You are successfully registered.</div>";
                header("Location: login.php");
                exit();
            } else {
                die("Something went wrong");
            }
        }
    }
    ?>
   <div class="heading">
        <h1>Register</h1>
    </div>
    <form action="registration.php" method="post">
        <div class="form-group">
            <input type="text" class="form-class" name="fullname" placeholder="FullName:">
        </div>
        <div class="form-group">
            <input type="text" name="email" placeholder="E-Mail:">
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Password:">
        </div>
        <div class="form-group">
            <input type="password" name="repeat_password" placeholder="Re-type password">
        </div>
        <div class="form-group">
            <input type="submit" value="Registration" name="Submit">
        </div>
    </form>
    </div>
</body>
</html>
