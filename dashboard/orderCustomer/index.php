<?php 

include_once "../../init.php";
include "../../genral/config.php";
$slecet = "SELECT * FROM `order_manager` ORDER BY order_manager.order_Id DESC";
$s = mysqli_query($connectSQL, $slecet);

if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $delete = "DELETE FROM order_manager WHERE order_Id = $id";
  mysqli_query($connectSQL, $delete);
  header("Refresh:0");
}

if (isset($_GET['edit'])) {
  $id = $_GET['edit'];
  $selectr = "SELECT * FROM order_manager WHERE order_Id = $id";
  $sr = mysqli_query($connectSQL, $selectr);
  $row = mysqli_fetch_assoc($sr);
  $dateR = $row['order_date'];
  include "./modal_status.php";
  if (isset($_POST['update'])) {
    $status = $_POST['status'];
    $update = "UPDATE order_manager SET  order_status = $status , order_date = '$dateR' WHERE order_Id = $id";
    $u = mysqli_query($connectSQL, $update);
  }
}

include "../../genral/functions.php";
include "../layouts/header.php";
include "../layouts/sidebar.php";

?>
<main class="app-content">
  <div class="col-md-12">
    <div class="tile">
      <h3 class="tile-title">Daftar Pesanan</h3>

      <!-- Tombol Tambah Pesanan -->
      <div class="mb-3">
        <a href="<?php echo $root_path ?>/dashboard/orderCustomer/add.php" class="btn btn-primary">
          <i class="fas fa-plus-circle"></i> Tambah Pesanan
        </a>
      </div>

      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">OrderID</th>
              <th scope="col">Nama</th>
              <th scope="col">Telepon</th>
              <th scope="col">Alamat</th>
              <th scope="col">Total (Rp)</th>
              <th scope="col">Metode Pembayaran</th>
              <th scope="col">Tanggal</th>
              <th scope="col">Bukti Pembayaran</th>
              <th scope="col">Status</th>
              <th scope="col">Pesanan</th>
              <th scope="col">Hapus</th>
              <th scope="col">Print</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($s as $data) { ?>
              <tr>
                <th scope="row"><?php echo $data['order_Id']; ?></th>
                <td><?php echo $data['name_customer']; ?></td>
                <td><?php echo $data['phone_customer']; ?></td>
                <td><?php echo $data['address_customer']; ?></td>
                <td><?php echo number_format($data['sumTotal'], 0, ',', '.'); ?></td>
                <td><?php echo $data['payment_method']; ?></td>
                <td><?php echo $data['order_date']; ?></td>
                <td>
                <?php
                  $proofQuery = "SELECT * FROM `payment_proofs` WHERE order_Id = " . $data['order_Id'];
                  $proofResult = mysqli_query($connectSQL, $proofQuery);
                  
                  if ($proofResult && mysqli_num_rows($proofResult) > 0) {
                      $proof = mysqli_fetch_assoc($proofResult);
                  
                      if (isset($proof['file_path']) && !empty($proof['file_path'])) {
                          echo '
                          <!-- Button to Open Modal -->
                          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#proofModal' . $data['order_Id'] . '">
                              View Proof
                          </button>
                  
                          <!-- Modal -->
                          <div class="modal fade" id="proofModal' . $data['order_Id'] . '" tabindex="-1" aria-labelledby="proofModalLabel' . $data['order_Id'] . '" aria-hidden="true">
                              <div class="modal-dialog modal-lg">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <h5 class="modal-title" id="proofModalLabel' . $data['order_Id'] . '">Payment Proof</h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body">
                                          <img src="../../uploads/' . htmlspecialchars($proof['file_path']) . '" alt="Payment Proof" class="img-fluid">
                                      </div>
                                      <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                      </div>
                                  </div>
                              </div>
                          </div>';
                      } else {
                          echo '<span class="text-muted">File name not available</span>';
                      }
                  } else {
                      echo '<span class="text-muted">No Proof</span>';
                  }
                ?>

                </td>
                <td class="text-success">
                  <?php echo $data['order_status']; ?>
                  <a href="<?php echo $root_path ?>/dashboard/orderCustomer/index.php?edit=<?php echo $data['order_Id']; ?>" class="btn btn-outline-info"><i class="fas fa-pencil-alt"></i></a>
                </td>
                <td>
                  <table class="table table-hover main_table">
                    <thead>
                      <tr>
                        <th scope="col">Nama Produk</th>
                        <th scope="col">Harga (Rp)</th>
                        <th scope="col">Jumlah</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $oId = $data['order_Id'];
                      $slecetP = "SELECT * FROM `chekout` JOIN order_manager WHERE order_manager.order_Id = chekout.order_Id AND chekout.order_Id = $oId";
                      $sP = mysqli_query($connectSQL, $slecetP);
                      foreach ($sP as $ddata) { ?>
                        <tr>
                          <th scope="row"><?php echo $ddata['product_name']; ?></th>
                          <td><?php echo number_format($ddata['price'], 0, ',', '.'); ?></td>
                          <td><?php echo $ddata['quantity']; ?></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </td>
                <td>
                  <a href="<?php echo $root_path ?>/dashboard/orderCustomer/index.php?delete=<?php echo $data['order_Id']; ?>" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a>
                </td>
                <td>
                  <a href="<?php echo $root_path ?>/dashboard/orderCustomer/invoice.php?print=<?php echo $data['order_Id']; ?>" class="btn btn-success"><i class="fas fa-print"></i></a>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>
<?php include "../layouts/footer.php"; ?>
