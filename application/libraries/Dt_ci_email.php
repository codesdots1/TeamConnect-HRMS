<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once("Phpmailer.php");

class Dt_ci_email
{

    var $from = FROM_EMAIL;
    var $fromName = FROM_NAME;
//    var $ccName = CC_NAME;
//    var $cc = FROM_CC;

    public function sendEmail($mailType, $to, $paymentDetails = array())
    {
        $subject = "";
        $body = "";
        $status = false;

        $amount = isset($paymentDetails['amount']) ? $paymentDetails['amount'] : "";
        $payID = isset($paymentDetails['payment_id_ut2']) ? $paymentDetails['payment_id_ut2'] : "";
        $clid = isset($paymentDetails['clid']) ? $paymentDetails['clid'] : "";
        $paymentStatus = isset($paymentDetails['status']) ? $paymentDetails['status'] : "";
        $paymentStatus = $paymentStatus == "1" ? "Success" : "Failed";

        $didQty = isset($paymentDetails['didQty']) ? $paymentDetails['didQty'] : "";
        $didNumber = isset($paymentDetails['number']) ? $paymentDetails['number'] : "";
        $didAreaCode = isset($paymentDetails['area_code']) ? $paymentDetails['area_code'] : "";
        $didAreaCodeDesc = isset($paymentDetails['area_description']) ? $paymentDetails['area_description'] : "";

        $ut2_did_details = isset($paymentDetails['ut2_did_details']) ? $paymentDetails['ut2_did_details'] : "";

        switch ($mailType) {
            case 1:
                $subject = "Subject type 1";
                $body = "";

                if (isset($paymentDetails['more_details'])) {

                    $moreDetails = $paymentDetails['more_details'];

                    $body .= "<h2>Package name: " . $moreDetails['package_name'] . "</h2>";
                    $body .= "<h2>Tokens: " . $moreDetails['tokens'] . "</h2>";
                    $body .= "<h2>Days: " . $moreDetails['term'] . "</h2>";
                    $body .= "<h2>Amount: " . $amount . "$</h2>";
                    $body .= "<h2>Payment Status: " . $paymentStatus . "</h2>";

                }

                break;
            case 2:
                $subject = "You Got A Payment!";
                $body = "<p>ID: " . $payID . " - You got a payment of: $" . $amount . ", From Phone Number: $clid</p> <br>Thank You<br>EZ TelePay";
                break;
            case 3:
                $subject = "Subject type 3";
                $body = "<h2>Amount: " . $amount . "</h2>";
                break;
            case 4:
                $subject = "Subject type 4";
                $body = "<h2>Congratulations you have been assigned DID number: " . $didNumber . "</h2>";
                break;
            case 5:
                $subject = "Subject - You have successfully raised a DID request";
                $body = "<h2>Congratulations you have successfully raised a DID request ( {$didQty} Nos.) for area code: {$didAreaCode} - {$didAreaCodeDesc}</h2>";
                break;
            case 6:
                $subject = "Subject - DID request received";
                $body = "<h2>DID request ( {$didQty} Nos.) received for area code: {$didAreaCode} - {$didAreaCodeDesc}</h2>";
                $body .= "<h2>User Id: {$ut2_did_details->id} </h2>";
                $body .= "<h2>Name: {$ut2_did_details->first_name} {$ut2_did_details->last_name} </h2>";
                $body .= "<h2>Email: {$ut2_did_details->email} </h2>";
                break;
            default:
                $subject = "";
                $body = "";
                break;
        }

        if ($subject != "" && $body != "") {
//            $status = $this->sendMail($this->from, $this->fromName, $this->cc ,$to, $subject, $body);
            $status = $this->sendMail($this->from, $this->fromName ,$to, $subject, $body);
        }

        return $status;
    }

    public function sendPasswordMail($to,$subject, $body)
    {
        $status = $this->sendMail($this->from, $this->fromName ,$to, $subject, $body);
//        $status = $this->sendMail($this->from, $this->fromName, $this->cc ,$to, $subject, $body);
        return $status;
    }

    public function sendMail($from, $fromName, $to, $subject, $body, $reply_to = '', $bcc = '', $cc = '', $attachments = [])
//    public function sendMail($from, $fromName,$ccName, $to, $subject, $body, $reply_to = '', $bcc = '', $cc, $attachments = [])
    {

        $mail = new PHPMailer();

        $mail->isSMTP();
        //$mail->SMTPDebug = 2;
        $mail->Debugoutput = 'html';
        $mail->Host = EMAIL_HOST;
        $mail->Port = EMAIL_PORT;
        $mail->SMTPSecure = EMAIL_SMTP_SECURE;
        $mail->SMTPAuth = true;
        $mail->Username = EMAIL_USERNAME;
        $mail->Password = EMAIL_PASSWORD;
        $mail->setFrom($from, $fromName);
		$mail->addCC('henish@alitainfotech.com');
//        $mail->setCC($cc, $ccName);
        $mail->IsHTML(true);

        $mail->Subject = FROM_NAME . ' - ' . $subject;
        $mail->Body = $body;
        $mail->AddAddress($to);

        if (count($attachments) > 0) {
            foreach ($attachments as $attachment) {
                if (file_exists($attachment['path'])) {
                    $mail->AddAttachment($attachment['path'], $attachment['name'], 'base64', $attachment['type']);
                }
            }
        }

        if ($reply_to != '') {
            $mail->AddReplyTo($reply_to);
        }
        if ($bcc != '') {
            $mail->AddBCC($bcc);
        }
        if ($cc != '') {
            $mail->AddCC($cc);
        }
        $requestTime = date('Y-m-d H:i:s');
        $sendStatus = $mail->send();

        $responseTime = date('Y-m-d H:i:s');

        $logEmailData = array(
            "response" => $mail->ErrorInfo,
            "request_time" => $requestTime,
            "response_time" => $responseTime,
            "status" => $sendStatus ? 1 : 0,
            "recipient" => $to,
            "email_type" => 0,
            "email_content" => $body,
            "contact_type" => 'email',
        );

        $CI =& get_instance();
        $CI->db->insert('tbl_log_email', $logEmailData);

        if ($sendStatus) {
            return true;
        } else {
            return false;
        }
    }

}
