<?php   defined('BASEPATH') or exit('No direct script access allowed');
//include APPPATH . 'third_party/sendgrid-php/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

include APPPATH . 'third_party/phpmailer/vendor/autoload.php';

class Sendgrid_email 
{
    public function __construct()
    {
        
    }
    public function send($to,$subject,$message){
        $mail = new PHPMailer(true);
        $options = get_options(array("email_sender","sendgrid_key","name"));
        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'mail.anamoly.se';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'noreply@anamoly.se';                     // SMTP username
            $mail->Password   = 'Mz#O^F0mbnG!njGe';                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom($options["email_sender"], $options["name"]);
            //$mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
            foreach($to as $k=>$v){
              $mail->addAddress($k);               // Name is optional
            }
            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            // Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;
            $mail->AltBody = $message;

            return $mail->send();
            
        } catch (Exception $e) {
            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
    }
    /*
    public function send($to,$subject,$message){
        $CI =& get_instance();
        $options = get_options(array("email_sender","sendgrid_key","name"));
       
        $CI->email->from($options["email_sender"],$options["name"]);
        $CI->email->to(array_keys($to));

        $CI->email->subject($subject);
        $CI->email->message($message);

        return $CI->email->send();
    }
    */
    /*
    public function send($to,$subject,$message){
        
        $email = new \SendGrid\Mail\Mail();
        $options = get_options(array("email_sender","sendgrid_key","name"));
         if($options["sendgrid_key"] == "")
            return false;
         
        $email->setFrom($options["email_sender"],$options["name"]);
        $email->setSubject($subject);
        // $to in in formate of array("email"=>"name","email"=>"name")
        $email->addTos($to);
        $email->addContent(
            "text/html", $message
        );
        $sendgrid = new \SendGrid($options["sendgrid_key"]);
        try {
            $response = $sendgrid->send($email);
            if( $response->statusCode() == 200 || $response->statusCode() == 202){
                return true;
            }
			else
			{
				return $response;
			}	
        } catch (Exception $e) {
            return $e;
        }
    }*/
}