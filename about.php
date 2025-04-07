<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - เกี่ยวกับเรา</title>
  <style>
    .box {
      border-top-color: var(--teal) !important;
    }
  </style>
</head>

<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center">เกี่ยวกับเรา</h2>
    <div class="h-line bg-dark"></div>
    <p class="text-center mt-3">
      Tvel คือเว็บไซต์ที่มุ่งเน้นการนำเสนอประสบการณ์การท่องเที่ยวที่ยอดเยี่ยมในอำเภอบางปะอิน เรามีข้อมูลครบครันเกี่ยวกับสถานที่ท่องเที่ยวที่น่าสนใจ พร้อมแนะนำที่พักและบริการต่าง ๆ <br>ที่สามารถตอบสนองความต้องการของนักท่องเที่ยวทุกคน ไม่ว่าจะเป็นการพักผ่อนแบบสงบหรือการค้นหาประสบการณ์ใหม่ ๆ ในการเดินทาง<br> ทีมงานของเราทุ่มเทในการให้บริการที่ดีที่สุด เพื่อให้ทุกการเดินทางของท่านสะดวกสบายและน่าจดจำ
    </p>
  </div><br>

  <div class="container">
    <div class="row justify-content-between align-items-center">
      <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2">
        <h3 class="mb-3">สู่ประสบการณ์การท่องเที่ยวที่ดีที่สุด</h3>
        <p>
        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Tvel มุ่งมั่นที่จะพาคุณเข้าสู่การเดินทางที่เต็มไปด้วยความสุขและความประทับใจในอำเภอบางปะอิน เรานำเสนอข้อมูลท่องเที่ยวที่ครอบคลุมและครบวงจร เพื่อให้คุณได้สัมผัสความงามและเอกลักษณ์ของสถานที่ต่าง ๆ ไม่ว่าจะเป็นวัดโบราณ พระราชวัง หรือแหล่งท่องเที่ยวทางธรรมชาติที่สวยงาม นอกจากนี้ เรายังมีการแนะนำที่พักที่สะดวกสบาย บริการที่เป็นมืออาชีพ และคำแนะนำเกี่ยวกับกิจกรรมท่องเที่ยวที่เหมาะสมกับทุกความต้องการของนักท่องเที่ยว
        <br> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;เรามุ่งเน้นให้คุณได้รับประสบการณ์ที่คุ้มค่าและสะดวกสบายที่สุดในการเดินทาง โดยการให้บริการที่ใส่ใจและช่วยเหลือท่านตลอดทุกขั้นตอนของการท่องเที่ยว ตั้งแต่การวางแผนการเดินทาง ไปจนถึงการสนับสนุนในระหว่างการท่องเที่ยว ทีมงานของเราพร้อมที่จะให้คำแนะนำที่ดีที่สุดเพื่อให้ท่านได้รับประสบการณ์การท่องเที่ยวที่ไม่เหมือนใครและน่าจดจำตลอดไป
        </p>
      </div>
      <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1">
        <img src="images/about/abouts.jpg" class="w-100">
      </div>
    </div>
  </div>

  <!-- <div class="container mt-5">
    <div class="row">
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
          <img src="images/about/hotel.svg" width="70px">
          <h4 class="mt-3">100+ ที่พัก</h4>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
          <img src="images/about/customers.svg" width="70px">
          <h4 class="mt-3">200+ ลูกค้า</h4>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
          <img src="images/about/rating.svg" width="70px">
          <h4 class="mt-3">150+ รีวิว</h4>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
          <img src="images/about/staff.svg" width="70px">
          <h4 class="mt-3">200+ พนักงาน</h4>
        </div>
      </div>
    </div>
  </div> -->

  <!-- <h3 class="my-5 fw-bold h-font text-center">สถานที่</h3>

  <div class="container px-4">
    <div class="swiper mySwiper">
      <div class="swiper-wrapper mb-5">
        <?php
        $about_r = selectAll('team_details');
        $path = ABOUT_IMG_PATH;
        while ($row = mysqli_fetch_assoc($about_r)) {
          echo <<<data
              <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                <img src="$path$row[picture]" class="w-100">
                <h5 class="mt-2">$row[name]</h5>
              </div>
            data;
        }

        ?>
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </div> -->


  <?php require('inc/footer.php'); ?>

  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

  <script>
    var swiper = new Swiper(".mySwiper", {
      spaceBetween: 40,
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
          slidesPerView: 3,
        },
        1024: {
          slidesPerView: 3,
        },
      }
    });
  </script>


</body>

</html>