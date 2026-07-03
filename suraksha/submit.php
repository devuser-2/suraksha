<?php

$conn = new mysqli("localhost", "root", "", "insurance_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$mobile = $_POST['mobile_no'];
$insurance = $_POST['insurance_required'];
$vehicle = $_POST['vehicle_number'];
$time = $_POST['preferred_time_to_call'];

$stmt = $conn->prepare("
    INSERT INTO insurance_quotes
    (name, mobile_no, insurance_required, vehicle_number, preferred_time_to_call)
    VALUES (?, ?, ?, ?, ?)
");

$stmt->bind_param("sssss", $name, $mobile, $insurance, $vehicle, $time);

$stmt->execute();

echo "Submitted Successfully!";

$stmt->close();
$conn->close();

?>