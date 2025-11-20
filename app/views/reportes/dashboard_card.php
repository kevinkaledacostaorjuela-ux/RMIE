<?php
/**
 * Componente reutilizable para tarjetas del dashboard de reportes
 */
function renderDashboardCard($title, $icon, $color, $link) {
    $gradients = [
        'primary' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        'success' => 'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)',
        'danger' => 'linear-gradient(135deg, #eb3349 0%, #f45c43 100%)',
        'warning' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
        'info' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
        'purple' => 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)',
        'pink' => 'linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)',
        'teal' => 'linear-gradient(135deg, #13547a 0%, #80d0c7 100%)',
        'orange' => 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
        'indigo' => 'linear-gradient(135deg, #30cfd0 0%, #330867 100%)',
        'cyan' => 'linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%)',
    ];
    
    $gradient = $gradients[$color] ?? $gradients['primary'];
    ?>
    <div class="col-md-6 col-lg-4 mb-4">
        <a href="<?= htmlspecialchars($link) ?>" class="text-decoration-none">
            <div class="report-card" style="background: <?= $gradient ?>">
                <div class="card-icon">
                    <i class="<?= htmlspecialchars($icon) ?>"></i>
                </div>
                <div class="card-content">
                    <h3 class="card-title"><?= htmlspecialchars($title) ?></h3>
                    <p class="card-subtitle">Ver reporte detallado</p>
                </div>
                <div class="card-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </a>
    </div>
    <?php
}
?>
