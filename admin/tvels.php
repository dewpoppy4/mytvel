<?php
require('inc/essentials.php');
require('inc/db_config.php');
adminLogin();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>แผงผู้ดูแลระบบ - สถานที่ท่องเที่ยว</title>
  <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="container-fluid" id="main-content">
    <div class="row">
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        <h3 class="mb-4">สถานที่ท่องเที่ยว</h3>

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">

            <div class="text-end mb-4">
              <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#add-tvel">
                <i class="bi bi-plus-square"></i> เพิ่ม
              </button>
            </div>

            <div class="table-responsive-lg" style="height: 450px; overflow-y: scroll;">
              <table class="table table-hover border text-center">
                <thead>
                  <tr class="bg-dark text-light">
                    <th scope="col">#</th>
                    <th scope="col">ชื่อ</th>
                    <th scope="col">คำอธิบาย</th>
                    <th scope="col">สถานะ</th>
                    <th scope="col">การจัดการ</th>
                  </tr>
                </thead>
                <tbody id="tvel-data">
                </tbody>
              </table>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>


  <!-- Add tvel modal -->

  <div class="modal fade" id="add-tvel">
  <div class="modal-dialog modal-lg">
    <form id="add_tvel_form" autocomplete="off">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">เพิ่มสถานที่ท่องเที่ยว</h5>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12 mb-3">
              <label class="form-label fw-bold">ชื่อ</label>
              <input type="text" name="name" class="form-control shadow-none" required>
            </div>
            <div class="col-12 mb-3">
              <label class="form-label fw-bold">คำอธิบาย</label>
              <textarea name="desc" rows="4" class="form-control shadow-none" required></textarea>
            </div>
            
            <!-- Hidden fields -->
            <input type="hidden" name="area" value="0">
            <input type="hidden" name="price" value="0">
            <input type="hidden" name="quantity" value="0">
            <input type="hidden" name="adult" value="0">
            <input type="hidden" name="children" value="0">
            <input type="hidden" name="features" value="[]">
            <input type="hidden" name="facilities" value="[]">
          </div>
        </div>
        <div class="modal-footer">
          <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
          <button type="submit" class="btn custom-bg text-white shadow-none">SUBMIT</button>
        </div>
      </div>
    </form>
  </div>
</div>

  <!-- Edit tvel modal -->

  <div class="modal fade" id="edit-tvel">
  <div class="modal-dialog modal-lg">
    <form id="edit_tvel_form" autocomplete="off">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">แก้ไข</h5>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12 mb-3">
              <label class="form-label fw-bold">ชื่อ</label>
              <input type="text" name="name" class="form-control shadow-none" required>
            </div>
            <div class="col-12 mb-3">
              <label class="form-label fw-bold">คำอธิบาย</label>
              <textarea name="desc" rows="4" class="form-control shadow-none" required></textarea>
            </div>
            
            <!-- Hidden fields -->
            <input type="hidden" name="area" value="0">
            <input type="hidden" name="price" value="0">
            <input type="hidden" name="quantity" value="0">
            <input type="hidden" name="adult" value="0">
            <input type="hidden" name="children" value="0">
            <input type="hidden" name="features" value="[]">
            <input type="hidden" name="facilities" value="[]">
            <input type="hidden" name="tvel_id">
          </div>
        </div>
        <div class="modal-footer">
          <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">ยกเลิก</button>
          <button type="submit" class="btn custom-bg text-white shadow-none">ตกลง</button>
        </div>
      </div>
    </form>
  </div>
</div>

  <!-- Manage tvel images modal -->

  <div class="modal fade" id="tvel-images" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">ชื่อที่พัก</h5>
          <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="image-alert"></div>
          <div class="border-bottom border-3 pb-3 mb-3">
            <form id="add_image_form">
              <label class="form-label fw-bold">เพิ่มรูปภาพ</label>
              <input type="file" name="image" accept=".jpg, .png, .webp, .jpeg" class="form-control shadow-none mb-3" required>
              <button class="btn custom-bg text-white shadow-none">เพิ่ม</button>
              <input type="hidden" name="tvel_id">
            </form>
          </div>
          <div class="table-responsive-lg" style="height: 350px; overflow-y: scroll;">
            <table class="table table-hover border text-center">
              <thead>
                <tr class="bg-dark text-light sticky-top">
                  <th scope="col" width="60%">รูปภาพ</th>
                  <th scope="col">Thumb</th>
                  <th scope="col">ลบออก</th>
                </tr>
              </thead>
              <tbody id="tvel-image-data">
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>


  <?php require('inc/scripts.php'); ?>

  <script src="scripts/tvels.js"></script>

</body>

</html>