<?php
require_once '../config.php';

// Cargar categorias para el filtro flotante
$categorias = [];
$catRes = $conn->query("SELECT * FROM categorias ORDER BY nombre ASC");
if ($catRes && $catRes->num_rows > 0) {
    while ($c = $catRes->fetch_assoc()) {
        $categorias[] = $c;
    }
}

// Determinar el filtro actual
$filtro_slug = isset($_GET['cat']) ? $conn->real_escape_string($_GET['cat']) : '';

// Lógica Global de Insignias (Nuevo/Tendencia)
$resNuevos = $conn->query("SELECT id FROM programas ORDER BY fecha_creacion DESC LIMIT 5");
$ids_nuevos = [];
if ($resNuevos) { while ($r = $resNuevos->fetch_assoc()) { $ids_nuevos[] = $r['id']; } }

$resTendencia = $conn->query("SELECT id FROM programas ORDER BY clicks DESC LIMIT 5");
$ids_tendencia = [];
if ($resTendencia) { while ($r = $resTendencia->fetch_assoc()) { $ids_tendencia[] = $r['id']; } }

function getBadgeHtml($id, $ids_nuevos, $ids_tendencia) {
    if (in_array($id, $ids_tendencia)) {
        return '<div style="position:absolute; top:-10px; right:-10px; background:#f97316; color:white; font-size:0.75rem; font-weight:800; padding:5px 12px; border-radius:30px; box-shadow:0 4px 15px rgba(249,115,22,0.4); display:flex; align-items:center; gap:6px; z-index:10;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"></path></svg> Tendencia</div>';
    }
    if (in_array($id, $ids_nuevos)) {
        return '<div style="position:absolute; top:-10px; right:-10px; background:#10b981; color:white; font-size:0.75rem; font-weight:800; padding:5px 12px; border-radius:30px; box-shadow:0 4px 15px rgba(16,185,129,0.4); display:flex; align-items:center; gap:6px; z-index:10;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 12 2 2 4-4"></path><path d="m21 12-4.46 2.87.69 5.12-4.9-1.39-2.8 4.38-2.8-4.38-4.9 1.39.69-5.12L2 12l4.46-2.87-.69-5.12 4.9 1.39 2.8-4.38 2.8 4.38 4.9-1.39-.69 5.12Z"></path></svg> Nuevo</div>';
    }
    return '';
}

// Construir consulta de programas
$sql_progs = "SELECT p.*, c.nombre as categoria_nombre FROM programas p LEFT JOIN categorias c ON p.categoria_id = c.id";
if ($filtro_slug !== '') {
    $sql_progs .= " WHERE c.slug = '$filtro_slug'";
}

if (isset($_GET['t'])) {
    if ($_GET['t'] === 'tendencia') {
        $sql_progs .= " ORDER BY p.clicks DESC LIMIT 15";
    } elseif ($_GET['t'] === 'nuevo') {
        $sql_progs .= " ORDER BY p.fecha_creacion DESC LIMIT 15";
    }
} else {
    $sql_progs .= " ORDER BY p.id DESC";
}

$programas = [];
$res = $conn->query($sql_progs);
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $programas[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Metadatos SEO Principales -->
    <title>Catálogo de Programas | Boosham Blog</title>
    <meta name="description"
        content="Explora el catálogo completo de Boosham Blog con las mejores herramientas y programas para PC en una sola página.">
    <meta name="keywords" content="catálogo programas, herramientas PC, software optimizado, boosham blog">
    <meta name="author" content="Boosham">
    <meta name="robots" content="index, follow">

    <!-- Open Graph (Metadatos para Redes Sociales) -->
    <meta property="og:title" content="Catálogo de Programas | Boosham Blog">
    <meta property="og:description"
        content="Explora nuestro ecosistema de programas favoritos optimizados para PC. ¡Descúbrelos todos!">
    <meta property="og:site_name" content="Boosham Blog">
    <meta property="og:image" content="../favicon.svg">
    <meta property="og:url" content="https://booshamblog.gt.tc/catalog/">
    <meta property="og:type" content="website">

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "Boosham Blog",
      "url": "https://booshamblog.gt.tc/"
    }
    </script>

    <link rel="icon" type="image/svg+xml" href="../favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            background-color: var(--bg-color);
            transition: 0.3s;
            color: var(--text-color);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        .catalog-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 120px 20px 60px;
        }

        .catalog-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .catalog-header h1 {
            font-size: 3rem;
            margin: 0 0 10px 0;
        }

        /* Floating Filters CSS */
        .floating-filter-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #ff8c00;
            color: #111;
            border: none;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(255,140,0,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            transition: 0.3s transform;
        }
        .floating-filter-btn:hover {
            transform: scale(1.1);
        }
        
        .floating-filter-panel {
            position: fixed;
            bottom: 30px;
            left: -400px; /* Oculto por defecto en la esquina izquierda */
            width: 300px;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            padding: 25px;
            box-shadow: var(--card-shadow);
            z-index: 999;
            transition: 0.4s left cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            flex-direction: column;
        }
        .floating-filter-panel.active {
            left: 30px;
        }
        .floating-filter-panel h4 {
            margin-top: 0;
            font-size: 1.2rem;
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .floating-filter-panel .close-panel-btn {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 1.5rem;
            line-height: 1;
        }
        .floating-filter-panel .close-panel-btn:hover {
            color: #ff8c00;
        }
        .floating-filter-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-height: 50vh;
            overflow-y: auto;
        }
        .filter-btn {
            background: var(--card-bg-hover);
            border: 1px solid var(--card-border);
            color: var(--text-color);
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
            font-size: 0.95rem;
            text-decoration: none;
            text-align: left;
        }
        .filter-btn:hover, .filter-btn.active {
            background: #ff8c00;
            color: black;
            border-color: #ff8c00;
        }

        .catalog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 30px;
        }

        .program-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            padding: 25px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            text-decoration: none;
            color: var(--text-color);
            transition: transform 0.3s, border-color 0.3s;
            aspect-ratio: 1 / 1;
            box-shadow: var(--card-shadow);
            position: relative;
        }

        .program-card:hover {
            transform: translateY(-10px);
            border-color: #ff8c00;
            background: var(--card-bg-hover);
        }

        .program-icon {
            width: 140px;
            height: 140px;
            background: #222;
            border-radius: 20px;
            margin-bottom: 20px;
            object-fit: cover;
            border: 1px solid var(--card-border);
            /* Margin so the image doesn't touch the card edges */
            padding: 6px;
            box-sizing: border-box;
            overflow: hidden;
        }

        .program-title {
            font-size: 1.15rem;
            font-weight: 700;
            margin: 0 0 10px 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .program-desc {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin: 0;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        [data-theme="light"] .program-icon {
            background-color: #b8a994 !important;
            /* Color madera para placeholders */
            border-color: #a3927b !important;
        }

        /* --- RESPONSIVO --- */
        @media (max-width: 768px) {
            .catalog-container {
                padding: 100px 15px 40px;
                /* Menos padding lateral y superior */
            }

            .catalog-header h1 {
                font-size: 2.2rem;
            }

            .catalog-header p {
                font-size: 1rem !important;
                padding: 0 10px;
            }

            .floating-filter-panel {
                bottom: 100px; /* Encima del boton movil hipotetico si existe, o justo arriba del boton flotante */
                left: -120%; /* Oculto fuera de pantalla con margen de seguridad */
                width: calc(100% - 40px);
                max-width: 320px;
            }
            .floating-filter-panel.active {
                left: 20px;
            }
            .floating-filter-btn {
                bottom: 20px;
                right: 20px;
            }

            .catalog-grid {
                /* Forzamos 1 sola columna en pantallas móviles para que fluya mejor */
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .program-card {
                padding: 15px;
                border-radius: 15px;
                /* En una sola columna, quizás sea mejor que fluyan en horizontal si cabe, pero
                   como la card ya usa flex-direction: column por defecto, centramos todo para
                   que la tarjeta luzca hermosa a ancho completo */
                align-items: center;
                text-align: center;
            }

            .program-icon {
                width: 100px;
                height: 100px;
                margin-bottom: 12px;
                border-radius: 12px;
            }

            .program-title {
                font-size: 1rem;
            }

            .program-desc {
                font-size: 0.8rem;
                -webkit-line-clamp: 2;
                /* Menos texto en móvil para hacer caja cuadrada perfecta */
            }
        }

        @media (max-width: 480px) {
            .catalog-grid {
                grid-template-columns: 1fr;
                /* 1 sola columna masiva en celulares puros */
            }

            .program-card {
                aspect-ratio: auto;
            }

            .program-icon {
                width: 120px;
                height: 120px;
            }
        }
    </style>
</head>

<body>

    <?php include '../Encabezado.php'; ?>

    <div class="catalog-container">
        <div class="catalog-header">
            <h1>Catálogo de Aplicaciones</h1>
            <p style="color: var(--text-muted); max-width: 600px; margin: 0 auto; font-size: 1.1rem;">Encuentra las
                mejores herramientas y programas para optimizar tu flujo de trabajo.</p>
        </div>



        <div class="catalog-grid">
            <?php if (empty($programas)): ?>
                <p style="text-align: center; grid-column: 1 / -1; color: var(--text-muted);">No hay programas disponibles
                    en este momento.</p>
            <?php else: ?>
                <?php foreach ($programas as $p): ?>
                    <?php
                    $desc = !empty($p['descripcion']) ? $p['descripcion'] : 'Sin descripción disponible.';

                    $estrellas = isset($p['estrellas_cache']) ? (float) $p['estrellas_cache'] : 0;
                    $starSvg = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
                    $filledStarSvg = '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';

                    if ($estrellas > 0) {
                        $percentage = ($estrellas / 5) * 100 . '%';
                        $ratingHtml = "
                        <div style='display:flex; align-items:center; gap:8px; margin-top:10px;'>
                            <div style='position: relative; display: inline-flex; width: 70px; height: 14px;'>
                                <div style='display: flex; position: absolute; top:0; left:0; color: gray; opacity: 0.3;'>" . str_repeat($starSvg, 5) . "</div>
                                <div style='display: flex; position: absolute; top:0; left:0; color: #fbbf24; overflow: hidden; width: {$percentage};'>
                                    <div style='display:flex; width: 70px;'>" . str_repeat($filledStarSvg, 5) . "</div>
                                </div>
                            </div>
                            <span style='color:var(--text-color); font-size:0.8rem; font-weight:700;'>{$estrellas}</span>
                        </div>
                    ";
                    } else {
                        $ratingHtml = "<div style='margin-top:10px;'><span style='color:gray; font-size:0.8rem; opacity:0.7;'>Sin calificar aún</span></div>";
                    }
                    $f = date('d/m/Y', strtotime($p['fecha_creacion']));
                    $fUrl = "../programa/lista/{$f}/{$p['slug']}";
                    $badgeHtml = getBadgeHtml($p['id'], $ids_nuevos, $ids_tendencia);
                    ?>
                    <a href="<?= $fUrl ?>" class="program-card">
                        <?= $badgeHtml ?>
                        <?php if (!empty($p['imagen_url'])): ?>
                            <img src="<?= htmlspecialchars(str_replace('../', '/', $p['imagen_url'])) ?>" alt="Icono" class="program-icon">
                        <?php else: ?>
                            <div class="program-icon"
                                style="display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,0.3); font-size:2rem; font-weight:800;">
                                ???</div>
                        <?php endif; ?>
                        <h3 class="program-title"><?= htmlspecialchars($p['titulo']) ?></h3>
                        <p class="program-desc"><?= htmlspecialchars($desc) ?></p>
                        <?= $ratingHtml ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div style="padding: 0 20px 40px 20px;">
        <?php include '../footer.php'; ?>
    </div>

    <!-- Panel Flotante de Filtros -->
    <div class="floating-filter-panel" id="floatingFilterPanel">
        <h4>
            <span>Filtrar Catálogo</span>
            <button class="close-panel-btn" id="closeFilterPanelBtn">&times;</button>
        </h4>
        <div class="floating-filter-list">
            <!-- Etiqueta Todo -->
            <a href="?" class="filter-btn <?= ($filtro_slug === '' && !isset($_GET['t'])) ? 'active' : '' ?>">Ver Todos</a>
            
            <div style="height: 1px; background: rgba(255,255,255,0.1); margin: 5px 0;"></div>
            
            <!-- Tendencias / Nuevos -->
            <a href="?t=tendencia" class="filter-btn <?= (isset($_GET['t']) && $_GET['t'] === 'tendencia') ? 'active' : '' ?>" style="color:#f97316; display:flex; gap:8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"></path></svg> 
                En Tendencia
            </a>
            <a href="?t=nuevo" class="filter-btn <?= (isset($_GET['t']) && $_GET['t'] === 'nuevo') ? 'active' : '' ?>" style="color:#10b981; display:flex; gap:8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 12 2 2 4-4"></path><path d="m21 12-4.46 2.87.69 5.12-4.9-1.39-2.8 4.38-2.8-4.38-4.9 1.39.69-5.12L2 12l4.46-2.87-.69-5.12 4.9 1.39 2.8-4.38 2.8 4.38 4.9-1.39-.69 5.12Z"></path></svg>
                Agregados Recientemente
            </a>

            <div style="height: 1px; background: rgba(255,255,255,0.1); margin: 5px 0;"></div>

            <!-- Resto de Categorias Dinámicas -->
            <?php foreach($categorias as $cat): ?>
                <a href="?cat=<?= htmlspecialchars($cat['slug']) ?>" class="filter-btn <?= ($filtro_slug === $cat['slug']) ? 'active' : '' ?>">
                    <?= htmlspecialchars($cat['nombre']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Botón Flotante para abrir Filtros -->
    <button class="floating-filter-btn" id="filterToggleBtn" aria-label="Abrir Filtros" title="Filtrar">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <!-- Funnel (Filter) -->
            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
            <!-- Plus Indicator (simulando funnel-plus) -->
            <g stroke-width="3" stroke="currentColor">
                <line x1="20" y1="18" x2="20" y2="24"></line>
                <line x1="17" y1="21" x2="23" y2="21"></line>
            </g>
        </svg>
    </button>

    <script>
        // Lógica del botón flotante
        const filterBtn = document.getElementById('filterToggleBtn');
        const filterPanel = document.getElementById('floatingFilterPanel');
        const closePanelBtn = document.getElementById('closeFilterPanelBtn');

        filterBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            filterPanel.classList.toggle('active');
        });

        closePanelBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            filterPanel.classList.remove('active');
        });

        // Cerrar panel al hacer clic fuera en cualquier lado de la pantalla
        document.addEventListener('click', (event) => {
            if (!filterPanel.contains(event.target) && filterPanel.classList.contains('active')) {
                filterPanel.classList.remove('active');
            }
        });
    </script>

</body>

</html>