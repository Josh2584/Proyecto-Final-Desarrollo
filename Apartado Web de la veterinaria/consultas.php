<?php
$pagina_actual = 'consultas';
require_once 'conexion.php';

$filtro_buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

if ($filtro_buscar !== '') {
    $sql = "SELECT
        C.Consulta_Id,
        TO_CHAR(C.Fecha, 'DD/MM/YYYY') AS Fecha,
        C.Motivo,
        C.Diagnostico,
        C.Tratamiento,
        A.Nombre_Animal,
        E.Nombre_Empleado,
        C.Costo_Consulta
    FROM CONSULTA C
    LEFT JOIN ANIMAL   A ON C.Animal_Id   = A.Animal_Id
    LEFT JOIN EMPLEADO E ON C.Empleado_Id = E.Empleado_Id
    WHERE UPPER(C.Motivo)        LIKE UPPER('%'||:buscar||'%')
       OR UPPER(C.Diagnostico)   LIKE UPPER('%'||:buscar||'%')
       OR UPPER(A.Nombre_Animal) LIKE UPPER('%'||:buscar||'%')
    ORDER BY C.Fecha DESC";
    $stid = oci_parse($conn, $sql);
    oci_bind_by_name($stid, ':buscar', $filtro_buscar);
} else {
    $sql = "SELECT
        C.Consulta_Id,
        TO_CHAR(C.Fecha, 'DD/MM/YYYY') AS Fecha,
        C.Motivo,
        C.Diagnostico,
        C.Tratamiento,
        A.Nombre_Animal,
        E.Nombre_Empleado,
        C.Costo_Consulta
    FROM CONSULTA C
    LEFT JOIN ANIMAL   A ON C.Animal_Id   = A.Animal_Id
    LEFT JOIN EMPLEADO E ON C.Empleado_Id = E.Empleado_Id
    ORDER BY C.Fecha DESC";
    $stid = oci_parse($conn, $sql);
}
oci_execute($stid);
$consultas = [];
while ($row = oci_fetch_assoc($stid)) {
    $consultas[] = $row;
}
oci_free_statement($stid);
oci_close($conn);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultas — Gran Malo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="main">
    <div class="topbar">
        <div>
            <h1>Consultas veterinarias</h1>
            <p class="topbar-sub">Historial de consultas médicas realizadas a los animales</p>
        </div>
        <a href="nueva_consulta.php" style="
            background:var(--teal-dark); color:#fff; text-decoration:none;
            border-radius:8px; padding:9px 20px;
            font-family:'DM Sans',sans-serif; font-size:14px; font-weight:500;
            display:flex; align-items:center; gap:8px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nueva consulta
        </a>
    </div>
    <div class="content">
        <form method="GET" action="consultas.php">
            <div class="filters-bar">
                <div class="search-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="buscar" placeholder="Buscar por animal, motivo o diagnóstico..." value="<?= htmlspecialchars($filtro_buscar) ?>">
                </div>
                <button type="submit" style="background:var(--teal-dark);color:#fff;border:none;border-radius:8px;padding:9px 20px;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:500;">Buscar</button>
                <?php if ($filtro_buscar): ?>
                    <a href="consultas.php" style="font-size:13px;color:var(--text-muted);text-decoration:none;">× Limpiar</a>
                <?php endif; ?>
            </div>
        </form>
        <div class="table-card">
            <div class="table-header">
                <h2>Consultas</h2>
                <span class="count-badge"><?= count($consultas) ?> registros</span>
            </div>
            <?php if (empty($consultas)): ?>
                <div class="empty-state"><p>No se encontraron consultas.</p></div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th><th>Fecha</th><th>Animal</th>
                            <th>Motivo</th><th>Diagnóstico</th><th>Tratamiento</th><th>Veterinario</th><th>Costo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($consultas as $c): ?>
                        <tr>
                            <td class="muted">#<?= $c['CONSULTA_ID'] ?></td>
                            <td class="muted"><?= $c['FECHA'] ?? '—' ?></td>
                            <td><strong><?= htmlspecialchars($c['NOMBRE_ANIMAL'] ?? '—') ?></strong></td>
                            <td class="muted"><?= htmlspecialchars($c['MOTIVO'] ?? '—') ?></td>
                            <td class="muted"><?= htmlspecialchars($c['DIAGNOSTICO'] ?? '—') ?></td>
                            <td class="muted"><?= htmlspecialchars($c['TRATAMIENTO'] ?? '—') ?></td>
                            <td class="muted"><?= htmlspecialchars($c['NOMBRE_EMPLEADO'] ?? '—') ?></td>
                            <td><strong style="color:var(--teal-dark);">$<?= number_format($c['COSTO_CONSULTA'],2) ?></strong></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
