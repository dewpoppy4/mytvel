<?php 
  require('../admin/inc/db_config.php');
  require('../admin/inc/essentials.php');
  date_default_timezone_set("Asia/bangkok");

  session_start();

  if(isset($_GET['fetch_restaurants']))
  {
    // ดึงข้อมูลการค้นหาจาก URL parameters
    $chk_avail = json_decode($_GET['chk_avail'],true);
    
    // ตรวจสอบความถูกต้องของวันที่เช็คอิน/เช็คเอาท์
    if($chk_avail['checkin']!='' && $chk_avail['checkout']!='')
    {
      $today_date = new DateTime(date("Y-m-d"));
      $checkin_date = new DateTime($chk_avail['checkin']);
      $checkout_date = new DateTime($chk_avail['checkout']);
  
      if($checkin_date == $checkout_date){
        echo"<h3 class='text-center text-danger'>วันที่ไม่ถูกต้อง!</h3>";
        exit;
      }
      else if($checkout_date < $checkin_date){
        echo"<h3 class='text-center text-danger'>วันที่ไม่ถูกต้อง!</h3>";
        exit;
      }
      else if($checkin_date < $today_date){
        echo"<h3 class='text-center text-danger'>วันที่ไม่ถูกต้อง!</h3>";
        exit;
      }
    }

    // แปลงข้อมูลจำนวนผู้เข้าพัก
    $guests = json_decode($_GET['guests'],true);
    $adults = ($guests['adults']!='') ? $guests['adults'] : 0;
    $children = ($guests['children']!='') ? $guests['children'] : 0;

    // แปลงข้อมูลสิ่งอำนวยความสะดวก
    $facility_list = json_decode($_GET['facility_list'],true);

    $count_restaurants = 0;
    $output = "";

    // ตรวจสอบสถานะเว็บไซต์
    $settings_q = "SELECT * FROM `settings` WHERE `sr_no`=1";
    $settings_r = mysqli_fetch_assoc(mysqli_query($con,$settings_q));

    // ดึงข้อมูลสถานที่ท่องเที่ยวทั้งหมด
    $restaurant_res = select("SELECT * FROM `restaurants` WHERE `status`=? AND `removed`=?",[1,0],'ii');

    while($restaurant_data = mysqli_fetch_assoc($restaurant_res))
    {
      // ตรวจสอบการจองในช่วงวันที่เลือก
      if($chk_avail['checkin']!='' && $chk_avail['checkout']!='')
      {
        $tb_query = "SELECT COUNT(*) AS `total_bookings` FROM `booking_order`
          WHERE booking_status=? AND restaurant_id=?
          AND check_out > ? AND check_in < ?";

        $values = ['booked',$restaurant_data['id'],$chk_avail['checkin'],$chk_avail['checkout']];
        $tb_fetch = mysqli_fetch_assoc(select($tb_query,$values,'siss'));

        if(($restaurant_data['quantity']-$tb_fetch['total_bookings'])==0){
          continue;
        }
      }

      // ดึงข้อมูลสิ่งอำนวยความสะดวก
      $fac_count=0;
      $fac_q = mysqli_query($con,"SELECT f.name, f.id FROM `facilities` f 
        INNER JOIN `restaurant_facilities` tfac ON f.id = tfac.facilities_id 
        WHERE tfac.restaurant_id = '$restaurant_data[id]'");

      $facilities_data = "";
      while($fac_row = mysqli_fetch_assoc($fac_q))
      {
        if(in_array($fac_row['id'],$facility_list['facilities'])){
          $fac_count++;
        }
        $facilities_data .="<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
          $fac_row[name]
        </span>";
      }

      if(count($facility_list['facilities'])!=$fac_count){
        continue;
      }

      // ดึงข้อมูลคุณลักษณะพิเศษ
      $fea_q = mysqli_query($con,"SELECT f.name FROM `features` f 
        INNER JOIN `restaurant_features` tfea ON f.id = tfea.features_id 
        WHERE tfea.restaurant_id = '$restaurant_data[id]'");

      $features_data = "";
      while($fea_row = mysqli_fetch_assoc($fea_q)){
        $features_data .="<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
          $fea_row[name]
        </span>";
      }

      // ดึงข้อมูลรูปภาพ
      $restaurant_thumb = RESTAURANTS_IMG_PATH."thumbnail.jpg";
      $thumb_q = mysqli_query($con,"SELECT * FROM `restaurant_images` 
        WHERE `restaurant_id`='$restaurant_data[id]' AND `thumb`='1'");

      if(mysqli_num_rows($thumb_q)>0){
        $thumb_res = mysqli_fetch_assoc($thumb_q);
        $restaurant_thumb = RESTAURANTS_IMG_PATH.$thumb_res['image'];
      }
     
      // ดึงข้อมูลคะแนนและรีวิว
      $rating_q = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews 
                   FROM rating_review WHERE restaurant_id = '$restaurant_data[id]'";
      $rating_res = mysqli_query($con, $rating_q);
      $rating_fetch = mysqli_fetch_assoc($rating_res);

      $avg_rating = $rating_fetch['avg_rating'] ?? 0;
      $total_reviews = $rating_fetch['total_reviews'];

      // ตัดข้อความให้เหลือ 50 ตัวอักษร
      $short_desc = '';
if(strlen($restaurant_data['description']) > 350) {
    $short_desc = mb_substr($restaurant_data['description'], 0, 350, 'UTF-8') . '...';
} else {
    $short_desc = $restaurant_data['description'];
}

      // สร้าง HTML สำหรับแสดงดาว
      $stars = '';
      for($i = 1; $i <= 5; $i++) {
          if($i <= $avg_rating){
              $stars .= "<i class='bi bi-star-fill text-warning'></i>";
          } else {
              $stars .= "<i class='bi bi-star text-warning'></i>";
          }
      }


      // สร้าง HTML สำหรับแสดงการ์ด
      $output.="
  <div class='card mb-4 border-0 shadow'>
    <div class='row g-0 p-3 align-items-center'>
      <div class='col-md-5 mb-lg-0 mb-md-0 mb-3'>
    <img src='$restaurant_thumb' 
         class='img-fluid rounded' 
         style='width: 97%; height: 300px; object-fit: cover;'>
</div>
      <div class='col-md-5 px-lg-3 px-md-3 px-0'>
        <h5 class='mb-3'>$restaurant_data[name]</h5>
        <div class='features mb-3'>
          $features_data
        </div>
        <div class='facilities mb-3'>
          $facilities_data
        </div>
        <div class='rating mb-4'>
          <h6 class='mb-1'>คะแนน</h6>
          $stars
          <span class='ms-2'>(".number_format($avg_rating,1).")</span>
          <span class='text-muted ms-2'>$total_reviews รีวิว</span>
        </div>
        <div class='description mb-3'>
          <p>$short_desc</p>
        </div>
      </div>
      <div class='col-md-2 mt-lg-0 mt-md-0 mt-4 text-center'>
        <a href='restaurant_details.php?id=$restaurant_data[id]' 
           class='btn btn-sm w-100 btn-outline-dark shadow-none'>ดูรายละเอียด</a>
      </div>
    </div>
  </div>
      ";

      $count_restaurants++;
    }

    if($count_restaurants>0){
      echo $output;
    }
    else{
      echo"<h3 class='text-center text-danger'>ไม่พบสถานที่ท่องเที่ยว!</h3>";
    }
  }
?>