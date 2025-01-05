<?php
include_once "../../init.php";
include "../../genral/config.php";

// Query semua admin
$slecet = "SELECT * FROM admins";
$s = mysqli_query($connectSQL, $slecet);

// Tangani penghapusan admin
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete = "DELETE FROM admins WHERE id = $id";
    mysqli_query($connectSQL, $delete);
    header("Location: $root_path/dashboard/admin/index.php?status=deleted");
    exit();
}

// Ambil status dari URL
$status = isset($_GET['status']) ? $_GET['status'] : null;

include "../../genral/functions.php";
include "../layouts/header.php";
include "../layouts/sidebar.php";
?>

<main class="app-content">
    <!-- Notifikasi Pop-up -->
    <?php if ($status == 'success'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Admin berhasil ditambahkan!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php elseif ($status == 'error'): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Gagal menambahkan admin. Silakan coba lagi!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php elseif ($status == 'deleted'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Admin berhasil dihapus!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="col-md-12">
        <div class="tile">
            <h3 class="tile-title">Daftar Admin</h3>

            <!-- Tombol Tambah Admin -->
            <div style="margin-bottom: 15px;">
                <a href="<?php echo $root_path; ?>/dashboard/admin/add.php" class="btn btn-primary">
                    Tambah Admin
                </a>
            </div>

            <!-- Tabel Daftar Admin -->
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($s as $data): ?>
                            <tr>
                                <td><?php echo $data['id']; ?></td>
                                <td><?php echo $data['name']; ?></td>
                                <td>
                                    <a href="<?php echo $root_path; ?>/dashboard/admin/index.php?delete=<?php echo $data['id']; ?>" class="btn btn-danger">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<?php include "../layouts/footer.php"; ?>
