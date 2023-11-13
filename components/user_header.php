<!-- header section starts  -->

<section class="header">

   <div class="flex">
      <a href="index.php" class="logo">
         <img src="images/logormbg.png">
      </a>
      <?php

      require_once 'components/connect.php';
      function logout()
      {
         if (isset($_COOKIE['user_id'])) {
            setcookie('user_id', '', time() - 3600, '/');
         }

         header('location: index.php');
      }

      $user_id = $_COOKIE['user_id'];
      $select_user = $conn->prepare("SELECT * FROM `users` WHERE `id` = ?");
      $select_user->execute([$user_id]);
      $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

      if (isset($_POST['logout'])) {
         logout();
      }
      ?>

      <div class="flex">
         <?php if (isset($fetch_user) && $fetch_user) { ?>
            <div class="flex">
               <a class="logo" id="openEdit"
                  style="background-color: transparent; cursor: pointer; text-decoration-line: underline;">
                  <?= $fetch_user['first_name'] . ' ' . $fetch_user['last_name']; ?>
               </a>
               <form action="" method="POST">
                  <input type="hidden" name="user_id" value="<?= $fetch_user['id']; ?>">
                  <input type="submit" value="Logout" onclick="return confirm('Logout this user?');" name="logout"
                     class="btn">
               </form>
            </div>
         <?php } else { ?>
            <a href="authentication.php" class="btn">Login</a>
            <a href="authentication.php" class="btn">Register</a>
         <?php } ?>
      </div>

      <div id="menu-btn" class="fas fa-bars"></div>
   </div>


   <nav class="navbar">
      <a href="index.php#home">home</a>
      <a href="index.php#about">about</a>
      <?php if ($fetch_user): ?>
         <a href="reservation.php">reservation</a>
      <?php else: ?>
         <a href="authentication.php" onclick="return confirm('Login to make a reservation.');">reservation</a>
      <?php endif; ?>
      <a href="index.php#gallery">gallery</a>
      <a href="index.php#contact">contact</a>
      <a href="index.php#reviews">reviews</a>
      <?php if ($fetch_user): ?>
         <a href="bookings.php">my bookings</a>
      <?php else: ?>
         <a href="authentication.php" onclick="return confirm('Login to view my bookings.');">my bookings</a>
      <?php endif; ?>
   </nav>

</section>

<!-- header section ends -->