<?php
session_start();
include_once "../init.php";
include "../genral/config.php";
include "../genral/functions.php";
include "../shared/header.php";
include "../shared/nav.php";

// Fungsi untuk cek user login
userPermissions();

if (isset($_SESSION['customerName'])) {
    $User = $_SESSION['customerName'];
    $select = "SELECT om.order_Id, om.order_date, om.sumTotal, om.payment_method, om.order_status, 
                      GROUP_CONCAT(co.product_name SEPARATOR '|') as product_names,
                      GROUP_CONCAT(co.quantity SEPARATOR '|') as quantities
               FROM order_manager om 
               INNER JOIN chekout co ON om.order_Id = co.order_Id 
               WHERE om.name_customer = '$User' 
               GROUP BY om.order_Id 
               ORDER BY om.order_date DESC";
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
        <table class="table table-bordered text-center">
          <thead>
            <tr>
              <th scope="col">Tanggal</th>
              <th scope="col">Nama Produk</th>
              <th scope="col">Quantity</th>
              <th scope="col">Total</th>
              <th scope="col">Metode Pembayaran</th>
              <th scope="col">Status</th>
              <th scope="col">Bukti Pembayaran</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($s as $data): ?>
            <tr>
              <td rowspan="<?php echo substr_count($data['product_names'], '|') + 1; ?>">
                <?php echo $data['order_date']; ?>
              </td>
              <?php 
                $products = explode('|', $data['product_names']);
                $quantities = explode('|', $data['quantities']);
                for ($i = 0; $i < count($products); $i++):
              ?>
              <?php if ($i > 0): ?><tr><?php endif; ?>
                <td><?php echo $products[$i]; ?></td>
                <td><?php echo $quantities[$i]; ?></td>
                <?php if ($i === 0): ?>
                  <td rowspan="<?php echo count($products); ?>">
                    Rp. <?php echo number_format($data['sumTotal'], 0, ',', '.'); ?>
                  </td>
                  <td rowspan="<?php echo count($products); ?>">
                    <?php echo $data['payment_method']; ?>
                  </td>
                  <td rowspan="<?php echo count($products); ?>" class="<?php echo $data['order_status'] == 'Reviewing' ? 'text-info' : ($data['order_status'] == 'Ready' ? 'text-success' : ($data['order_status'] == 'Delivery' ? 'text-warning' : 'text-muted')); ?> font-weight-bold">
                    <?php echo $data['order_status']; ?>
                  </td>
                  <td rowspan="<?php echo count($products); ?>">
                    <?php
                    // Cek apakah ada bukti pembayaran di database
                    $query = "SELECT * FROM payment_proofs WHERE order_id = '" . $data['order_Id'] . "'";
                    $result = mysqli_query($connectSQL, $query);

                    if (mysqli_num_rows($result) > 0):
                        $proof = mysqli_fetch_assoc($result);
                        $filePath = $proof['file_path'];
                    ?>
                      <!-- Tombol untuk melihat bukti pembayaran -->
                      <a href="#" class="btn btn-primary btn-sm mb-2" data-toggle="modal" data-target="#fileModal-<?php echo $data['order_Id']; ?>">Lihat</a>

                      <!-- Modal untuk menampilkan bukti pembayaran -->
                      <div class="modal fade" id="fileModal-<?php echo $data['order_Id']; ?>" tabindex="-1" role="dialog" aria-labelledby="fileModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="fileModalLabel">Lihat</h5>
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

                    <!-- Tombol untuk upload bukti -->
                    <form action="upload_proof.php" method="post" enctype="multipart/form-data" class="upload-button-form">
                      <input type="hidden" name="order_id" value="<?php echo $data['order_Id']; ?>">
                      <input type="file" name="proof_file" class="proof-file d-none" required>
                      <button type="button" class="btn btn-outline-primary btn-sm trigger-upload">Upload</button>
                    </form>
                  </td>
                <?php endif; ?>
              </tr>
              <?php endfor; ?>
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

<script>
  // JavaScript untuk tombol upload
  document.querySelectorAll('.trigger-upload').forEach(button => {
    button.addEventListener('click', function() {
      const fileInput = this.closest('.upload-button-form').querySelector('.proof-file');
      fileInput.click();
      fileInput.addEventListener('change', function() {
        if (fileInput.files.length > 0) {
          this.closest('.upload-button-form').submit();
        }
      });
    });
  });
</script>
