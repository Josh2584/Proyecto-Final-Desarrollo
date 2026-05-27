<?php
// ============================================================
//  venta_mascota.php — Vender o adoptar una mascota
// ============================================================
session_start();
$pagina_actual = 'animales';
require_once 'conexion.php';

$error = '';

// --- Procesar formulario ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $animal_id   = (int)$_POST['animal_id'];
    $cliente_id  = (int)$_POST['cliente_id'];
    $empleado_id = (int)$_POST['empleado_id'];
    $metodo_pago = trim($_POST['metodo_pago']);
    $tipo        = trim($_POST['tipo']); // 'Venta' o 'Adopcion'
    $precio      = (float)$_POST['precio_venta'];

    try {
        // 1. Obtener siguiente ID de venta
        $stid = oci_parse($conn, "SELECT NVL(MAX(Venta_Id),0)+1 FROM VENTAS");
        oci_execute($stid);
        $row = oci_fetch_array($stid, OCI_NUM);
        $venta_id = (int)$row[0];
        oci_free_statement($stid);

        // 2. Insertar venta
        $sql = "INSERT INTO VENTAS VALUES (:vid, SYSDATE, :cid, :eid, :mp, :tv)";
        $stid = oci_parse($conn, $sql);
        oci_bind_by_name($stid, ':vid', $venta_id);
        oci_bind_by_name($stid, ':cid', $cliente_id);
        oci_bind_by_name($stid, ':eid', $empleado_id);
        oci_bind_by_name($stid, ':mp',  $metodo_pago);
        oci_bind_by_name($stid, ':tv',  $tipo);
        oci_execute($stid);
        oci_free_statement($stid);

        // 3. Insertar en VENTAS_ANIMAL
        $sql = "INSERT INTO VENTAS_ANIMAL VALUES (:aid, :vid, :pv)";
        $stid = oci_parse($conn, $sql);
        oci_bind_by_name($stid, ':aid', $animal_id);
        oci_bind_by_name($stid, ':vid', $venta_id);
        oci_bind_by_name($stid, ':pv',  $precio);
        oci_execute($stid);
        oci_free_statement($stid);

        // 4. Asignar cliente al animal
        $sql = "UPDATE ANIMAL SET Cliente_Id = :cid WHERE Animal_Id = :aid";
        $stid = oci_parse($conn, $sql);
        oci_bind_by_name($stid, ':cid', $cliente_id);
        oci_bind_by_name($stid, ':aid', $animal_id);
        oci_execute($stid);
        oci_free_statement($stid);

        oci_commit($conn);

        // Obtener nombres para el ticket
        $stid = oci_parse($conn, "SELECT Nombre_Animal FROM ANIMAL WHERE Animal_Id = :aid");
        oci_bind_by_name($stid, ':aid', $animal_id);
        oci_execute($stid);
        $a = oci_fetch_assoc($stid);
        oci_free_statement($stid);

        $stid = oci_parse($conn, "SELECT Nombre_Cliente FROM CLIENTE WHERE Cliente_Id = :cid");
        oci_bind_by_name($stid, ':cid', $cliente_id);
        oci_execute($stid);
        $c = oci_fetch_assoc($stid);
        oci_free_statement($stid);

        $stid = oci_parse($conn, "SELECT Nombre_Empleado FROM EMPLEADO WHERE Empleado_Id = :eid");
        oci_bind_by_name($stid, ':eid', $empleado_id);
        oci_execute($stid);
        $e = oci_fetch_assoc($stid);
        oci_free_statement($stid);

        oci_close($conn);

        $_SESSION['ticket'] = [
            'tipo'  => $tipo === 'Adopcion' ? 'Adopción de mascota' : 'Venta de mascota',
            'folio' => $venta_id,
            'volver'=> 'mascotas.php',
            'total' => $precio,
            'datos' => [
                'Animal'      => $a['NOMBRE_ANIMAL'] ?? '—',
                'Cliente'     => $c['NOMBRE_CLIENTE'] ?? '—',
                'Atendido por'=> $e['NOMBRE_EMPLEADO'] ?? '—',
                'Tipo'        => $tipo,
                'Método de pago' => $metodo_pago,
                'Precio'      => '$' . number_format($precio, 2, '.', ','),
            ]
        ];
        header('Location: ticket.php');
        exit;

    } catch (Exception $ex) {
        $error = 'Error al registrar: ' . $ex->getMessage();
    }
}

// --- Cargar datos para el formulario ---
$animales = [];
$stid = oci_parse($conn, "SELECT A.Animal_Id, A.Nombre_Animal, A.Precio_Lista, R.Nombre_Raza
    FROM ANIMAL A LEFT JOIN RAZA R ON A.Raza_Id = R.Raza_Id
    WHERE A.Cliente_Id IS NULL ORDER BY A.Nombre_Animal");
oci_execute($stid);
while ($row = oci_fetch_assoc($stid)) $animales[] = $row;
oci_free_statement($stid);

$clientes = [];
$stid = oci_parse($conn, "SELECT Cliente_Id, Nombre_Cliente FROM CLIENTE ORDER BY Nombre_Cliente");
oci_execute($stid);
while ($row = oci_fetch_assoc($stid)) $clientes[] = $row;
oci_free_statement($stid);

$empleados = [];
$stid = oci_parse($conn, "SELECT Empleado_Id, Nombre_Empleado FROM EMPLEADO ORDER BY Nombre_Empleado");
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
    <title>Venta de mascota — Gran Malo</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-card { background:#fff; border:1px solid var(--border); border-radius:var(--radius); padding:28px 32px; max-width:560px; }
        .form-group { margin-bottom:20px; }
        .form-group label { display:block; font-size:12px; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted); margin-bottom:6px; font-weight:600; }
        .form-group select, .form-group input {
            width:100%; padding:10px 14px;
            border:1px solid var(--border); border-radius:8px;
            font-family:'DM Sans',sans-serif; font-size:14px; color:var(--text-main);
            background:#fff; outline:none;
        }
        .form-group select:focus, .form-group input:focus { border-color:var(--teal-mid); }
        .btn-submit { background:var(--teal-dark); color:#fff; border:none; border-radius:8px; padding:12px 28px; font-family:'DM Sans',sans-serif; font-size:14px; font-weight:500; cursor:pointer; width:100%; margin-top:8px; }
        .precio-preview { background:var(--teal-light); border-radius:8px; padding:12px 16px; font-family:'Fraunces',serif; font-size:20px; color:var(--teal-dark); margin-bottom:20px; display:none; }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="main">
    <div class="topbar">
        <div>
            <h1>Venta / Adopción de mascota</h1>
            <p class="topbar-sub">Registrar una nueva venta o adopción</p>
        </div>
        <a href="mascotas.php" style="font-size:13px;color:var(--text-muted);text-decoration:none;">← Volver</a>
    </div>
    <div class="content">
        <?php if ($error): ?>
            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="form-card">
            <form method="POST" action="venta_mascota.php">

                <div class="form-group">
                    <label>Animal</label>
                    <select name="animal_id" id="animal_select" required onchange="actualizarPrecio()">
                        <option value="">— Selecciona un animal —</option>
                        <?php foreach ($animales as $a): ?>
                            <option value="<?= $a['ANIMAL_ID'] ?>"
                                    data-precio="<?= $a['PRECIO_LISTA'] ?? 0 ?>">
                                <?= htmlspecialchars($a['NOMBRE_ANIMAL']) ?>
                                (<?= htmlspecialchars($a['NOMBRE_RAZA'] ?? 'Sin raza') ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="precio-preview" id="precio_preview">
                    Precio lista: $<span id="precio_valor">0.00</span>
                </div>

                <div class="form-group">
                    <label>Precio de venta ($)</label>
                    <input type="number" name="precio_venta" id="precio_venta" step="0.01" min="0" required placeholder="0.00">
                </div>

                <div class="form-group">
                    <label>Cliente</label>
                    <select name="cliente_id" required>
                        <option value="">— Selecciona un cliente —</option>
                        <?php foreach ($clientes as $c): ?>
                            <option value="<?= $c['CLIENTE_ID'] ?>">
                                <?= htmlspecialchars($c['NOMBRE_CLIENTE']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Empleado que atiende</label>
                    <select name="empleado_id" required>
                        <option value="">— Selecciona un empleado —</option>
                        <?php foreach ($empleados as $e): ?>
                            <option value="<?= $e['EMPLEADO_ID'] ?>">
                                <?= htmlspecialchars($e['NOMBRE_EMPLEADO']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tipo de operación</label>
                    <select name="tipo" required>
                        <option value="Venta">Venta</option>
                        <option value="Adopcion">Adopción</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Método de pago</label>
                    <select name="metodo_pago" required>
                        <option value="Efectivo">Efectivo</option>
                        <option value="Tarjeta">Tarjeta</option>
                        <option value="Mixto">Mixto</option>
                    </select>
                </div>

                <button type="submit" class="btn-submit">Registrar y generar ticket</button>
            </form>
        </div>
    </div>
</div>
<script>
function actualizarPrecio() {
    const sel = document.getElementById('animal_select');
    const opt = sel.options[sel.selectedIndex];
    const precio = opt.dataset.precio || 0;
    document.getElementById('precio_valor').textContent = parseFloat(precio).toFixed(2);
    document.getElementById('precio_venta').value = parseFloat(precio).toFixed(2);
    document.getElementById('precio_preview').style.display = precio > 0 ? 'block' : 'none';
}
</script>
</body>
</html>
