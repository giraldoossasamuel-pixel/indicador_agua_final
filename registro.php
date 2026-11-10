<?php
if(session_status()===PHP_SESSION_NONE) session_start();
$mensaje=''; $tipo='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 require 'conexion.php'; $nombre=trim($_POST['nombre']??'');$correo=trim($_POST['correo']??'');$clave=$_POST['clave']??'';$confirmar=$_POST['confirmar']??'';
 if($nombre===''||$correo===''||$clave===''){$mensaje='Completa todos los campos.';}
 elseif(strlen($clave)<6){$mensaje='La contraseña debe tener mínimo 6 caracteres.';}
 elseif($clave!==$confirmar){$mensaje='Las contraseñas no coinciden.';}
 else{$stmt=$conexion->prepare('SELECT id_usuario FROM usuarios WHERE correo=? LIMIT 1');$stmt->bind_param('s',$correo);$stmt->execute();if($stmt->get_result()->num_rows){$mensaje='Ese correo ya está registrado.';}else{$hash=password_hash($clave,PASSWORD_DEFAULT);$stmt=$conexion->prepare('INSERT INTO usuarios(nombre,correo,clave) VALUES(?,?,?)');$stmt->bind_param('sss',$nombre,$correo,$hash);if($stmt->execute()){$mensaje='Cuenta creada correctamente. Ya puedes iniciar sesión.';$tipo='success';}else{$mensaje='No se pudo crear la cuenta.';}}}
}
$tituloPagina='Crear cuenta | Indicador de Agua'; include 'includes/header.php'; ?>
<section class="auth-wrap"><div class="auth-card"><span class="section-tag">Registro</span><h1>Crear cuenta</h1><p>Regístrate para usar el acceso del proyecto.</p><?php if($mensaje): ?><div class="alert <?=$tipo?>"><?=htmlspecialchars($mensaje)?></div><?php endif; ?><form method="POST"><div class="field"><label>Nombre completo</label><input type="text" name="nombre" maxlength="100" required></div><div class="field"><label>Correo electrónico</label><input type="email" name="correo" maxlength="120" required></div><div class="field"><label>Contraseña</label><input type="password" name="clave" minlength="6" required></div><div class="field"><label>Confirmar contraseña</label><input type="password" name="confirmar" minlength="6" required></div><button class="btn btn-green" type="submit">Registrarme</button></form><div class="auth-links"><a href="login.php">Ya tengo cuenta</a><a href="index.php">Inicio</a></div></div></section>
<?php include 'includes/footer.php'; ?>
