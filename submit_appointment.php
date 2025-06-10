<?php
session_start();
include 'db_connect.php';

// CSRF token validation
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    error_log("CSRF validation failed for appointment submission.", 0);
    header('Location: book_appointment.php?success=0&error=' . urlencode('Invalid session. Please try again.'));
    exit;
}

// Regenerate CSRF token for next request
try {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
} catch (Exception $e) {
    error_log("CSRF token regeneration failed: " . $e->getMessage(), 0);
    $_SESSION['csrf_token'] = hash('sha256', uniqid(mt_rand(), true));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $name = filter_var(trim($_POST['name'] ?? ''), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $phone = filter_var(trim($_POST['phone'] ?? ''), FILTER_SANITIZE_STRING);
    $business = $_POST['business'] ?? '';
    $appointment_date = $_POST['appointment_date'] ?? '';
    $message = filter_var(trim($_POST['message'] ?? ''), FILTER_SANITIZE_STRING);

    // Validate inputs
    $errors = [];
    if (empty($name)) {
        $errors[] = 'Name is required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required.';
    }
    if (!preg_match('/^[0-9+\-\(\)\s]{10,15}$/', $phone) && !empty($phone)) {
        $errors[] = 'Invalid phone number.';
    }
    if (!in_array($business, ['Construction', 'Car Wash'])) {
        $errors[] = 'Invalid business selection.';
    }
    if (empty($appointment_date) || !strtotime($appointment_date)) {
        $errors[] = 'Valid appointment date is required.';
    } elseif (strtotime($appointment_date) < time()) {
        $errors[] = 'Appointment date cannot be in the past.';
    }

    if (!empty($errors)) {
        error_log("Appointment validation errors: " . implode(', ', $errors), 0);
        header('Location: book_appointment.php?success=0&error=' . urlencode(implode(' ', $errors)));
        exit;
    }

    // Prepare and execute database insertion
    try {
        $stmt = $conn->prepare("INSERT INTO appointments (name, email, phone, business, appointment_date, message, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("ssssss", $name, $email, $phone, $business, $appointment_date, $message);

        if ($stmt->execute()) {
            // Send confirmation email to user (using PHPMailer or similar)
            try {
                // Placeholder for PHPMailer configuration
                /*
                require 'vendor/autoload.php';
                $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.example.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'your_email@example.com';
                $mail->Password = 'your_password';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                $mail->setFrom('no-reply@trambakraj.com', 'Trambakraj Enterprises');
                $mail->addAddress($email, $name);
                $mail->isHTML(true);
                $mail->Subject = 'Appointment Confirmation';
                $mail->Body = "
                    <h2>Appointment Confirmation</h2>
                    <p>Dear " . htmlspecialchars($name) . ",</p>
                    <p>Your appointment for $business on " . htmlspecialchars($appointment_date) . " has been booked successfully.</p>
                    <p>We'll contact you soon to confirm details.</p>
                    <p>Best regards,<br>Trambakraj Enterprises</p>
                ";
                $mail->send();
                */
                // Log email success (remove in production with actual email sending)
                error_log("User email would be sent to $email for appointment.", 0);
            } catch (Exception $e) {
                error_log("User email sending failed: " . $e->getMessage(), 0);
            }

            // Notify admin
            try {
                // Placeholder for admin notification email
                /*
                $mail->clearAddresses();
                $mail->addAddress('admin@trambakraj.com', 'Admin');
                $mail->Subject = 'New Appointment';
                $mail->Body = "
                    <h2>New Appointment</h2>
                    <p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>
                    <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
                    <p><strong>Phone:</strong> " . htmlspecialchars($phone) . "</p>
                    <p><strong>Business:</strong> $business</p>
                    <p><strong>Date:</strong> " . htmlspecialchars($appointment_date) . "</p>
                    <p><strong>Message:</strong> " . htmlspecialchars($message) . "</p>
                ";
                $mail->send();
                */
                error_log("Admin notification would be sent for new appointment.", 0);
            } catch (Exception $e) {
                error_log("Admin email sending failed: " . $e->getMessage(), 0);
            }

            header('Location: book_appointment.php?success=1');
        } else {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        $stmt->close();
    } catch (Exception $e) {
        error_log("Appointment submission failed: " . $e->getMessage(), 0);
        header('Location: book_appointment.php?success=0&error=' . urlencode('Error booking appointment. Please try again.'));
    }
    exit;
}
?>