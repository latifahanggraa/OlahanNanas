<?php
include_once "../init.php"; // Sesuaikan dengan struktur folder Anda
include "../genral/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orderId = $_POST['order_id'];
    $targetDir = "../uploads/"; // Direktori penyimpanan
    $fileName = basename($_FILES["proof_file"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Periksa tipe file yang diizinkan
    $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf'];
    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["proof_file"]["tmp_name"], $targetFilePath)) {
            // Simpan informasi file ke database
            $query = "INSERT INTO payment_proofs (order_id, file_path) VALUES ('$orderId', '$targetFilePath')";
            $result = mysqli_query($connectSQL, $query);

            if ($result) {
                echo "Bukti transfer berhasil diunggah dan disimpan.";
            } else {
                echo "Terjadi kesalahan saat menyimpan ke database.";
            }
        } else {
            echo "Maaf, file gagal diunggah.";
        }
    } else {
        echo "Format file tidak valid. Hanya JPG, JPEG, PNG, dan PDF yang diizinkan.";
    }
} else {
    echo "Permintaan tidak valid.";
}
?>
