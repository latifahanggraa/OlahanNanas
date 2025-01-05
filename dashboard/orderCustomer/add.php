<?php
include_once "../../init.php";
include "../../genral/config.php";

$success = "";
$error = "";

// Proses Tambah Pesanan
if (isset($_POST['submit'])) {
    $name = $_POST['name_customer'];
    $phone = $_POST['phone_customer'];
    $address = $_POST['address_customer'];
    $total = $_POST['sumTotal'];
    $payment = $_POST['payment_method'];
    $status = $_POST['order_status'];
    $date = date('Y-m-d'); // Tanggal otomatis
    
    if (empty($name) || empty($phone) || empty($address) || empty($total) || empty($payment)) {
        $error = "Semua field harus diisi!";
    } else {
        $insert = "INSERT INTO order_manager (name_customer, phone_customer, address_customer, sumTotal, payment_method, order_status, order_date)
                   VALUES ('$name', '$phone', '$address', '$total', '$payment', '$status', '$date')";
        $i = mysqli_query($connectSQL, $insert);

        if ($i) {
            $success = "Pesanan berhasil ditambahkan!";
        } else {
            $error = "Gagal menambahkan pesanan!";
        }
    }
}

include "../layouts/header.php";
include "../layouts/sidebar.php";
?>

<main class="app-content">
  <div class="col-md-8">
    <div class="tile">
      <h3 class="tile-title">Tambah Pesanan</h3>

      <!-- Alert Error -->
      <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
      <?php endif; ?>

      <!-- Alert Success -->
      <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
      <?php endif; ?>

      <form method="POST" autocomplete="off">
        <div class="form-group">
          <label>Nama Customer:</label>
          <input type="text" name="name_customer" class="form-control" placeholder="Masukkan nama customer">
        </div>
        <div class="form-group">
          <label>Telepon:</label>
          <input type="text" name="phone_customer" class="form-control" placeholder="Masukkan nomor telepon">
        </div>
        <div class="form-group">
          <label>Alamat:</label>
          <input type="text" name="address_customer" class="form-control" placeholder="Masukkan alamat customer">
        </div>
        <div class="form-group">
          <label>Total (Rp):</label>
          <input type="number" name="sumTotal" class="form-control" placeholder="Masukkan total pesanan">
        </div>
        <div class="form-group">
          <label>Metode Pembayaran:</label>
          <select name="payment_method" class="form-control">
            <option value="Cash">Cash</option>
            <option value="Transfer">Transfer</option>
            <option value="Credit Card">Credit Card</option>
          </select>
        </div>
        <div class="form-group">
          <label>Status Pesanan:</label>
          <select name="order_status" class="form-control">
            <option value="Pending">Pending</option>
            <option value="Completed">Completed</option>
            <option value="Cancelled">Cancelled</option>
          </select>
        </div>
        <div class="form-group">
          <button type="submit" name="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan
          </button>
          <a href="<?php echo $root_path ?>/dashboard/orderCustomer/index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
          </a>
        </div>
      </form>
    </div>
  </div>
</main>

<?php include "../layouts/footer.php"; ?>
