<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require("PHPMailer/src/Exception.php");
require("PHPMailer/src/PHPMailer.php");
require("PHPMailer/src/SMTP.php");

define('CLAVE', '6LfW12gdAAAAAIGR8aWSCkccuNe4rQ1FiuL9pkzG');
$token = $_POST['token'];
$action = $_POST['action'];

$cu = curl_init();
curl_setopt($cu, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
curl_setopt($cu, CURLOPT_POST, 1);
curl_setopt($cu, CURLOPT_POSTFIELDS, http_build_query(array('secret' => CLAVE, 'response' =>  $token)));
curl_setopt($cu, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($cu);
curl_close($cu);

$datos = json_decode($response, true);

if($datos['success'] == 1 && $datos['score'] >= 0.5){
    if($datos['action'] == 'validarUsuario');
     //datos del formulario
     $nombre = $_POST["nombre"];
     $rut = $_POST["rut"];
     $email = $_POST["email"];
     $fono   = $_POST["fono"];
     $marca  = $_POST["marca"];
     $modelo = $_POST["modelo"];
     $year    = $_POST["year"];
     $patente    = $_POST["patente"];
 
     //correo envío
     $mymail  =  "ifigenia.parry@gmail.com";
     $subject =  "Contacto FSG Seguros";
     $mensaje =  "<html><body> ".
                 "Nombre: $nombre<br>" .
                 "RUT: $rut<br>"  .
                 "Email: $correo<br>" .
                 "Fono: $fono<br>" .
                 "Modelo: $modelo<br>" .
                 "Año: $year<br>" .
                 "Patente: $patente<br>" .
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
    // Server settings
    $mail->isSMTP();                                    //Send using SMTP
    $mail->Host       = 'mail.ifigeniaparry.cl';        //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                           //Enable SMTP authentication
    $mail->Username   = 'contacto@ifigeniaparry.cl';//SMTP username
    $mail->Password   = 'KM_eL#q936_6';                 //SMTP password
    $mail->SMTSecure  = 'ssl';
    $mail->Port       = 465;                            //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    /** ======================================== */
    /** SECCIÓN CONFIGURACION SERVIDOR DE CORREO */

    //correo 
    try {
        //Recipients
        $mail->setFrom($correo, $nombre);
        $mail->addAddress($mymail);             //Add a recipient

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
       
        $mail2->setFrom('ifigenia.parry@gmail.com', 'FSG Seguros');
        $mail2->addAddress($reply_to);             //Add a recipient

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

    echo "<script>window.location='https://fsgseguros.cl/landing/envio-exitoso.html';</script>";
}else{
    echo "<script>alert('Validación incorrecta'); window.location='https://fsgseguros.cl/landing/';</script>";

}

?>