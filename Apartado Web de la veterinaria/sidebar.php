<?php
if (!isset($pagina_actual)) {
    $pagina_actual = basename($_SERVER['PHP_SELF'], '.php');
}

function nav_link($href, $label, $icono, $id, $actual) {
    $clase = ($actual === $id) ? 'nav-link active' : 'nav-link';
    echo "<a href='$href' class='$clase'>$icono <span>$label</span></a>";
}

$ico_home = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 9.5L12 3l9 6.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>';
$ico_pets = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="4.5" cy="9.5" r="2"/><circle cx="9" cy="5.5" r="2"/><circle cx="15" cy="5.5" r="2"/><circle cx="19.5" cy="9.5" r="2"/><path d="M12 13c-3.866 0-7 3.134-7 7 0 .553.448 1 1 1h12c.553 0 1-.448 1-1 0-3.866-3.134-7-7-7z"/></svg>';
$ico_prod = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-4 0v2M8 7V5a2 2 0 0 0-4 0v2"/><line x1="12" y1="12" x2="12" y2="17"/><line x1="9.5" y1="14.5" x2="14.5" y2="14.5"/></svg>';
$ico_oper = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>';
$ico_cons = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="13" x2="15" y2="13"/><line x1="9" y1="17" x2="12" y2="17"/></svg>';
?>
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-title">Gran Malo</div>
        <div class="logo-sub">Sistema veterinario</div>
    </div>
    <nav class="sidebar-nav">
        <div class="sidebar-section">General</div>
        <?php nav_link('index.php',      'Inicio',      $ico_home, 'index',      $pagina_actual); ?>
        <div class="sidebar-section">Catálogos</div>
        <?php nav_link('mascotas.php',   'Animales',    $ico_pets, 'animales',   $pagina_actual); ?>
        <?php nav_link('productos.php',  'Productos',   $ico_prod, 'productos',  $pagina_actual); ?>
        <div class="sidebar-section">Clínica</div>
        <?php nav_link('consultas.php',  'Consultas',   $ico_cons, 'consultas',  $pagina_actual); ?>
        <?php nav_link('operaciones.php','Operaciones', $ico_oper, 'operaciones',$pagina_actual); ?>
    </nav>
</aside>
