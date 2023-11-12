<?php

include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
}

if (isset($_POST['book'])) {

    $booking_id = create_unique_id();
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);
    $rooms = $_POST['rooms'];
    $rooms = filter_var($rooms, FILTER_SANITIZE_STRING);
    $check_in = $_POST['check_in'];
    $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);
    $check_out = $_POST['check_out'];
    $check_out = filter_var($check_out, FILTER_SANITIZE_STRING);
    $adults = $_POST['adults'];
    $adults = filter_var($adults, FILTER_SANITIZE_STRING);
    $childs = $_POST['childs'];
    $childs = filter_var($childs, FILTER_SANITIZE_STRING);
    $room_type = $_POST['room_type'];
    $room_type = filter_var($room_type, FILTER_SANITIZE_STRING);

    $total_rooms = 0;

    $check_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE check_in = ?");
    $check_bookings->execute([$check_in]);

    while ($fetch_bookings = $check_bookings->fetch(PDO::FETCH_ASSOC)) {
        $total_rooms += $fetch_bookings['rooms'];
    }

    if ($total_rooms >= 30) {
        $warning_msg[] = 'rooms are not available';
    } else {

        $verify_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE user_id = ? AND name = ? AND email = ? AND number = ? AND rooms = ? AND check_in = ? AND check_out = ? AND adults = ? AND childs = ? AND room_type = ?");
        $verify_bookings->execute([$user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs, $room_type]);

        if ($verify_bookings->rowCount() > 0) {
            $warning_msg[] = 'room booked already!';
        } else {
            $book_room = $conn->prepare("INSERT INTO `bookings`(booking_id, user_id, name, email, number, rooms, check_in, check_out, adults, childs, room_type) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
            $book_room->execute([$booking_id, $user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs, $room_type]);
            $success_msg[] = 'room booked successfully!';
        }

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reservation</title>

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/reservation.css">
</head>

<body>
    <?php include 'components/user_header.php'; ?>

    <!-- reservation section starts  -->



    <section class="reservation" id="reservation">

        <div style="text-align: center">
            <img src="images/services1.png" alt="Image Preview" class="image-preview"
                onclick="openFullscreen('images/services1.png')">
        </div>

        <form action="" method="post">
            <h3>make a reservation</h3>
            <div class="flex">
                <div class="box">
                    <p>your name <span>*</span></p>
                    <input type="text" name="name" maxlength="50" required placeholder="enter your name" class="input">
                </div>
                <div class="box">
                    <p>your email <span>*</span></p>
                    <input type="email" name="email" maxlength="50" required placeholder="enter your email"
                        class="input">
                </div>
                <div class="box">
                    <p>your number <span>*</span></p>
                    <input type="number" name="number" maxlength="10" min="0" max="9999999999" required
                        placeholder="enter your number" class="input">
                </div>
                <div class="box">
                    <p>rooms <span>*</span></p>
                    <select name="rooms" class="input" required>
                        <option value="1" selected>1 room</option>
                        <option value="2">2 rooms</option>
                        <option value="3">3 rooms</option>
                        <option value="4">4 rooms</option>
                        <option value="5">5 rooms</option>
                        <option value="6">6 rooms</option>
                    </select>
                </div>
                <div class="box">
                    <p>check in <span>*</span></p>
                    <input type="date" name="check_in" class="input" required>
                </div>
                <div class="box">
                    <p>check out <span>*</span></p>
                    <input type="date" name="check_out" class="input" required>
                </div>
                <div class="box">
                    <p>adults <span>*</span></p>
                    <select name="adults" class="input" required>
                        <option value="1" selected>1 adult</option>
                        <option value="2">2 adults</option>
                        <option value="3">3 adults</option>
                        <option value="4">4 adults</option>
                        <option value="5">5 adults</option>
                        <option value="6">6 adults</option>
                    </select>
                </div>
                <div class="box">
                    <p>childs <span>*</span></p>
                    <select name="childs" class="input" required>
                        <option value="0" selected>0 child</option>
                        <option value="1">1 child</option>
                        <option value="2">2 childs</option>
                        <option value="3">3 childs</option>
                        <option value="4">4 childs</option>
                        <option value="5">5 childs</option>
                        <option value="6">6 childs</option>
                    </select>
                </div>
                <div class="box">
                    <p> select room type <span>*</span></p>
                    <select name="room_type" class="input" required>
                        <option value="0" selected>single room</option>
                        <option value="1">double room</option>
                        <option value="2">twin room</option>
                        <option value="3">family room</option>
                        <option value="4">suite room</option>
                        <option value="5">sea view room</option>
                        <option value="6">garden view room</option>
                        <option value="7">executive room</option>
                        <option value="8">connecting room</option>
                        <option value="9">high-floor room</option>
                        <option value="10">special room</option>
                    </select>
                </div>
            </div>
            <input type="submit" value="book now" name="book" class="btn">
        </form>

    </section>

    <div id="fullscreen-container">
        <img src="" alt="Fullscreen Image" id="fullscreen-image">
        <span class="close-btn" onclick="closeFullscreen()">&#x2716;</span>
    </div>

    <!-- reservation section ends -->


    <?php include 'components/footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <script>
        function openFullscreen(imagePath) {
            var fullscreenContainer = document.getElementById('fullscreen-container');
            var fullscreenImage = document.getElementById('fullscreen-image');

            fullscreenImage.src = imagePath;
            fullscreenContainer.style.display = 'flex';

            document.addEventListener('keydown', closeOnEscape);
        }

        function closeFullscreen() {
            var fullscreenContainer = document.getElementById('fullscreen-container');
            fullscreenContainer.style.display = 'none';

            document.removeEventListener('keydown', closeOnEscape);
        }

        function closeOnEscape(event) {
            if (event.key === 'Escape') {
                closeFullscreen();
            }
        }
    </script>

    <?php include 'components/message.php'; ?>

</body>

</html>