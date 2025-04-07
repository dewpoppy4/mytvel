<?php 

  require('../inc/db_config.php');
  require('../inc/essentials.php');
  entreLogin();

  if(isset($_POST['add_restaurant']))
  {
    $features = filteration(json_decode($_POST['features']));
    $facilities = filteration(json_decode($_POST['facilities']));

    $frm_data = filteration($_POST);
    $flag = 0;

    $q1 = "INSERT INTO `restaurants` (`name`, `area`, `price`, `quantity`, `adult`, `children`, `description`) VALUES (?,?,?,?,?,?,?)";
    $values = [$frm_data['name'],$frm_data['area'],$frm_data['price'],$frm_data['quantity'],$frm_data['adult'],$frm_data['children'],$frm_data['desc']];

    if(insert($q1,$values,'siiiiis')){
      $flag = 1;
    }
    
    $restaurant_id = mysqli_insert_id($con);

    $q2 = "INSERT INTO `restaurant_facilities`(`restaurant_id`, `facilities_id`) VALUES (?,?)";
    if($stmt = mysqli_prepare($con,$q2))
    {
      foreach($facilities as $f){
        mysqli_stmt_bind_param($stmt,'ii',$restaurant_id,$f);
        mysqli_stmt_execute($stmt);
      }
      mysqli_stmt_close($stmt);
    }
    else{
      $flag = 0;
      die('query cannot be prepared - insert');
    }

    
    $q3 = "INSERT INTO `restaurant_features`(`restaurant_id`, `features_id`) VALUES (?,?)";
    if($stmt = mysqli_prepare($con,$q3))
    {
      foreach($features as $f){
        mysqli_stmt_bind_param($stmt,'ii',$restaurant_id,$f);
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


  if(isset($_POST['get_all_restaurants']))
{
  $res = select("SELECT * FROM `restaurants` WHERE `removed`=?",[0],'i');
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
          <button type='button' onclick='edit_details($row[id])' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit-restaurant'>
            <i class='bi bi-pencil-square'></i> 
          </button>
          <button type='button' onclick=\"restaurant_images($row[id],'$row[name]')\" class='btn btn-info shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#restaurant-images'>
            <i class='bi bi-images'></i> 
          </button>
          <button type='button' onclick='remove_restaurant($row[id])' class='btn btn-danger shadow-none btn-sm'>
            <i class='bi bi-trash'></i> 
          </button>
        </td>
      </tr>
    ";
    $i++;
  }

  echo $data;
}

if(isset($_POST['get_restaurant']))
{
  $frm_data = filteration($_POST);

  $res1 = select("SELECT * FROM `restaurants` WHERE `id`=?",[$frm_data['get_restaurant']],'i');
  $res2 = select("SELECT * FROM `restaurant_features` WHERE `restaurant_id`=?",[$frm_data['get_restaurant']],'i');
  $res3 = select("SELECT * FROM `restaurant_facilities` WHERE `restaurant_id`=?",[$frm_data['get_restaurant']],'i');

  $restaurantdata = mysqli_fetch_assoc($res1);
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

  $data = ["restaurantdata" => $restaurantdata, "features" => $features, "facilities" => $facilities];
  $data = json_encode($data);
  echo $data;
}

  if(isset($_POST['edit_restaurant']))
  {
    $features = filteration(json_decode($_POST['features']));
    $facilities = filteration(json_decode($_POST['facilities']));

    $frm_data = filteration($_POST);
    $flag = 0;

    $q1 = "UPDATE `restaurants` SET `name`=?,`area`=?,`price`=?,`quantity`=?,
      `adult`=?,`children`=?,`description`=? WHERE `id`=?";
    $values = [$frm_data['name'],$frm_data['area'],$frm_data['price'],$frm_data['quantity'],$frm_data['adult'],$frm_data['children'],$frm_data['desc'],$frm_data['restaurant_id']];
    
    if(update($q1,$values,'siiiiisi')){
      $flag = 1;
    }

    $del_features = delete("DELETE FROM `restaurant_features` WHERE `restaurant_id`=?", [$frm_data['restaurant_id']],'i');
    $del_facilities = delete("DELETE FROM `restaurant_facilities` WHERE `restaurant_id`=?", [$frm_data['restaurant_id']],'i');

    if(!($del_facilities && $del_features)){
      $flag = 0;
    }

    $q2 = "INSERT INTO `restaurant_facilities`(`restaurant_id`, `facilities_id`) VALUES (?,?)";
    if($stmt = mysqli_prepare($con,$q2))
    {
      foreach($facilities as $f){
        mysqli_stmt_bind_param($stmt,'ii',$frm_data['restaurant_id'],$f);
        mysqli_stmt_execute($stmt);
      }
      $flag = 1;
      mysqli_stmt_close($stmt);
    }
    else{
      $flag = 0;
      die('query cannot be prepared - insert');
    }

    
    $q3 = "INSERT INTO `restaurant_features`(`restaurant_id`, `features_id`) VALUES (?,?)";
    if($stmt = mysqli_prepare($con,$q3))
    {
      foreach($features as $f){
        mysqli_stmt_bind_param($stmt,'ii',$frm_data['restaurant_id'],$f);
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

    $q = "UPDATE `restaurants` SET `status`=? WHERE `id`=?";
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

    $img_r = uploadImage($_FILES['image'],RESTAURANTS_FOLDER);

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
      $q = "INSERT INTO `restaurant_images`(`restaurant_id`, `image`) VALUES (?,?)";
      $values = [$frm_data['restaurant_id'],$img_r];
      $res = insert($q,$values,'is');
      echo $res;
    }
  }

  if(isset($_POST['get_restaurant_images']))
  {
    $frm_data = filteration($_POST);
    $res = select("SELECT * FROM `restaurant_images` WHERE `restaurant_id`=?",[$frm_data['get_restaurant_images']],'i');

    $path = RESTAURANTS_IMG_PATH;

    while($row = mysqli_fetch_assoc($res))
    {
      if($row['thumb']==1){
        $thumb_btn = "<i class='bi bi-check-lg text-light bg-success px-2 py-1 rounded fs-5'></i>";
      }
      else{
        $thumb_btn = "<button onclick='thumb_image($row[sr_no],$row[restaurant_id])' class='btn btn-secondary shadow-none'>
          <i class='bi bi-check-lg'></i>
        </button>";
      }

      echo<<<data
        <tr class='align-middle'>
          <td><img src='$path$row[image]' class='img-fluid'></td>
          <td>$thumb_btn</td>
          <td>
            <button onclick='rem_image($row[sr_no],$row[restaurant_id])' class='btn btn-danger shadow-none'>
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

    $values = [$frm_data['image_id'],$frm_data['restaurant_id']];

    $pre_q = "SELECT * FROM `restaurant_images` WHERE `sr_no`=? AND `restaurant_id`=?";
    $res = select($pre_q,$values,'ii');
    $img = mysqli_fetch_assoc($res);

    if(deleteImage($img['image'],RESTAURANTS_FOLDER)){
      $q = "DELETE FROM `restaurant_images` WHERE `sr_no`=? AND `restaurant_id`=?";
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

    $pre_q = "UPDATE `restaurant_images` SET `thumb`=? WHERE `restaurant_id`=?";
    $pre_v = [0,$frm_data['restaurant_id']];
    $pre_res = update($pre_q,$pre_v,'ii');

    $q = "UPDATE `restaurant_images` SET `thumb`=? WHERE `sr_no`=? AND `restaurant_id`=?";
    $v = [1,$frm_data['image_id'],$frm_data['restaurant_id']];
    $res = update($q,$v,'iii');

    echo $res;

  }

  if(isset($_POST['remove_restaurant']))
  {
    $frm_data = filteration($_POST);

    $res1 = select("SELECT * FROM `restaurant_images` WHERE `restaurant_id`=?",[$frm_data['restaurant_id']],'i');

    while($row = mysqli_fetch_assoc($res1)){
      deleteImage($row['image'],RESTAURANTS_FOLDER);
    }

    $res2 = delete("DELETE FROM `restaurant_images` WHERE `restaurant_id`=?",[$frm_data['restaurant_id']],'i');
    $res3 = delete("DELETE FROM `restaurant_features` WHERE `restaurant_id`=?",[$frm_data['restaurant_id']],'i');
    $res4 = delete("DELETE FROM `restaurant_facilities` WHERE `restaurant_id`=?",[$frm_data['restaurant_id']],'i');
    $res5 = update("UPDATE `restaurants` SET `removed`=? WHERE `id`=?",[1,$frm_data['restaurant_id']],'ii');

    if($res2 || $res3 || $res4 || $res5){
      echo 1;
    }
    else{
      echo 0;
    }

  }

?>