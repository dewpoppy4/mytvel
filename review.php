<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('inc/links.php'); ?>
  <title>ระบบรีวิว</title>
</head>
<body class="bg-light">
  <?php 
    require('inc/header.php'); 

    // ตรวจสอบการล็อกอิน
    if(!(isset($_SESSION['login']) && $_SESSION['login']==true)){
      redirect('index.php');
    }

    // ตรวจสอบพารามิเตอร์ที่ส่งมา
    if(!isset($_GET['item_id']) || !isset($_GET['item_type'])){
      redirect('index.php');
    }
  ?>

<br><br><div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8 col-sm-12">
        <div class="card border-0">
          <div class="card-body">
            <div class="review-header">
              <h4 class="card-title d-flex align-items-center mb-0">
                <i class="bi bi-chat-square-heart-fill fs-3 me-2"></i> 
                ให้คะแนน & รีวิว
              </h4>
            </div>
            
            <form id="review-form">
              <div class="rating-container">
                
                <select class="form-select shadow-none mb-4 w-75" name="rating">
                  <option value="5">เยี่ยมมาก ⭐⭐⭐⭐⭐</option>
                  <option value="4">ดี ⭐⭐⭐⭐</option>
                  <option value="3">พอใช้ ⭐⭐⭐</option>
                  <option value="2">แย่ ⭐⭐</option>
                  <option value="1">แย่มาก ⭐</option>
                </select>
              </div>

              <div class="mb-4">
                <label class="form-label fw-bold">รีวิวของคุณ</label>
                <textarea name="review" rows="4" required 
                  class="form-control shadow-none"
                  placeholder="แชร์ประสบการณ์ของคุณ..."
                ></textarea>
              </div>
              
              <input type="hidden" name="item_id" value="<?php echo $_GET['item_id'] ?>">
              <input type="hidden" name="item_type" value="<?php echo $_GET['item_type'] ?>">

              <div class="d-flex justify-content-between">
                <a href="javascript:history.back()" 
                   class="btn btn-outline-secondary shadow-none px-4">
                   <i class="bi bi-arrow-left me-2"></i>กลับ
                </a>
                <button type="submit" class="btn custom-bg text-white shadow-none px-4">
                  <i class="bi bi-send-fill me-2"></i>ส่งรีวิว
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php require('inc/footer.php'); ?>

  <script>
  let review_form = document.getElementById('review-form');
  
  // เก็บ URL ที่มาไว้
  const returnUrl = document.referrer;

  review_form.addEventListener('submit', function(e) {
    e.preventDefault();

    let data = new FormData();
    data.append('review_form', '');
    data.append('rating', review_form.elements['rating'].value);
    data.append('review', review_form.elements['review'].value);
    data.append('item_id', review_form.elements['item_id'].value);
    data.append('item_type', review_form.elements['item_type'].value);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/review.php", true);

    xhr.onload = function() {
      if(this.responseText == 1) {
        alert('success', 'ขอบคุณสำหรับการรีวิว!');
        // กลับไปยังหน้าที่มา
        window.location.href = returnUrl;
      } else {
        alert('error', "การรีวิวล้มเหลว กรุณาลองใหม่อีกครั้ง");
      }
    }

    xhr.send(data);
  });
</script>
</body>
</html>