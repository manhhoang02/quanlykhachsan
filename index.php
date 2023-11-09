<?php

include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
   $user_id = $_COOKIE['user_id'];
} else {
   setcookie('user_id', create_unique_id(), time() + 60 * 60 * 24 * 30, '/');
   header('location:index.php');
}

if (isset($_POST['check'])) {

   $check_in = $_POST['check_in'];
   $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);

   $total_rooms = 0;

   $check_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE check_in = ?");
   $check_bookings->execute([$check_in]);

   while ($fetch_bookings = $check_bookings->fetch(PDO::FETCH_ASSOC)) {
      $total_rooms += $fetch_bookings['rooms'];
   }

   // if the hotel has total 30 rooms 
   if ($total_rooms >= 30) {
      $warning_msg[] = 'rooms are not available';
   } else {
      $success_msg[] = 'rooms are available';
   }

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

   $total_rooms = 0;

   $check_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE check_in = ?");
   $check_bookings->execute([$check_in]);

   while ($fetch_bookings = $check_bookings->fetch(PDO::FETCH_ASSOC)) {
      $total_rooms += $fetch_bookings['rooms'];
   }

   if ($total_rooms >= 30) {
      $warning_msg[] = 'rooms are not available';
   } else {

      $verify_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE user_id = ? AND name = ? AND email = ? AND number = ? AND rooms = ? AND check_in = ? AND check_out = ? AND adults = ? AND childs = ?");
      $verify_bookings->execute([$user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs]);

      if ($verify_bookings->rowCount() > 0) {
         $warning_msg[] = 'room booked alredy!';
      } else {
         $book_room = $conn->prepare("INSERT INTO `bookings`(booking_id, user_id, name, email, number, rooms, check_in, check_out, adults, childs) VALUES(?,?,?,?,?,?,?,?,?,?)");
         $book_room->execute([$booking_id, $user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs]);
         $success_msg[] = 'room booked successfully!';
      }

   }

}

if (isset($_POST['send'])) {

   $id = create_unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $message = $_POST['message'];
   $message = filter_var($message, FILTER_SANITIZE_STRING);

   $verify_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $verify_message->execute([$name, $email, $number, $message]);

   if ($verify_message->rowCount() > 0) {
      $warning_msg[] = 'message sent already!';
   } else {
      $insert_message = $conn->prepare("INSERT INTO `messages`(id, name, email, number, message) VALUES(?,?,?,?,?)");
      $insert_message->execute([$id, $name, $email, $number, $message]);
      $success_msg[] = 'message send successfully!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <!-- home section starts  -->

   <section class="home" id="home">

      <div class="swiper home-slider">

         <div class="swiper-wrapper">

            <div class="box swiper-slide">
               <img src="images/home-img-1.jpg" alt="">
               <div class="flex">
                  <h3>luxurious rooms</h3>
                  <!-- <a href="#availability" class="btn">check availability</a> -->
               </div>
            </div>

            <div class="box swiper-slide">
               <img src="images/home-img-2.jpg" alt="">
               <div class="flex">
                  <h3>foods and drinks</h3>
                  <a href="#reservation" class="btn">make a reservation</a>
               </div>
            </div>

            <div class="box swiper-slide">
               <img src="images/home-img-3.jpg" alt="">
               <div class="flex">
                  <h3>luxurious halls</h3>
                  <a href="#contact" class="btn">contact us</a>
               </div>
            </div>

         </div>

         <div class="swiper-button-next"></div>
         <div class="swiper-button-prev"></div>

      </div>

   </section>

   <!-- home section ends -->

   <!-- availability section starts  -->

   <section class="availability" id="availability">

      <form action="" method="post">
         <div class="flex">
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
                  <option value="1">1 adult</option>
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
                  <option value="-">0 child</option>
                  <option value="1">1 child</option>
                  <option value="2">2 childs</option>
                  <option value="3">3 childs</option>
                  <option value="4">4 childs</option>
                  <option value="5">5 childs</option>
                  <option value="6">6 childs</option>
               </select>
            </div>
            <div class="box">
               <p>rooms <span>*</span></p>
               <select name="rooms" class="input" required>
                  <option value="1">1 room</option>
                  <option value="2">2 rooms</option>
                  <option value="3">3 rooms</option>
                  <option value="4">4 rooms</option>
                  <option value="5">5 rooms</option>
                  <option value="6">6 rooms</option>
               </select>
            </div>
         </div>
         <input type="submit" value="check availability" name="check" class="btn">
      </form>

   </section>

   <!-- availability section ends -->

   <!-- about section starts  -->

   <section class="about" id="about">

      <div class="row">
         <div class="image">
            <img src="images/about-img-1.jpg" alt="">
         </div>
         <div class="content">
            <h3>best staff</h3>
            <p>Khách sạn của chúng tôi tự hào có đội ngũ nhân viên chuyên nghiệp và thân thiện, luôn sẵn sàng đáp ứng
               mọi nhu cầu của bạn. Tận tâm và chu đáo, đội ngũ nhân viên tạo ra một môi trường ấm áp và thoải mái, giúp
               bạn cảm thấy như ở nhà.</p>
            <a href="#reservation" class="btn">make a reservation</a>
         </div>
      </div>

      <div class="row revers">
         <div class="image">
            <img src="images/about-img-2.jpg" alt="">
         </div>
         <div class="content">
            <h3>best foods</h3>
            <p>Thực đơn phong phú và đa dạng của chúng tôi sẽ làm hài lòng mọi đòi hỏi ẩm thực của bạn. Tận hưởng những
               món ngon độc đáo, được chế biến bởi đầu bếp tài năng, trong không gian sang trọng và ấm cúng của nhà hàng
               chúng tôi.</p>
            <a href="#contact" class="btn">contact us</a>
         </div>
      </div>

      <div class="row">
         <div class="image">
            <img src="images/about-img-3.jpg" alt="">
         </div>
         <div class="content">
            <h3>swimming pool</h3>
            <p>Hồ bơi của chúng tôi là nơi lý tưởng để thư giãn và tận hưởng ánh nắng mặt trời. Với nước biển mặn mẽ và
               không gian thoải mái, bạn có thể tận hưởng những giờ phút thư giãn, tận hưởng hồ bơi với gia đình hoặc
               bạn bè.</p>
            <a href="#availability" class="btn">check availability</a>
         </div>
      </div>

   </section>

   <!-- about section ends -->

   <!-- services section starts  -->

   <section class="services">

      <div class="box-container">

         <div class="box">
            <img src="images/icon-1.png" alt="">
            <h3>food & drinks</h3>
            <p>Thực đơn đa dạng và đồ uống tuyệt vời tại nhà hàng và quầy bar của chúng tôi sẽ làm bạn hài lòng.</p>
         </div>

         <div class="box">
            <img src="images/icon-2.png" alt="">
            <h3>outdoor dining</h3>
            <p>Tận hưởng không gian ăn uống ngoài trời tại chỗ, giữa không gian thoáng đãng và thời tiết tuyệt vời.</p>
         </div>

         <div class="box">
            <img src="images/icon-3.png" alt="">
            <h3>beach view</h3>
            <p>Vị trí biển tuyệt đẹp mang đến khung cảnh biển xanh và bãi cát trắng tuyệt đẹp trước mắt.</p>
         </div>

         <div class="box">
            <img src="images/icon-4.png" alt="">
            <h3>decorations</h3>
            <p>Khách sạn được trang trí với tinh tế, tạo nên không gian ấm cúng và lãng mạn, giúp bạn có trải nghiệm độc
               đáo.</p>
         </div>

         <div class="box">
            <img src="images/icon-5.png" alt="">
            <h3>swimming pool</h3>
            <p>Hồ bơi là nơi thư giãn tuyệt vời với nước biển mát lạnh và không gian thoải mái.</p>
         </div>

         <div class="box">
            <img src="images/icon-6.png" alt="">
            <h3>resort beach</h3>
            <p>Khu nghỉ dưỡng biển cách bãi biển riêng của chúng tôi, là nơi lý tưởng để tận hưởng thời gian thư giãn và
               tham gia các hoạt động biển.</p>
         </div>

      </div>

   </section>

   <!-- services section ends -->

   <!-- gallery section starts  -->

   <section class="gallery" id="gallery">

      <div class="swiper gallery-slider">
         <div class="swiper-wrapper">
            <img src="images/gallery-img-1.jpg" class="swiper-slide" alt="">
            <img src="images/gallery-img-2.webp" class="swiper-slide" alt="">
            <img src="images/gallery-img-3.webp" class="swiper-slide" alt="">
            <img src="images/gallery-img-4.webp" class="swiper-slide" alt="">
            <img src="images/gallery-img-5.webp" class="swiper-slide" alt="">
            <img src="images/gallery-img-6.webp" class="swiper-slide" alt="">
         </div>
         <div class="swiper-pagination"></div>
      </div>

   </section>

   <!-- gallery section ends -->

   <!-- contact section starts  -->

   <section class="contact" id="contact">

      <div class="row">

         <form action="" method="post">
            <h3>send us message</h3>
            <input type="text" name="name" required maxlength="50" placeholder="enter your name" class="box">
            <input type="email" name="email" required maxlength="50" placeholder="enter your email" class="box">
            <input type="number" name="number" required maxlength="10" min="0" max="9999999999"
               placeholder="enter your number" class="box">
            <textarea name="message" class="box" required maxlength="1000" placeholder="enter your message" cols="30"
               rows="10"></textarea>
            <input type="submit" value="send message" name="send" class="btn">
         </form>

         <div class="faq">
            <h3 class="title">Các câu hỏi thường gặp</h3>
            <div class="box active">
               <h3>Làm thế nào để hủy đặt phòng?</h3>
               <p>Để hủy đặt phòng, vui lòng liên hệ với bộ phận dịch vụ khách hàng của chúng tôi thông qua số điện
                  thoại hoặc địa chỉ email được cung cấp trên xác nhận đặt phòng của bạn. Họ sẽ hỗ trợ bạn trong quá
                  trình hủy đặt và cung cấp thông tin liên quan về chính sách hủy và các khoản phí có thể áp dụng.</p>
            </div>
            <div class="box">
               <h3>Còn chỗ trống không?</h3>
               <p>Để kiểm tra tính sẵn sàng của phòng, bạn có thể truy cập trang web của chúng tôi hoặc liên hệ trực
                  tiếp với đội ngũ đặt phòng qua điện thoại hoặc email. Họ sẽ cung cấp thông tin thời gian thực về tính
                  sẵn sàng của phòng và hỗ trợ bạn trong việc đặt phòng nếu còn phòng trống.</p>
            </div>
            <div class="box">
               <h3>Phương thức thanh toán là gì?</h3>
               <p>Chúng tôi chấp nhận nhiều phương thức thanh toán, bao gồm thẻ tín dụng (như Visa, MasterCard và
                  American Express), thẻ ghi nợ và chuyển khoản ngân hàng. Bạn có thể chọn phương thức thanh toán ưa
                  thích khi đặt phòng hoặc tham khảo thêm thông tin với đội ngũ dịch vụ khách hàng của chúng tôi.</p>
            </div>
            <div class="box">
               <h3>Làm thế nào để sử dụng mã giảm giá?</h3>
               <p>Nếu bạn có mã giảm giá hoặc mã khuyến mãi, bạn thường có thể nhập nó trong quá trình đặt phòng trên
                  trang web của chúng tôi. Thường có một ô được chỉ định để bạn nhập mã, và giảm giá hoặc ưu đãi kèm
                  theo mã sẽ được áp dụng vào đơn đặt phòng của bạn. Nếu gặp bất kỳ vấn đề nào, đội ngũ dịch vụ khách
                  hàng của chúng tôi có thể hỗ trợ bạn về việc sử dụng mã giảm giá.</p>
            </div>
            <div class="box">
               <h3>Yêu cầu về độ tuổi là gì?</h3>
               <p>Yêu cầu về độ tuổi có thể khác nhau tùy theo chính sách của khách sạn và quy định địa phương. Nói
                  chung, cá nhân phải ít nhất 18 tuổi để đặt phòng và nhận phòng tại khách sạn. Tuy nhiên, một số khách
                  sạn có thể có các yêu cầu độ tuổi cụ thể hoặc chính sách riêng đối với một số dịch vụ hoặc loại phòng.
                  Để biết thông tin chính xác về yêu cầu độ tuổi, nên liên hệ trực tiếp với khách sạn hoặc xem xét các
                  điều khoản và điều kiện của họ.</p>
            </div>
         </div>

      </div>

   </section>

   <!-- contact section ends -->

   <!-- reviews section starts  -->

   <section class="reviews" id="reviews">

      <div class="swiper reviews-slider">

         <div class="swiper-wrapper">
            <div class="swiper-slide box">
               <img src="images/pic-1.png" alt="">
               <h3>Mạnh - Bắc Ninh</h3>
               <p>Tôi đã có một kỳ nghỉ tuyệt vời ở khách sạn này. Nhân viên thân thiện và thực đơn đa dạng làm cho tôi
                  cảm thấy hài lòng.</p>
            </div>
            <div class="swiper-slide box">
               <img src="images/pic-2.png" alt="">
               <h3>Mạnh - Hà Nội 2</h3>
               <p>Không gian phòng ốc thoải mái và rất sạch sẽ. Tôi rất thích bãi biển riêng và dịch vụ thú vị tại hồ
                  bơi.</p>
            </div>
            <div class="swiper-slide box">
               <img src="images/pic-3.png" alt="">
               <h3>Dũng - Lào Ninh</h3>
               <p>Khách sạn này thực sự đáng giá. Dịch vụ khách hàng xuất sắc và các tiện nghi vượt qua sự kỳ vọng của
                  tôi.</p>
            </div>
            <div class="swiper-slide box">
               <img src="images/pic-4.png" alt="">
               <h3>Duy - Bố Sang</h3>
               <p>Đây là lần thứ hai tôi đã đến khách sạn này và tôi không bao giờ thất vọng. Mọi thứ đều hoàn hảo, từ
                  thực đơn tới cảnh quan biển tuyệt đẹp.</p>
            </div>
            <div class="swiper-slide box">
               <img src="images/pic-5.png" alt="">
               <h3>Tín - Thái Bình</h3>
               <p>Tôi thật sự ấn tượng với sự trang trí và không gian của khách sạn. Mọi thứ đều đẹp đẽ và sang trọng.
                  Tôi sẽ khuyên bạn bè của tôi đến đây.</p>
            </div>
         </div>

         <div class="swiper-pagination"></div>
      </div>

   </section>

   <!-- reviews section ends  -->


   <?php include 'components/footer.php'; ?>

   <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

   <?php include 'components/message.php'; ?>

</body>

</html>