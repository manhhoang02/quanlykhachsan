<?php

include '../components/connect.php';

if (isset($_COOKIE['admin_id'])) {
   $admin_id = $_COOKIE['admin_id'];
} else {
   $admin_id = '';
   header('location:login.php');
}

function verifyBooking($_conn, $booking_id)
{
   $verify_booking = $_conn->prepare("SELECT * FROM `bookings` WHERE booking_id = ?");
   $verify_booking->execute([$booking_id]);
   return $verify_booking->rowCount() > 0;
}

function update_status($_conn, $booking_id, $status)
{
   $update_status = $_conn->prepare("UPDATE `bookings` SET status = ? WHERE booking_id = ?");
   $update_status->execute([$status, $booking_id]);
}

if (isset($_POST['accept_booking'])) {

   $booking_id = $_POST['booking_id'];
   $booking_id = filter_var($booking_id, FILTER_SANITIZE_STRING);

   if (verifyBooking($conn, $booking_id)) {
      update_status($conn, $booking_id, 'success');
   } else {
      $warning_msg[] = 'Booking not found!';
   }

}

if (isset($_POST['accept_payment'])) {

   $booking_id = $_POST['booking_id'];
   $booking_id = filter_var($booking_id, FILTER_SANITIZE_STRING);

   if (verifyBooking($conn, $booking_id)) {
      update_status($conn, $booking_id, 'paid');
   } else {
      $warning_msg[] = 'Booking not found!';
   }

}


if (isset($_POST['delete'])) {

   $booking_id = $_POST['booking_id'];
   $booking_id = filter_var($booking_id, FILTER_SANITIZE_STRING);

   if (verifyBooking($conn, $booking_id)) {
      $delete_bookings = $conn->prepare("DELETE FROM `bookings` WHERE booking_id = ?");
      $delete_bookings->execute([$booking_id]);
      $success_msg[] = 'Booking deleted!';
   } else {
      $warning_msg[] = 'Booking deleted already!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Bookings</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" type="text/css" href="../css/admin_style.css">

</head>

<body>

   <!-- header section starts  -->
   <?php include '../components/admin_header.php'; ?>
   <!-- header section ends -->

   <!-- bookings section starts  -->

   <section class="grid">

      <h1 class="heading">bookings</h1>

      <div class="box-container">

         <?php
         $select_bookings = $conn->prepare("SELECT * FROM `bookings`");
         $select_bookings->execute();
         if ($select_bookings->rowCount() > 0) {
            while ($fetch_bookings = $select_bookings->fetch(PDO::FETCH_ASSOC)) {
               $service_id = $fetch_bookings['room_type'];
               $select_services = $conn->prepare("SELECT * FROM `services` WHERE id = ? LIMIT 1");
               $select_services->execute([$service_id]);
               $service = $select_services->fetch(PDO::FETCH_ASSOC);
               ?>
               <div class="box">
                  <p>booking id : <span>
                        <?= $fetch_bookings['booking_id']; ?>
                     </span></p>
                  <p>name : <span>
                        <?= $fetch_bookings['name']; ?>
                     </span></p>
                  <p>email : <span>
                        <?= $fetch_bookings['email']; ?>
                     </span></p>
                  <p>number : <span>
                        <?= $fetch_bookings['number']; ?>
                     </span></p>
                  <p>check in : <span>
                        <?= $fetch_bookings['check_in']; ?>
                     </span></p>
                  <p>check out : <span>
                        <?= $fetch_bookings['check_out']; ?>
                     </span></p>
                  <p>rooms : <span>
                        <?= $fetch_bookings['rooms']; ?>
                     </span></p>
                  <p>adults : <span>
                        <?= $fetch_bookings['adults']; ?>
                     </span></p>
                  <p>childs : <span>
                        <?= $fetch_bookings['childs']; ?>
                     </span></p>
                  <p>room type : <span>
                        <?= $service['name'] ?>
                     </span></p>
                  <p>status : <span>
                        <?= $fetch_bookings['status'] ?>
                     </span></p>
                  <form action="" method="POST">
                     <input type="hidden" name="booking_id" value="<?= $fetch_bookings['booking_id']; ?>">
                     <?php switch ($fetch_bookings['status']) {
                        case 'pending': ?>
                           <input type="submit" value="accept booking" onclick="return confirm('accept this booking?');"
                              name="accept_booking" class="btn">
                           <?php break;

                        case 'paying': ?>
                           <input type="submit" value="accept payment" onclick="return confirm('accept this booking?');"
                              name="accept_payment" class="btn">
                           <?php break;
                     } ?>
                     <input type="submit" value="delete booking" onclick="return confirm('delete this booking?');"
                        name="delete" class="btn">
                  </form>
               </div>
               <?php
            }
         } else {
            ?>
            <div class="box" style="text-align: center;">
               <p>no bookings found!</p>
               <a href="dashboard.php" class="btn">go to home</a>
            </div>
            <?php
         }
         ?>

      </div>

   </section>

   <!-- bookings section ends -->
















   <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

   <!-- custom js file link  -->
   <script src="../js/admin_script.js"></script>

   <?php include '../components/message.php'; ?>

</body>

</html>