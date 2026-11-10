<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$usuarioLogueado = isset($_SESSION['id_usuario']);
$nombreUsuario = $_SESSION['nombre'] ?? '';
$esAdmin = ($_SESSION['rol'] ?? '') === 'admin';
$paginaActual = basename($_SERVER['PHP_SELF']);
function activo($pagina) { global $paginaActual; return $paginaActual === $pagina ? 'active' : ''; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Indicador de Consumo de Agua en la Huerta: tecnología, riego eficiente y agricultura sostenible.">
<title><?= htmlspecialchars($tituloPagina ?? 'Indicador de Consumo de Agua en la Huerta') ?></title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header class="site-header"><nav class="navbar"><div class="nav-container">
<a class="logo" href="index.php" aria-label="Indicador de Consumo de Agua en la Huerta"><img class="logo-image" src="imagenes/logo-huerta.png" alt="Indicador de Consumo de Agua en la Huerta"></a>
<button class="menu-toggle" type="button" aria-label="Abrir menú" aria-expanded="false">☰</button>
<div class="nav-links" id="navLinks">
<a class="<?= activo('index.php') ?>" href="index.php">Inicio</a>
<a class="<?= activo('antecedentes.php') ?>" href="antecedentes.php">Antecedentes</a>
<a class="<?= activo('fundamentos.php') ?>" href="fundamentos.php">Fundamentos</a>
<a class="<?= activo('servicios.php') ?>" href="servicios.php">Servicios</a>
<a class="<?= activo('indicador.php') ?>" href="indicador.php">Indicador</a>
<a class="<?= activo('noticias.php') ?>" href="noticias.php">Noticias</a>
<a class="<?= activo('galeria.php') ?>" href="galeria.php">Galería</a>
<a class="<?= activo('contacto.php') ?>" href="contacto.php">Contacto</a>
<?php if ($usuarioLogueado && $esAdmin): ?><a class="<?= activo('admin.php') ?>" href="admin.php">Administración</a><?php endif; ?>
<?php if ($usuarioLogueado): ?>
<span class="user-pill"><span class="user-dot"></span><?= htmlspecialchars($nombreUsuario, ENT_QUOTES, 'UTF-8') ?></span><a class="nav-login" href="cerrar_sesion.php">Salir</a>
<?php else: ?><a class="nav-login" href="login.php">Iniciar sesión</a><?php endif; ?>
</div></div></nav></header><main>
