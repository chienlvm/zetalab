<?php
// Load the PHPMailer class
require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';
require_once ABSPATH . WPINC . '/PHPMailer/SMTP.php';
class GmailMailer {
  private $mailer;
  private $emailFrom;

  public function __construct($email, $password) {
    $this->emailFrom = $email;
    $this->mailer = new PHPMailer\PHPMailer\PHPMailer(true);
    $this->mailer->SMTPDebug = 0; // 0 - Production mode, 2 - Debug mode
    $this->mailer->isSMTP();
    $this->mailer->Host = 'smtp.gmail.com';
    $this->mailer->SMTPAuth = true;
    $this->mailer->Username = $email;
    $this->mailer->Password = $password;
    $this->mailer->SMTPSecure = 'tls';
    $this->mailer->Port = 587;
    $this->mailer->setFrom('info.leeit@gmail.com', 'Zetabim team');
  }

  public function sendMail($to, $subject, $body) {
    $this->mailer->addAddress($to);
    $this->mailer->isHTML(true);
    $this->mailer->Subject = $subject;
    $this->mailer->Body = $body;

    return $this->mailer->send();
  }
  public function getEmailFrom() {
    return $this->emailFrom;
  }
}
