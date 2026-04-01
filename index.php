<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <title>Boosham blog - 8VOCASH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --orange-btn: #F97316;
        }

        body {
            overflow-x: hidden;
        }

        /* --- PUNTOS DE FONDO --- */
        #dotsCanvas {
            position: fixed;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            z-index: 0;
            pointer-events: none;
        }

        /* --- HERO --- */
        .hero-section {
            position: relative;
            height: 140vh;
            view-timeline: --hero-timeline block;
            z-index: 2;
        }
        .hero-sticky {
            position: sticky;
            top: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            mask-image: linear-gradient(to bottom, black 0%, black 60%, transparent 95%);
            -webkit-mask-image: linear-gradient(to bottom, black 0%, black 60%, transparent 95%);
        }
        .hero-background {
            position: absolute;
            inset: 0;
            background-image: var(--hero-bg);
            background-size: cover;
            background-position: center;
            z-index: -1;
            animation: hero-out linear forwards;
            animation-timeline: --hero-timeline;
            animation-range: exit 0% exit 100%;
        }
        .hero-content {
            text-align: center;
            animation: hero-out linear forwards;
            animation-timeline: --hero-timeline;
            animation-range: exit 0% exit 100%;
        }
        @keyframes hero-out {
            0% { transform: scale(1); filter: blur(0px); opacity: 1; }
            100% { transform: scale(3); filter: blur(25px); opacity: 0; }
        }

        /* --- CATÁLOGO --- */
        .catalog-section {
            position: relative;
            z-index: 5;
            margin-top: -15vh;
            padding: 15vh 5% 5vh;
        }
        .dual-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 35px;
            max-width: 1300px;
            margin: 0 auto;
        }
        .card { position: sticky; top: 120px; padding-top: calc(var(--index) * 1.5rem); margin-bottom: 2rem; }
        .card__content {
            background: var(--carousel-bg);
            border: 1px solid var(--card-border); color: var(--card-text);
            border-radius: 24px;
            padding: 30px;
            min-height: 220px;
            display: flex;
            align-items: center;
            gap: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.7);
        }

        [data-theme="light"] .card__content {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            border: 2px solid #d4cfc7 !important; /* Margen/borde más notorio para la propia card en light mode */
            margin: 5px; /* Pequeño espaciado extra por si acaso se refería a margin CSS */
        }
        
        /* Asegurar que la margen (borde) de los placeholders se note también en Dark Mode */
        .stacking-icon {
            border: 2px solid var(--card-border) !important;
        }
        
        [data-theme="light"] .stacking-icon {
            border: 2px solid #a3927b !important; /* Borde más oscuro y gordito pero no mucho */
            background-color: #b8a994 !important; /* Color madera para que haga juego si no carga imagen */
        }

        /* --- CARRUSEL INFINITO (INTEGRADO) --- */
        .carousel-outer {
            width: 100%;
            overflow: hidden;
            padding: 100px 0;
            position: relative;
            z-index: 5;
            background: transparent;
        }
        .carousel-track {
            display: flex;
            width: calc(320px * 10); /* 5 items + 5 duplicados */
            animation: scroll-loop 30s linear infinite;
        }
        .carousel-box {
            width: 280px;
            height: 160px;
            background: var(--carousel-bg);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            margin: 0 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .carousel-box:hover {
            border-color: var(--orange-btn);
            transform: scale(1.05);
            background: var(--card-bg-hover);
        }
        .carousel-box span {
            color: var(--card-text); opacity: 0.7;
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 2px;
        }
        @keyframes scroll-loop {
            0% { transform: translateX(0); }
            100% { transform: translateX(calc(-320px * 5)); }
        }

        /* Estilos de botones rápidos */
        .btn-orange { background: var(--orange-btn); padding: 18px 50px; border-radius: 50px; color: black; font-weight: 700; text-decoration: none; display: inline-block; transition: 0.3s; }
        .btn-orange:hover { transform: scale(1.05); }
        .btn-outline { background: transparent; border: 2px solid var(--orange-btn); color: var(--text-color); }
        .btn-outline:hover { background: var(--orange-btn); color: black; }
        
        @media (max-width: 900px) {
            .dual-container { grid-template-columns: 1fr; }
        }

        

        .hero-section > * {
            position: relative;
            z-index: 2;
        }
    
        .hero-logo-box {
            background: var(--hero-logo-bg);
            box-shadow: var(--hero-logo-shadow);
            padding: 10px 40px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 20px;
            transition: background 0.3s, box-shadow 0.3s;
        }
        .hero-text-box {
            color: var(--hero-subtitle);
            font-size: 1.2rem;
            max-width: 700px;
            margin: 2rem auto;
            background: var(--hero-text-bg);
            padding: 1.5rem;
            border-radius: 15px;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: var(--hero-text-shadow);
            border: 1px solid var(--header-border);
            transition: background 0.3s, box-shadow 0.3s, border-color 0.3s;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: var(--hero-gradient-overlay);
            pointer-events: none;
            z-index: 1;
            transition: 0.3s;
        }

        /* --- RESPONSIVO PARA MÓVILES (Index) --- */
        @media (max-width: 768px) {
            .hero-logo-box {
                padding: 10px 20px;
                margin-bottom: 10px;
            }
            .hero-logo-box img {
                height: 5.5rem !important; /* Más pequeño en móviles */
            }
            .hero-text-box {
                font-size: 0.95rem;
                padding: 1rem;
                margin: 1rem 15px; /* Evita que toque los bordes */
                line-height: 1.5;
            }
            .hero-section {
                height: 120vh; /* Menos scroll necesario para pasar el hero */
            }
            .catalog-section {
                padding: 10vh 15px 5vh;
            }
            .catalog-section h2 {
                font-size: 2rem !important;
                margin-bottom: 3rem !important;
            }
            .card__content {
                padding: 20px;
                flex-direction: column; /* Apila el icono y el texto verticalmente */
                text-align: center;
                gap: 15px;
                min-height: auto;
            }
            .stacking-icon {
                width: 80px !important;
                height: 80px !important;
                min-width: 80px !important;
            }
            .carousel-outer {
                padding: 60px 0; /* Menos espacio en móvil */
            }
            .carousel-box {
                width: 200px; /* Tarjetas más pequeñas en carrusel */
                height: 120px;
                margin: 0 10px;
            }
            @keyframes scroll-loop {
                0% { transform: translateX(0); }
                100% { transform: translateX(calc(-220px * 5)); } /* Ajustado al nuevo ancho (200 + 10x2) */
            }
        }
    </style>
</head>
<body>

    <?php include 'Encabezado.php'; ?>

    <canvas id="dotsCanvas"></canvas>

    <section class="hero-section">
        <div class="hero-sticky">
            <div class="hero-background"></div>
            <div class="hero-content">
                <div class="hero-logo-box">
                    <img src="logo/logo-text.svg" alt="Boosham blog" style="height: 9rem; filter: var(--logo-filter); max-width: 100%;">
                </div>
                <p class="hero-text-box">
                    Herramientas de alto rendimiento y tus programas favoritos optimizados para PC en un solo ecosistema profesional.
                </p>
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
            if($res) {
                while($row = $res->fetch_assoc()) {
                    $programas[] = $row;
                }
            }

            // Fill up to 10 with placeholders
            $total = count($programas);
            for($i = $total; $i < 10; $i++) {
                $programas[] = [
                    'id' => null,
                    'titulo' => 'Próximamente',
                    'descripcion' => 'Espacio reservado para nuevo software',
                    'imagen_url' => ''
                ];
            }

            // Distribute into 2 columns
            $col1 = [];
            $col2 = [];
            foreach($programas as $index => $prog) {
                if($index % 2 == 0) {
                    $col1[] = $prog;
                } else {
                    $col2[] = $prog;
                }
            }
            
            // Function to render columns
            function renderColumn($col, $startIndex) {
                $idx = $startIndex;
                echo '<div class="card-column">';
                foreach($col as $p) {
                    $hasImg = !empty($p['imagen_url']) && strpos($p['imagen_url'], 'via.placeholder') === false;
                    /* Se corrige el uso de comillas dentro del style para evitar que se rompa el atributo HTML */
                    $bgStyle = $hasImg ? "background:url(&quot;{$p['imagen_url']}&quot;) center/cover no-repeat;" : "background:var(--carousel-bg); display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,0.3); font-size:2rem; font-weight:800;";
                    $innerContent = $hasImg ? "" : "???";
                    
                    $btn = $p['id'] 
                        ? "<a href='programa/lista.php?id={$p['id']}' class='btn-orange btn-outline' style='padding:10px 20px; font-size:0.9rem; text-wrap:nowrap;'>Ver programa</a>" 
                        : "<a href='#' class='btn-orange btn-outline' style='padding:10px 20px; font-size:0.9rem; filter: grayscale(1); cursor:not-allowed; text-wrap:nowrap;' title='Próximamente'>Ver programa</a>";
                    $desc = mb_strimwidth($p['descripcion'], 0, 100, "...");
                    
                    $estrellas = isset($p['estrellas_cache']) ? (float)$p['estrellas_cache'] : 0;
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
                    
                    echo "<div class='card' style='--index: {$idx}'>
                            <div class='card__content'>
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
            
            renderColumn($col1, 1);
            renderColumn($col2, 1);
            ?>
        </div>
        <div style="text-align: center; margin-top: 4rem;">
            <a href="catalog/index.php" class="btn-orange" style="font-size: 1.1rem; padding: 20px 60px;">Ver todo el catálogo</a>
        </div>
    </main>

<div style="text-align: center; padding: 100px 0 20px; position: relative; z-index: 5;">
        <h2 style="font-size: 2.5rem; font-weight: 800; color: var(--text-color); letter-spacing: -0.02em;">
            Programas que tenemos en nuestra página
        </h2>
        <div style="width: 50px; height: 4px; background: var(--orange-btn); margin: 20px auto; border-radius: 10px;"></div>
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