<?php
// ============================================================
//  venta_producto.php — Vender un producto
// ============================================================
session_start();
$pagina_actual = 'productos';
require_once 'conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto_id = (int)$_POST['producto_id'];
    $cliente_id  = (int)$_POST['cliente_id'];
    $empleado_id = (int)$_POST['empleado_id'];
    $metodo_pago = trim($_POST['metodo_pago']);
    $cantidad    = (int)$_POST['cantidad'];
    $precio      = (float)$_POST['precio_venta'];

    // Verificar existencia
    $stid = oci_parse($conn, "SELECT Existencia, Descripcion_Producto FROM PRODUCTOS WHERE Producto_Id = :pid");
    oci_bind_by_name($stid, ':pid', $producto_id);
    oci_execute($stid);
    $prod = oci_fetch_assoc($stid);
    oci_free_statement($stid);

    if (!$prod) {
        $error = 'Producto no encontrado.';
    } elseif ((int)$prod['EXISTENCIA'] < $cantidad) {
        $error = 'No hay suficiente existencia. Disponible: ' . $prod['EXISTENCIA'];
    } else {
        // 1. Siguiente ID de venta
        $stid = oci_parse($conn, "SELECT NVL(MAX(Venta_Id),0)+1 FROM VENTAS");
        oci_execute($stid);
        $row = oci_fetch_array($stid, OCI_NUM);
        $venta_id = (int)$row[0];
        oci_free_statement($stid);

        // 2. Insertar venta
        $tipo = 'Venta producto';
        $sql = "INSERT INTO VENTAS VALUES (:vid, SYSDATE, :cid, :eid, :mp, :tv)";
        $stid = oci_parse($conn, $sql);
        oci_bind_by_name($stid, ':vid', $venta_id);
        oci_bind_by_name($stid, ':cid', $cliente_id);
        oci_bind_by_name($stid, ':eid', $empleado_id);
        oci_bind_by_name($stid, ':mp',  $metodo_pago);
        oci_bind_by_name($stid, ':tv',  $tipo);
        oci_execute($stid);
        oci_free_statement($stid);

        // 3. Insertar en VENTAS_PRODUCTO
        $sql = "INSERT INTO VENTAS_PRODUCTO VALUES (:vid, :pid, :pv, :cant)";
        $stid = oci_parse($conn, $sql);
        oci_bind_by_name($stid, ':vid',  $venta_id);
        oci_bind_by_name($stid, ':pid',  $producto_id);
        oci_bind_by_name($stid, ':pv',   $precio);
        oci_bind_by_name($stid, ':cant', $cantidad);
        oci_execute($stid);
        oci_free_statement($stid);

        // 4. Actualizar existencia
        $sql = "UPDATE PRODUCTOS SET Existencia = Existencia - :cant WHERE Producto_Id = :pid";
        $stid = oci_parse($conn, $sql);
        oci_bind_by_name($stid, ':cant', $cantidad);
        oci_bind_by_name($stid, ':pid',  $producto_id);
        oci_execute($stid);
        oci_free_statement($stid);

        oci_commit($conn);

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

        $total = $precio * $cantidad;
        $_SESSION['ticket'] = [
            'tipo'  => 'Venta de producto',
            'folio' => $venta_id,
            'volver'=> 'productos.php',
            'total' => $total,
            'datos' => [
                'Producto'       => $prod['DESCRIPCION_PRODUCTO'],
                'Cantidad'       => $cantidad . ' unidad(es)',
                'Precio unitario'=> '$' . number_format($precio, 2, '.', ','),
                'Cliente'        => $c['NOMBRE_CLIENTE'] ?? '—',
                'Atendido por'   => $e['NOMBRE_EMPLEADO'] ?? '—',
                'Método de pago' => $metodo_pago,
                'Total'          => '$' . number_format($total, 2, '.', ','),
            ]
        ];
        header('Location: ticket.php');
        exit;
    }
}

// Cargar datos
$productos = [];
$stid = oci_parse($conn, "SELECT Producto_Id, Descripcion_Producto, Precio_Lista, Existencia FROM PRODUCTOS WHERE Existencia > 0 ORDER BY Descripcion_Producto");
oci_execute($stid);
while ($row = oci_fetch_assoc($stid)) $productos[] = $row;
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
    <title>Venta de producto — Gran Malo</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-card { background:#fff; border:1px solid var(--border); border-radius:var(--radius); padding:28px 32px; max-width:560px; }
        .form-group { margin-bottom:20px; }
        .form-group label { display:block; font-size:12px; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted); margin-bottom:6px; font-weight:600; }
        .form-group select, .form-group input { width:100%; padding:10px 14px; border:1px solid var(--border); border-radius:8px; font-family:'DM Sans',sans-serif; font-size:14px; color:var(--text-main); background:#fff; outline:none; }
        .form-group select:focus, .form-group input:focus { border-color:var(--teal-mid); }
        .btn-submit { background:var(--teal-dark); color:#fff; border:none; border-radius:8px; padding:12px 28px; font-family:'DM Sans',sans-serif; font-size:14px; font-weight:500; cursor:pointer; width:100%; margin-top:8px; }
        .info-box { background:var(--teal-light); border-radius:8px; padding:12px 16px; font-size:13px; color:var(--teal-dark); margin-bottom:20px; display:none; }
        .total-preview { background:var(--teal-light); border-radius:8px; padding:14px 16px; font-family:'Fraunces',serif; font-size:22px; color:var(--teal-dark); margin-bottom:20px; }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="main">
    <div class="topbar">
        <div>
            <h1>Venta de producto</h1>
            <p class="topbar-sub">Registrar una nueva venta de producto</p>
        </div>
        <a href="productos.php" style="font-size:13px;color:var(--text-muted);text-decoration:none;">← Volver</a>
    </div>
    <div class="content">
        <?php if ($error): ?>
            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="form-card">
            <form method="POST" action="venta_producto.php">

                <div class="form-group">
                    <label>Producto</label>
                    <select name="producto_id" id="prod_select" required onchange="actualizarProducto()">
                        <option value="">— Selecciona un producto —</option>
                        <?php foreach ($productos as $p): ?>
                            <option value="<?= $p['PRODUCTO_ID'] ?>"
                                    data-precio="<?= $p['PRECIO_LISTA'] ?? 0 ?>"
                                    data-stock="<?= $p['EXISTENCIA'] ?>">
                                <?= htmlspecialchars($p['DESCRIPCION_PRODUCTO']) ?>
                                (Stock: <?= $p['EXISTENCIA'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="info-box" id="info_box">
                    Precio lista: $<span id="precio_ref">0.00</span> &nbsp;·&nbsp; Stock disponible: <span id="stock_ref">0</span>
                </div>

                <div class="form-group">
                    <label>Cantidad</label>
                    <input type="number" name="cantidad" id="cantidad" min="1" value="1" required onchange="calcularTotal()">
                </div>

                <div class="form-group">
                    <label>Precio unitario ($)</label>
                    <input type="number" name="precio_venta" id="precio_venta" step="0.01" min="0" required placeholder="0.00" onchange="calcularTotal()">
                </div>

                <div class="total-preview" id="total_preview" style="display:none">
                    Total: $<span id="total_valor">0.00</span>
                </div>

                <div class="form-group">
                    <label>Cliente</label>
                    <select name="cliente_id" required>
                        <option value="">— Selecciona un cliente —</option>
                        <?php foreach ($clientes as $c): ?>
                            <option value="<?= $c['CLIENTE_ID'] ?>"><?= htmlspecialchars($c['NOMBRE_CLIENTE']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Empleado que atiende</label>
                    <select name="empleado_id" required>
                        <option value="">— Selecciona un empleado —</option>
                        <?php foreach ($empleados as $e): ?>
                            <option value="<?= $e['EMPLEADO_ID'] ?>"><?= htmlspecialchars($e['NOMBRE_EMPLEADO']) ?></option>
                        <?php endforeach; ?>
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
function actualizarProducto() {
    const sel = document.getElementById('prod_select');
    const opt = sel.options[sel.selectedIndex];
    const precio = opt.dataset.precio || 0;
    const stock  = opt.dataset.stock  || 0;
    document.getElementById('precio_ref').textContent = parseFloat(precio).toFixed(2);
    document.getElementById('stock_ref').textContent  = stock;
    document.getElementById('precio_venta').value = parseFloat(precio).toFixed(2);
    document.getElementById('info_box').style.display = precio > 0 ? 'block' : 'none';
    document.getElementById('cantidad').max = stock;
    calcularTotal();
}
function calcularTotal() {
    const precio = parseFloat(document.getElementById('precio_venta').value) || 0;
    const cant   = parseInt(document.getElementById('cantidad').value) || 0;
    const total  = precio * cant;
    document.getElementById('total_valor').textContent = total.toFixed(2);
    document.getElementById('total_preview').style.display = total > 0 ? 'block' : 'none';
}
</script>
</body>
</html>
