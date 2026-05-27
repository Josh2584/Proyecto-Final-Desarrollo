<?php
// ============================================================
//  index.php — Panel principal
// ============================================================
require_once 'conexion.php';

// --- Función auxiliar: ejecuta un SELECT y devuelve el primer número ---
function contar($conn, $sql) {
    $stid = oci_parse($conn, $sql);
    oci_execute($stid);
    $row = oci_fetch_array($stid, OCI_NUM);
    oci_free_statement($stid);
    return $row ? (int)$row[0] : 0;
}

$total_animales   = contar($conn, "SELECT COUNT(*) FROM ANIMAL");
$total_productos  = contar($conn, "SELECT COUNT(*) FROM PRODUCTOS");
$total_clientes   = contar($conn, "SELECT COUNT(*) FROM CLIENTE");
$total_consultas  = contar($conn, "SELECT COUNT(*) FROM CONSULTA");
$total_cirugias   = contar($conn, "SELECT COUNT(*) FROM CIRUGIA");
$animales_disp    = contar($conn, "SELECT COUNT(*) FROM ANIMAL WHERE Cliente_Id IS NULL");

oci_close($conn);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veterinaria Gran Malo — Inicio</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- ===== SIDEBAR ===== -->
<?php include 'sidebar.php'; ?>

<!-- ===== CONTENIDO PRINCIPAL ===== -->
<div class="main">
    <div class="topbar">
        <div>
            <h1>Panel principal</h1>
            <p class="topbar-sub">Resumen general de la veterinaria</p>
        </div>
        <span style="font-size:13px; color:var(--text-muted);">
            <?= date('d/m/Y') ?>
        </span>
    </div>

    <div class="content">

        <!-- Métricas -->
        <div class="metrics-grid">

            <div class="metric-card">
                <div class="metric-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                        <path d="M20 7c0 4.4-8 14-8 14S4 11.4 4 7a8 8 0 0 1 16 0z"/>
                        <circle cx="12" cy="7" r="2.5"/>
                    </svg>
                </div>
                <span class="metric-label">Animales en sistema</span>
                <span class="metric-value"><?= $total_animales ?></span>
            </div>

            <div class="metric-card">
                <div class="metric-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                        <path d="M12 2C8 2 5 5 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-4-3-7-7-7z"/>
                        <path d="M9 9c0-1.1.9-2 2-2h2a2 2 0 0 1 0 4h-1v2"/>
                        <circle cx="13" cy="16" r="0.5" fill="currentColor"/>
                    </svg>
                </div>
                <span class="metric-label">Disponibles para venta y adopción</span>
                <span class="metric-value"><?= $animales_disp ?></span>
            </div>

            <div class="metric-card">
                <div class="metric-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                        <path d="M6 2h12a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z"/>
                        <line x1="9" y1="9" x2="15" y2="9"/>
                        <line x1="9" y1="13" x2="12" y2="13"/>
                    </svg>
                </div>
                <span class="metric-label">Productos en catálogo</span>
                <span class="metric-value"><?= $total_productos ?></span>
            </div>

            <div class="metric-card">
                <div class="metric-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        <path d="M21 21v-2a4 4 0 0 0-3-3.85"/>
                    </svg>
                </div>
                <span class="metric-label">Clientes registrados</span>
                <span class="metric-value"><?= $total_clientes ?></span>
            </div>

            <div class="metric-card">
                <div class="metric-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
                <span class="metric-label">Consultas realizadas</span>
                <span class="metric-value"><?= $total_consultas ?></span>
            </div>

            <div class="metric-card">
                <div class="metric-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                        <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20z"/>
                        <path d="M12 8v4l3 3"/>
                    </svg>
                </div>
                <span class="metric-label">Cirugías realizadas</span>
                <span class="metric-value"><?= $total_cirugias ?></span>
            </div>

        </div><!-- /metrics-grid -->

        <!-- Accesos rápidos -->
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px;">

        <a href="mascotas.php" style="text-decoration:none;">
            <div class="table-card"
                 style="padding:20px; cursor:pointer; transition:border-color 0.15s;
                        min-height:170px; display:flex; flex-direction:column; justify-content:center;"
                 onmouseover="this.style.borderColor='var(--teal-mid)'"
                 onmouseout="this.style.borderColor='var(--border)'">

                <p style="font-size:13px; color:var(--text-muted); margin-bottom:6px;">
                    Ver sección
                </p>

                <p style="font-family:'Fraunces',serif; font-size:18px; color:var(--teal-dark);">
                    🐾 Mascotas
                </p>

                0<p style="font-size:13px; color:var(--text-muted); margin-top:4px;">
                    Animales disponibles para venta o adopción
                </p>

            </div>
        </a>

        <a href="productos.php" style="text-decoration:none;">
            <div class="table-card"
                 style="padding:20px; cursor:pointer; transition:border-color 0.15s;
                        min-height:170px; display:flex; flex-direction:column; justify-content:center;"
                 onmouseover="this.style.borderColor='var(--teal-mid)'"
                 onmouseout="this.style.borderColor='var(--border)'">

                <p style="font-size:13px; color:var(--text-muted); margin-bottom:6px;">
                    Ver sección
                </p>

                <p style="font-family:'Fraunces',serif; font-size:18px; color:var(--teal-dark);">
                    📦 Productos
                </p>

                <p style="font-size:13px; color:var(--text-muted); margin-top:4px;">
                    Catálogo de productos e inventario
                </p>

            </div>
        </a>

        <a href="operaciones.php" style="text-decoration:none;">
            <div class="table-card"
                 style="padding:20px; cursor:pointer; transition:border-color 0.15s;
                        min-height:170px; display:flex; flex-direction:column; justify-content:center;"
                 onmouseover="this.style.borderColor='var(--teal-mid)'"
                 onmouseout="this.style.borderColor='var(--border)'">

                <p style="font-size:13px; color:var(--text-muted); margin-bottom:6px;">
                    Ver sección
                </p>

                <p style="font-family:'Fraunces',serif; font-size:18px; color:var(--teal-dark);">
                    🩺 Operaciones
                </p>

                <p style="font-size:13px; color:var(--text-muted); margin-top:4px;">
                    Cirugías de animales
                </p>

            </div>
        </a>

        <a href="consultas.php" style="text-decoration:none;">
            <div class="table-card"
                 style="padding:20px; cursor:pointer; transition:border-color 0.15s;
                        min-height:170px; display:flex; flex-direction:column; justify-content:center;"
                 onmouseover="this.style.borderColor='var(--teal-mid)'"
                 onmouseout="this.style.borderColor='var(--border)'">

                <p style="font-size:13px; color:var(--text-muted); margin-bottom:6px;">
                    Ver sección
                </p>

                <p style="font-family:'Fraunces',serif; font-size:18px; color:var(--teal-dark);">
                    📋 Consultas
                </p>

                <p style="font-size:13px; color:var(--text-muted); margin-top:4px;">
                      Consultas veterinarias
                </p>

            </div>
        </a>

    </div>

    </div><!-- /content -->
</div><!-- /main -->

</body>
</html>
