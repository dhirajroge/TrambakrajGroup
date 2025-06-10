<?php
header('Content-Type: application/json');
include 'db_connect.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $business = $_POST['business'];
    $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
    
    if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL) || !in_array($business, ['Construction', 'Car Wash']) || empty($message)) {
        $response['message'] = 'Please fill out all required fields correctly.';
        echo json_encode($response);
        exit;
    }
    
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, phone, business, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $phone, $business, $message);
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Message sent successfully!';
    } else {
        $response['message'] = 'Error sending message. Please try again.';
    }
    
    $stmt->close();
}

echo json_encode($response);
?>