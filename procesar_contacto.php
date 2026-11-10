<?php
require 'conexion.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){header('Location:contacto.php');exit;}
$nombre=trim($_POST['nombre']??'');$correo=trim($_POST['correo']??'');$asunto=trim($_POST['asunto']??'');$mensaje=trim($_POST['mensaje']??'');
if($nombre===''||$correo===''||$asunto===''||$mensaje===''){header('Location:contacto.php?error=1');exit;}
$stmt=$conexion->prepare('INSERT INTO contactos(nombre,correo,asunto,mensaje) VALUES(?,?,?,?)');$stmt->bind_param('ssss',$nombre,$correo,$asunto,$mensaje);$stmt->execute();header('Location:contacto.php?enviado=1');exit;
?>
