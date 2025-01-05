<?php
session_start();
include_once "../init.php"; // Sesuaikan dengan struktur folder Anda
include "../genral/config.php";
include "../genral/functions.php";
include "../shared/header.php";
include "../shared/nav.php";

// Fungsi untuk cek user login
userPermissions();

if (isset($_SESSION['customerName'])) {
    $User = $_SESSION['customerName'];
    $select = "SELECT * FROM `order_manager` WHERE name_customer = '$User' ORDER BY order_manager.order_Id DESC";
    $s = mysqli_query($connectSQL, $select);
    $numRow = mysqli_num_rows($s);
}
?>

<div class="cart pt-5">
  <div class="container text-center">
    <h2 class="text-center py-3 mt-4">Order Tracking <i class="fas fa-truck-moving"></i></h2>

    <?php if ($numRow > 0): ?>
    <div class="row mt-3">
      <div class="card m-auto">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">OrderID</th>
              <th scope="col">Telepon</th>
              <th scope="col">Alamat</th>
              <th scope="col">Total</th>
              <th scope="col">Metode Pembayaran</th>
              <th scope="col">Tanggal</th>
              <th scope="col">Status</th>
              <th scope="col">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($s as $data): ?>
            <tr>
              <th scope="row"><?php echo $data['order_Id']; ?></th>
              <td><?php echo $data['phone_customer']; ?></td>
              <td><?php echo $data['address_customer']; ?></td>
              <td>Rp. <?php echo number_format($data['sumTotal'], 0, ',', '.'); ?></td>
              <td><?php echo $data['payment_method']; ?></td>
              <td><?php echo $data['order_date']; ?></td>
              <td class="<?php echo $data['order_status'] == 'Reviewing' ? 'text-info' : ($data['order_status'] == 'Ready' ? 'text-success' : ($data['order_status'] == 'Delivery' ? 'text-warning' : 'text-muted')); ?> font-weight-bold">
              <?php echo $data['order_status']; ?>
              </td>
              <td>
                <!-- Tombol Upload Bukti Transfer -->
                <form action="upload_proof.php" method="post" enctype="multipart/form-data" class="d-inline">
                  <input type="hidden" name="order_id" value="<?php echo $data['order_Id']; ?>">
                  <div class="form-group">
                    <input type="file" name="proof_file" class="form-control-file" required>
                  </div>
                  <button type="submit" class="btn btn-primary btn-sm mt-2">Upload</button>
                </form>

                <!-- Tampilkan nama file jika sudah diupload -->
                <?php
                $query = "SELECT * FROM payment_proofs WHERE order_id = '" . $data['order_Id'] . "'";
                $result = mysqli_query($connectSQL, $query);
                if (mysqli_num_rows($result) > 0):
                    $proof = mysqli_fetch_assoc($result);
                    $filePath = $proof['file_path'];
                ?>
                  <br>
                  <a href="#" class="text-info" data-toggle="modal" data-target="#fileModal-<?php echo $data['order_Id']; ?>">
                    Lihat Bukti Pembayaran 
                  </a>

                  <!-- Modal untuk melihat bukti pembayaran -->
                  <div class="modal fade" id="fileModal-<?php echo $data['order_Id']; ?>" tabindex="-1" role="dialog" aria-labelledby="fileModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="fileModalLabel">Bukti Pembayaran</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <?php if (in_array(pathinfo($filePath, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])): ?>
                            <img src="<?php echo $filePath; ?>" alt="Bukti Pembayaran" class="img-fluid">
                          <?php elseif (pathinfo($filePath, PATHINFO_EXTENSION) == 'pdf'): ?>
                            <embed src="<?php echo $filePath; ?>" width="100%" height="500px" />
                          <?php else: ?>
                            <p>Format file tidak dapat ditampilkan.</p>
                          <?php endif; ?>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php else: ?>
      <div class="col-md-6 mx-auto text-center">
        <a href="<?php echo $root_path; ?>/index.php" class="btn btn-warning">Shopping</a>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include "../shared/footer.php"; ?>
<!-- customer request -->