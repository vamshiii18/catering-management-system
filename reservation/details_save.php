<?php
session_start();
include('includes/dbcon.php');

$id = $_SESSION['id'];
$venue = $_POST['venue'];
$date = date("Y-m-d", strtotime($_POST['date']));
$time = $_POST['time'];
$motif = $_POST['motif'];
$pax = $_POST['pax'];
$type = $_POST['type'];
$ocassion = $_POST['ocassion'];
$cid = $_POST['combo_id'];

$queryCheckDate = mysqli_query($con, "SELECT * FROM `reservation` WHERE r_date = '$date' AND r_status = 'Approved'");

if (mysqli_num_rows($queryCheckDate) > 0) {
    echo "<script>alert('Date is already reserved'); window.history.back(); </script>";
} else {
    $queryCombo = mysqli_query($con, "SELECT * FROM combo WHERE combo_id = '$cid'");
    $row = mysqli_fetch_array($queryCombo);
    $price = $row['combo_price'];
    $payable = $pax * $price;

    $updateQuery = "UPDATE reservation SET payable = '$payable', balance = '$payable', r_venue = '$venue', r_date = '$date', r_time = '$time', r_motif = '$motif', r_ocassion = '$ocassion', r_type = '$type', pax = '$pax', combo_id = '$cid', price = '$price' WHERE rid = '$id'";

    if (mysqli_query($con, $updateQuery)) {
        $_SESSION['id'] = $id;
        echo "<script>document.location='payment.php'</script>";
    } else {
        echo "Error updating reservation: " . mysqli_error($con);
    }
}
?>
