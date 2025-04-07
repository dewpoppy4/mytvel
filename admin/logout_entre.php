<?php 

  require('inc/essentials.php');

  session_start();
  session_destroy();
  redirect('entre_index.php');

?>