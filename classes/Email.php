<?php 

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;


class Email{

    protected $nombre;
    protected $email;
    protected $token;

    public function __construct($nombre, $email, $token)
    {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->token = $token;
        
    }

    public function enviarEmail(){

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '8ba33e3b74e3ba';
        $mail->Password = 'a3f8eb9d224a3b';
  

  
   

   //configurar el contenido del EMAIL
   $mail->setFrom('cuentas@UpTask.com');
   $mail->addAddress('cuentas@UpTask.com', 'UpTask.com');
   $mail->Subject = 'Confirma tu Cuenta';

   //Habilitar html
   $mail->isHTML(TRUE);
   $mail->CharSet = 'UTF-8';

   $contenido = '<html>';
   $contenido .= "<p color:'blue'><strong>Hola " . $this->nombre .  "</strong> Has Creado tu cuenta en UpTask, solo debes confirmarla presionando el siguiente enlace</p>";
   $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/confirmar?token=" . $this->token . "'>Confirmar Cuenta</a>";        
   $contenido .= "<p>Si tu no solicitaste este cambio, puedes ignorar el mensaje</p>";
   $contenido .= '</html>';

$mail->Body = $contenido;

$mail->send();
}

public function restablecerPassword(){
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.mailtrap.io';
    $mail->SMTPAuth = true;
    $mail->Port = 2525;
    $mail->Username = '8ba33e3b74e3ba';
    $mail->Password = 'a3f8eb9d224a3b';





//configurar el contenido del EMAIL
$mail->setFrom('cuentas@UpTask.com');
$mail->addAddress('cuentas@UpTask.com', 'UpTask.com');
$mail->Subject = 'Restablece tu contraseña';

//Habilitar html
$mail->isHTML(TRUE);
$mail->CharSet = 'UTF-8';

$contenido = '<html>';
$contenido .= "<p color:'blue'><strong>Hola " . $this->nombre .  "</strong> Has click en el siguiente enlace para restablecer tu contraseña</p>";
$contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/restablecer?token=" . $this->token . "'>Restablecer Contraseña</a>";        
$contenido .= "<p>Si tu no solicitaste este cambio, puedes ignorar el mensaje</p>";
$contenido .= '</html>';

$mail->Body = $contenido;

$mail->send();
}


}
?>