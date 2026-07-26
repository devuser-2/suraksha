<?php

$conn = new mysqli("localhost", "root", "", "insurance_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get Form Data
$name = $_POST['name'] ?? '';
$mobile = $_POST['mobile_no'] ?? '';
$insurance = $_POST['insurance_type'] ?? '';
$gender = $_POST['gender'] ?? '';
$age = $_POST['age'] ?? '';
$pin = $_POST['pin'] ?? '';
$vehicle = $_POST['vehicle_number'] ?? '';

// Save into MySQL
$stmt = $conn->prepare("
INSERT INTO insurance_quotes
(name, mobile_no, insurance_required, gender, age, pin, vehicle_number)
VALUES (?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "ssssiss",
    $name,
    $mobile,
    $insurance,
    $gender,
    $age,
    $pin,
    $vehicle
);

$stmt->execute();
$stmt->close();


// Send to Google Sheets

$googleScriptURL = "https://script.google.com/macros/s/AKfycbzeF8rLQI4hB0ufAatAdEVD9k0i-qLFLvbGjR6vkcw4ykE422NU9PIVsBIHUiXZPbw/exec";

$data = [
    "name" => $name,
    "mobile_no" => $mobile,
    "insurance_type" => $insurance,
    "gender" => $gender,
    "age" => $age,
    "pin" => $pin,
    "vehicle_number" => $vehicle
];

$options = [
    "http" => [
        "header"  => "Content-Type: application/json",
        "method"  => "POST",
        "content" => json_encode($data)
    ]
];

$context = stream_context_create($options);

@file_get_contents($googleScriptURL, false, $context);

$conn->close();

echo "<script>
alert('Thank you! Our insurance advisor will contact you shortly.');
window.location='index.html';
</script>";

?>