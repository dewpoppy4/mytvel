
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - รายละเอียดสถานที่ท่องเที่ยว</title>
</head>

<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <?php
  if (!isset($_GET['id'])) {
    redirect('tvels.php');
  }

  $data = filteration($_GET);
  $tvel_res = select("SELECT * FROM `tvels` WHERE `id`=? AND `status`=? AND `removed`=?", [$data['id'], 1, 0], 'iii');

  if (mysqli_num_rows($tvel_res) == 0) {
    redirect('tvels.php');
  }

  $tvel_data = mysqli_fetch_assoc($tvel_res);
  ?>

  <div class="container">
    <div class="row">
      <!-- Name and Navigation -->
      <div class="col-12 my-5 mb-4 px-4">
        <h2 class="fw-bold"><?php echo $tvel_data['name'] ?></h2>
        <div style="font-size: 14px;">
          <a href="index.php" class="text-secondary text-decoration-none">หน้าแรก</a>
          <span class="text-secondary"> > </span>
          <a href="tvels.php" class="text-secondary text-decoration-none">สถานที่ท่องเที่ยว</a>
        </div>
      </div>

      <!-- Main Image Carousel -->
      <div class="col-lg-7 col-md-12 px-4">
        <div id="tvelCarousel" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <?php
            $tvel_img = TVELS_IMG_PATH . "thumbnail.jpg";
            $img_q = mysqli_query($con, "SELECT * FROM `tvel_images` 
                WHERE `tvel_id`='$tvel_data[id]'");

            if (mysqli_num_rows($img_q) > 0) {
              $active_class = 'active';
              while ($img_res = mysqli_fetch_assoc($img_q)) {
                echo "
                    <div class='carousel-item $active_class'>
                      <img src='" . TVELS_IMG_PATH . $img_res['image'] . "' class='d-block w-100 rounded'>
                    </div>
                  ";
                $active_class = '';
              }
            } else {
              echo "<div class='carousel-item active'>
                  <img src='$tvel_img' class='d-block w-100'>
                </div>";
            }
            ?>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#tvelCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#tvelCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      </div>


       <!-- Rating Summary -->
  <div class="col-lg-4">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body">
        <?php 
        // Calculate rating counts
        $rating_q = "SELECT rating, COUNT(*) as count FROM `rating_review` 
          WHERE tvel_id = '$tvel_data[id]' 
          GROUP BY rating ORDER BY rating DESC";
        
        $rating_res = mysqli_query($con, $rating_q);
        $total_reviews = 0;
        $rating_data = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        
        while($row = mysqli_fetch_assoc($rating_res)) {
          $rating_data[$row['rating']] = $row['count'];
          $total_reviews += $row['count'];
        }

        // Calculate average rating
        $avg_rating = 0;
        if($total_reviews > 0) {
          foreach($rating_data as $rating => $count) {
            $avg_rating += $rating * $count;
          }
          $avg_rating /= $total_reviews;
        }
        ?>

        <h2 class="mb-3 text-center"><?php echo number_format($avg_rating, 1) ?></h2>
        <div class="d-flex justify-content-center mb-3">
          <?php 
          for($i = 1; $i <= 5; $i++) {
            if($i <= $avg_rating) {
              echo '<i class="bi bi-star-fill text-warning"></i>';
            } else {
              echo '<i class="bi bi-star text-warning"></i>';
            }
          }
          ?>
        </div>
        <p class="text-center mb-4"><?php echo $total_reviews ?> รีวิว</p>

        <!-- Rating Bars -->
        <?php 
        foreach($rating_data as $rating => $count) {
          $percent = ($total_reviews > 0) ? ($count / $total_reviews) * 100 : 0;
          ?>
          <div class="d-flex align-items-center mb-2">
            <div class="text-nowrap me-3"><?php echo $rating ?> ดาว</div>
            <div class="progress flex-grow-1" style="height: 12px;">
              <div class="progress-bar bg-warning" 
                role="progressbar" 
                style="width: <?php echo $percent ?>%" 
                aria-valuenow="<?php echo $percent ?>" 
                aria-valuemin="0" 
                aria-valuemax="100">
              </div>
            </div>
            <div class="text-nowrap ms-3"><?php echo $count ?></div>
          </div>
          
        <?php } ?><br>
        <?php
      // เพิ่มปุ่มรีวิวสำหรับผู้ใช้ที่ล็อกอินแล้ว
      if(isset($_SESSION['login']) && $_SESSION['login']==true){
        echo<<<review
          <a href="review.php?item_id=$tvel_data[id]&item_type=tvel" 
             class="btn btn-outline-dark shadow-none w-100 mt-2">
            <i class="bi bi-star me-1"></i>ให้คะแนนและรีวิว
          </a>
        review;
      }
      ?>
      </div>
    </div>
  </div>
</div>

      <!-- Description and Reviews -->
      <div class="col-12 mt-4 px-4">
  <div class="mb-5">
    <h5>รายละเอียด</h5>
    <p class="text-break" style="text-indent: 2em; white-space: pre-line;">
      <?php echo $tvel_data['description'] ?>
    </p>
  </div>

  <div class="row">
    <div class="col-lg-8">
      <h5 class="mb-3">รีวิวและคะแนน</h5>
      <?php
      $review_q = "SELECT rr.*, uc.name AS uname, uc.profile 
                   FROM rating_review rr
                   INNER JOIN user_cred uc ON rr.user_id = uc.id
                   WHERE rr.tvel_id = '$tvel_data[id]'
                   ORDER BY sr_no DESC LIMIT 15";

      $review_res = mysqli_query($con, $review_q);
      $img_path = USERS_IMG_PATH;

      if (mysqli_num_rows($review_res) == 0) {
          echo '<p class="text-center text-muted">ยังไม่มีรีวิว!</p>';
      } else {
          while ($row = mysqli_fetch_assoc($review_res)) {
              // สร้าง HTML สำหรับแสดงดาว
              $stars = '';
              for ($i = 1; $i <= 5; $i++) {
                  if ($i <= $row['rating']) {
                      $stars .= "<i class='bi bi-star-fill text-warning'></i>";
                  } else {
                      $stars .= "<i class='bi bi-star text-warning'></i>";
                  }
              }

              echo <<<reviews
                  <div class="review-card mb-4 bg-white p-3 rounded shadow-sm">
                      <div class="d-flex justify-content-between align-items-center mb-2">
                          <div class="d-flex align-items-center">
                              <img src="$img_path$row[profile]" 
                                   class="rounded-circle me-2" 
                                   loading="lazy" 
                                   width="40px"
                                   height="40px"
                                   alt="User Profile">
                              <h6 class="m-0">$row[uname]</h6>
                          </div>
                          <div class="rating">
                              $stars
                              <small class="ms-2">($row[rating])</small>
                          </div>
                      </div>
                      <p class="review-text mb-0">$row[review]</p>
                  </div>
              reviews;
          }
      }
      ?>
    </div>
   
</div>
    </div>
  </div>

  <?php require('inc/footer.php'); ?>

</body>

</html>