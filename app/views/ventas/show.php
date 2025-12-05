<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user'])) { header('Location: /RMIE/index.php'); exit(); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detalle de Venta - RMIE</title>
<link rel="icon" type="image/x-icon" href="/RMIE/public/favicon.ico">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
.container-box { background: rgba(255,255,255,0.1); backdrop-filter: blur(12px); border-radius: 16px; border:1px solid rgba(255,255,255,0.2); padding:24px; margin:24px auto; max-width: 1100px; }
.header { color:#fff; margin-bottom: 16px; }
.card { background: rgba(255,255,255,0.85); border-radius:12px; border:none; }
.badge-modern { border-radius: 999px; padding:6px 12px; font-weight:600; }
</style>
</head>
<body>
<div class="container-box">
  <div class="d-flex justify-content-between align-items-center header">
    <h2><i class="fas fa-eye"></i> Detalle de la Venta #<?= htmlspecialchars($venta->id_ventas) ?></h2>
    <div>
      <a class="btn btn-sm btn-secondary" href="/RMIE/app/controllers/SaleController.php?accion=index"><i class="fas fa-arrow-left"></i> Volver</a>
      <a class="btn btn-sm btn-warning" href="/RMIE/app/controllers/SaleController.php?accion=edit&id=<?= urlencode($venta->id_ventas) ?>"><i class="fas fa-edit"></i> Editar</a>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-md-4">
      <div class="card p-3">
        <h5><i class="fas fa-info-circle"></i> Venta</h5>
        <ul class="list-unstyled mb-0">
          <li><strong>ID:</strong> #<?= htmlspecialchars($venta->id_ventas) ?></li>
          <li><strong>Fecha:</strong> <?= htmlspecialchars(date('d/m/Y H:i', strtotime($venta->fecha_venta ?? 'now'))) ?></li>
          <li><strong>Estado:</strong> <span class="badge bg-info"><?= htmlspecialchars(ucfirst($venta->estado)) ?></span></li>
          <li><strong>Total:</strong> $<?= number_format(floatval($venta->total ?? 0), 2) ?></li>
          <li><strong>Cantidad total:</strong> <?= htmlspecialchars($venta->cantidad ?? 0) ?></li>
        </ul>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-3">
        <h5><i class="fas fa-user"></i> Cliente</h5>
        <ul class="list-unstyled mb-0">
          <li><strong>Nombre:</strong> <?= htmlspecialchars($cliente->nombre ?? 'N/A') ?></li>
          <li><strong>ID Cliente:</strong> <?= htmlspecialchars($venta->id_clientes) ?></li>
        </ul>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-3">
        <h5><i class="fas fa-user-tie"></i> Responsable</h5>
        <ul class="list-unstyled mb-0">
          <li><strong>Usuario:</strong> <?= htmlspecialchars(($usuario->nombres ?? '').' '.($usuario->apellidos ?? '')) ?></li>
          <li><strong>Num. Doc:</strong> <?= htmlspecialchars($venta->num_doc ?? '') ?></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="card p-3 mt-3">
    <h5><i class="fas fa-box"></i> Productos</h5>
    <div class="table-responsive">
      <table class="table table-sm">
        <thead>
          <tr>
            <th>Producto</th>
            <th class="text-end">Cantidad</th>
            <th class="text-end">Precio Unitario</th>
            <th class="text-end">Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($venta->productos_asignados)): ?>
            <?php foreach ($venta->productos_asignados as $p): ?>
              <tr>
                <td><?= htmlspecialchars($p->nombre ?? '') ?></td>
                <td class="text-end"><?= htmlspecialchars($p->cantidad ?? 1) ?></td>
                <td class="text-end">$<?= number_format(floatval($p->precio_unitario ?? $p->precio_producto ?? 0), 2) ?></td>
                <td class="text-end">$<?= number_format(floatval($p->subtotal ?? ($p->cantidad * ($p->precio_unitario ?? $p->precio_producto ?? 0))), 2) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="4" class="text-center text-muted">Sin productos asignados</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
