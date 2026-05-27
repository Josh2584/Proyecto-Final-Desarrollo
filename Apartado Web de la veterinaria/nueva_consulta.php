<?php
// ============================================================
//  nueva_consulta.php — Registrar una consulta veterinaria
// ============================================================
session_start();
$pagina_actual = 'consultas';
require_once 'conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $animal_id   = (int)$_POST['animal_id'];
    $empleado_id = (int)$_POST['empleado_id'];
    $motivo      = trim($_POST['motivo']);
    $diagnostico = trim($_POST['diagnostico']);
    $tratamiento = trim($_POST['tratamiento']);
    $costo = (float)$_POST['costo'];

    // Siguiente ID consulta
    $stid = oci_parse($conn, "SELECT NVL(MAX(Consulta_Id),0)+1 FROM CONSULTA");
    oci_execute($stid);
    $row = oci_fetch_array($stid, OCI_NUM);
    $consulta_id = (int)$row[0];
    oci_free_statement($stid);

    $sql = "INSERT INTO CONSULTA (Consulta_Id, Fecha, Motivo, Diagnostico, Tratamiento, Costo_Consulta, Empleado_Id, Animal_Id, Venta_Id)
            VALUES (:cid, SYSDATE, :mot, :diag, :trat, :costo, :eid, :aid, NULL)";
    $stid = oci_parse($conn, $sql);
    oci_bind_by_name($stid, ':cid',  $consulta_id);
    oci_bind_by_name($stid, ':mot',  $motivo);
    oci_bind_by_name($stid, ':diag', $diagnostico);
    oci_bind_by_name($stid, ':trat', $tratamiento);
    oci_bind_by_name($stid, ':eid',  $empleado_id);
    oci_bind_by_name($stid, ':aid',  $animal_id);
    oci_bind_by_name($stid, ':costo', $costo);
    oci_execute($stid);
    oci_free_statement($stid);
    oci_commit($conn);

    // Obtener nombres para el ticket
    $stid = oci_parse($conn, "SELECT Nombre_Animal FROM ANIMAL WHERE Animal_Id = :aid");
    oci_bind_by_name($stid, ':aid', $animal_id);
    oci_execute($stid);
    $a = oci_fetch_assoc($stid);
    oci_free_statement($stid);

    $stid = oci_parse($conn, "SELECT Nombre_Empleado FROM EMPLEADO WHERE Empleado_Id = :eid");
    oci_bind_by_name($stid, ':eid', $empleado_id);
    oci_execute($stid);
    $e = oci_fetch_assoc($stid);
    oci_free_statement($stid);

    oci_close($conn);

    $_SESSION['ticket'] = [
        'tipo'  => 'Consulta veterinaria',
        'folio' => $consulta_id,
        'volver'=> 'consultas.php',
        'datos' => [
            'Animal'       => $a['NOMBRE_ANIMAL'] ?? '—',
            'Veterinario'  => $e['NOMBRE_EMPLEADO'] ?? '—',
            'Motivo'       => $motivo,
            'Diagnóstico'  => $diagnostico,
            'Tratamiento'  => $tratamiento,
            'Costo' => '$' . number_format($costo,2),
            'Fecha'        => date('d/m/Y'),
        ]
    ];
    header('Location: ticket.php');
    exit;
}

// Cargar datos
$animales = [];
$stid = oci_parse($conn, "SELECT Animal_Id, Nombre_Animal FROM ANIMAL ORDER BY Nombre_Animal");
oci_execute($stid);
while ($row = oci_fetch_assoc($stid)) $animales[] = $row;
oci_free_statement($stid);

$empleados = [];
$stid = oci_parse($conn, "SELECT Empleado_Id, Nombre_Empleado, Especialidad FROM EMPLEADO ORDER BY Nombre_Empleado");
oci_execute($stid);
while ($row = oci_fetch_assoc($stid)) $empleados[] = $row;
oci_free_statement($stid);

oci_close($conn);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva consulta — Gran Malo</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-card { background:#fff; border:1px solid var(--border); border-radius:var(--radius); padding:28px 32px; max-width:560px; }
        .form-group { margin-bottom:20px; }
        .form-group label { display:block; font-size:12px; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted); margin-bottom:6px; font-weight:600; }
        .form-group select, .form-group input, .form-group textarea { width:100%; padding:10px 14px; border:1px solid var(--border); border-radius:8px; font-family:'DM Sans',sans-serif; font-size:14px; color:var(--text-main); background:#fff; outline:none; }
        .form-group textarea { resize:vertical; min-height:80px; }
        .form-group select:focus, .form-group input:focus, .form-group textarea:focus { border-color:var(--teal-mid); }
        .btn-submit { background:var(--teal-dark); color:#fff; border:none; border-radius:8px; padding:12px 28px; font-family:'DM Sans',sans-serif; font-size:14px; font-weight:500; cursor:pointer; width:100%; margin-top:8px; }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="main">
    <div class="topbar">
        <div>
            <h1>Nueva consulta</h1>
            <p class="topbar-sub">Registrar una consulta veterinaria</p>
        </div>
        <a href="consultas.php" style="font-size:13px;color:var(--text-muted);text-decoration:none;">← Volver</a>
    </div>
    <div class="content">
        <?php if ($error): ?>
            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="form-card">
            <form method="POST" action="nueva_consulta.php">

                <div class="form-group">
                    <label>Animal</label>
                    <select name="animal_id" required>
                        <option value="">— Selecciona un animal —</option>
                        <?php foreach ($animales as $a): ?>
                            <option value="<?= $a['ANIMAL_ID'] ?>"><?= htmlspecialchars($a['NOMBRE_ANIMAL']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Veterinario</label>
                    <select name="empleado_id" required>
                        <option value="">— Selecciona un veterinario —</option>
                        <?php foreach ($empleados as $e): ?>
                            <option value="<?= $e['EMPLEADO_ID'] ?>">
                                <?= htmlspecialchars($e['NOMBRE_EMPLEADO']) ?>
                                <?= $e['ESPECIALIDAD'] ? '(' . htmlspecialchars($e['ESPECIALIDAD']) . ')' : '' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Motivo de la consulta</label>
                    <input type="text" name="motivo" required placeholder="Ej. Revisión general, vacuna, etc." maxlength="100">
                </div>

                <div class="form-group">
                    <label>Diagnóstico</label>
                    <textarea name="diagnostico" placeholder="Diagnóstico del veterinario..." maxlength="100"></textarea>
                </div>

                <div class="form-group">
                    <label>Tratamiento</label>
                    <textarea name="tratamiento" placeholder="Tratamiento indicado..." maxlength="100"></textarea>
                </div>

                <div class="form-group">
                    <label>Costo de consulta</label>
                    <input type="number"
                        name="costo"
                        step="0.01"
                        min="0"
                        required
                        placeholder="Ej. 350.00">
                </div>

                <button type="submit" class="btn-submit">Registrar y generar ticket</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
