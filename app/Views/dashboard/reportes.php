<?php
$esPdf = !empty($exportar) && $exportar === 'pdf';

if ($esPdf):
// ─── PDF MODE: clean HTML without admin layout ───
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte - InstalFuego</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; padding: 30px; }
        .report-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #ddd; }
        .report-header .left h2 { margin: 0; font-size: 18px; }
        .report-header .left p { margin: 2px 0; color: #666; }
        .report-header .right { text-align: right; }
        .report-header .right h1 { margin: 0 0 10px 0; font-size: 24px; text-transform: uppercase; }
        .report-header .right p { margin: 2px 0; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th { background: #f0f0f0; padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; }
        td { padding: 8px 10px; border-bottom: 1px solid #eee; font-size: 11px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .totals-box { width: 50%; margin-left: auto; background: #f9f9f9; padding: 16px; border-radius: 4px; }
        .totals-box .row { display: flex; justify-content: space-between; padding: 4px 0; border-bottom: 1px solid #eee; font-size: 11px; }
        .totals-box .total-final { font-weight: bold; font-size: 16px; padding-top: 8px; border-top: 2px solid #999; margin-top: 4px; }
        .footer { text-align: center; padding-top: 20px; border-top: 1px solid #ddd; font-size: 10px; color: #888; margin-top: 40px; }
        .estado-badge { display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 9px; font-weight: bold; }
        .estado-pendiente { background: #fef3cd; color: #856404; }
        .estado-procesada { background: #d4edda; color: #155724; }
        .estado-rechazada { background: #f8d7da; color: #721c24; }
        .estado-other { background: #e2e3e5; color: #383d41; }
    </style>
</head>
<body>
    <div class="report-header">
        <div class="left">
            <?php
            $logoFile = dirname(__DIR__,3) . '/public/img/Photoroom-20251106_165742.png';
            $logoSrcPdf = '';
            if (file_exists($logoFile)) {
                $imgData = base64_encode(file_get_contents($logoFile));
                $logoSrcPdf = 'data:image/png;base64,' . $imgData;
            }
            if (!empty($logoSrcPdf)):
            ?>
            <img src="<?= $logoSrcPdf ?>" alt="InstalFuego" style="height:50px; margin-bottom: 6px;">
            <?php endif; ?>
            <h2>InstalFuego C.A.</h2>
            <p>Guacara, Carabobo, Venezuela</p>
        </div>
        <div class="right">
            <h1>REPORTE</h1>
            <p><strong>Tipo:</strong> <?= $tipo === 'cotizaciones' ? 'Solicitudes de Presupuesto' : 'Más Solicitados' ?></p>
            <p><strong>Período:</strong> <?= date('d/m/Y', strtotime($fechaInicio)) ?> - <?= date('d/m/Y', strtotime($fechaFin)) ?></p>
            <p><strong>Generado:</strong> <?= date('d/m/Y') ?></p>
        </div>
    </div>

    <h3 style="font-size:14px; margin-bottom:12px;">Detalle de Reporte</h3>

    <table>
        <thead>
            <tr>
                <?php if ($tipo === 'cotizaciones'): ?>
                <th>ID</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Estado</th>
                <th class="text-right">Monto Est.</th>
                <?php else: ?>
                <th>Tipo</th>
                <th>Nombre</th>
                <th class="text-right">Total Solicitado</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data)): ?>
            <tr><td colspan="5" class="text-center" style="color:#999;">No hay datos para el período seleccionado.</td></tr>
            <?php else: ?>
                <?php foreach ($data as $row): ?>
                <tr>
                    <?php if ($tipo === 'cotizaciones'): ?>
                    <td><strong>COT-<?= str_pad((string)$row['id'], 3, '0', STR_PAD_LEFT) ?></strong></td>
                    <td><?= date('d/m/Y', strtotime($row['fecha_solicitud'])) ?></td>
                    <td><?= htmlspecialchars($row['cliente']) ?></td>
                    <td>
                        <?php
                        $cls = 'estado-other';
                        if (isset($row['estado_id'])) {
                            if ($row['estado_id'] == 3) $cls = 'estado-procesada';
                            else if ($row['estado_id'] == 4) $cls = 'estado-procesada'; // Facturado
                            else if ($row['estado_id'] == 5 || $row['estado_id'] == 6) $cls = 'estado-rechazada';
                            else if ($row['estado_id'] == 2) $cls = 'estado-pendiente';
                        }
                        
                        $estadoName = ucfirst(str_replace('_', ' ', $row['estado'] ?? ''));
                        if (strtolower($estadoName) === 'aceptada por el cliente' || strtolower($estadoName) === 'aceptada') {
                            $estadoName = 'Procesada';
                        }
                        ?>
                        <span class="estado-badge <?= $cls ?>"><?= htmlspecialchars($estadoName) ?></span>
                    </td>
                    <td class="text-right">Bs. <?= number_format((float)($row['total'] ?? 0), 2, ',', '.') ?></td>
                    <?php else: ?>
                    <td><?= htmlspecialchars($row['tipo_item']) ?></td>
                    <td><?= htmlspecialchars($row['nombre']) ?></td>
                    <td class="text-right"><?= htmlspecialchars($row['total_solicitado']) ?></td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if ($tipo === 'cotizaciones'): ?>
    <div class="totals-box">
        <div class="row"><span>Total Presupuestos:</span><strong><?= $totales['total_cotizaciones'] ?></strong></div>
        <div class="row"><span>Monto Total Estimado:</span><strong>Bs. <?= number_format($totales['estimado'], 2, ',', '.') ?></strong></div>
        <div class="row"><span>Monto Procesado:</span><strong style="color:#28a745;">Bs. <?= number_format($totales['procesado'], 2, ',', '.') ?></strong></div>
        <div class="row"><span>Monto Pendiente:</span><strong style="color:#ffc107;">Bs. <?= number_format($totales['pendiente'], 2, ',', '.') ?></strong></div>
        <div class="row total-final"><span>Tasa de Conversión:</span><?= $totales['total_cotizaciones'] > 0 ? round(($totales['cotizaciones_procesadas'] / $totales['total_cotizaciones']) * 100) : 0 ?>%</div>
    </div>
    <?php endif; ?>

    <div class="footer">Reporte generado automáticamente por el sistema InstalFuego</div>
</body>
</html>
<?php
return; // Stop rendering - PDF mode complete
endif; ?><?php
// ─── WEB MODE: normal admin layout ───
$title = 'Reportes';
$active_nav = 'reportes';
require_once __DIR__ . '/../layouts/_head.php'; ?>
<body class="bg-gray-50">
<div class="flex h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../layouts/_sidebar.php'; ?>
    <main class="flex-1 overflow-y-auto flex flex-col">

        <header class="bg-white border-b border-gray-200 px-8 py-5 flex-shrink-0">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Reportes</h1>
                    <p class="text-gray-500 text-sm mt-0.5">Estadísticas y análisis del sistema</p>
                </div>
            </div>
        </header>

      <div class="p-8">

        <!-- Filters Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
          <h2 class="text-lg font-bold text-gray-900 mb-4">Generar Reporte</h2>
          <form method="GET" action="<?= htmlspecialchars(rtrim(str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']), '/')) ?>/dashboard/reportes" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
              <label class="block text-sm font-semibold text-gray-900 mb-2">Tipo de Reporte</label>
              <select name="tipo" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="cotizaciones" <?= $tipo === 'cotizaciones' ? 'selected' : '' ?>>Solicitudes de Presupuesto</option>
                <option value="mas_solicitados" <?= $tipo === 'mas_solicitados' ? 'selected' : '' ?>>Más Solicitados</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-900 mb-2">Estado</label>
              <select name="estado" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="">Todos</option>
                <option value="2" <?= $estado == '2' ? 'selected' : '' ?>>Pendiente</option>
                <option value="3" <?= $estado == '3' ? 'selected' : '' ?>>Procesada</option>
                <option value="4" <?= $estado == '4' ? 'selected' : '' ?>>Rechazada</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-900 mb-2">Fecha Inicio</label>
              <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?= htmlspecialchars($fechaInicio) ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-900 mb-2">Fecha Fin</label>
              <input type="date" id="fecha_fin" name="fecha_fin" value="<?= htmlspecialchars($fechaFin) ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>

            <div class="flex items-end">
              <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 w-full h-[46px] font-semibold">Generar</button>
            </div>
          </form>
        </div>

        <!-- Report Document -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">

          <!-- Action Buttons -->
          <div class="flex justify-end gap-3 p-6 border-b border-gray-200">
            <a href="?tipo=<?= urlencode($tipo) ?>&estado=<?= urlencode($estado) ?>&fecha_inicio=<?= urlencode($fechaInicio) ?>&fecha_fin=<?= urlencode($fechaFin) ?>&exportar=pdf" class="flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold shadow-sm">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
              </svg>
              Descargar PDF
            </a>
            <a href="?tipo=<?= urlencode($tipo) ?>&estado=<?= urlencode($estado) ?>&fecha_inicio=<?= urlencode($fechaInicio) ?>&fecha_fin=<?= urlencode($fechaFin) ?>&exportar=csv" class="flex items-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-semibold text-gray-700">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
              </svg>
              CSV
            </a>
          </div>

          <!-- Report Content -->
          <div class="p-8" id="report-content">

            <!-- Report Header -->
            <?php
              $logoFile = dirname(__DIR__,3) . '/public/img/Photoroom-20251106_165742.png';
              $logoUrl = ($base_url ?? '') . '/img/Photoroom-20251106_165742.png';
            ?>
            <div class="flex justify-between items-start mb-8 pb-6 border-b-2 border-gray-200">
              <div>
                <?php if (file_exists($logoFile)): ?>
                <img src="<?= $logoUrl ?>" alt="InstalFuego" class="h-16 mb-2 object-contain">
                <?php endif; ?>
                <p class="text-sm text-gray-600">InstalFuego C.A.</p>
                <p class="text-sm text-gray-600">Guacara, Carabobo, Venezuela</p>
              </div>
              <div class="text-right">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">REPORTE</h1>
                <p class="text-sm text-gray-600 mb-1"><span class="font-semibold">Tipo:</span> <?= $tipo === 'cotizaciones' ? 'Solicitudes de Presupuesto' : 'Más Solicitados' ?></p>
                <p class="text-sm text-gray-600 mb-1"><span class="font-semibold">Período:</span> <?= date('d/m/Y', strtotime($fechaInicio)) ?> - <?= date('d/m/Y', strtotime($fechaFin)) ?></p>
                <p class="text-sm text-gray-600 mb-1"><span class="font-semibold">Generado:</span> <?= date('d/m/Y') ?></p>
              </div>
            </div>

            <!-- Data Table -->
            <div class="mb-8">
              <h2 class="text-lg font-bold text-gray-900 mb-4">Detalle de Reporte</h2>
              <table class="w-full">
                <thead class="bg-gray-100">
                  <tr>
                    <?php if ($tipo === 'cotizaciones'): ?>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Fecha</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Cliente</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Estado</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Monto Est.</th>
                    <?php else: ?>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tipo</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nombre</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Total Solicitado</th>
                    <?php endif; ?>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <?php if (empty($data)): ?>
                  <tr><td colspan="5" class="px-4 py-4 text-center text-gray-500 font-medium">No hay datos para el período seleccionado.</td></tr>
                  <?php else: ?>
                    <?php foreach ($data as $row): ?>
                      <tr>
                        <?php if ($tipo === 'cotizaciones'): ?>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">COT-<?= str_pad((string)$row['id'], 3, '0', STR_PAD_LEFT) ?></td>
                        <td class="px-4 py-3 text-sm text-gray-600"><?= date('d/m/Y', strtotime($row['fecha_solicitud'])) ?></td>
                        <td class="px-4 py-3 text-sm text-gray-900"><?= htmlspecialchars($row['cliente']) ?></td>
                        <td class="px-4 py-3">
                          <?php
                            $bg = 'bg-gray-100 text-gray-800';
                            if (isset($row['estado_id'])) {
                                if ($row['estado_id'] == 3) $bg = 'bg-green-100 text-green-800'; // Procesada
                                else if ($row['estado_id'] == 4) $bg = 'bg-blue-100 text-blue-800'; // Facturado
                                else if ($row['estado_id'] == 5 || $row['estado_id'] == 6) $bg = 'bg-red-100 text-red-800'; // Anulado
                                else if ($row['estado_id'] == 2) $bg = 'bg-yellow-100 text-yellow-800'; // Pendiente
                            }
                            $estadoName = ucfirst(str_replace('_', ' ', $row['estado'] ?? ''));
                            if (strtolower($estadoName) === 'aceptada por el cliente' || strtolower($estadoName) === 'aceptada') {
                                $estadoName = 'Procesada';
                            }
                          ?>
                          <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold <?= $bg ?>">
                            <?= htmlspecialchars($estadoName) ?>
                          </span>
                        </td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900 text-right">Bs. <?= number_format((float)($row['total'] ?? 0), 2, ',', '.') ?></td>
                        <?php else: ?>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900"><?= htmlspecialchars($row['tipo_item']) ?></td>
                        <td class="px-4 py-3 text-sm text-gray-900"><?= htmlspecialchars($row['nombre']) ?></td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900 text-right"><?= htmlspecialchars($row['total_solicitado']) ?></td>
                        <?php endif; ?>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>

            <?php if ($tipo === 'cotizaciones'): ?>
            <!-- Totals Section -->
            <div class="flex justify-end mb-8">
              <div class="w-96">
                <div class="bg-gray-50 rounded-lg p-4">
                  <div class="flex justify-between py-2 text-sm border-b border-gray-200">
                    <span class="text-gray-600">Total Presupuestos:</span>
                    <span class="font-bold text-gray-900"><?= $totales['total_cotizaciones'] ?></span>
                  </div>
                  <div class="flex justify-between py-2 text-sm border-b border-gray-200">
                    <span class="text-gray-600">Monto Total Estimado:</span>
                    <span class="font-bold text-gray-900">Bs. <?= number_format($totales['estimado'], 2, ',', '.') ?></span>
                  </div>
                  <div class="flex justify-between py-2 text-sm border-b border-gray-200">
                    <span class="text-gray-600">Monto Procesado:</span>
                    <span class="font-semibold text-green-700">Bs. <?= number_format($totales['procesado'], 2, ',', '.') ?></span>
                  </div>
                  <div class="flex justify-between py-2 text-sm border-b border-gray-200">
                    <span class="text-gray-600">Monto Pendiente:</span>
                    <span class="font-semibold text-yellow-700">Bs. <?= number_format($totales['pendiente'], 2, ',', '.') ?></span>
                  </div>
                  <div class="flex justify-between py-3 border-t-2 border-gray-300 mt-2">
                    <span class="text-base font-bold text-gray-900">Tasa de Conversión:</span>
                    <span class="text-xl font-bold text-red-700">
                      <?= $totales['total_cotizaciones'] > 0 ? round(($totales['cotizaciones_procesadas'] / $totales['total_cotizaciones']) * 100) : 0 ?>%
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <?php endif; ?>

            <!-- Footer -->
            <div class="text-center pt-6 border-t border-gray-200">
              <p class="text-sm text-gray-600 mb-2">Reporte generado automáticamente por el sistema InstalFuego</p>
            </div>

          </div>

        </div>

      </div>
    </main>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const fechaInicio = document.getElementById("fecha_inicio");
    const fechaFin = document.getElementById("fecha_fin");

    function validateDates() {
        if (fechaInicio.value) {
            fechaFin.min = fechaInicio.value;
        } else {
            fechaFin.min = "";
        }
        
        if (fechaFin.value) {
            fechaInicio.max = fechaFin.value;
        } else {
            fechaInicio.max = "";
        }
    }

    fechaInicio.addEventListener("change", function() {
        if (fechaInicio.value && fechaFin.value && fechaInicio.value > fechaFin.value) {
            fechaFin.value = fechaInicio.value;
        }
        validateDates();
    });

    fechaFin.addEventListener("change", function() {
        if (fechaInicio.value && fechaFin.value && fechaFin.value < fechaInicio.value) {
            fechaInicio.value = fechaFin.value;
        }
        validateDates();
    });

    // Validar al cargar
    validateDates();
});
</script>
</body>
</html>
