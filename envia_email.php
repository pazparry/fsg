<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require("PHPMailer/src/Exception.php");
require("PHPMailer/src/PHPMailer.php");
require("PHPMailer/src/SMTP.php");


$recaptcha_secret = '6LfAAcIbAAAAAKizQkWc_9JvjZw3r1hjrNQU_e1n'; 
$recaptcha_response = $_POST['recaptcha_response']; 
$url = 'https://www.google.com/recaptcha/api/siteverify'; 

$data = array( 'secret' => $recaptcha_secret, 'response' => $recaptcha_response, 'remoteip' => $_SERVER['REMOTE_ADDR'] ); 
$curlConfig = array( CURLOPT_URL => $url, CURLOPT_POST => true, CURLOPT_RETURNTRANSFER => true, CURLOPT_POSTFIELDS => $data ); 
$ch = curl_init(); 
curl_setopt_array($ch, $curlConfig); 
$response = curl_exec($ch); 
curl_close($ch);

$jsonResponse = json_decode($response);
if ($jsonResponse->success === true) { 
    $uploadStatus = 1;
            
    /* Upload attachment file
    if(!empty($_FILES["attachment"]["name"])){
        
        // File path config
        $targetDir = "users_uploads/";
        $fileName = basename($_FILES["attachment"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
        
        // Allow certain file formats
        $allowTypes = array('pdf', 'doc', 'docx', 'jpg', 'png', 'jpeg');
        if(in_array($fileType, $allowTypes)){
            // Upload file to the server
            if(move_uploaded_file($_FILES["attachment"]["tmp_name"], $targetFilePath)){
                $uploadedFile = $targetFilePath;
            }else{
                $uploadStatus = 0;
                $statusMsg = "Sorry, there was an error uploading your file.";
            }
        }else{
            $uploadStatus = 0;
            $statusMsg = 'Sorry, only PDF, DOC, JPG, JPEG, & PNG files are allowed to upload.';
        }
    }*/

    
    //datos del formulario
    $nombre = $_POST["nombre"];
    $correo = $_POST["email"];
    $fono   = $_POST["fono"];
    $marca  = $_POST["marca"];
    $modelo = $_POST["modelo"];
    $ano    = $_POST["ano"];

    //correo envío
    $mymail  =  "abacompra@gmail.com";
    $subject =  "Contacto ABA Chocados";
    $mensaje =  "<html><body> ".
                "Nombre: $nombre<br>" .
                "Email: $correo<br>" .
                "Fono: $fono<br>" .
                "Modelo: $modelo<br>" .
                "Año: $ano<br>" .
                "</body></html>" . 
                "";

    $reply_to = $correo;
    $reply_subj = "Gracias por Contactarnos";
    $reply_msg = "<html>
                <body>
                
                Gracias por contactarnos.<br><br>

                Le confirmamos que hemos recibido exitosamente su mensaje.<br><br>

                Nos pondremos en contacto con usted a la brevedad.<br><br>
                
                (Por favor no responda este email, gracias)<br>
    
                </body>
                </html>
                ";
   
    //objeto correo 
    $mail = new PHPMailer(true);
   
    /** SECCIÓN CONFIGURACION SERVIDOR DE CORREO */
    /** ======================================== */
    /*Server settings
    $mail->isSMTP();                                    //Send using SMTP
    $mail->Host       = 'mail.autoschocados.cl';        //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                           //Enable SMTP authentication
    $mail->Username   = '_mainaccount@autoschocados.cl';//SMTP username
    $mail->Password   = 'WP65N=D(vx^4';                 //SMTP password
    $mail->SMTSecure  = 'ssl';
    $mail->Port       = 465;                            //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    /** ======================================== */
    /** SECCIÓN CONFIGURACION SERVIDOR DE CORREO */

    
    //correo 
    try {
        //Recipients
        $mail->setFrom($correo, $nombre);
        $mail->addAddress($mymail);             //Add a recipient
    
        //Archivo adjunto
        if (array_key_exists('upload_file', $_FILES)) {
            foreach ($_FILES["upload_file"]["name"] as $k => $v) {
                $ext = PHPMailer::mb_pathinfo($_FILES['upload_file']['name'][$k], PATHINFO_EXTENSION);
                $uploadfile = tempnam(sys_get_temp_dir(), hash('sha256', $_FILES['upload_file']['name'][$k])) . '.' . $ext;
               
                if (move_uploaded_file($_FILES['upload_file']['tmp_name'][$k], $uploadfile)) {
                    if (!$mail->addAttachment($uploadfile, $_FILES['upload_file']['name'][$k])) {
                        echo 'Failed to attach file ' . $_FILES['upload_file']['name'][$k];
                    }
                }
            }
        }

        //Content
        $mail->isHTML(true);                                //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $mensaje;
    
        //Send mail
        if (!$mail->send()) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } 

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    //reply
    $mail2 = new PHPMailer(true);
    try {
        //Recipients
       
        $mail2->setFrom('no-reply@wdf.cl', 'ABA Chocados');
        $mail2->addAddress($reply_to);             //Add a recipient
    
        //Archivo adjunto
        /* if (array_key_exists('upload_file', $_FILES)) {

            foreach ($_FILES["upload_file"]["name"] as $k => $v) {
                $ext = PHPMailer::mb_pathinfo($_FILES['upload_file']['name'][$k], PATHINFO_EXTENSION);
                $uploadfile = tempnam(sys_get_temp_dir(), hash('sha256', $_FILES['upload_file']['name'][$k])) . '.' . $ext;
               
                if (move_uploaded_file($_FILES['upload_file']['tmp_name'][$k], $uploadfile)) {
                    if (!$mail->addAttachment($uploadfile, $_FILES['upload_file']['name'][$k])) {
                        echo 'Failed to attach file ' . $_FILES['upload_file']['name'][$k];
                    }
                    
                }
            }
        }*/

        //Content
        $mail2->isHTML(true);                                //Set email format to HTML
        $mail2->Subject = $reply_subj;
        $mail2->Body    = $reply_msg;
    
        //Send mail
        if (!$mail2->send()) {
            echo 'Mailer 2 Error: ' . $mail2->ErrorInfo;
        } 

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo} - {$mail2->ErrorInfo}";
    }

    echo "<script>alert('Gracias por su consulta! nos pondremos en contacto con usted a la brevedad'); window.location='https://autoschocados.cl/';</script>";

} else {
    echo "<script>alert('Validación incorrecta'); window.location='https://autoschocados.cl/landing/';</script>";
}

?>