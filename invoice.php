<?php

include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
    header('location: authentication.php');
}

$booking_id = isset($_GET['booking_id']) ? $_GET['booking_id'] : '';

// lấy data bảng bookings
$select_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE booking_id = ?");
$select_bookings->execute([$booking_id]);
$fetch_booking = $select_bookings->fetch(PDO::FETCH_ASSOC);

if ($fetch_booking) {
    // lấy data bảng services
    $service_id = $fetch_booking['room_type'];
    $select_services = $conn->prepare("SELECT * FROM `services` WHERE id = ? LIMIT 1");
    $select_services->execute([$service_id]);
    $service = $select_services->fetch(PDO::FETCH_ASSOC);
}

if (isset($_POST['cancel'])) {

    $booking_id = $_POST['booking_id'];
    $booking_id = filter_var($booking_id, FILTER_SANITIZE_STRING);

    $select_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE booking_id = ?");
    $select_bookings->execute([$booking_id]);

    if ($select_bookings->rowCount() > 0) {
        $delete_booking = $conn->prepare("DELETE FROM `bookings` WHERE booking_id = ?");
        $delete_booking->execute([$booking_id]);
        $success_msg[] = 'booking cancelled successfully!';
    } else {
        $warning_msg[] = 'booking cancelled already!';
    }

}

function calculate_room_price($adults, $rooms, $children, $base_price)
{
    // Giá cho mỗi người lớn
    $adult_price = 50;
    // Giá cho mỗi trẻ em
    $child_rice = 25;

    // Tính toán tổng giá
    $total_room = $rooms * $base_price;
    $total_adult = $adults * $adult_price;
    $total_child = $children * $child_rice;

    $total = $total_room + $total_adult + $total_child;

    return $total;
}

$total_price = calculate_room_price($fetch_booking['adults'], $fetch_booking['rooms'], $fetch_booking['childs'], $service['price']);

if (isset($_POST['pay'])) {
    $payment_id = create_unique_id();
    $payment_date = date('Y-m-d');
    $payment_method = $_POST['method'];
    $payment_method = filter_var($payment_method, FILTER_SANITIZE_STRING);

    $pay_invoice = $conn->prepare("INSERT INTO `payments`(id, booking_id, user_id, amount, payment_date, payment_method) VALUES(?,?,?,?,?,?)");
    $pay_invoice->execute([$payment_id, $booking_id, $user_id, $total_price, $payment_date, $payment_method]);

    if ($pay_invoice->rowCount() > 0) {
        $update_status = $conn->prepare("UPDATE `bookings` SET status = 'paying' WHERE booking_id = ?");
        $update_status->execute([$booking_id]);

        $success_msg[] = 'payment success!';
        header('location: bookings.php');
    } else {
        $warning_msg[] = 'payment failed!';
    }

}

// lấy method từ bảng payments
$select_payments = $conn->prepare("SELECT * FROM `payments` WHERE booking_id = ?");
$select_payments->execute([$booking_id]);
$fetch_payment = $select_payments->fetch(PDO::FETCH_ASSOC);
$p_method = $fetch_payment['payment_method'];

print_r($p_method);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>invoice</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/invoice.css">
</head>

<body>

    <!-- header section starts  -->
    <?php include 'components/user_header.php'; ?>
    <!-- header section ends -->

    <section class="invoice">
        <form action="" method="post">

            <h3>invoice</h3>

            <div class="flex">

                <div class="info">
                    <p class="service_name">
                        <?= $service['name'] ?>
                    </p>

                    <div class="info_booking">
                        <p class="text">name : <span>
                                <?= $fetch_booking['name']; ?>
                            </span></p>
                        <p class="text">email : <span>
                                <?= $fetch_booking['email']; ?>
                            </span></p>
                        <p class="text">number : <span>
                                <?= $fetch_booking['number']; ?>
                            </span></p>
                        <p class="text">check in : <span>
                                <?= $fetch_booking['check_in']; ?>
                            </span></p>
                        <p class="text">check out : <span>
                                <?= $fetch_booking['check_out']; ?>
                            </span></p>
                        <p class="text">rooms : <span>
                                <?= $fetch_booking['rooms']; ?>
                            </span></p>
                        <p class="text">adults : <span>
                                <?= $fetch_booking['adults']; ?>
                            </span></p>
                        <p class="text">childs : <span>
                                <?= $fetch_booking['childs']; ?>
                            </span></p>
                    </div>

                    <div class="box">
                        <p>method <span>*</span></p>
                        <select <?php echo $p_method ? 'disabled' : ''; ?> name="method" class="input" required>
                            <option value="cash" <?php echo ($p_method == 'cash') ? 'selected' : ''; ?>>cash</option>
                            <option value="bank-transfer" <?php echo ($p_method == 'bank-transfer') ? 'selected' : ''; ?>>
                                bank transfer</option>
                            <option value="credit-card" <?php echo ($p_method == 'credit-card') ? 'selected' : ''; ?>>
                                credit card</option>
                            <option value="family-room" <?php echo ($p_method == 'family-room') ? 'selected' : ''; ?>>
                                family room</option>
                            <option value="e-wallet" <?php echo ($p_method == 'e-wallet') ? 'selected' : ''; ?>>e-wallet
                            </option>
                            <option value="atm-card" <?php echo ($p_method == 'atm-card') ? 'selected' : ''; ?>>atm card
                            </option>
                        </select>

                    </div>
                </div>

                <img src="images/rooms/<?= $service['image_url'] ?>" alt="Image Preview" class="image">
            </div>

            <div class="pay_btn_container">
                <input type=<?= ($fetch_booking['status'] == 'paying' || $fetch_booking['status'] == 'paid') ? "hidden" : "submit" ?> value="pay now" class="btn_pay" name="pay">
                <p class="total">total: <span>
                        <?= $total_price ?>
                    </span> $</p>
            </div>

        </form>

    </section>

    <?php include 'components/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- custom js file link  -->
    <script src="js/script.js"></script>

    <?php include 'components/message.php'; ?>

</body>

</html>