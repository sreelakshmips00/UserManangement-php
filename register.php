<?php
require 'db.php';  
require 'PHPMailer-master\PHPMailer-master\src/PHPMailer.php';  
require 'PHPMailer-master\PHPMailer-master\src/SMTP.php';       
require 'PHPMailer-master\PHPMailer-master\src/Exception.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password

    
    if (strpos($email, '@gmail.com') === false) {
        $error = "Please use a valid Gmail address.";
    } else {
        
        $stmt = $conn->prepare("INSERT INTO users (username, email, phone, password_hash) VALUES (?, ?, ?, ?)");

        // Execute the query with the prepared data
        if ($stmt->execute([$username, $email, $phone, $password])) {
            
            $mail = new PHPMailer\PHPMailer\PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Use Gmail's SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'sree123assignment@gmail.com'; // Sender's email address
            $mail->Password = 'rgha piqd gbng wacq'; // Sender's app password
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('sree123assignment@gmail.com', 'SreeLogin&Reg.app');
            $mail->addAddress($email, $username); // Add recipient email
            $mail->Subject = 'Welcome to Our Platform!';
            $mail->Body    = "Hi $username,\n\nThank you for registering with us!";
            $mail->AltBody = "Hi $username,\n\nThank you for registering with us!"; // Alternative plain text body

            // Send email and check if successful
            if ($mail->send()) {
                // Redirect to the login page after successful registration
                header("Location: index.php");
                exit;
            } else {
                $error = "Failed to send the confirmation email: " . $mail->ErrorInfo;
            }
        } else {
            $error = "Registration failed. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Register</title>
</head>
<body>
    <form method="POST" action="">
        <h2>Register</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <label>Username:</label>
        <input type="text" name="username" required><br>
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Phone:</label>
        <input type="text" name="phone" required><br>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>
