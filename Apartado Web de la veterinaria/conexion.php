<?php
// ============================================================
//  conexion.php — Conexión a la base de datos Oracle
//  Cambia los valores de $usuario, $contrasena y $sid
//  según tu instalación de Oracle.
// ============================================================

$host      = 'localhost';   // IP o nombre del servidor Oracle
$puerto    = '1521';        // Puerto por defecto de Oracle
$sid       = 'XE';          // SID de tu base de datos (XE para Oracle Express)
$usuario   = 'Veterinaria';  // Tu usuario de Oracle
$contrasena = '6493'; // Tu contraseña

// Crear la cadena de conexión
$cadena_conexion = "//$host:$puerto/xepdb1";

// Intentar conectar
$conn = oci_connect($usuario, $contrasena, $cadena_conexion, 'UTF8');

// Si falla la conexión, mostrar el error y detener el script
if (!$conn) {
    $error = oci_error();
    die("<p style='color:red; font-family:sans-serif;'>
            <strong>Error de conexión a Oracle:</strong><br>
            " . htmlspecialchars($error['message']) . "
         </p>");
}
// Si llegamos aquí, la conexión fue exitosa
?>
