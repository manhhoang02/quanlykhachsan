<?php

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
    header('location:authentication.php');
}

$select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ? LIMIT 1");
$select_user->execute([$user_id]);
$fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['update_profile'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];


    // Update first_name and last_name
    if (!empty($first_name) || !empty($last_name)) {
        if (!empty($first_name) && !empty($last_name)) {
            $update_name = $conn->prepare("UPDATE `users` SET first_name = ?, last_name = ? WHERE id = ?");
            $update_name->execute([$first_name, $last_name, $user_id]);
            $success_msg[] = 'Name updated!';

            echo '<script type="text/javascript"> 
                    (() => {
                        if (window.localStorage && !localStorage.getItem("reload")) {
                            localStorage["reload"] = true;
                            window.location.reload();
                        } else {
                            localStorage.removeItem("reload");
                        }
                    })();
                </script>';
        } else {
            $warning_msg[] = 'First name and last name are required!';
        }
    }

    // Update email
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);

    if (!empty($email)) {
        $verify_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
        $verify_email->execute([$email]);
        if ($verify_email->rowCount() > 0) {
            $warning_msg[] = 'Email already taken!';
        } else {
            $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ?");
            $update_email->execute([$email, $user_id]);
            $success_msg[] = 'Email updated!';
        }
    }

    // Update password
    $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
    $prev_pass = $fetch_user['password'];
    $current_pass = sha1($_POST['current_pass']);
    $current_pass = filter_var($current_pass, FILTER_SANITIZE_STRING);
    $new_pass = sha1($_POST['new_pass']);
    $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
    $c_new_pass = sha1($_POST['c_new_pass']);
    $c_new_pass = filter_var($c_new_pass, FILTER_SANITIZE_STRING);

    if ($current_pass != $empty_pass) {
        if ($current_pass != $prev_pass) {
            $warning_msg[] = 'Old password not matched!';
        } elseif ($c_new_pass != $new_pass) {
            $warning_msg[] = 'New password not matched!';
        } else {
            if ($new_pass != $empty_pass) {
                $update_password = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
                $update_password->execute([$c_new_pass, $user_id]);
                $success_msg[] = 'Password updated!';
            } else {
                $warning_msg[] = 'Please enter new password!';
            }
        }
    }
}


?>

<!-- modal edit starts -->

<div class="modal_edit" id="modalEditProfile">

    <div class="modal_edit_content">
        <span class="close" id="close">&#x2716;</span>
        <form method="post" action="">
            <div class="row">
                <div class="box">
                    <p>first name</p>
                    <input type="text" name="first_name" placeholder="<?= $fetch_user['first_name']; ?>" class="input">
                </div>
                <div class="box" style="margin-left: 2rem;">
                    <p>last name</p>
                    <input type="text" name="last_name" placeholder="<?= $fetch_user['last_name']; ?>" class="input">
                </div>
            </div>
            <div class="box">
                <p>email</p>
                <input type="email" name="email" placeholder="<?= $fetch_user['email']; ?>" class="input">
            </div>
            <div class="box">
                <p>current password</p>
                <input type="password" maxlength="20" name="current_pass" placeholder="enter current password"
                    class="input">
            </div>
            <div class="box">
                <p>new password</p>
                <input type="password" maxlength="20" name="new_pass" placeholder="enter new password" class="input">
            </div>
            <div class="box">
                <p>confirm new password</p>
                <input type="password" maxlength="20" name="c_new_pass" placeholder="confirm new password"
                    class="input">
            </div>

            <input type="submit" name="update_profile" value="update" class="btn">
        </form>
    </div>

</div>

<!-- modal edit ends -->

<style>
    .row {
        display: flex;
    }

    .modal_edit {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        justify-content: center;
        align-items: center;
        z-index: 3;
    }

    .modal_edit_content {
        background-color: var(--main-color);
        padding: 2rem;
        border-radius: 5px;
        /* height: 50rem; */
        width: 60rem;
    }

    .modal_edit .close {
        font-size: 2rem;
        color: var(--sub-color);
        cursor: pointer;
        position: fixed;
        top: 10px;
        right: 10px;
    }

    .modal_edit .modal_edit_content form .box p {
        font-size: 2rem;
        color: var(--sub-color);
    }

    .modal_edit .modal_edit_content form .box {
        flex: 1 1 40rem;
        margin: 1rem 0;
    }

    .modal_edit .modal_edit_content form .box .input {
        padding: 1rem 0;
        margin: 1rem 0;
        border-bottom: var(--border);
        background: var(--main-color);
        color: var(--white);
        font-size: 1.8rem;
        width: 100%;
    }
</style>