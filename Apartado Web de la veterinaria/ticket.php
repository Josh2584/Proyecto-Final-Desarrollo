<?php
// ============================================================
//  ticket.php — Ticket de confirmación
// ============================================================
session_start();

if (!isset($_SESSION['ticket'])) {
    header('Location: index.php');
    exit;
}

$ticket = $_SESSION['ticket'];
unset($_SESSION['ticket']); // Limpiar después de mostrar
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket — Gran Malo</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .ticket-wrapper {
            max-width: 560px;
            margin: 40px auto;
            padding: 0 16px;
        }
        .ticket-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        }
        .ticket-header {
            background: var(--teal-dark);
            color: #fff;
            padding: 28px 32px 24px;
        }
        .ticket-header .tipo {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            opacity: 0.6;
            margin-bottom: 6px;
        }
        .ticket-header h1 {
            font-family: 'Fraunces', serif;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .ticket-header .folio {
            font-size: 13px;
            opacity: 0.7;
        }
        .ticket-divider {
            border: none;
            border-top: 2px dashed var(--border);
            margin: 0;
        }
        .ticket-body {
            padding: 28px 32px;
        }
        .ticket-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 0;
            border-bottom: 1px solid var(--bg);
            gap: 16px;
        }
        .ticket-row:last-child { border-bottom: none; }
        .ticket-row .label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted);
            flex-shrink: 0;
        }
        .ticket-row .value {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-main);
            text-align: right;
        }
        .ticket-total {
            background: var(--teal-light);
            border-radius: 10px;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        .ticket-total .label {
            font-size: 13px;
            color: var(--teal-mid);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .ticket-total .amount {
            font-family: 'Fraunces', serif;
            font-size: 26px;
            color: var(--teal-dark);
        }
        .ticket-footer {
            padding: 20px 32px 28px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        .btn-print {
            background: var(--teal-dark);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 11px 24px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-back {
            background: var(--bg);
            color: var(--text-main);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 11px 24px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .success-icon {
            width: 48px; height: 48px;
            background: rgba(255,255,255,0.15);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 12px;
        }
        @media print {
            .sidebar, .ticket-footer, .topbar { display: none !important; }
            .main { margin-left: 0 !important; }
            .ticket-wrapper { margin: 0; padding: 0; max-width: 100%; }
            .ticket-card { box-shadow: none; border: none; }
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="main">
    <div class="topbar">
        <div>
            <h1>Ticket de operación</h1>
            <p class="topbar-sub">Comprobante generado exitosamente</p>
        </div>
    </div>

    <div class="ticket-wrapper">
        <div class="ticket-card">

            <div class="ticket-header">
                <div class="success-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </div>
                <div class="tipo"><?= htmlspecialchars($ticket['tipo'] ?? 'Operación') ?></div>
                <h1>Veterinaria Gran Malo</h1>
                <div class="folio">Folio #<?= htmlspecialchars($ticket['folio'] ?? '—') ?> &nbsp;·&nbsp; <?= date('d/m/Y H:i') ?></div>
            </div>

            <hr class="ticket-divider">

            <div class="ticket-body">
                <?php foreach ($ticket['datos'] as $label => $value): ?>
                    <?php if ($value !== null && $value !== ''): ?>
                    <div class="ticket-row">
                        <span class="label"><?= htmlspecialchars($label) ?></span>
                        <span class="value"><?= htmlspecialchars($value) ?></span>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php if (isset($ticket['total'])): ?>
                <div class="ticket-total">
                    <span class="label">Total</span>
                    <span class="amount">$<?= number_format($ticket['total'], 2, '.', ',') ?></span>
                </div>
                <?php endif; ?>
            </div>

            <div class="ticket-footer">
                <button class="btn-print" onclick="window.print()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                        <rect x="6" y="14" width="12" height="8"/>
                    </svg>
                    Imprimir ticket
                </button>
                <a href="<?= htmlspecialchars($ticket['volver'] ?? 'index.php') ?>" class="btn-back">
                    ← Volver
                </a>
            </div>

        </div>
    </div>
</div>
</body>
</html>
