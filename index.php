<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Metadatos SEO Principales -->
    <title>Boosham Blog | Herramientas y Programas para PC</title>
    <meta name="description"
        content="Descubre en Boosham Blog las mejores herramientas y tus programas de PC favoritos en una sola pagina.">
    <meta name="keywords" content="descargar programas, herramientas PC, optimización, software gratis, boosham blog">
    <meta name="author" content="Boosham">
    <meta name="robots" content="index, follow">

    <!-- Open Graph (Metadatos para Redes Sociales como Discord, FB, WA) -->
    <meta property="og:title" content="Boosham Blog | Herramientas exclusivas">
    <meta property="og:description"
        content="Programas favoritos optimizados para PC en un solo ecosistema. ¡Descúbrelos!">
    <meta property="og:site_name" content="Boosham Blog">
    <meta property="og:image" content="https://booshamblog.gt.tc/favicon.svg">
    <meta property="og:url" content="https://booshamblog.gt.tc/">
    <meta property="og:type" content="website">

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "Boosham Blog",
      "url": "https://booshamblog.gt.tc/"
    }
    </script>

    <!-- Verificación de Google Search Console -->
    <!-- PEGA AQUÍ TU CÓDIGO DE VERIFICACIÓN <meta name="google-site-verification" content="..." /> -->

    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <link rel="preload" as="image" href="fondos/wallpaper-index-dark.webp" fetchpriority="high">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.min.css">

</head>

<body>

    <?php include 'Encabezado.php'; ?>

    <canvas id="dotsCanvas"></canvas>

    <section class="hero-section">
        <div class="hero-sticky">
            <div class="hero-background"></div>
            <div class="hero-content">
                <div class="hero-logo-box">
                    <img width="380" height="144" src="logo/logo-text.svg" alt="Boosham blog"
                        style="height: 9rem; width: 380px; filter: var(--logo-filter); max-width: 100%;">
                </div>
                <h1 class="hero-text-box" style="font-weight: normal; margin-top: 2rem; margin-bottom: 2rem;">
                    Herramientas de alto rendimiento y tus programas favoritos optimizados para PC en un solo ecosistema
                    profesional.
                </h1>
                <a href="#catalog" class="btn-orange">Entrar al catálogo</a>
            </div>
        </div>
    </section>

    <main id="catalog" class="catalog-section">
        <h2 style="text-align: center; font-size: 3rem; margin-bottom: 5rem;">Programas Nuevos y en Tendencia</h2>
        <div class="dual-container">
            <?php
            $res = $conn->query("SELECT * FROM programas ORDER BY id DESC LIMIT 10");
            $programas = [];

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

            // Extraer las dos listas separadas
            $col1 = [];
            $res1 = $conn->query("SELECT * FROM programas ORDER BY clicks DESC LIMIT 5");
            if ($res1) { while ($row = $res1->fetch_assoc()) { $col1[] = $row; } }

            $col2 = [];
            $res2 = $conn->query("SELECT * FROM programas ORDER BY fecha_creacion DESC LIMIT 5");
            if ($res2) { while ($row = $res2->fetch_assoc()) { $col2[] = $row; } }

            // Rellenar con placeholders si hay menos de 5
            for ($i = count($col1); $i < 5; $i++) {
                $col1[] = ['id' => null, 'titulo' => 'Próximamente', 'descripcion' => 'Espacio reservado para nuevo software', 'imagen_url' => ''];
            }
            for ($i = count($col2); $i < 5; $i++) {
                $col2[] = ['id' => null, 'titulo' => 'Próximamente', 'descripcion' => 'Espacio reservado para nuevo software', 'imagen_url' => ''];
            }

            // Function to render columns
            function renderColumn($col, $startIndex, $colTitle)
            {
                $idx = $startIndex;
                echo '<div class="card-column">';
                echo "<h3 style='text-align: center; font-size: 1.8rem; margin-top: 0; margin-bottom: 2.5rem; color: var(--text-color); display:flex; align-items:center; justify-content:center; gap:10px;'>{$colTitle}</h3>";
                foreach ($col as $p) {
                    $hasImg = !empty($p['imagen_url']) && strpos($p['imagen_url'], 'via.placeholder') === false;
                    /* Se corrige el uso de comillas dentro del style para evitar que se rompa el atributo HTML */
                    $imgAbs = str_replace('../', '/', $p['imagen_url']);
                    $bgStyle = $hasImg ? "background:url(&quot;{$imgAbs}&quot;) center/cover no-repeat;" : "background:var(--carousel-bg); display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,0.3); font-size:2rem; font-weight:800;";
                    $innerContent = $hasImg ? "" : "???";

                    if ($p['id'] !== null) {
                        $f = date('d/m/Y', strtotime($p['fecha_creacion']));
                        $fUrl = "programa/lista/{$f}/{$p['slug']}";
                        $btn = "<a href='{$fUrl}' class='btn-orange btn-outline' style='padding:10px 20px; font-size:0.9rem; text-wrap:nowrap;'>Ver programa</a>";
                    } else {
                        $btn = "<a href='#' class='btn-orange btn-outline' style='padding:10px 20px; font-size:0.9rem; filter: grayscale(1); cursor:not-allowed; text-wrap:nowrap;' title='Próximamente'>Ver programa</a>";
                    }
                    $desc = mb_strimwidth($p['descripcion'], 0, 100, "...");

                    $estrellas = isset($p['estrellas_cache']) ? (float) $p['estrellas_cache'] : 0;
                    if ($estrellas > 0) {
                        $percentage = ($estrellas / 5) * 100 . '%';
                        $starSvg = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
                        $filledStarSvg = '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
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

                    global $ids_nuevos, $ids_tendencia;
                    $badgeHtml = $p['id'] !== null ? getBadgeHtml($p['id'], $ids_nuevos, $ids_tendencia) : '';

                    echo "<div class='card' style='--index: {$idx}'>
                            <div class='card__content'>
                                {$badgeHtml}
                                <div class='stacking-icon' style='width:140px;height:140px;{$bgStyle}border-radius:20px;border:1px solid var(--card-border);flex-shrink:0;'>{$innerContent}</div>
                                <div style='margin-left:20px;flex-grow:1;'>
                                    <h3 style='margin:0 0 5px 0;'>" . htmlspecialchars($p['titulo']) . "</h3>
                                    <p style='margin:0;color:currentColor;opacity:0.7;font-size:0.85rem;line-height:1.4;'>" . htmlspecialchars($desc) . "</p>
                                    {$ratingHtml}
                                </div>
                                <div style='padding-left:15px;'>
                                    {$btn}
                                </div>
                            </div>
                        </div>";
                    $idx++;
                }
                echo '</div>';
            }

            renderColumn($col1, 1, '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color:#f97316;"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"></path></svg> Tendencias');
            renderColumn($col2, 1, '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color:#10b981;"><path d="m9 12 2 2 4-4"></path><path d="m21 12-4.46 2.87.69 5.12-4.9-1.39-2.8 4.38-2.8-4.38-4.9 1.39.69-5.12L2 12l4.46-2.87-.69-5.12 4.9 1.39 2.8-4.38 2.8 4.38 4.9-1.39-.69 5.12Z"></path></svg> Nuevos');
            ?>
        </div>
        <div style="text-align: center; margin-top: 4rem;">
            <a href="catalog/index.php" class="btn-orange" style="font-size: 1.1rem; padding: 20px 60px;">Ver todo el
                catálogo</a>
        </div>
    </main>

    <div style="text-align: center; padding: 100px 0 20px; position: relative; z-index: 5;">
        <h2 style="font-size: 2.5rem; font-weight: 800; color: var(--text-color); letter-spacing: -0.02em;">
            Programas que tenemos en nuestra página
        </h2>
        <div style="width: 50px; height: 4px; background: var(--orange-btn); margin: 20px auto; border-radius: 10px;">
        </div>
    </div>

    <div class="carousel-outer">
        <div class="carousel-track">
            <div class="carousel-box"><span>PLACEHOLDER 01</span></div>
            <div class="carousel-box"><span>PLACEHOLDER 02</span></div>
            <div class="carousel-box"><span>PLACEHOLDER 03</span></div>
            <div class="carousel-box"><span>PLACEHOLDER 04</span></div>
            <div class="carousel-box"><span>PLACEHOLDER 05</span></div>
            <div class="carousel-box"><span>PLACEHOLDER 01</span></div>
            <div class="carousel-box"><span>PLACEHOLDER 02</span></div>
            <div class="carousel-box"><span>PLACEHOLDER 03</span></div>
            <div class="carousel-box"><span>PLACEHOLDER 04</span></div>
            <div class="carousel-box"><span>PLACEHOLDER 05</span></div>
        </div>
    </div>

    </div>

    <div style="padding: 0 20px 40px 20px;"> <?php include 'footer.php'; ?>
    </div>

    <script>
        /* Lógica de puntos interactivos */
        const canvas = document.getElementById('dotsCanvas');
        const ctx = canvas.getContext('2d');
        let points = [];
        let mouse = { x: -1000, y: -1000 };

        function initCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            points = [];
            for (let x = 0; x < canvas.width; x += 45) {
                for (let y = 0; y < canvas.height; y += 45) {
                    points.push({ x, y, baseSize: 1.2 });
                }
            }
        }
        window.addEventListener('mousemove', (e) => { mouse.x = e.clientX; mouse.y = e.clientY; });
        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            points.forEach(p => {
                const dist = Math.hypot(mouse.x - p.x, mouse.y - p.y);
                let size = p.baseSize;
                const isLight = document.documentElement.getAttribute('data-theme') === 'light';
                let defaultColor = isLight ? 'rgba(139, 90, 43, 0.12)' : 'rgba(249, 115, 22, 0.15)';
                let hoverRgb = isLight ? '139, 90, 43' : '249, 115, 22';

                let color = defaultColor;
                if (dist < 180) {
                    const ratio = (180 - dist) / 180;
                    size = p.baseSize + (ratio * 4.5);
                    color = `rgba(${hoverRgb}, ${0.15 + ratio * 0.7})`;
                }
                ctx.fillStyle = color;
                ctx.beginPath(); ctx.arc(p.x, p.y, size, 0, Math.PI * 2); ctx.fill();
            });
            requestAnimationFrame(animate);
        }
        window.addEventListener('resize', initCanvas);
        initCanvas(); animate();
    </script>
</body>

</html>