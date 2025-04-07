<?php
  require('inc/essentials.php');
  require('inc/db_config.php');

  session_start();
  if((isset($_SESSION['adminLogin']) && $_SESSION['adminLogin']==true)){
    redirect('dashboard.php');
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>สมัครสมาชิกผู้ดูแลระบบ</title>
  <?php require('inc/links.php'); ?>
  <style>
    div.register-form {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%,-50%);
      width: 400px;
    }
  </style>
</head>
<body class="bg-light">
  
  <div class="register-form text-center rounded bg-white shadow overflow-hidden">
    <form method="POST">
      <h4 class="bg-dark text-white py-3">สมัครสมาชิกผู้ดูแลระบบ</h4>
      <div class="p-4">
        <div class="mb-3">
          <input name="admin_name" required type="text" 
            class="form-control shadow-none" 
            placeholder="ชื่อผู้ใช้">
        </div>
        <div class="mb-3">
          <input name="admin_pass" required type="password" 
            class="form-control shadow-none" 
            placeholder="รหัสผ่าน">
        </div>
        <div class="mb-4">
          <input name="confirm_pass" required type="password" 
            class="form-control shadow-none" 
            placeholder="ยืนยันรหัสผ่าน">
        </div>
        <button name="register" type="submit" 
          class="btn text-white custom-bg shadow-none">
          สมัครสมาชิก
        </button>
      </div>
      <div class="mb-3">
        <a href="index.php" class="text-decoration-none">
          มีบัญชีอยู่แล้ว? เข้าสู่ระบบ
        </a>
      </div>
    </form>
  </div>

  <?php 
    if(isset($_POST['register'])) {
      $frm_data = filteration($_POST);

      // ตรวจสอบรหัสผ่านตรงกัน
      if($frm_data['admin_pass'] != $frm_data['confirm_pass']) {
        alert('error','รหัสผ่านไม่ตรงกัน!');
        exit;
      }

      // ตรวจสอบชื่อผู้ใช้ซ้ำ
      $query = "SELECT * FROM `admin_cred` WHERE `admin_name`=?";
      $values = [$frm_data['admin_name']];
      $res = select($query,$values,"s");

      if($res->num_rows != 0) {
        alert('error','ชื่อผู้ใช้นี้มีอยู่แล้ว!');
        exit;
      }

      // เพิ่มข้อมูลผู้ดูแลระบบใหม่
      $query = "INSERT INTO `admin_cred`(`admin_name`,`admin_pass`) VALUES (?,?)";
      $values = [$frm_data['admin_name'],$frm_data['admin_pass']];
      
      if(insert($query,$values,'ss')) {
        alert('success','สมัครสมาชิกสำเร็จ!');
        redirect('index.php');
      }
      else {
        alert('error','เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง!');
      }
    }
  ?>

  <?php require('inc/scripts.php') ?>
</body>
</html>