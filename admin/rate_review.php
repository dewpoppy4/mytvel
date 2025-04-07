<?php
  require('inc/essentials.php');
  require('inc/db_config.php');
  adminLogin();

  if(isset($_GET['seen']))
  {
    $frm_data = filteration($_GET);

    if($frm_data['seen']=='all'){
      $q = "UPDATE `rating_review` SET `seen`=?";
      $values = [1];
      if(update($q,$values,'i')){
        alert('success','Marked all as read!');
      }
      else{
        alert('error','Operation Failed!');
      }
    }
    else{
      $q = "UPDATE `rating_review` SET `seen`=? WHERE `sr_no`=?";
      $values = [1,$frm_data['seen']];
      if(update($q,$values,'ii')){
        alert('success','Marked as read!');
      }
      else{
        alert('error','Operation Failed!');
      }
    }
  }

  if(isset($_GET['del']))
  {
    $frm_data = filteration($_GET);

    if($frm_data['del']=='all'){
      $q = "DELETE FROM `rating_review`";
      if(mysqli_query($con,$q)){
        alert('success','All data deleted!');
      }
      else{
        alert('error','Operation failed!');
      }
    }
    else{
      $q = "DELETE FROM `rating_review` WHERE `sr_no`=?";
      $values = [$frm_data['del']];
      if(delete($q,$values,'i')){
        alert('success','Data deleted!');
      }
      else{
        alert('error','Operation failed!');
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>แผงผู้ดูแลระบบ - คะแนน & รีวิว</title>
  <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="container-fluid" id="main-content">
    <div class="row">
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        <h3 class="mb-4">คะแนน & รีวิว</h3>

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">

            <div class="text-end mb-4">
              <a href="?seen=all" class="btn btn-dark rounded-pill shadow-none btn-sm">
                <i class="bi bi-check-all"></i> ทำเครื่องหมายว่าอ่านทั้งหมด
              </a>
              <a href="?del=all" class="btn btn-danger rounded-pill shadow-none btn-sm">
                <i class="bi bi-trash"></i> ลบทั้งหมด
              </a>
            </div>

            <div class="table-responsive-md">
              <table class="table table-hover border">
              <thead>
  <tr class="bg-dark text-light">
    <th scope="col">#</th>
    <th scope="col">ชื่อ</th>
    <th scope="col">ประเภท</th>
    <th scope="col">ผู้ใช้</th>
    <th scope="col">คะแนน</th>
    <th scope="col" width="30%">รีวิว</th>
    <th scope="col">วันที่</th>
    <th scope="col">การกระทำ</th>
  </tr>
</thead>
                <tbody>
                <tbody>
  <?php 
    // Combined query for rooms, tvels, and restaurants reviews
    $q = "SELECT 
            rr.*,
            uc.name AS uname,
            CASE 
              WHEN r.name IS NOT NULL THEN r.name
              WHEN t.name IS NOT NULL THEN t.name 
              WHEN res.name IS NOT NULL THEN res.name
            END AS item_name,
            CASE 
              WHEN r.name IS NOT NULL THEN 'Room'
              WHEN t.name IS NOT NULL THEN 'Travel'
              WHEN res.name IS NOT NULL THEN 'Restaurant'
            END AS item_type
          FROM `rating_review` rr
          INNER JOIN `user_cred` uc ON rr.user_id = uc.id
          LEFT JOIN `rooms` r ON rr.room_id = r.id
          LEFT JOIN `tvels` t ON rr.tvel_id = t.id
          LEFT JOIN `restaurants` res ON rr.restaurant_id = res.id
          ORDER BY `sr_no` DESC";

    $data = mysqli_query($con,$q);
    $i=1;

    while($row = mysqli_fetch_assoc($data))
    {
      $date = date('d-m-Y',strtotime($row['datentime']));

      $seen='';
      if($row['seen']!=1){
        $seen = "<a href='?seen=$row[sr_no]' class='btn btn-sm rounded-pill btn-primary mb-2'>ทำเครื่องหมายว่าอ่านแล้ว</a> <br>";
      }
      $seen.="<a href='?del=$row[sr_no]' class='btn btn-sm rounded-pill btn-danger'>ลบออก</a>";

      echo<<<query
        <tr>
          <td>$i</td>
          <td>$row[item_name]</td>
          <td>$row[item_type]</td>
          <td>$row[uname]</td>
          <td>$row[rating]</td>
          <td>$row[review]</td>
          <td>$date</td>
          <td>$seen</td>
        </tr>
      query;
      $i++;
    }
  ?>
</tbody>
                </tbody>
              </table>
            </div>

          </div>
        </div>


      </div>
    </div>
  </div>
  

  <?php require('inc/scripts.php'); ?>

</body>
</html>