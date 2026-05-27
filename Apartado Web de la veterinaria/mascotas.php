<?php
// ============================================================
//  mascotas.php — Animales disponibles
// ============================================================
$pagina_actual = 'mascotas';
require_once 'conexion.php';

// --- Leer filtros que el usuario envía por el formulario ---
$filtro_nombre   = isset($_GET['nombre'])    ? trim($_GET['nombre'])    : '';
$filtro_genero   = isset($_GET['genero'])    ? trim($_GET['genero'])    : '';
$filtro_categoria= isset($_GET['categoria']) ? trim($_GET['categoria']) : '';

// --- Construir la consulta SQL con los filtros opcionales ---
// Trae animales sin dueño (disponibles) junto con su raza y categoría
$sql = "SELECT
            A.Animal_Id,
            A.Nombre_Animal,
            A.Genero,
            A.Color,
            A.Precio_Lista,
            TO_CHAR(A.Fecha_Nacimiento, 'DD/MM/YYYY') AS Fecha_Nac,
            R.Nombre_Raza,
            CA.Nombre_Categoria_Animal
        FROM ANIMAL A
        LEFT JOIN RAZA R ON A.Raza_Id = R.Raza_Id
        LEFT JOIN CATEGORIA_ANIMALES CA ON R.CategoriaA_Id = CA.CategoriaA_Id
        WHERE A.Cliente_Id IS NULL";   /* Solo animales sin dueño = disponibles */

// Añadir filtros si el usuario los proporcionó
if ($filtro_nombre !== '') {
    $sql .= " AND UPPER(A.Nombre_Animal) LIKE UPPER(:nombre)";
}
if ($filtro_genero !== '') {
    $sql .= " AND A.Genero = :genero";
}
if ($filtro_categoria !== '') {
    $sql .= " AND CA.CategoriaA_Id = :categoria";
}

$sql .= " ORDER BY A.Nombre_Animal";

// Preparar y ejecutar la consulta
$stid = oci_parse($conn, $sql);
if ($filtro_nombre !== '')    oci_bind_by_name($stid, ':nombre',    $buscar_nombre);
if ($filtro_genero !== '')    oci_bind_by_name($stid, ':genero',    $filtro_genero);
if ($filtro_categoria !== '') oci_bind_by_name($stid, ':categoria', $filtro_categoria);

// Preparar variable para el LIKE
$buscar_nombre = '%' . $filtro_nombre . '%';

oci_execute($stid);

// Recuperar todos los resultados en un arreglo
$animales = [];
while ($row = oci_fetch_assoc($stid)) {
    $animales[] = $row;
}
oci_free_statement($stid);

// --- Obtener categorías para el filtro ---
$stid_cat = oci_parse($conn, "SELECT CategoriaA_Id, Nombre_Categoria_Animal FROM CATEGORIA_ANIMALES ORDER BY Nombre_Categoria_Animal");
oci_execute($stid_cat);
$categorias = [];
while ($row = oci_fetch_assoc($stid_cat)) {
    $categorias[] = $row;
}
oci_free_statement($stid_cat);

oci_close($conn);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mascotas — Gran Malo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main">
    <div class="topbar">
        <div>
            <h1>Mascotas disponibles</h1>
            <p class="topbar-sub">Animales sin dueño asignado, disponibles para venta o adopción</p>
        </div>
        <a href="venta_mascota.php" style="
            background:var(--teal-dark); color:#fff; text-decoration:none;
            border-radius:8px; padding:9px 20px;
            font-family:'DM Sans',sans-serif; font-size:14px; font-weight:500;
            display:flex; align-items:center; gap:8px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nueva venta / adopción
        </a>
    </div>

    <div class="content">

        <!-- Filtros de búsqueda -->
        <form method="GET" action="mascotas.php">
            <div class="filters-bar">

                <div class="search-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input
                        type="text"
                        name="nombre"
                        placeholder="Buscar por nombre..."
                        value="<?= htmlspecialchars($filtro_nombre) ?>"
                    >
                </div>

                <select name="genero" class="filter-select">
                    <option value="">Todos los géneros</option>
                    <option value="Macho"  <?= $filtro_genero==='Macho'  ? 'selected':'' ?>>Macho</option>
                    <option value="Hembra" <?= $filtro_genero==='Hembra' ? 'selected':'' ?>>Hembra</option>
                </select>

                <select name="categoria" class="filter-select">
                    <option value="">Todas las categorías</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['CATEGORIAA_ID'] ?>"
                            <?= $filtro_categoria == $cat['CATEGORIAA_ID'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['NOMBRE_CATEGORIA_ANIMAL']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" style="
                    background:var(--teal-dark); color:#fff;
                    border:none; border-radius:8px;
                    padding:9px 20px; cursor:pointer;
                    font-family:'DM Sans',sans-serif; font-size:14px; font-weight:500;">
                    Filtrar
                </button>

                <?php if ($filtro_nombre || $filtro_genero || $filtro_categoria): ?>
                    <a href="mascotas.php" style="font-size:13px; color:var(--text-muted); text-decoration:none;">
                        × Limpiar filtros
                    </a>
                <?php endif; ?>
            </div>
        </form>

        <!-- Tabla de animales -->
        <div class="table-card">
            <div class="table-header">
                <h2>Animales</h2>
                <span class="count-badge"><?= count($animales) ?> registros</span>
            </div>

            <?php if (empty($animales)): ?>
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5">
                        <circle cx="4.5" cy="9.5" r="2"/><circle cx="9" cy="5.5" r="2"/>
                        <circle cx="15" cy="5.5" r="2"/><circle cx="19.5" cy="9.5" r="2"/>
                        <path d="M12 13c-3.866 0-7 3.134-7 7 0 .553.448 1 1 1h12c.553 0 1-.448 1-1 0-3.866-3.134-7-7-7z"/>
                    </svg>
                    <p>No se encontraron animales con esos filtros.</p>
                </div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Raza</th>
                            <th>Género</th>
                            <th>Color</th>
                            <th>Nacimiento</th>
                            <th>Precio lista</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($animales as $a): ?>
                        <tr>
                            <td data-label="ID" class="muted">#<?= $a['ANIMAL_ID'] ?></td>

                            <td data-label="Nombre">
                                <strong><?= htmlspecialchars($a['NOMBRE_ANIMAL'] ?? '—') ?></strong>
                            </td>

                            <td data-label="Categoría">
                                <?php if ($a['NOMBRE_CATEGORIA_ANIMAL']): ?>
                                    <span class="badge badge-disponible">
                                        <?= htmlspecialchars($a['NOMBRE_CATEGORIA_ANIMAL']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="muted">—</span>
                                <?php endif; ?>
                            </td>

                            <td data-label="Raza" class="muted">
                                <?= htmlspecialchars($a['NOMBRE_RAZA'] ?? '—') ?>
                            </td>

                            <td data-label="Género">
                                <?php
                                    $g = $a['GENERO'] ?? '';
                                    $clase = $g === 'Macho' ? 'badge-libre' : 'badge-adopcion';
                                    echo $g
                                        ? "<span class='badge $clase'>$g</span>"
                                        : '<span class="muted">—</span>';
                                ?>
                            </td>

                            <td data-label="Color" class="muted">
                                <?= htmlspecialchars($a['COLOR'] ?? '—') ?>
                            </td>

                            <td data-label="Nacimiento" class="muted">
                                <?= $a['FECHA_NAC'] ?? '—' ?>
                            </td>

                            <td data-label="Precio">
                                <?php if ($a['PRECIO_LISTA'] !== null): ?>
                                    <strong style="color:var(--teal-dark);">
                                        $<?= number_format($a['PRECIO_LISTA'], 2, '.', ',') ?>
                                    </strong>
                                <?php else: ?>
                                    <span class="muted">—</span>
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
