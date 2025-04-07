<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - ติดต่อเรา</title>
</head>

<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center">ติดต่อเรา</h2>
    <div class="h-line bg-dark"></div>
    <p class="text-center mt-3">
      หากท่านมีข้อสงสัยหรือต้องการข้อมูลเพิ่มเติมเกี่ยวกับบริการของเรา ท่านสามารถติดต่อเราได้ทางโทรศัพท์หรืออีเมล ทีมงานของเรายินดีที่จะตอบคำถามและให้ความช่วยเหลือท่านในทุก ๆ เรื่อง <br>ไม่ว่าจะเป็นการจองที่พักหรือข้อมูลเกี่ยวกับสถานที่ท่องเที่ยวในบางปะอิน หากท่านต้องการติดต่อผ่านช่องทางโซเชียลมีเดีย เราก็พร้อมให้บริการเช่นกัน <br>เราจะทำทุกวิถีทางเพื่อให้ท่านได้รับการช่วยเหลือและคำแนะนำที่ดีที่สุดในการเดินทางของท่าน
    </p>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-lg-6 col-md-6 mb-5 px-4">

        <div class="bg-white rounded shadow p-4">
          <iframe class="w-100 rounded mb-4" height="320px" src="<?php echo $contact_r['iframe'] ?>" loading="lazy"></iframe>

          <h5>ที่อยู่</h5>
          <a href="<?php echo $contact_r['gmap'] ?>" target="_blank" class="d-inline-block text-decoration-none text-dark mb-2">
            <i class="bi bi-geo-alt-fill"></i> <?php echo $contact_r['address'] ?>
          </a>

          <h5 class="mt-4">ติดต่อ</h5>
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


          <h5 class="mt-4">อีเมล</h5>
          <a href="mailto: <?php echo $contact_r['email'] ?>" class="d-inline-block text-decoration-none text-dark">
            <i class="bi bi-envelope-fill"></i> <?php echo $contact_r['email'] ?>
          </a>

          <h5 class="mt-4">ติดตามเรา</h5>
          <?php
          if ($contact_r['tw'] != '') {
            echo <<<data
                <a href="$contact_r[tw]" class="d-inline-block text-dark fs-5 me-2">
                  <i class="bi bi-twitter me-1"></i>
                </a>
              data;
          }
          ?>

          <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block text-dark fs-5 me-2">
            <i class="bi bi-facebook me-1"></i>
          </a>
          <a href="<?php echo $contact_r['insta'] ?>" class="d-inline-block text-dark fs-5">
            <i class="bi bi-instagram me-1"></i>
          </a>
        </div>
      </div>
      <div class="col-lg-6 col-md-6 px-4">
        <div class="bg-white rounded shadow p-4">
          <form method="POST">
            <h5>ส่งข้อความถึงเรา</h5>
            <div class="mt-3">
              <label class="form-label" style="font-weight: 500;">ชื่อ</label>
              <input name="name" required type="text" class="form-control shadow-none">
            </div>
            <div class="mt-3">
              <label class="form-label" style="font-weight: 500;">อีเมล</label>
              <input name="email" required type="email" class="form-control shadow-none">
            </div>
            <div class="mt-3">
              <label class="form-label" style="font-weight: 500;">เรื่อง</label>
              <input name="subject" required type="text" class="form-control shadow-none">
            </div>
            <div class="mt-3">
              <label class="form-label" style="font-weight: 500;">ข้อความ</label>
              <textarea name="message" required class="form-control shadow-none" rows="5" style="resize: none;"></textarea>
            </div>
            <div class="d-flex justify-content-center">
              <button type="submit" name="send" class="btn text-white custom-bg mt-3">ส่งเลย</button>
            </div>
            
          </form>
        </div>
      </div>
    </div>
  </div>


  <?php

  if (isset($_POST['send'])) {
    $frm_data = filteration($_POST);

    $q = "INSERT INTO `user_queries`(`name`, `email`, `subject`, `message`) VALUES (?,?,?,?)";
    $values = [$frm_data['name'], $frm_data['email'], $frm_data['subject'], $frm_data['message']];

    $res = insert($q, $values, 'ssss');
    if ($res == 1) {
      alert('success', 'Mail sent!');
    } else {
      alert('error', 'Server Down! Try again later.');
    }
  }
  ?>

  <?php require('inc/footer.php'); ?>

</body>

</html>