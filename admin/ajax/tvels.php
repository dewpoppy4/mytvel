<?php 

  require('../inc/db_config.php');
  require('../inc/essentials.php');
  adminLogin();

  if(isset($_POST['add_tvel']))
  {
    $features = filteration(json_decode($_POST['features']));
    $facilities = filteration(json_decode($_POST['facilities']));

    $frm_data = filteration($_POST);
    $flag = 0;

    $q1 = "INSERT INTO `tvels` (`name`, `area`, `price`, `quantity`, `adult`, `children`, `description`) VALUES (?,?,?,?,?,?,?)";
    $values = [$frm_data['name'],$frm_data['area'],$frm_data['price'],$frm_data['quantity'],$frm_data['adult'],$frm_data['children'],$frm_data['desc']];

    if(insert($q1,$values,'siiiiis')){
      $flag = 1;
    }
    
    $tvel_id = mysqli_insert_id($con);

    $q2 = "INSERT INTO `tvel_facilities`(`tvel_id`, `facilities_id`) VALUES (?,?)";
    if($stmt = mysqli_prepare($con,$q2))
    {
      foreach($facilities as $f){
        mysqli_stmt_bind_param($stmt,'ii',$tvel_id,$f);
        mysqli_stmt_execute($stmt);
      }
      mysqli_stmt_close($stmt);
    }
    else{
      $flag = 0;
      die('query cannot be prepared - insert');
    }

    
    $q3 = "INSERT INTO `tvel_features`(`tvel_id`, `features_id`) VALUES (?,?)";
    if($stmt = mysqli_prepare($con,$q3))
    {
      foreach($features as $f){
        mysqli_stmt_bind_param($stmt,'ii',$tvel_id,$f);
        mysqli_stmt_execute($stmt);
      }
      mysqli_stmt_close($stmt);
    }
    else{
      $flag = 0;
      die('query cannot be prepared - insert');
    }
    
    if($flag){
      echo 1;
    }
    else{
      echo 0;
    }


  }


  if(isset($_POST['get_all_tvels']))
{
  $res = select("SELECT * FROM `tvels` WHERE `removed`=?",[0],'i');
  $i=1;
  $data = "";

  while($row = mysqli_fetch_assoc($res))
  {
    if($row['status']==1){
      $status = "<button onclick='toggle_status($row[id],0)' class='btn btn-dark btn-sm shadow-none'>active</button>";
    }
    else{
      $status = "<button onclick='toggle_status($row[id],1)' class='btn btn-warning btn-sm shadow-none'>inactive</button>";
    }

    // Truncate description to 100 characters
    $description = mb_strlen($row['description'], 'UTF-8') > 60 ? 
    mb_substr($row['description'], 0, 60, 'UTF-8') . '...' : 
    $row['description'];

    $data.="
      <tr class='align-middle'>
        <td>$i</td>
        <td>$row[name]</td>
        <td title='$row[description]'>$description</td>
        <td>$status</td>
        <td>
          <button type='button' onclick='edit_details($row[id])' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit-tvel'>
            <i class='bi bi-pencil-square'></i> 
          </button>
          <button type='button' onclick=\"tvel_images($row[id],'$row[name]')\" class='btn btn-info shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#tvel-images'>
            <i class='bi bi-images'></i> 
          </button>
          <button type='button' onclick='remove_tvel($row[id])' class='btn btn-danger shadow-none btn-sm'>
            <i class='bi bi-trash'></i> 
          </button>
        </td>
      </tr>
    ";
    $i++;
  }

  echo $data;
}

if(isset($_POST['get_tvel']))
{
  $frm_data = filteration($_POST);

  $res1 = select("SELECT * FROM `tvels` WHERE `id`=?",[$frm_data['get_tvel']],'i');
  $res2 = select("SELECT * FROM `tvel_features` WHERE `tvel_id`=?",[$frm_data['get_tvel']],'i');
  $res3 = select("SELECT * FROM `tvel_facilities` WHERE `tvel_id`=?",[$frm_data['get_tvel']],'i');

  $tveldata = mysqli_fetch_assoc($res1);
  $features = [];
  $facilities = [];

  if(mysqli_num_rows($res2)>0)
  {
    while($row = mysqli_fetch_assoc($res2)){
      array_push($features,$row['features_id']);
    }
  }

  if(mysqli_num_rows($res3)>0)
  {
    while($row = mysqli_fetch_assoc($res3)){
      array_push($facilities,$row['facilities_id']);
    }
  }

  $data = ["tveldata" => $tveldata, "features" => $features, "facilities" => $facilities];
  $data = json_encode($data);
  echo $data;
}

  if(isset($_POST['edit_tvel']))
  {
    $features = filteration(json_decode($_POST['features']));
    $facilities = filteration(json_decode($_POST['facilities']));

    $frm_data = filteration($_POST);
    $flag = 0;

    $q1 = "UPDATE `tvels` SET `name`=?,`area`=?,`price`=?,`quantity`=?,
      `adult`=?,`children`=?,`description`=? WHERE `id`=?";
    $values = [$frm_data['name'],$frm_data['area'],$frm_data['price'],$frm_data['quantity'],$frm_data['adult'],$frm_data['children'],$frm_data['desc'],$frm_data['tvel_id']];
    
    if(update($q1,$values,'siiiiisi')){
      $flag = 1;
    }

    $del_features = delete("DELETE FROM `tvel_features` WHERE `tvel_id`=?", [$frm_data['tvel_id']],'i');
    $del_facilities = delete("DELETE FROM `tvel_facilities` WHERE `tvel_id`=?", [$frm_data['tvel_id']],'i');

    if(!($del_facilities && $del_features)){
      $flag = 0;
    }

    $q2 = "INSERT INTO `tvel_facilities`(`tvel_id`, `facilities_id`) VALUES (?,?)";
    if($stmt = mysqli_prepare($con,$q2))
    {
      foreach($facilities as $f){
        mysqli_stmt_bind_param($stmt,'ii',$frm_data['tvel_id'],$f);
        mysqli_stmt_execute($stmt);
      }
      $flag = 1;
      mysqli_stmt_close($stmt);
    }
    else{
      $flag = 0;
      die('query cannot be prepared - insert');
    }

    
    $q3 = "INSERT INTO `tvel_features`(`tvel_id`, `features_id`) VALUES (?,?)";
    if($stmt = mysqli_prepare($con,$q3))
    {
      foreach($features as $f){
        mysqli_stmt_bind_param($stmt,'ii',$frm_data['tvel_id'],$f);
        mysqli_stmt_execute($stmt);
      }
      $flag = 1;
      mysqli_stmt_close($stmt);
    }
    else{
      $flag = 0;
      die('query cannot be prepared - insert');
    }
    
    if($flag){
      echo 1;
    }
    else{
      echo 0;
    }

  }

  if(isset($_POST['toggle_status']))
  {
    $frm_data = filteration($_POST);

    $q = "UPDATE `tvels` SET `status`=? WHERE `id`=?";
    $v = [$frm_data['value'],$frm_data['toggle_status']];

    if(update($q,$v,'ii')){
      echo 1;
    }
    else{
      echo 0;
    }
  }

  if(isset($_POST['add_image']))
  {
    $frm_data = filteration($_POST);

    $img_r = uploadImage($_FILES['image'],TVELS_FOLDER);

    if($img_r == 'inv_img'){
      echo $img_r;
    }
    else if($img_r == 'inv_size'){
      echo $img_r;
    }
    else if($img_r == 'upd_failed'){
      echo $img_r;
    }
    else{
      $q = "INSERT INTO `tvel_images`(`tvel_id`, `image`) VALUES (?,?)";
      $values = [$frm_data['tvel_id'],$img_r];
      $res = insert($q,$values,'is');
      echo $res;
    }
  }

  if(isset($_POST['get_tvel_images']))
  {
    $frm_data = filteration($_POST);
    $res = select("SELECT * FROM `tvel_images` WHERE `tvel_id`=?",[$frm_data['get_tvel_images']],'i');

    $path = TVELS_IMG_PATH;

    while($row = mysqli_fetch_assoc($res))
    {
      if($row['thumb']==1){
        $thumb_btn = "<i class='bi bi-check-lg text-light bg-success px-2 py-1 rounded fs-5'></i>";
      }
      else{
        $thumb_btn = "<button onclick='thumb_image($row[sr_no],$row[tvel_id])' class='btn btn-secondary shadow-none'>
          <i class='bi bi-check-lg'></i>
        </button>";
      }

      echo<<<data
        <tr class='align-middle'>
          <td><img src='$path$row[image]' class='img-fluid'></td>
          <td>$thumb_btn</td>
          <td>
            <button onclick='rem_image($row[sr_no],$row[tvel_id])' class='btn btn-danger shadow-none'>
              <i class='bi bi-trash'></i>
            </button>
          </td>
        </tr>
      data;
    }

  }

  if(isset($_POST['rem_image']))
  {
    $frm_data = filteration($_POST);

    $values = [$frm_data['image_id'],$frm_data['tvel_id']];

    $pre_q = "SELECT * FROM `tvel_images` WHERE `sr_no`=? AND `tvel_id`=?";
    $res = select($pre_q,$values,'ii');
    $img = mysqli_fetch_assoc($res);

    if(deleteImage($img['image'],TVELS_FOLDER)){
      $q = "DELETE FROM `tvel_images` WHERE `sr_no`=? AND `tvel_id`=?";
      $res = delete($q,$values,'ii');
      echo $res;
    }
    else{
      echo 0;
    }

  }

  if(isset($_POST['thumb_image']))
  {
    $frm_data = filteration($_POST);

    $pre_q = "UPDATE `tvel_images` SET `thumb`=? WHERE `tvel_id`=?";
    $pre_v = [0,$frm_data['tvel_id']];
    $pre_res = update($pre_q,$pre_v,'ii');

    $q = "UPDATE `tvel_images` SET `thumb`=? WHERE `sr_no`=? AND `tvel_id`=?";
    $v = [1,$frm_data['image_id'],$frm_data['tvel_id']];
    $res = update($q,$v,'iii');

    echo $res;

  }

  if(isset($_POST['remove_tvel']))
  {
    $frm_data = filteration($_POST);

    $res1 = select("SELECT * FROM `tvel_images` WHERE `tvel_id`=?",[$frm_data['tvel_id']],'i');

    while($row = mysqli_fetch_assoc($res1)){
      deleteImage($row['image'],TVELS_FOLDER);
    }

    $res2 = delete("DELETE FROM `tvel_images` WHERE `tvel_id`=?",[$frm_data['tvel_id']],'i');
    $res3 = delete("DELETE FROM `tvel_features` WHERE `tvel_id`=?",[$frm_data['tvel_id']],'i');
    $res4 = delete("DELETE FROM `tvel_facilities` WHERE `tvel_id`=?",[$frm_data['tvel_id']],'i');
    $res5 = update("UPDATE `tvels` SET `removed`=? WHERE `id`=?",[1,$frm_data['tvel_id']],'ii');

    if($res2 || $res3 || $res4 || $res5){
      echo 1;
    }
    else{
      echo 0;
    }

  }

?>