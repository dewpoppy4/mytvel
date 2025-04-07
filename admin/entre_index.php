<?php
  require('inc/essentials.php');
  require('inc/db_config.php');

  session_start();
  if((isset($_SESSION['entreLogin']) && $_SESSION['entreLogin']==true)){
    redirect('entre_restaurants.php');
  }
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>เข้าสู่ระบบผู้ประกอบการ</title>
  <?php require('inc/links.php'); ?>
  <style>
    div.login-form{
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%,-50%);
      width: 400px;
    }
  </style>
</head>
<body class="bg-light">
  
  <div class="login-form text-center rounded bg-white shadow overflow-hidden">
    <form method="POST">
      <h4 class="bg-dark text-white py-3">เข้าสู่ระบบผู้ประกอบการ</h4>
      <div class="p-4">
        <div class="mb-3">
          <input name="entre_name" required type="text" 
                 class="form-control shadow-none text-center" 
                 placeholder="ชื่อผู้ใช้">
        </div>
        <div class="mb-4">
          <input name="entre_pass" required type="password" 
                 class="form-control shadow-none text-center" 
                 placeholder="รหัสผ่าน">
        </div>
        <button name="login" type="submit" 
                class="btn text-white custom-bg shadow-none">
          เข้าสู่ระบบ
        </button>
      </div>
      <div class="mb-3">
        <a href="entre_register.php" class="text-decoration-none">
          ยังไม่มีบัญชี? สมัครสมาชิก
        </a>
      </div>
    </form>
  </div>

  <?php 
    if(isset($_POST['login'])) {
      $frm_data = filteration($_POST);
      $query = "SELECT * FROM `entre_cred` WHERE `entre_name`=? AND `entre_pass`=?";
      $values = [$frm_data['entre_name'],$frm_data['entre_pass']];
      $res = select($query,$values,"ss");
      
      if($res->num_rows==1){
        $row = mysqli_fetch_assoc($res);
        $_SESSION['entreLogin'] = true;
        $_SESSION['entreId'] = $row['sr_no']; 
        redirect('entre_restaurants.php');
      }
      else{
        alert('error','ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง!');
      }
    }
  ?>

  <?php require('inc/scripts.php') ?>
</body>
</html>