<?php 
include_once "../../init.php";
include "../../genral/config.php";

// Variabel untuk Filter
$whereClause = "";
if (isset($_POST['filter'])) {
    $filterType = $_POST['filter_type'];

    switch ($filterType) {
        case 'year':
            $year = $_POST['year'];
            $whereClause = "WHERE YEAR(o.order_date) = $year";
            break;

        case 'month':
            $startMonth = $_POST['start_month'];
            $endMonth = $_POST['end_month'];
            if (isset($startMonth) && isset($endMonth)) {
                $whereClause = "WHERE o.order_date BETWEEN '$startMonth-01' AND '$endMonth-31'";
            }
            break;

        case 'day':
            if (isset($_POST['start_day']) && isset($_POST['end_day'])) {
                $startDay = $_POST['start_day'];
                $endDay = $_POST['end_day'];
                $whereClause = "WHERE o.order_date BETWEEN '$startDay' AND '$endDay'";
            }
            break;
    }
}

// Query untuk Laporan Penjualan dengan Grouping
$query = "
SELECT 
    o.order_Id, 
    DATE_FORMAT(o.order_date, '%d-%m-%Y %H:%i:%s') AS formatted_date, 
    o.name_customer, 
    GROUP_CONCAT(CONCAT(c.product_name, ' (', c.quantity, ')') SEPARATOR '|') AS products,
    GROUP_CONCAT(c.price SEPARATOR '|') AS unit_prices,
    GROUP_CONCAT(c.quantity SEPARATOR '|') AS quantities,
    SUM(c.price * c.quantity) AS total_price
FROM 
    order_manager AS o
JOIN 
    chekout AS c 
ON 
    o.order_Id = c.order_Id
$whereClause
GROUP BY 
    o.order_Id, o.name_customer, o.order_date
ORDER BY 
    o.order_date DESC";

$result = mysqli_query($connectSQL, $query);
$totalPendapatan = 0;

include "../../genral/functions.php";
include "../layouts/header.php";
include "../layouts/sidebar.php";
?>

<main class="app-content">
  <div class="col-md-12">
    <div class="tile">
      <h3 class="tile-title">Laporan Penjualan</h3>

      <!-- Form Filter -->
      <form method="POST" class="mb-4">
        <label for="filter_type">Filter Berdasarkan:</label>
        <select name="filter_type" id="filter_type" class="form-select w-25 d-inline-block" onchange="updateFilterFields()">
          <option value="year">Per Tahun</option>
          <option value="month">Per Bulan</option>
          <option value="day">Per Hari</option>
        </select>

        <!-- Filter Tahun -->
        <div id="year_filter" class="mt-2 d-none">
          <label for="year">Pilih Tahun:</label>
          <select name="year" id="year" class="form-select w-25 d-inline-block">
            <?php 
              for ($i = date('Y'); $i >= 2000; $i--) {
                  echo "<option value='$i'>$i</option>";
              }
            ?>
          </select>
        </div>

        <!-- Filter Bulan -->
        <div id="month_filter" class="mt-2 d-none">
          <label for="start_month">Pilih Bulan Mulai:</label>
          <input type="month" name="start_month" id="start_month" class="form-control w-25 d-inline-block">
          
          <label for="end_month" class="mt-2">Pilih Bulan Selesai:</label>
          <input type="month" name="end_month" id="end_month" class="form-control w-25 d-inline-block">
        </div>

        <!-- Filter Hari -->
        <div id="day_filter" class="mt-2 d-none">
          <label for="start_day">Pilih Tanggal Mulai:</label>
          <input type="date" name="start_day" id="start_day" class="form-control w-25 d-inline-block">
          
          <label for="end_day" class="mt-2">Pilih Tanggal Selesai:</label>
          <input type="date" name="end_day" id="end_day" class="form-control w-25 d-inline-block">
        </div>

        <button type="submit" name="filter" class="btn btn-primary mt-2">Terapkan</button>
      </form>

      <!-- Tabel Laporan -->
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Tanggal Pembelian</th>
              <th>Nama Pembeli</th>
              <th>Nama Produk</th>
              <th>Harga Satuan (Rp)</th>
              <th>Jumlah</th>
              <th>Total Harga (Rp)</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            if (mysqli_num_rows($result) > 0) {
              while ($data = mysqli_fetch_assoc($result)) {
                  $totalPendapatan += $data['total_price'];

                  // Pecah data produk
                  $products = explode('|', $data['products']);
                  $unit_prices = explode('|', $data['unit_prices']);
                  $quantities = explode('|', $data['quantities']);
                  
                  for ($i = 0; $i < count($products); $i++): 
            ?>
              <tr>
                <?php if ($i == 0): ?>
                  <td rowspan="<?php echo count($products); ?>"><?php echo $data['order_Id']; ?></td>
                  <td rowspan="<?php echo count($products); ?>"><?php echo $data['formatted_date']; ?></td>
                  <td rowspan="<?php echo count($products); ?>"><?php echo $data['name_customer']; ?></td>
                <?php endif; ?>
                <td><?php echo $products[$i]; ?></td>
                <td><?php echo number_format($unit_prices[$i], 0, ',', '.'); ?></td>
                <td><?php echo $quantities[$i]; ?></td>
                <?php if ($i == 0): ?>
                  <td rowspan="<?php echo count($products); ?>"><?php echo number_format($data['total_price'], 0, ',', '.'); ?></td>
                <?php endif; ?>
              </tr>
            <?php 
                  endfor;
              }
            } else { ?>
              <tr>
                <td colspan="7" class="text-center">Tidak ada data untuk filter yang dipilih.</td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>

      <!-- Total Pendapatan -->
      <div class="mt-3">
        <h5><strong>Total Pendapatan:</strong> Rp <?php echo number_format($totalPendapatan, 0, ',', '.'); ?></h5>
      </div>
    </div>
  </div>
</main>

<script>
  function updateFilterFields() {
    const filterType = document.getElementById('filter_type').value;

    document.getElementById('year_filter').classList.add('d-none');
    document.getElementById('month_filter').classList.add('d-none');
    document.getElementById('day_filter').classList.add('d-none');

    if (filterType === 'year') {
      document.getElementById('year_filter').classList.remove('d-none');
    } else if (filterType === 'month') {
      document.getElementById('month_filter').classList.remove('d-none');
    } else if (filterType === 'day') {
      document.getElementById('day_filter').classList.remove('d-none');
    }
  }
</script>

<?php include "../layouts/footer.php"; ?>
