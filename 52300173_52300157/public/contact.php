<?php
// Xu ly khi gui form
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$message_status = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require '../config/db.php';
    require 'PHPMailer/PHPMailer.php';
    require 'PHPMailer/SMTP.php';
    require 'PHPMailer/Exception.php';
    

    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $message = htmlspecialchars($_POST["message"]);

    $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $message]);


    if ($stmt->execute()) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'phamanthuy478@gmail.com';       
            $mail->Password = 'tgrl umzl cqwr hthl';           
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('phamanthuy478@gmail.com', 'Website Contact');
            $mail->addAddress('phamanthuy478@gmail.com');

            $mail->isHTML(true);
            $mail->Subject = "New Contact from $name";
            $mail->Body = "<h3>Contact Form Submission</h3>
                           <p><strong>Name:</strong> $name</p>
                           <p><strong>Email:</strong> $email</p>
                           <p><strong>Message:</strong><br>$message</p>";

            $mail->send();
            $message_status = "✅ Message sent successfully!";
        } catch (Exception $e) {
            $message_status = "⚠️ Saved, but email not sent. Error: {$mail->ErrorInfo}";
        }
    } else {
        $message_status = "❌ Failed to save message.";
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact - Atait</title>
  <link rel="icon" type="image/x-icon" href="../uploads/logo.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/contact.css">
</head>
<body>
        <a href="home.php" class="logo-link">
            <h1 class="logo-atat">Atait</h1>
        </a>

  <section class="contact">
    <div class="content">
      <h2>Contact Us</h2>
      <p>If you have any questions, feedback, or need support, feel free to reach out to us. Our team is always ready to assist you. Just fill out the form below or use the contact information provided — we’ll get back to you as soon as possible.</p>
      <?php if (!empty($message_status)) echo "<p style='color:yellow;'>$message_status</p>"; ?>
    </div>

    <div class="container">
        <div class="contactInfo">
                <div class="box">
                    <div class="icon">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>

                    <div class="text">
                        <h3>Address</h3>
                        <p>410 Le Hong Phong Street,<br> Ward 1, District 10,<br> Ho Chi Minh City</p>
                    </div>
                </div>

                <div class="box">
                    <div class="icon">
                        <i class="fa-solid fa-phone-volume"></i>
                    </div>

                    <div class="text">
                        <h3>Phone</h3>
                        <p>0123-456-789</p>
                    </div>
                </div>

                <div class="box">
                    <div class="icon">
                        <i class="fa-solid fa-envelope"></i>
                    </div>

                    <div class="text">
                        <h3>Email</h3>
                        <p>atait@gmail.com</p>
                    </div>
                </div>
            </div>
      <div class="contactForm">
        <form method="POST" action="">
          <h2>Send Message</h2>
          <div class="input-box">
            <input type="text" name="name" required />
            <span>Full Name</span>
          </div>
          <div class="input-box">
            <input type="email" name="email" required />
            <span>Email</span>
          </div>
          <div class="input-box">
            <textarea name="message" required></textarea>
            <span>Your Message</span>
          </div>
          <div class="input-box">
            <input type="submit" value="Send" />
          </div>
        </form>
      </div>
    </div>
  </section>
</body>
</html>
