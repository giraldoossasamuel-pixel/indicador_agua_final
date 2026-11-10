<?php
require 'conexion.php';
$tituloPagina = 'Indicador de Agua | Indicador de Consumo de Agua';
$ultima = $conexion->query("SELECT * FROM mediciones ORDER BY fecha_medicion DESC, id_medicion DESC LIMIT 1")->fetch_assoc();
$hist = $conexion->query("SELECT consumo_litros, humedad, agua_ahorrada, eficiencia, DATE_FORMAT(fecha_medicion,'%H:%i') AS hora FROM mediciones ORDER BY fecha_medicion DESC, id_medicion DESC LIMIT 8");
$historial = [];
while ($fila = $hist->fetch_assoc()) $historial[] = $fila;
$historial = array_reverse($historial);
include 'includes/header.php';
$humedad = (float)$ultima['humedad'];
$estado = $humedad < 55 ? 'Necesita riego' : ($humedad <= 75 ? 'Condiciones adecuadas' : 'Humedad alta');
$estadoClase = $humedad < 55 ? 'danger' : ($humedad <= 75 ? 'ok' : 'water');
?>
<section class="page-banner indicator-banner"><div class="page-banner-inner"><span class="section-tag">Producto principal · Monitoreo</span><h1>Indicador Inteligente de Consumo de Agua</h1><p>Un panel digital para consultar el consumo, la humedad del suelo, el ahorro y la eficiencia del riego de la huerta.</p><div class="indicator-badges"><span>● Datos registrados</span><span>● Actualización en tiempo real del panel</span></div></div></section>

<section class="section indicator-section">
    <div class="indicator-top"><div><span class="section-tag">Panel de control</span><h2>Estado actual de la huerta</h2></div><button class="btn btn-green" id="actualizarBtn" type="button">Actualizar medición</button></div>

    <div class="measurement-grid" id="measurementGrid">
        <article class="measurement-card water-card"><div class="measurement-head"><span>💧</span><small>CONSUMO HOY</small></div><strong id="consumoValor"><?= number_format($ultima['consumo_litros'],1) ?></strong><em>litros</em><p>Agua utilizada en el seguimiento actual.</p></article>
        <article class="measurement-card soil-card"><div class="measurement-head"><span>🌱</span><small>HUMEDAD DEL SUELO</small></div><strong id="humedadValor"><?= number_format($ultima['humedad'],0) ?></strong><em>%</em><p>Lectura estimada de humedad disponible.</p></article>
        <article class="measurement-card save-card"><div class="measurement-head"><span>♻</span><small>AGUA AHORRADA</small></div><strong id="ahorroValor"><?= number_format($ultima['agua_ahorrada'],1) ?></strong><em>litros</em><p>Estimación de agua conservada frente al uso de referencia.</p></article>
        <article class="measurement-card efficiency-card"><div class="measurement-head"><span>▥</span><small>EFICIENCIA DEL RIEGO</small></div><strong id="eficienciaValor"><?= number_format($ultima['eficiencia'],0) ?></strong><em>%</em><p>Relación entre el agua utilizada y el aprovechamiento del riego.</p></article>
    </div>

    <div class="dashboard-grid">
        <article class="dashboard-card"><div class="dashboard-title"><div><span class="section-tag">Consumo</span><h3>Nivel de consumo actual</h3></div><strong id="consumoPercent"><?= min(100, max(0, round(((float)$ultima['consumo_litros']/70)*100))) ?>%</strong></div><div class="progress-large"><span id="consumoBar" style="width:<?= min(100, max(0, round(((float)$ultima['consumo_litros']/70)*100))) ?>%"></span></div><div class="scale"><span>0 L</span><span>35 L</span><span>70 L</span></div><div class="dashboard-note">El indicador visual facilita identificar si el consumo se acerca al nivel de referencia definido para el proyecto.</div></article>
        <article class="dashboard-card status-card"><span class="section-tag">Lectura del sistema</span><h3>Estado de la huerta</h3><div class="status-pill <?= $estadoClase ?>" id="estadoPill"><span></span><?= $estado ?></div><div class="status-lines"><div><span>Última medición</span><strong id="horaValor"><?= date('d/m/Y H:i', strtotime($ultima['fecha_medicion'])) ?></strong></div><div><span>Objetivo</span><strong>Uso eficiente del agua</strong></div></div></article>
    </div>

    <div class="dashboard-card history-card"><div class="dashboard-title"><div><span class="section-tag">Historial</span><h3>Registro de las últimas mediciones</h3></div><span class="history-label">Litros de consumo</span></div><div class="chart" id="chart"></div><div class="chart-labels" id="chartLabels"></div></div>

    <div class="indicator-info"><div><span class="section-tag">¿Cómo funciona?</span><h2>De la medición a la decisión.</h2></div><div class="indicator-steps"><div><b>01</b><h3>Medir</h3><p>Se registran variables como consumo y humedad del suelo.</p></div><div><b>02</b><h3>Analizar</h3><p>Los datos se organizan para observar cambios y tendencias.</p></div><div><b>03</b><h3>Actuar</h3><p>La información sirve como apoyo para mejorar el riego.</p></div></div></div>
</section>

<script>
const historial = <?= json_encode($historial, JSON_UNESCAPED_UNICODE) ?>;
function renderChart(data){
    const chart=document.getElementById('chart'), labels=document.getElementById('chartLabels');
    chart.innerHTML=''; labels.innerHTML='';
    const max=Math.max(...data.map(x=>Number(x.consumo_litros)),70);
    data.forEach(x=>{
        const col=document.createElement('div'); col.className='chart-col';
        const bar=document.createElement('span'); bar.style.height=Math.max(8,(Number(x.consumo_litros)/max)*100)+'%'; bar.title=x.consumo_litros+' L';
        col.appendChild(bar); chart.appendChild(col);
        const l=document.createElement('span'); l.textContent=x.hora; labels.appendChild(l);
    });
}
renderChart(historial);

document.getElementById('actualizarBtn').addEventListener('click', async function(){
    this.disabled=true; this.textContent='Actualizando...';
    try{
        const r=await fetch('actualizar_medicion.php',{method:'POST'});
        const d=await r.json();
        if(!d.ok) throw new Error(d.mensaje || 'No se pudo actualizar');
        document.getElementById('consumoValor').textContent=Number(d.medicion.consumo_litros).toFixed(1);
        document.getElementById('humedadValor').textContent=Math.round(d.medicion.humedad);
        document.getElementById('ahorroValor').textContent=Number(d.medicion.agua_ahorrada).toFixed(1);
        document.getElementById('eficienciaValor').textContent=Math.round(d.medicion.eficiencia);
        const p=Math.min(100,Math.max(0,Math.round((Number(d.medicion.consumo_litros)/70)*100)));
        document.getElementById('consumoBar').style.width=p+'%'; document.getElementById('consumoPercent').textContent=p+'%';
        document.getElementById('horaValor').textContent=d.medicion.fecha;
        const pill=document.getElementById('estadoPill'); pill.className='status-pill '+d.estado_clase; pill.innerHTML='<span></span>'+d.estado;
        historial.push(d.medicion); if(historial.length>8) historial.shift(); renderChart(historial);
    }catch(e){ alert('No se pudo actualizar la medición. Revisa que MySQL esté activo en XAMPP.'); }
    this.disabled=false; this.textContent='Actualizar medición';
});
</script>
<?php include 'includes/footer.php'; ?>
