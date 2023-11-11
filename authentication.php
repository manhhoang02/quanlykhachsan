<?php
include 'components/connect.php';

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
    $id = create_unique_id();
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $re_email = $_POST['re-email'];
    $re_password = sha1($_POST['re-password']);
    $c_password = sha1($_POST['confirm-re-password']);

    $select_user = $conn->prepare("SELECT EXISTS(
        SELECT *
        FROM users
        WHERE email = ?
      )");
    $select_user->execute([$re_email]);
    $check_email_existed = $select_user->fetch(PDO::FETCH_COLUMN);

    if ($re_password != $c_password) {
        $warning_msg[] = 'Password not matched!';
    } elseif ($check_email_existed) {
        $warning_msg[] = 'Email has been used!';
    } else {
        $insert_user = $conn->prepare("INSERT INTO `users` (id, first_name, last_name, email, password) VALUES (?, ?, ?, ?, ?)");
        $insert_user->execute([$id, $firstName, $lastName, $re_email, $re_password]);

        if ($insert_user) {
            // Đăng nhập khi đăng ký user thành công
            $email = $re_email;
            $password = $re_password;

            $select_users = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
            $select_users->execute([$email, $password]);
            $row = $select_users->fetch(PDO::FETCH_ASSOC);

            if ($select_users->rowCount() > 0) {
                setcookie('user_id', $row['id'], time() + 60 * 60 * 24 * 30, '/');
                header('location: index.php');
            } else {
                $warning_msg[] = 'Incorrect email or password!';
            }
        } else {
            $warning_msg[] = 'Registration failed!';
        }
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
                <div class="row" style="align-items: center; justify-content: space-between;">
                    <input type="submit" value="login" name="login" class="btn">
                    <a href="admin/login.php" target="_blank"
                        style="font-size: 1.5rem; text-decoration-line: underline;">
                        <p>Login as admin</p>
                    </a>
                </div>
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
                    <input type="password" name="confirm-re-password" maxlength="50" required
                        placeholder="confirm your password" class="input">
                </div>
                <input type="submit" value="register" name="register" class="btn">
            </form>
        </div>
        <!-- register section ends -->

    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <?php include 'components/message.php'; ?>

</body>

</html>