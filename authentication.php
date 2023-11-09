<?php
include '../project/components/connect.php';


if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = sha1($_POST['password']);

    $select_users = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
    $select_users->execute([$email, $password]);
    $row = $select_users->fetch(PDO::FETCH_ASSOC);

    if ($select_users->rowCount() > 0) {
        setcookie('user_id', $row['id'], time() + 60 * 60 * 24 * 30, '/');
        header('location: index.php');
    } else {
        $warning_msg[] = 'Incorrect email or password!';
    }
}

if (isset($_POST['register'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['re-email'];
    $password = sha1($_POST['re-password']);

    $insert_user = $conn->prepare("INSERT INTO `users` (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
    $insert_user->execute([$firstName, $lastName, $email, $password]);

    if ($insert_user) {
        header('location: index.php');
    } else {
        $warning_msg[] = 'Registration failed!';
    }
}

?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login and register</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">


    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/authentication.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include 'components/user_header.php'; ?>

    <section class="authentication" id="authentication">
        <!-- login section starts  -->
        <div class="form-container">
            <h3>Sign In</h3>
            <form id="loginForm" method="post">
                <div class="box">
                    <p>email <span>*</span></p>
                    <input type="email" name="email" maxlength="50" required placeholder="enter your email"
                        class="input">
                </div>
                <div class="box">
                    <p>password <span>*</span></p>
                    <input type="password" name="password" maxlength="50" required placeholder="enter your password"
                        class="input">
                </div>
                <input type="submit" value="login" name="login" class="btn">
            </form>
        </div>
        <!-- login section ends -->

        <!-- register section starts  -->
        <div class="form-container">
            <h3>Register</h3>
            <form id="registerForm" method="post">
                <div class="row">
                    <div class="box">
                        <p>first name <span>*</span></p>
                        <input type="text" name="firstName" maxlength="50" required placeholder="enter your first name"
                            class="input">
                    </div>
                    <div class="box" style="margin-left: 2.5rem;">
                        <p>last name <span>*</span></p>
                        <input type="text" name="lastName" maxlength="50" required placeholder="enter your last name"
                            class="input">
                    </div>
                </div>
                <div class="box">
                    <p>email <span>*</span></p>
                    <input type="email" name="re-email" maxlength="50" required placeholder="enter your email"
                        class="input">
                </div>
                <div class="box">
                    <p>password <span>*</span></p>
                    <input type="password" name="re-password" maxlength="50" required placeholder="enter your password"
                        class="input">
                </div>
                <div class="box">
                    <p>confirm password <span>*</span></p>
                    <input type="password" name="re-password" maxlength="50" required
                        placeholder="confirm your password" class="input">
                </div>
                <input type="submit" value="register" name="register" class="btn">
            </form>
        </div>
        <!-- register section ends -->

    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <?php include '../project/components/message.php'; ?>

</body>

</html>