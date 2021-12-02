<?php



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

$jsonResponse = json_decode($response);
if ($jsonResponse->success === true) { 

    // Paste mail function or whatever else you want to happen here!
    $marca = $_POST["marca"];
    $modelo = $_POST["modelo"];
    $year = $_POST["year"];
    $patente = $_POST["patente"];
    $nombre = $_POST["nombre"];
    $rut = $_POST["rut"];
    $fono = $_POST["fono"];
    $email = $_POST["email"];
    
    
    $mymail = "at.clientes@fsgseguros.cl";
    $header = "Content-Type: text/html;" . "\n";
    $header .= "From: FSG Seguros <no-reply@fsgseguros.cl>" . "\nReply-To:" . $_POST["email"] . "\n";
    $header .= "Return-path:  at.clientes@fsgseguros.cl\r\n";
    $header .= "X-Mailer:PHP/" . phpversion() . "\n";
    $header .= "Mime-Version: 1.0\n";


    echo "<script>window.location='https://fsgseguros.cl/landing/envio-exitoso.html';</script>";

    $subject = "Formulario Landing FSG Seguros";
    $mensaje = "<html><body> ".
    "Nombre: $nombre <br>" .
    "Rut: $rut <br>" .
        "Email: $email <br>" .
        "Fono: $fono <br>" .
        "Marca: $marca <br>" .
        "Modelo: $modelo <br>" .
        "Año: $year <br>" .
        "Patente: $patente <br>" .
        "</body></html>" . 
        "";
    $reply_msg = "<html>
            ";
    $reply_header = 'MIME-Version: 1.0' . "\r\n";
    $reply_header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    // Additional headers
    $reply_header .= 'From: FSG Seguros <no-reply@fsgseguros.cl>' . "\r\n";
    $reply_msg = "<html>
                <body>
                
                Gracias por contactarnos.<br><br>

                Le confirmamos que hemos recibido exitosamente su mensaje.<br><br>

                Nos pondremos en contacto con usted a la brevedad.<br><br>
                
                (Por favor no responda este email, gracias)<br>
    
                </body>
                </html>
                ";

    $reply_to = $email;
    $reply_subj = "Gracias por contactarnos";

    mail($mymail, $subject, $mensaje, $header);
    mail($reply_to, $reply_subj, $reply_msg, $reply_header);

    
 } else {
    echo "<script>alert('Validación incorrecta'); window.location='https://fsgseguros.cl/landing/';</script>";
}


?>