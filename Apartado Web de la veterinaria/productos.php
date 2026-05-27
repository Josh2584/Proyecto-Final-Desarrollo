<?php
// ============================================================
//  productos.php — Catálogo de productos
// ============================================================
$pagina_actual = 'productos';
require_once 'conexion.php';

// --- Leer filtros ---
$filtro_buscar   = isset($_GET['buscar'])    ? trim($_GET['buscar'])    : '';
$filtro_categoria= isset($_GET['categoria']) ? trim($_GET['categoria']) : '';
$filtro_receta   = isset($_GET['receta'])    ? trim($_GET['receta'])    : '';

// --- Construir consulta ---
$sql = "SELECT
            P.Producto_Id,
            P.Descripcion_Producto,
            P.Precio_Lista,
            P.Existencia,
            P.Requiere_Receta,
            CP.Nombre_Categoria_Producto
        FROM PRODUCTOS P
        LEFT JOIN CATEGORIA_PRODUCTO CP ON P.CategoriaP_Id = CP.CategoriaP_Id
        WHERE 1=1";

if ($filtro_buscar !== '') {
    $sql .= " AND UPPER(P.Descripcion_Producto) LIKE UPPER(:buscar)";
}
if ($filtro_categoria !== '') {
    $sql .= " AND P.CategoriaP_Id = :categoria";
}
if ($filtro_receta !== '') {
    $sql .= " AND P.Requiere_Receta = :receta";
}

$sql .= " ORDER BY P.Descripcion_Producto";

$stid = oci_parse($conn, $sql);

$buscar_like = '%' . $filtro_buscar . '%';
if ($filtro_buscar !== '')    oci_bind_by_name($stid, ':buscar',    $buscar_like);
if ($filtro_categoria !== '') oci_bind_by_name($stid, ':categoria', $filtro_categoria);
if ($filtro_receta !== '')    oci_bind_by_name($stid, ':receta',    $filtro_receta);

oci_execute($stid);

$productos = [];
while ($row = oci_fetch_assoc($stid)) {
    $productos[] = $row;
}
oci_free_statement($stid);

// --- Categorías para el filtro ---
$stid_cat = oci_parse($conn, "SELECT CategoriaP_Id, Nombre_Categoria_Producto FROM CATEGORIA_PRODUCTO ORDER BY Nombre_Categoria_Producto");
oci_execute($stid_cat);
$categorias = [];
while ($row = oci_fetch_assoc($stid_cat)) {
    $categorias[] = $row;
}
oci_free_statement($stid_cat);

// --- Estadísticas rápidas ---
$total = count($productos);
$sin_stock = 0;
$requieren_receta = 0;
foreach ($productos as $p) {
    if ((int)$p['EXISTENCIA'] === 0) $sin_stock++;
    if ($p['REQUIERE_RECETA'] === 'S') $requieren_receta++;
}

oci_close($conn);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos — Gran Malo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main">
    <div class="topbar">
        <div>
            <h1>Catálogo de productos</h1>
            <p class="topbar-sub">Inventario de medicamentos, alimentos y accesorios</p>
        </div>
        <a href="venta_producto.php" style="
            background:var(--teal-dark); color:#fff; text-decoration:none;
            border-radius:8px; padding:9px 20px;
            font-family:'DM Sans',sans-serif; font-size:14px; font-weight:500;
            display:flex; align-items:center; gap:8px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nueva venta
        </a>
    </div>

    <div class="content">

        <!-- Métricas rápidas -->
        <div class="metrics-grid" style="margin-bottom:24px;">
            <div class="metric-card">
                <span class="metric-label">Total de productos</span>
                <span class="metric-value"><?= $total ?></span>
            </div>
            <div class="metric-card">
                <span class="metric-label">Sin existencia</span>
                <span class="metric-value" style="color:<?= $sin_stock > 0 ? '#c0392b' : 'var(--teal-dark)' ?>">
                    <?= $sin_stock ?>
                </span>
            </div>
            <div class="metric-card">
                <span class="metric-label">Requieren receta</span>
                <span class="metric-value"><?= $requieren_receta ?></span>
            </div>
        </div>

        <!-- Filtros -->
        <form method="GET" action="productos.php">
            <div class="filters-bar">

                <div class="search-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input
                        type="text"
                        name="buscar"
                        placeholder="Buscar producto..."
                        value="<?= htmlspecialchars($filtro_buscar) ?>"
                    >
                </div>

                <select name="categoria" class="filter-select">
                    <option value="">Todas las categorías</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['CATEGORIAP_ID'] ?>"
                            <?= $filtro_categoria == $cat['CATEGORIAP_ID'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['NOMBRE_CATEGORIA_PRODUCTO']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="receta" class="filter-select">
                    <option value="">Con y sin receta</option>
                    <option value="S" <?= $filtro_receta==='S' ? 'selected':'' ?>>Requiere receta</option>
                    <option value="N" <?= $filtro_receta==='N' ? 'selected':'' ?>>Sin receta</option>
                </select>

                <button type="submit" style="
                    background:var(--teal-dark); color:#fff;
                    border:none; border-radius:8px;
                    padding:9px 20px; cursor:pointer;
                    font-family:'DM Sans',sans-serif; font-size:14px; font-weight:500;">
                    Filtrar
                </button>

                <?php if ($filtro_buscar || $filtro_categoria || $filtro_receta): ?>
                    <a href="productos.php" style="font-size:13px; color:var(--text-muted); text-decoration:none;">
                        × Limpiar filtros
                    </a>
                <?php endif; ?>

            </div>
        </form>

        <!-- Tabla de productos -->
        <div class="table-card">
            <div class="table-header">
                <h2>Productos</h2>
                <span class="count-badge"><?= count($productos) ?> registros</span>
            </div>

            <?php if (empty($productos)): ?>
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5">
                        <rect x="2" y="7" width="20" height="14" rx="2"/>
                        <path d="M16 7V5a2 2 0 0 0-4 0v2M8 7V5a2 2 0 0 0-4 0v2"/>
                    </svg>
                    <p>No se encontraron productos con esos filtros.</p>
                </div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Descripción</th>
                            <th>Categoría</th>
                            <th>Precio lista</th>
                            <th>Existencia</th>
                            <th>Receta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $p): ?>
                        <tr>
                            <td data-label="ID" class="muted">#<?= $p['PRODUCTO_ID'] ?></td>

                            <td data-label="Descripción">
                                <strong><?= htmlspecialchars($p['DESCRIPCION_PRODUCTO'] ?? '—') ?></strong>
                            </td>

                            <td data-label="Categoría" class="muted">
                                <?= htmlspecialchars($p['NOMBRE_CATEGORIA_PRODUCTO'] ?? '—') ?>
                            </td>

                            <td data-label="Precio">
                                <?php if ($p['PRECIO_LISTA'] !== null): ?>
                                    <strong style="color:var(--teal-dark);">
                                        $<?= number_format($p['PRECIO_LISTA'], 2, '.', ',') ?>
                                    </strong>
                                <?php else: ?>
                                    <span class="muted">—</span>
                                <?php endif; ?>
                            </td>

                            <td data-label="Existencia">
                                <?php
                                    $exist = (int)($p['EXISTENCIA'] ?? 0);
                                    if ($exist === 0) {
                                        echo '<span class="badge badge-vendido">Sin stock</span>';
                                    } elseif ($exist <= 5) {
                                        echo '<span class="badge badge-adopcion">' . $exist . ' unid.</span>';
                                    } else {
                                        echo '<span class="badge badge-disponible">' . $exist . ' unid.</span>';
                                    }
                                ?>
                            </td>

                            <td data-label="Receta">
                                <?php if ($p['REQUIERE_RECETA'] === 'S'): ?>
                                    <span class="badge badge-receta">Sí requiere</span>
                                <?php else: ?>
                                    <span class="badge badge-libre">No requiere</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div><!-- /table-card -->

    </div><!-- /content -->
</div><!-- /main -->

</body>
</html>
