<?php
include_once  "../init.php";
include "../genral/config.php";
include "../genral/functions.php";

// Example bank details
$bankName = "Bank BRI";
$accountNumber = "6810 0100 0644 532";
$accountName = "Tri Wahyuningsih";

// WhatsApp link for payment confirmation
$whatsappNumber = "6285725400399";  // Use the country code for the number
$whatsappLink = "https://wa.me/$whatsappNumber?text=" . urlencode("Hello, I have made a payment for my order. Please confirm.");

include "../shared/header.php"; 
include "../shared/nav.php"; 
?>

<div class="container pt-5" style="padding: 30px; margin-top: 100px; height: 656.5px;">
    <div class="card p-4 text-center">
        <h3>Bank Transfer Payment Details</h3>
        <p>Please transfer the total amount to the bank account below:</p>
        <h3><strong><?php echo $bankName; ?></strong></h3>
        <h1><strong><?php echo $accountNumber; ?></strong></h1>
        <h2><strong> <?php echo $accountName; ?>  </strong></h2>
        <p>After making the payment, click the link below to confirm your payment via WhatsApp:</p>
        <a href="<?php echo $whatsappLink; ?>" target="_blank" class="btn btn-success">Confirm Payment on WhatsApp</a>
    </div>
</div>

<?php include "../shared/footer.php" ?>