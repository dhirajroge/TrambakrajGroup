<?php
header('Content-Type: application/json');
include 'db_connect.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Invalid email address.';
        echo json_encode($response);
        exit;
    }
    
    try {
        $stmt = $conn->prepare("INSERT INTO newsletter_subscriptions (email) VALUES (?)");
        $stmt->bind_param("s", $email);
        
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Subscribed successfully!';
        } else {
            $response['message'] = 'Error subscribing. Please try again.';
        }
        
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        $response['message'] = 'You are already subscribed.';
    }
}

echo json_encode($response);
?>