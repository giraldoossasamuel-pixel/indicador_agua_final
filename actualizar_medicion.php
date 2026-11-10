<?php
header('Content-Type: application/json; charset=utf-8');
require 'conexion.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo json_encode(['ok'=>false,'mensaje'=>'Método no permitido']); exit; }
$consumo = round(38 + mt_rand(0,260)/10, 1);
$humedad = mt_rand(48,82);
$ahorro = round(12 + mt_rand(0,180)/10, 1);
$eficiencia = mt_rand(72,94);
$stmt=$conexion->prepare('INSERT INTO mediciones(consumo_litros,humedad,agua_ahorrada,eficiencia) VALUES(?,?,?,?)');
$stmt->bind_param('dddd',$consumo,$humedad,$ahorro,$eficiencia);
if(!$stmt->execute()){ echo json_encode(['ok'=>false,'mensaje'=>'No se pudo guardar la medición']); exit; }
$estado = $humedad < 55 ? 'Necesita riego' : ($humedad <= 75 ? 'Condiciones adecuadas' : 'Humedad alta');
$clase = $humedad < 55 ? 'danger' : ($humedad <= 75 ? 'ok' : 'water');
echo json_encode(['ok'=>true,'medicion'=>['consumo_litros'=>$consumo,'humedad'=>$humedad,'agua_ahorrada'=>$ahorro,'eficiencia'=>$eficiencia,'hora'=>date('H:i'),'fecha'=>date('d/m/Y H:i')],'estado'=>$estado,'estado_clase'=>$clase],JSON_UNESCAPED_UNICODE);
