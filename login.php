<?php
if(session_status()===PHP_SESSION_NONE) session_start();
if(isset($_SESSION['id_usuario'])){header('Location:index.php');exit;}
$mensaje='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 require 'conexion.php';
 $correo=trim($_POST['correo']??''); $clave=$_POST['clave']??'';
 if($correo===''||$clave==='') $mensaje='Completa todos los campos.';
 else { $stmt=$conexion->prepare('SELECT id_usuario,nombre,clave,rol FROM usuarios WHERE correo=? LIMIT 1'); $stmt->bind_param('s',$correo); $stmt->execute(); $res=$stmt->get_result(); $u=$res->fetch_assoc();
 if($u && password_verify($clave,$u['clave'])){$_SESSION['id_usuario']=$u['id_usuario'];$_SESSION['nombre']=$u['nombre'];$_SESSION['rol']=$u['rol'];header('Location:'.($u['rol']==='admin'?'admin.php':'index.php'));exit;} else $mensaje='Correo o contraseña incorrectos.'; }
}
$tituloPagina='Iniciar sesión | Indicador de Agua'; include 'includes/header.php'; ?>
<section class="auth-wrap"><div class="auth-card"><span class="section-tag">Cuenta</span><h1>Bienvenido</h1><p>Ingresa para acceder al sitio con tu usuario.</p><?php if($mensaje): ?><div class="alert"><?=htmlspecialchars($mensaje)?></div><?php endif; ?><form method="POST"><div class="field"><label>Correo electrónico</label><input type="email" name="correo" required></div><div class="field"><label>Contraseña</label><input type="password" name="clave" required></div><button class="btn btn-green" type="submit">Iniciar sesión</button></form><div class="auth-links"><a href="registro.php">Crear cuenta</a><a href="index.php">Volver al inicio</a></div></div></section>
<?php include 'includes/footer.php'; ?>
