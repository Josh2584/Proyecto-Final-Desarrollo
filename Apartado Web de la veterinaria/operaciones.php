<?php
$pagina_actual = 'operaciones';
require_once 'conexion.php';

$filtro_buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

if ($filtro_buscar !== '') {
    $sql = "SELECT
        CG.Cirugia_Id,
        TO_CHAR(CG.Fecha, 'DD/MM/YYYY') AS Fecha,
        CG.Tipo_Cirugia,
        CG.Descripcion,
        A.Nombre_Animal,
        E.Nombre_Empleado,
        CG.Costo_Cirugia
    FROM CIRUGIA CG
    LEFT JOIN ANIMAL   A ON CG.Animal_Id   = A.Animal_Id
    LEFT JOIN EMPLEADO E ON CG.Empleado_Id = E.Empleado_Id
    WHERE UPPER(CG.Tipo_Cirugia) LIKE UPPER('%'||:buscar||'%')
       OR UPPER(CG.Descripcion)  LIKE UPPER('%'||:buscar||'%')
       OR UPPER(A.Nombre_Animal) LIKE UPPER('%'||:buscar||'%')
    ORDER BY CG.Fecha DESC";
    $stid = oci_parse($conn, $sql);
    oci_bind_by_name($stid, ':buscar', $filtro_buscar);
} else {
    $sql = "SELECT
        CG.Cirugia_Id,
        TO_CHAR(CG.Fecha, 'DD/MM/YYYY') AS Fecha,
        CG.Tipo_Cirugia,
        CG.Descripcion,
        A.Nombre_Animal,
        E.Nombre_Empleado,
        CG.Costo_Cirugia
    FROM CIRUGIA CG
    LEFT JOIN ANIMAL   A ON CG.Animal_Id   = A.Animal_Id
    LEFT JOIN EMPLEADO E ON CG.Empleado_Id = E.Empleado_Id
    ORDER BY CG.Fecha DESC";
    $stid = oci_parse($conn, $sql);
}
oci_execute($stid);
$cirugias = [];
while ($row = oci_fetch_assoc($stid)) {
    $cirugias[] = $row;
}
oci_free_statement($stid);
oci_close($conn);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operaciones — Gran Malo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="main">
    <div class="topbar">
        <div>
            <h1>Operaciones quirúrgicas</h1>
            <p class="topbar-sub">Historial de cirugías realizadas a los animales</p>
        </div>
        <a href="nueva_cirugia.php" style="
            background:var(--teal-dark); color:#fff; text-decoration:none;
            border-radius:8px; padding:9px 20px;
            font-family:'DM Sans',sans-serif; font-size:14px; font-weight:500;
            display:flex; align-items:center; gap:8px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nueva cirugía
        </a>
    </div>
    <div class="content">
        <form method="GET" action="operaciones.php">
            <div class="filters-bar">
                <div class="search-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="buscar" placeholder="Buscar por animal o tipo de cirugía..." value="<?= htmlspecialchars($filtro_buscar) ?>">
                </div>
                <button type="submit" style="background:var(--teal-dark);color:#fff;border:none;border-radius:8px;padding:9px 20px;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:500;">Buscar</button>
                <?php if ($filtro_buscar): ?>
                    <a href="operaciones.php" style="font-size:13px;color:var(--text-muted);text-decoration:none;">× Limpiar</a>
                <?php endif; ?>
            </div>
        </form>
        <div class="table-card">
            <div class="table-header">
                <h2>Cirugías</h2>
                <span class="count-badge"><?= count($cirugias) ?> registros</span>
            </div>
            <?php if (empty($cirugias)): ?>
                <div class="empty-state"><p>No se encontraron cirugías registradas.</p></div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th><th>Fecha</th><th>Animal</th>
                            <th>Tipo de cirugía</th><th>Descripción</th><th>Cirujano</th> <th>Costo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cirugias as $cg): ?>
                        <tr>
                            <td class="muted">#<?= $cg['CIRUGIA_ID'] ?></td>
                            <td class="muted"><?= $cg['FECHA'] ?? '—' ?></td>
                            <td><strong><?= htmlspecialchars($cg['NOMBRE_ANIMAL'] ?? '—') ?></strong></td>
                            <td><?= htmlspecialchars($cg['TIPO_CIRUGIA'] ?? '—') ?></td>
                            <td class="muted"><?= htmlspecialchars($cg['DESCRIPCION'] ?? '—') ?></td>
                            <td class="muted"><?= htmlspecialchars($cg['NOMBRE_EMPLEADO'] ?? '—') ?></td>
                            <td><strong style="color:var(--teal-dark);">$<?= number_format($cg['COSTO_CIRUGIA'],2) ?></strong></td>
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
