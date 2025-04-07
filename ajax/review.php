<?php
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

// เริ่ม session
session_start();

// ตรวจสอบว่ามีการล็อกอินหรือไม่
if(!isset($_SESSION['login']) || $_SESSION['login'] != true){
  echo 0;
  exit;
}

if(isset($_POST['review_form'])) {
  $frm_data = filteration($_POST);
  
  // เลือกฟิลด์ในฐานข้อมูลตามประเภทรายการ
  $table_id_field = '';
  switch($frm_data['item_type']) {
    case 'room':
      $table_id_field = 'room_id';
      break;
    case 'tvel':
      $table_id_field = 'tvel_id';
      break;
    case 'restaurant':
      $table_id_field = 'restaurant_id';
      break;
    default:
      echo 0;
      exit;
  }

  // บันทึกข้อมูลลงฐานข้อมูล
  $q = "INSERT INTO `rating_review`(`$table_id_field`, `user_id`, `rating`, `review`) 
        VALUES (?,?,?,?)";
  
  $values = [$frm_data['item_id'], $_SESSION['uId'], 
             $frm_data['rating'], $frm_data['review']];

  $result = insert($q, $values, 'iiss');
  
  echo $result;
}
?>