<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - หน้าแรก</title>
  <style>
    .availability-form {
      margin-top: -50px;
      z-index: 2;
      position: relative;
    }

    @media screen and (max-width: 575px) {
      .availability-form {
        margin-top: 25px;
        padding: 0 35px;
      }
    }
  </style>
</head>

<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <!-- Carousel -->

  <div class="container-fluid px-lg-4 mt-4">
    <div class="swiper swiper-container">
      <div class="swiper-wrapper">
        <?php
        $res = selectAll('carousel');
        while ($row = mysqli_fetch_assoc($res)) {
          $path = CAROUSEL_IMG_PATH;
          echo <<<data
              <div class="swiper-slide">
                <img src="$path$row[image]" class="w-100 d-block">
              </div>
            data;
        }
        ?>
      </div>
    </div>
  </div>

  <!-- check availability form -->

  <div class="container availability-form">
    <div class="row">
      <div class="col-lg-12 bg-white shadow p-4 rounded">
        <h5 class="mb-4"></h5>
        <form action="rooms.php">
          <div class="row align-items-end">
            <div class="col-lg-3 mb-3">
              <label class="form-label" style="font-weight: 700;">เช็ค-อิน</label>
              <input type="date" class="form-control shadow-none" name="checkin" required>
            </div>
            <div class="col-lg-3 mb-3">
              <label class="form-label" style="font-weight: 700;">เช็ค-เอาท์</label>
              <input type="date" class="form-control shadow-none" name="checkout" required>
            </div>
            <div class="col-lg-3 mb-3">
              <label class="form-label" style="font-weight: 700;">ผู้ใหญ่</label>
              <select class="form-select shadow-none" name="adult">
                <?php
                $guests_q = mysqli_query($con, "SELECT MAX(adult) AS `max_adult`, MAX(children) AS `max_children` 
                    FROM `rooms` WHERE `status`='1' AND `removed`='0'");
                $guests_res = mysqli_fetch_assoc($guests_q);

                for ($i = 1; $i <= $guests_res['max_adult']; $i++) {
                  echo "<option value='$i'>$i</option>";
                }
                ?>
              </select>
            </div>
            <div class="col-lg-2 mb-3">
              <label class="form-label" style="font-weight: 700;">เด็ก</label>
              <select class="form-select shadow-none" name="children">
                <?php
                for ($i = 1; $i <= $guests_res['max_children']; $i++) {
                  echo "<option value='$i'>$i</option>";
                }
                ?>
              </select>
            </div>
            <input type="hidden" name="check_availability">
            <div class="col-lg-1 mb-lg-3 mt-2">
              <button type="submit" class="btn text-white shadow-none custom-bg">ค้นหา</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">สถานที่ท่องเที่ยวที่คุณอาจสนใจ</h2>

  <div class="container">
    <div class="row">
      <?php
      
      $tvel_res = select("SELECT * FROM `tvels` WHERE `status`=? AND `removed`=? ORDER BY `id` DESC LIMIT 3", [1, 0], 'ii');

      while ($tvel_data = mysqli_fetch_assoc($tvel_res)) {
        // get thumbnail of image
        $tvel_thumb = TVELS_IMG_PATH . "thumbnail.jpg";
        $thumb_q = mysqli_query($con, "SELECT * FROM `tvel_images` 
            WHERE `tvel_id`='$tvel_data[id]' 
            AND `thumb`='1'");
    
        if (mysqli_num_rows($thumb_q) > 0) {
            $thumb_res = mysqli_fetch_assoc($thumb_q);
            $tvel_thumb = TVELS_IMG_PATH . $thumb_res['image'];
        }
    
        // Handle description length limit
        $description = $tvel_data['description'];
if (mb_strlen($description, 'UTF-8') > 250) {
    $description = mb_substr($description, 0, 250, 'UTF-8') . '...';
}
    
        // ดึงข้อมูลคะแนนและรีวิว
        $rating_q = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews 
             FROM rating_review 
             WHERE tvel_id = '$tvel_data[id]'";
$rating_res = mysqli_query($con, $rating_q);
$rating_fetch = mysqli_fetch_assoc($rating_res);

// แปลงคะแนนให้เหลือทศนิยม 1 ตำแหน่ง
$avg_rating = number_format($rating_fetch['avg_rating'] ?? 0, 1); 
$total_reviews = $rating_fetch['total_reviews'];

// สร้าง HTML สำหรับแสดงดาว
$stars = '';
for($i = 1; $i <= 5; $i++) {
    if($i <= $avg_rating){
        $stars .= "<i class='bi bi-star-fill text-warning'></i>";
    } else {
        $stars .= "<i class='bi bi-star text-warning'></i>";
    }
}
    
        // แสดงการ์ด
        echo <<<data
            <div class="col-lg-4 col-md-6 my-3">
                <div class="card border-0 shadow" style="max-width: 350px; margin: auto;">
                    <img src="$tvel_thumb" class="card-img-top" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5>$tvel_data[name]</h5>
                        <div class="d-flex align-items-center mb-3">
                            <div class="rating">
                                $stars
                                <span class="ms-2">($avg_rating)</span>
                            </div>
                            <div class="ms-auto">
                                <small class="text-muted">$total_reviews รีวิว</small>
                            </div>
                        </div>
                        <div class="description mb-4 ">
                            <p>$description</p>
                        </div>
                        <div class="d-flex justify-content-evenly mb-2">
                            <a href="tvel_details.php?id=$tvel_data[id]" class="btn btn-sm btn-outline-dark shadow-none">ดูรายละเอียด</a>
                        </div>
                    </div>
                </div>
            </div>
        data;
    }
      ?>

      <div class="col-lg-12 text-center mt-5">
        <a href="tvels.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">ดูเพิ่มเติม >>></a>
      </div>
    </div>
  </div>

  <!-- Our Rooms -->

  <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">ที่พักคุณอาจสนใจ</h2>

  <div class="container">
    <div class="row">

      <?php

      $room_res = select("SELECT * FROM `rooms` WHERE `status`=? AND `removed`=? ORDER BY `id` DESC LIMIT 3", [1, 0], 'ii');

      while ($room_data = mysqli_fetch_assoc($room_res)) {
        // get features of room

        $fea_q = mysqli_query($con, "SELECT f.name FROM `features` f 
            INNER JOIN `room_features` rfea ON f.id = rfea.features_id 
            WHERE rfea.room_id = '$room_data[id]'");

        $features_data = "";
        while ($fea_row = mysqli_fetch_assoc($fea_q)) {
          $features_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
              $fea_row[name]
            </span>";
        }

        // get facilities of room

        $fac_q = mysqli_query($con, "SELECT f.name FROM `facilities` f 
            INNER JOIN `room_facilities` rfac ON f.id = rfac.facilities_id 
            WHERE rfac.room_id = '$room_data[id]'");

        $facilities_data = "";
        while ($fac_row = mysqli_fetch_assoc($fac_q)) {
          $facilities_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
              $fac_row[name]
            </span>";
        }

        // get thumbnail of image

        $room_thumb = ROOMS_IMG_PATH . "thumbnail.jpg";
        $thumb_q = mysqli_query($con, "SELECT * FROM `room_images` 
            WHERE `room_id`='$room_data[id]' 
            AND `thumb`='1'");

        if (mysqli_num_rows($thumb_q) > 0) {
          $thumb_res = mysqli_fetch_assoc($thumb_q);
          $room_thumb = ROOMS_IMG_PATH . $thumb_res['image'];
        }

        $book_btn = "";

        if (!$settings_r['shutdown']) {
          $login = 0;
          if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
            $login = 1;
          }

          $book_btn = "<button onclick='checkLoginToBook($login,$room_data[id])' class='btn btn-sm text-white custom-bg shadow-none'>จองที่พัก</button>";
        }

        // ดึงข้อมูลคะแนนและรีวิว
        $rating_q = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews 
             FROM rating_review 
             WHERE room_id = '$room_data[id]'";
$rating_res = mysqli_query($con, $rating_q);
$rating_fetch = mysqli_fetch_assoc($rating_res);

// แปลงคะแนนให้เหลือทศนิยม 1 ตำแหน่ง
$avg_rating = number_format($rating_fetch['avg_rating'] ?? 0, 1);
$total_reviews = $rating_fetch['total_reviews'];

// สร้าง HTML สำหรับแสดงดาว
$stars = '';
for($i = 1; $i <= 5; $i++) {
    if($i <= $avg_rating){
        $stars .= "<i class='bi bi-star-fill text-warning'></i>";
    } else {
        $stars .= "<i class='bi bi-star text-warning'></i>";
    }
}

// แก้ไขส่วนแสดงการ์ดห้องพัก
echo <<<data
    <div class="col-lg-4 col-md-6 my-3">
        <div class="card border-0 shadow" style="max-width: 350px; margin: auto;">
            <img src="$room_thumb" class="card-img-top" style="height: 200px; object-fit: cover;">
            <div class="card-body">
                <h5>$room_data[name]</h5>
                <div class="d-flex align-items-center mb-3">
                    <div class="rating">
                        $stars
                        <span class="ms-2">($avg_rating)</span>
                    </div>
                    <div class="ms-auto">
                        <small class="text-muted">$total_reviews รีวิว</small>
                    </div>
                </div>
                <h6 class="mb-4">฿ $room_data[price] ต่อคืน</h6>
                <div class="features mb-4">
                    <h6 class="mb-1">คุณสมบัติ</h6>
                    $features_data
                </div>
                <div class="facilities mb-4">
                    <h6 class="mb-1">สิ่งอำนวยความสะดวก</h6>
                    $facilities_data
                </div>
                <div class="guests mb-4">
                    <h6 class="mb-1">ผู้เข้าพัก</h6>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        $room_data[adult] ผู้ใหญ่
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        $room_data[children] เด็ก
                    </span>
                </div>
                <div class="d-flex justify-content-evenly mb-2">
                    $book_btn
                    <a href="room_details.php?id=$room_data[id]" class="btn btn-sm btn-outline-dark shadow-none">ดูรายละเอียด</a>
                </div>
            </div>
        </div>
    </div>
data;
      }

      ?>

      <div class="col-lg-12 text-center mt-5">
        <a href="rooms.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">ดูเพิ่มเติม >>></a>
      </div>
    </div>
  </div>

  <!-- Our Facilities -->

  

  <!-- Testimonials -->

  <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">รีวิว</h2>

  <div class="container mt-5">
    <div class="swiper swiper-testimonials">
      <div class="swiper-wrapper mb-5">
        <?php

$review_q = "SELECT 
    rr.*, uc.name AS uname, uc.profile,
    CASE
        WHEN rr.room_id IS NOT NULL THEN r.name
        WHEN rr.tvel_id IS NOT NULL THEN t.name
        WHEN rr.restaurant_id IS NOT NULL THEN res.name
    END AS item_name,
    CASE
        WHEN rr.room_id IS NOT NULL THEN 'ที่พัก'
        WHEN rr.tvel_id IS NOT NULL THEN 'สถานที่ท่องเที่ยว'
        WHEN rr.restaurant_id IS NOT NULL THEN 'ร้านอาหาร'
    END AS item_type
FROM `rating_review` rr
INNER JOIN `user_cred` uc ON rr.user_id = uc.id
LEFT JOIN `rooms` r ON rr.room_id = r.id
LEFT JOIN `tvels` t ON rr.tvel_id = t.id
LEFT JOIN `restaurants` res ON rr.restaurant_id = res.id
WHERE (rr.room_id IS NOT NULL OR rr.tvel_id IS NOT NULL OR rr.restaurant_id IS NOT NULL)
    AND rr.rating IS NOT NULL
    AND (r.status = 1 OR t.status = 1 OR res.status = 1)
    AND (r.removed = 0 OR t.removed = 0 OR res.removed = 0)
ORDER BY rr.sr_no DESC 
LIMIT 6";

$review_res = mysqli_query($con, $review_q);
$img_path = USERS_IMG_PATH;

if (mysqli_num_rows($review_res) == 0) {
echo 'ยังไม่มีรีวิว!';
} else {
while ($row = mysqli_fetch_assoc($review_res)) {
    $stars = "";
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $row['rating']) {
            $stars .= "<i class='bi bi-star-fill text-warning'></i>";
        } else {
            $stars .= "<i class='bi bi-star text-warning'></i>";
        }
    }

    echo <<<slides
        <div class="swiper-slide bg-white p-4">
            <div class="profile d-flex align-items-center mb-3">
                <img src="$img_path$row[profile]" class="rounded-circle" loading="lazy" width="30px">
                <h6 class="m-0 ms-2">$row[uname]</h6>
            </div>
            <div class="item-info mb-2">
                <small class="badge bg-light text-dark">$row[item_type]</small>
                <h6 class="m-0">$row[item_name]</h6>
            </div>
            <p class="mb-2">$row[review]</p>
            <div class="rating">
                $stars
            </div>
        </div>
    slides;
}
}

        ?>
      </div>
      <div class="swiper-pagination"></div>
    </div>
    <div class="col-lg-12 text-center mt-5">
      <a href="about.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">ดูเพิ่มเติม >>></a>
    </div>
  </div>

  <!-- Reach us -->

  <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">ติดต่อเรา</h2>

  <div class="container">
    <div class="row">
      <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded">
        <iframe class="w-100 rounded" height="320px" src="<?php echo $contact_r['iframe'] ?>" loading="lazy"></iframe>
      </div>
      <div class="col-lg-4 col-md-4">
        <div class="bg-white p-4 rounded mb-4">
          <h5>เบอร์โทร</h5>
          <a href="tel: +<?php echo $contact_r['pn1'] ?>" class="d-inline-block mb-2 text-decoration-none text-dark">
            <i class="bi bi-telephone-fill"></i> +<?php echo $contact_r['pn1'] ?>
          </a>
          <br>
          <?php
          if ($contact_r['pn2'] != '') {
            echo <<<data
                <a href="tel: +$contact_r[pn2]" class="d-inline-block text-decoration-none text-dark">
                  <i class="bi bi-telephone-fill"></i> +$contact_r[pn2]
                </a>
              data;
          }

          ?>
        </div>
        <div class="bg-white p-4 rounded mb-4">
          <h5>การติดตาม</h5>
          <?php
          if ($contact_r['tw'] != '') {
            echo <<<data
                <a href="$contact_r[tw]" class="d-inline-block mb-3">
                  <span class="badge bg-light text-dark fs-6 p-2"> 
                  <i class="bi bi-twitter me-1"></i> Twitter
                  </span>
                </a>
                <br>
              data;
          }
          ?>

          <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block mb-3">
            <span class="badge bg-light text-dark fs-6 p-2">
              <i class="bi bi-facebook me-1"></i> Facebook
            </span>
          </a>
          <br>
          <a href="<?php echo $contact_r['insta'] ?>" class="d-inline-block">
            <span class="badge bg-light text-dark fs-6 p-2">
              <i class="bi bi-instagram me-1"></i> Instagram
            </span>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Password reset modal and code -->

  <div class="modal fade" id="recoveryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="recovery-form">
          <div class="modal-header">
            <h5 class="modal-title d-flex align-items-center">
              <i class="bi bi-shield-lock fs-3 me-2"></i> Set up New Password
            </h5>
          </div>
          <div class="modal-body">
            <div class="mb-4">
              <label class="form-label">New Password</label>
              <input type="password" name="pass" required class="form-control shadow-none">
              <input type="hidden" name="email">
              <input type="hidden" name="token">
            </div>
            <div class="mb-2 text-end">
              <button type="button" class="btn shadow-none me-2" data-bs-dismiss="modal">CANCEL</button>
              <button type="submit" class="btn btn-dark shadow-none">SUBMIT</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


  <?php require('inc/footer.php'); ?>

  <?php

  if (isset($_GET['account_recovery'])) {
    $data = filteration($_GET);

    $t_date = date("Y-m-d");

    $query = select(
      "SELECT * FROM `user_cred` WHERE `email`=? AND `token`=? AND `t_expire`=? LIMIT 1",
      [$data['email'], $data['token'], $t_date],
      'sss'
    );

    if (mysqli_num_rows($query) == 1) {
      echo <<<showModal
          <script>
            var myModal = document.getElementById('recoveryModal');

            myModal.querySelector("input[name='email']").value = '$data[email]';
            myModal.querySelector("input[name='token']").value = '$data[token]';

            var modal = bootstrap.Modal.getOrCreateInstance(myModal);
            modal.show();
          </script>
        showModal;
    } else {
      alert("error", "Invalid or Expired Link !");
    }
  }

  ?>

  <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

  <script>
    var swiper = new Swiper(".swiper-container", {
      spaceBetween: 30,
      effect: "fade",
      loop: true,
      autoplay: {
        delay: 3500,
        disableOnInteraction: false,
      }
    });

    var swiper = new Swiper(".swiper-testimonials", {
      effect: "coverflow",
      grabCursor: true,
      centeredSlides: true,
      slidesPerView: "auto",
      slidesPerView: "3",
      loop: true,
      coverflowEffect: {
        rotate: 50,
        stretch: 0,
        depth: 100,
        modifier: 1,
        slideShadows: false,
      },
      pagination: {
        el: ".swiper-pagination",
      },
      breakpoints: {
        320: {
          slidesPerView: 1,
        },
        640: {
          slidesPerView: 1,
        },
        768: {
          slidesPerView: 2,
        },
        1024: {
          slidesPerView: 3,
        },
      }
    });

    // recover account

    let recovery_form = document.getElementById('recovery-form');

    recovery_form.addEventListener('submit', (e) => {
      e.preventDefault();

      let data = new FormData();

      data.append('email', recovery_form.elements['email'].value);
      data.append('token', recovery_form.elements['token'].value);
      data.append('pass', recovery_form.elements['pass'].value);
      data.append('recover_user', '');

      var myModal = document.getElementById('recoveryModal');
      var modal = bootstrap.Modal.getInstance(myModal);
      modal.hide();

      let xhr = new XMLHttpRequest();
      xhr.open("POST", "ajax/login_register.php", true);

      xhr.onload = function() {
        if (this.responseText == 'failed') {
          alert('error', "Account reset failed!");
        } else {
          alert('success', "Account Reset Successful !");
          recovery_form.reset();
        }
      }

      xhr.send(data);
    });
  </script>

</body>

</html>