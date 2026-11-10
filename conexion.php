<?php
/* Conexión automática para XAMPP */
$host = 'localhost';
$usuario = 'root';
$clave = '';
$baseDatos = 'indicador_agua';

$conexion = new mysqli($host, $usuario, $clave);
if ($conexion->connect_error) {
    die('No se pudo conectar con MySQL. Abre XAMPP y activa MySQL.');
}
$conexion->set_charset('utf8mb4');

if (!$conexion->query("CREATE DATABASE IF NOT EXISTS `$baseDatos` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
    die('No se pudo crear la base de datos: ' . $conexion->error);
}
if (!$conexion->select_db($baseDatos)) {
    die('No se pudo seleccionar la base de datos.');
}

$conexion->query("ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS rol ENUM('usuario','admin') NOT NULL DEFAULT 'usuario'");

$tablas = [
"CREATE TABLE IF NOT EXISTS usuarios (
 id_usuario INT AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(100) NOT NULL,
 correo VARCHAR(120) NOT NULL UNIQUE,
 clave VARCHAR(255) NOT NULL,
 rol ENUM('usuario','admin') NOT NULL DEFAULT 'usuario',
 fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB",
"CREATE TABLE IF NOT EXISTS contactos (
 id_contacto INT AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(80) NOT NULL,
 correo VARCHAR(120) NOT NULL,
 asunto VARCHAR(120) NOT NULL,
 mensaje TEXT NOT NULL,
 fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB",
"CREATE TABLE IF NOT EXISTS galeria (
 id_imagen INT AUTO_INCREMENT PRIMARY KEY,
 titulo VARCHAR(120) NOT NULL,
 ruta_imagen VARCHAR(255) NOT NULL,
 descripcion TEXT,
 fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB",
"CREATE TABLE IF NOT EXISTS mediciones (
 id_medicion INT AUTO_INCREMENT PRIMARY KEY,
 consumo_litros DECIMAL(8,2) NOT NULL,
 humedad DECIMAL(5,2) NOT NULL,
 agua_ahorrada DECIMAL(8,2) NOT NULL,
 eficiencia DECIMAL(5,2) NOT NULL,
 fecha_medicion DATETIME DEFAULT CURRENT_TIMESTAMP,
 INDEX(fecha_medicion)
) ENGINE=InnoDB"
];
foreach ($tablas as $sql) {
    if (!$conexion->query($sql)) {
        die('Error preparando la base de datos: ' . $conexion->error);
    }
}

$check = $conexion->query("SELECT COUNT(*) AS total FROM galeria");
if ($check && (int)$check->fetch_assoc()['total'] === 0) {
    $stmt = $conexion->prepare('INSERT INTO galeria(titulo,ruta_imagen,descripcion) VALUES(?,?,?)');
    $datos = [
        ['Huerta sostenible','imagenes/imagen-huerta.jpg','Huerta con cultivos saludables y prácticas sostenibles.'],
        ['Riego por goteo','imagenes/imagen-riego-goteo.jpg','Sistema de riego localizado para entregar agua directamente al suelo.'],
        ['Sensor de humedad','imagenes/imagen-sensor-humedad.jpg','Sensor utilizado para observar la humedad del suelo.'],
        ['Monitoreo inteligente','imagenes/imagen-monitoreo-inteligente.jpg','Tecnología aplicada al seguimiento de las condiciones de la huerta.']
    ];
    foreach ($datos as $d) { $stmt->bind_param('sss',$d[0],$d[1],$d[2]); $stmt->execute(); }
    $stmt->close();
}

$check = $conexion->query("SELECT COUNT(*) AS total FROM mediciones");
if ($check && (int)$check->fetch_assoc()['total'] === 0) {
    $stmt = $conexion->prepare('INSERT INTO mediciones(consumo_litros,humedad,agua_ahorrada,eficiencia,fecha_medicion) VALUES(?,?,?,?,?)');
    $muestras = [
        [42.5,62,18.4,78,'2026-09-29 08:00:00'],
        [45.2,65,19.8,80,'2026-09-29 09:00:00'],
        [47.1,68,20.5,82,'2026-09-29 10:00:00'],
        [48.5,67,21.3,82,'2026-09-29 11:00:00'],
        [46.8,70,22.1,84,'2026-09-29 12:00:00'],
        [44.6,72,23.0,86,'2026-09-29 13:00:00']
    ];
    foreach ($muestras as $m) { $stmt->bind_param('dddds',$m[0],$m[1],$m[2],$m[3],$m[4]); $stmt->execute(); }
    $stmt->close();
}
?>
