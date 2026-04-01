<?php
require_once '../config.php';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 1;
$row = null;

if (isset($conn)) {
    $stmt = $conn->prepare("SELECT * FROM programas WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
    }
}
if (empty($row)) {
    // Modo fallback con Placeholders
    $row = [
        'titulo' => 'Programa de Prueba',
        'descripcion' => 'Este es un texto placeholder (Lorem Ipsum) de descripción general para visualizar el diseño.',
        'link_descarga' => '#',
        'imagen_url' => 'https://via.placeholder.com/300',
        'caracteristicas' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        'novedades' => 'Lorem ipsum dolor sit amet, actualizaciones recientes incluidas.',
        'requisitos' => 'Windows 10, Memoria RAM de 4GB, Procesador Dual-Core.',
        'publico_objetivo' => 'Estudiantes, profesionales y público en general.',
        'info_archivo' => 'Formato: EXE, Tamaño aproximado: 50MB.',
        'instrucciones' => '1. Descargar el archivo.\n2. Ejecutar el instalador.\n3. Seguir el asistente.',
        'aviso_legal' => 'La descarga se realiza bajo su propio riesgo.',
        'reviews' => '★★★★★ Reseña de usuario genérica (Lorem Ipsum)'
    ];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($row['titulo'] ?? 'Programa') ?></title>
    <link rel="icon" type="image/svg+xml" href="../favicon.svg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --orange-btn: #ff8c00;
        }

        /* --- PUNTOS DE FONDO --- */
        #dotsCanvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 0;
            pointer-events: none;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            transition: background 0.3s, color 0.3s;
        }

        .container {
            position: relative;
            z-index: 2;
            max-width: 1200px;
            margin: 0 auto;
            padding: 120px 20px 40px;
        }

        .hero-software {
            display: flex;
            align-items: stretch;
            justify-content: space-between;
            gap: 0;
            margin-bottom: 50px;
            min-height: 340px;
            /* Balance óptimo para imágenes de 250px + reflejo */
            background: var(--header-bg);
            border: 1px solid var(--header-border);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        /* Left info panel - takes most space */
        .hero-info-panel {
            display: flex;
            align-items: center;
            gap: 28px;
            padding: 36px 32px;
            flex: 1;
            min-width: 0;
        }



        /* When there are NO screenshots, hero-info-panel takes full width like before */
        .hero-software.no-screenshots .hero-info-panel {
            padding: 40px;
            flex: 1;
        }

        .hero-details h1 {
            font-size: 3rem;
            margin: 0 0 15px 0;
            color: var(--text-color);
        }

        /* Hero icon — padding keeps edge images from touching the border */
        .hero-program-icon {
            width: 130px;
            height: 130px;
            border-radius: 16px;
            border: 1px solid var(--header-border);
            flex-shrink: 0;
            object-fit: cover;
            padding: 6px;
            box-sizing: border-box;
            background: var(--card-bg);
            overflow: hidden;
        }

        .hero-program-icon.placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: 800;
            border-color: var(--card-border);
        }

        .hero-details p {
            font-size: 1.1rem;
            line-height: 1.5;
            color: var(--text-muted);
            margin: 0 0 25px 0;
        }

        .btn-download {
            display: inline-block;
            background: #3b82f6;
            color: white;
            padding: 15px 35px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
            transition: 0.3s;
        }

        .btn-download:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.6);
        }

        /* Grid Glassmorphism para Categorías */
        .grid-sections {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 25px;
        }

        .section-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 25px;
            transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .section-card:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-5px);
        }

        .section-card h2 {
            font-size: 1.3rem;
            margin: 0 0 15px 0;
            color: #60a5fa;
            /* Azul claro pero brillante */
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 10px;
            font-weight: 600;
        }

        .section-card p {
            font-size: 0.95rem;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.85);
            margin: 0;
            white-space: pre-line;
            /* Para respetar saltos de linea de la DB */
        }

        @media (max-width: 768px) {
            .hero-software {
                flex-direction: column;
                text-align: center;
                padding: 30px 20px;
            }

            .hero-screenshots-panel {
                flex: 1 1 auto;
                width: 100%;
                border-left: none;
                border-top: 1px solid var(--header-border);
                min-height: 200px;
            }

            .hero-details h1 {
                font-size: 2.2rem;
            }
        }

        /* --- CTA DESCARGA FINAL --- */
        .download-cta {
            position: relative;
            z-index: 2;
            max-width: 700px;
            margin: 60px auto 0;
            background: rgba(255, 140, 0, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 140, 0, 0.25);
            border-radius: 24px;
            padding: 50px 40px;
            text-align: center;
            box-shadow: 0 0 60px rgba(255, 140, 0, 0.08);
        }

        .download-cta h2 {
            font-size: 1.6rem;
            font-weight: 800;
            margin: 0 0 10px 0;
            color: white;
        }

        .download-cta p {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.95rem;
            margin: 0 0 30px 0;
        }

        .btn-download-final {
            display: inline-block;
            background: var(--orange-btn);
            color: black;
            padding: 18px 55px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: 0.02em;
            box-shadow: 0 4px 25px rgba(255, 140, 0, 0.4);
            transition: 0.3s;
        }

        .btn-download-final:hover {
            transform: translateY(-3px) scale(1.04);
            box-shadow: 0 8px 35px rgba(255, 140, 0, 0.6);
        }

        /* Footer wrapper */
        .footer-wrapper {
            position: relative;
            z-index: 2;
            padding: 60px 20px 40px;
        }

        /* Cleanup: eliminamos redundancias que ya están arriba */
        .section-card {
            background: var(--card-bg) !important;
            border: 1px solid var(--card-border) !important;
            box-shadow: var(--card-shadow);
        }

        /* ... resto de estilos de section-card ... */
        .section-card:hover {
            background: var(--card-bg-hover) !important;
            border-color: var(--orange-btn) !important;
        }

        .section-card h2 {
            border-bottom: 1px solid var(--header-border) !important;
            color: var(--text-color) !important;
        }

        .section-card p {
            color: var(--text-muted) !important;
        }

        .download-cta {
            background: var(--card-bg) !important;
            border: 1px solid var(--header-border) !important;
            box-shadow: var(--card-shadow) !important;
        }

        .download-cta h2,
        .download-cta p {
            color: var(--text-color) !important;
        }

        .download-cta p {
            opacity: 0.8;
        }

        /* PLACEHOLDER IMAGE - Tonos madera clara y oscura coordinados */
        .program-icon-placeholder {
            background-color: var(--card-bg);
            /* En dark mode usa el fondo de las cards */
            border-color: var(--card-border);
            color: var(--text-muted);
        }

        [data-theme="light"] .hero-software img,
        [data-theme="light"] .program-icon-placeholder {
            background-color: #b8a994 !important;
            border-color: #a3927b !important;
            color: #bfa082 !important;
        }

        /* --- COVER FLOW — Robust JS-Driven Library Flip --- */
        :root {
            --cover-size: 400px;
        }

        /* Renderizado base grande para nitidez absoluta */

        .hero-screenshots-panel {
            flex: 0 0 450px;
            border-left: 1px solid var(--header-border);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .hero-screenshots-panel .cards {
            list-style: none;
            white-space: nowrap;
            margin: 0;
            /* Padding dinámico: permitimos espacio vertical para el zoom 1.5x y reflejos */
            padding: 40px calc(50% - (var(--cover-size) / 2));
            width: 100%;
            display: flex;
            align-items: center;
            overflow-x: scroll;
            overflow-y: visible;
            scroll-snap-type: x mandatory;
            -ms-overflow-style: none;
            scrollbar-width: none;
            perspective: 1000px;
            scroll-behavior: smooth;
        }

        .hero-screenshots-panel .cards::-webkit-scrollbar {
            display: none;
        }

        .hero-screenshots-panel .cards li {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 var(--cover-size);
            width: var(--cover-size);
            aspect-ratio: 16/9;
            scroll-snap-align: center;
            position: relative;
            transition: z-index 0.3s;
            margin: 0;
        }

        .hero-screenshots-panel .cards li img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            display: block;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
            /* Mantenemos en GPU para que los textos y la imagen no se pixeleen al terminar */
            will-change: transform, filter;
            -webkit-box-reflect: below 0.4em linear-gradient(rgb(0 0 0 / 0), rgb(0 0 0 / 0.2));
        }

        /* ---------- Single screenshot: bigger, centered ---------- */
        .hero-screenshots-panel .cards.single-image {
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            padding: 16px;
        }

        .hero-screenshots-panel .cards.single-image li {
            width: 90%;
            aspect-ratio: 16/9;
            perspective: none;
        }

        .hero-screenshots-panel .cards.single-image li img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
            transform: none !important;
            filter: none !important;
            -webkit-box-reflect: none;
        }

        @keyframes rotate-cover {
            0% {
                transform: translateX(-40%) rotateY(-60deg) scale(0.85);
                filter: brightness(0.3) contrast(1.2);
            }

            35% {
                transform: translateX(0) rotateY(-50deg) scale(0.9);
                filter: brightness(0.5);
            }

            50% {
                transform: rotateY(0deg) translateZ(50px) scale(1.45);
                filter: brightness(1);
            }

            65% {
                transform: translateX(0) rotateY(50deg) scale(0.9);
                filter: brightness(0.5);
            }

            100% {
                transform: translateX(40%) rotateY(60deg) scale(0.85);
                filter: brightness(0.3) contrast(1.2);
            }
        }

        /* Carousel arrow buttons */
        .carousel-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.55);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            z-index: 200;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .carousel-btn:hover {
            background: var(--orange-btn);
            border-color: var(--orange-btn);
        }

        .carousel-btn.left {
            left: 12px;
        }

        .carousel-btn.right {
            right: 12px;
        }

        /* Lightbox — above everything including the fixed header (z:1000) */
        #screenshot-lightbox {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 10001;
            background: rgba(0, 0, 0, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            align-items: center;
            justify-content: center;
        }

        #screenshot-lightbox.open {
            display: flex;
        }

        #screenshot-lightbox img {
            max-width: 90vw;
            max-height: 85vh;
            object-fit: contain;
            border-radius: 14px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.9);
            animation: lb-in 0.25s ease;
            /* Prevent clicks on the image from bubbling to backdrop */
            pointer-events: none;
        }

        /* Lightbox open animation */
        @keyframes lb-in {
            from {
                opacity: 0;
                transform: scale(0.88);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Lightbox close animation */
        @keyframes lb-out {
            from {
                opacity: 1;
                transform: scale(1);
            }

            to {
                opacity: 0;
                transform: scale(0.88);
            }
        }

        #screenshot-lightbox img {
            max-width: 90vw;
            max-height: 85vh;
            object-fit: contain;
            border-radius: 14px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.9);
            animation: lb-in 0.25s ease forwards;
            pointer-events: none;
        }

        #screenshot-lightbox.closing img {
            animation: lb-out 0.22s ease forwards;
        }

        /* Fade the backdrop too */
        #screenshot-lightbox {
            transition: background 0.25s ease, backdrop-filter 0.25s ease;
        }

        #screenshot-lightbox.closing {
            background: rgba(0, 0, 0, 0);
            backdrop-filter: blur(0px);
        }

        /* Hide the fixed nav when lightbox is open */
        body.lightbox-open .header-wrapper {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }

        #lightbox-close {
            position: fixed;
            top: 20px;
            right: 24px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #fff;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.3rem;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10002;
            transition: background 0.2s;
        }

        #lightbox-close:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* --- RESPONSIVO PARA MÓVILES --- */
        @media (max-width: 768px) {
            :root {
                --cover-size: 260px; /* Screenshots ajustados (punto medio) */
            }
            .container {
                padding: 100px 15px 30px;
            }
            /* Hero Panel Adjustments */
            .hero-info-panel {
                flex-direction: column;
                text-align: center;
                padding: 25px 15px !important;
            }
            .hero-program-icon {
                margin: 0 auto;
            }
            .hero-details p {
                font-size: 1rem;
            }
            #hero-rating-container > div {
                justify-content: center !important;
            }
            
            /* Grid Sections */
            .grid-sections {
                grid-template-columns: 1fr;
            }
            .section-card {
                padding: 20px;
            }

            /* Call to Action */
            .download-cta {
                padding: 35px 20px;
                margin-top: 40px;
            }
            .btn-download-final {
                padding: 15px 40px;
                font-size: 1.05rem;
                width: 100%;
                box-sizing: border-box;
            }

            /* Giscus & Reviews */
            .giscus-reviews-container {
                padding: 20px 15px !important;
            }
            #rating-summary-panel {
                flex-direction: column;
                padding: 20px 15px !important;
                gap: 20px !important;
            }
            .reaction-badges-container {
                flex-direction: column !important;
            }
            .reaction-badges-container > div {
                width: 100% !important;
                justify-content: center !important;
            }
        }
    </style>
</head>

<body>

    <canvas id="dotsCanvas"></canvas>

    <?php include '../Encabezado.php'; ?>

    <div class="container">
        <?php
        $screenshotsDir = "capturas/{$id}/";
        $images = [];
        $numImages = 0;
        if (is_dir($screenshotsDir)) {
            $images = glob($screenshotsDir . "*.{jpg,jpeg,png,gif,webp}", GLOB_BRACE) ?: [];
            $numImages = count($images);
        }
        $hasScreenshots = $numImages > 0;
        ?>

        <!-- Hero: left info + right cover-flow (only when screenshots exist) -->
        <div class="hero-software<?= $hasScreenshots ? '' : ' no-screenshots' ?>">
            <div class="hero-info-panel">
                <?php if (!empty($row['imagen_url']) && strpos($row['imagen_url'], 'via.placeholder') === false): ?>
                    <img src="<?= htmlspecialchars($row['imagen_url']) ?>" alt="Icono del Software"
                        class="hero-program-icon">
                <?php else: ?>
                    <div class="hero-program-icon placeholder">???</div>
                <?php endif; ?>
                <div class="hero-details">
                    <h1><?= htmlspecialchars($row['titulo'] ?? 'Sin Título') ?></h1>
                    <p style="margin-bottom: 12px;"><?= htmlspecialchars($row['descripcion'] ?? 'Sin descripción disponible.') ?></p>
                    <div id="hero-rating-container" style="margin-bottom: 25px; min-height: 24px;">
                        <?php
                        $estrellas = isset($row['estrellas_cache']) ? (float)$row['estrellas_cache'] : 0;
                        if ($estrellas > 0):
                            $percentage = ($estrellas / 5) * 100 . '%';
                            $starSvg = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
                            $filledStarSvg = '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
                        ?>
                            <div style='display:flex; align-items:center; gap:10px;'>
                                <div style='position: relative; display: inline-flex; width: 100px; height: 20px;'>
                                    <div style='display: flex; position: absolute; top:0; left:0; color: gray; opacity: 0.3;'><?= str_repeat($starSvg, 5) ?></div>
                                    <div id="hero-rating-stars-fill" style='display: flex; position: absolute; top:0; left:0; color: #fbbf24; overflow: hidden; width: <?= $percentage ?>; transition: width 0.5s ease-out;'>
                                        <div style='display:flex; width: 100px;'><?= str_repeat($filledStarSvg, 5) ?></div>
                                    </div>
                                </div>
                                <span style='color:var(--text-color); font-size:1.1rem; font-weight:700;'><span id="hero-rating-text"><?= $estrellas ?></span> <span style='font-size:0.9rem; font-weight:normal; opacity:0.7;'>/ 5</span></span>
                            </div>
                        <?php else: ?>
                            <span id="hero-rating-empty" style='color:var(--text-muted); font-size:0.95rem; opacity:0.8;'>Aún no hay reseñas</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if ($hasScreenshots): ?>
                <!-- Right cover-flow hero panel -->
                <div class="hero-screenshots-panel">
                    <?php
                    $singleClass = ($numImages === 1) ? ' single-image' : '';
                    ?>
                    <div class="carousel-wrapper">
                        <?php if ($numImages > 1): ?>
                            <button id="prevBtn" class="carousel-btn left">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m15 18-6-6 6-6" />
                                </svg>
                            </button>
                        <?php endif; ?>
                        <ul id="cover-flow-cards" class="cards<?= $singleClass ?>">
                            <?php foreach ($images as $img): ?>
                                <li><img src="<?= htmlspecialchars($img) ?>" alt="Screenshot"
                                        data-src="<?= htmlspecialchars($img) ?>"></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php if ($numImages > 1): ?>
                            <button id="nextBtn" class="carousel-btn right">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m9 18 6-6-6-6" />
                                </svg>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div><!-- /.hero-software -->

        <!-- Lightbox modal -->
        <div id="screenshot-lightbox">
            <button id="lightbox-close">×</button>
            <img id="lightbox-img" src="" alt="Captura ampliada">
        </div>


        <div class="grid-sections">
            <div class="section-card">
                <h2>Características Principales</h2>
                <p><?= htmlspecialchars(!empty($row['caracteristicas']) ? $row['caracteristicas'] : "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.") ?>
                </p>
            </div>
            <div class="section-card">
                <h2>Novedades</h2>
                <p><?= htmlspecialchars(!empty($row['novedades']) ? $row['novedades'] : "Se han integrado mejoras de rendimiento globales (Lorem Ipsum) y corrección de pequeños bugs.") ?>
                </p>
            </div>
            <div class="section-card">
                <h2>Requisitos del Sistema</h2>
                <p><?= htmlspecialchars(!empty($row['requisitos']) ? $row['requisitos'] : "Sistema Operativo: Windows 10/11\nProcesador: Múltiples Núcleos\nRAM: Min. 4GB") ?>
                </p>
            </div>
            <div class="section-card">
                <h2>Público Objetivo</h2>
                <p><?= htmlspecialchars(!empty($row['publico_objetivo']) ? $row['publico_objetivo'] : "Orientado a profesionales de la industria, estudiantes y entusiastas (Placeholder Lorem Ipsum).") ?>
                </p>
            </div>
            <div class="section-card">
                <h2>Información del Archivo</h2>
                <p><?= htmlspecialchars(!empty($row['info_archivo']) ? $row['info_archivo'] : "Nombre: app_installer.exe\nPeso Aprox: 120MB\nLicencia: Gratuita") ?>
                </p>
            </div>
            <div class="section-card">
                <h2>Instrucciones de Instalación</h2>
                <p><?= htmlspecialchars(!empty($row['instrucciones']) ? $row['instrucciones'] : "1. Proceda a descargar.\n2. Ejecute Install.exe como administrador.\n3. Disfrute.") ?>
                </p>
            </div>
            <div class="section-card">
                <h2>Aviso Legal / Disclaimer</h2>
                <p><?= htmlspecialchars(!empty($row['aviso_legal']) ? $row['aviso_legal'] : "El uso de esta copia de prueba es responsabilidad entera del consumidor final.") ?>
                </p>
            </div>

        </div>

        <!-- CTA de descarga centrado al final -->
        <div class="download-cta">
            <h2>¿Listo para descargar?</h2>
            <p>Descarga <?= htmlspecialchars($row['titulo'] ?? 'este programa') ?> de forma segura y gratuita.</p>
            <a href="<?= htmlspecialchars($row['link_descarga'] ?? '#') ?>" target="_blank" class="btn-download-final">⬇
                Descargar Software</a>
        </div>

        <!-- Guía de emojis/reacciones para votar -->
        <div
            style="max-width: 700px; margin: 20px auto 0; padding: 22px 28px; border-radius: 20px; background: var(--card-bg); border: 1px solid var(--header-border); backdrop-filter: blur(10px);">
            <h3
                style="color: var(--text-color); font-size: 1rem; margin-bottom: 14px; display:flex; align-items:center; gap:8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 16v-4M12 8h.01" />
                </svg>
                ¿Cómo calificar este programa?
            </h3>
            <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 14px;">Usa las <strong>reacciones de
                    Giscus</strong> (justo arriba de la caja de comentarios) para dar tu puntuación. Cada emoji vale:
            </p>
            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                <div
                    style="display:flex; align-items:center; gap:7px; background: rgba(251,191,36,0.08); border:1px solid rgba(251,191,36,0.2); padding:7px 13px; border-radius:12px; font-size:0.85rem;">
                    <span style="font-size:1.2rem;">🎉</span><span style="color:var(--text-color);">Hooray</span>
                    <span
                        style="background:#fbbf24; color:#000; border-radius:8px; padding:1px 7px; font-weight:700; font-size:0.78rem;">★
                        5</span>
                </div>
                <div
                    style="display:flex; align-items:center; gap:7px; background: rgba(251,191,36,0.08); border:1px solid rgba(251,191,36,0.2); padding:7px 13px; border-radius:12px; font-size:0.85rem;">
                    <span style="font-size:1.2rem;">❤️</span><span style="color:var(--text-color);">Heart</span>
                    <span
                        style="background:#fbbf24; color:#000; border-radius:8px; padding:1px 7px; font-weight:700; font-size:0.78rem;">★
                        5</span>
                </div>
                <div
                    style="display:flex; align-items:center; gap:7px; background: rgba(251,191,36,0.08); border:1px solid rgba(251,191,36,0.2); padding:7px 13px; border-radius:12px; font-size:0.85rem;">
                    <span style="font-size:1.2rem;">🚀</span><span style="color:var(--text-color);">Rocket</span>
                    <span
                        style="background:#fbbf24; color:#000; border-radius:8px; padding:1px 7px; font-weight:700; font-size:0.78rem;">★
                        5</span>
                </div>
                <div
                    style="display:flex; align-items:center; gap:7px; background: rgba(100,150,255,0.08); border:1px solid rgba(100,150,255,0.2); padding:7px 13px; border-radius:12px; font-size:0.85rem;">
                    <span style="font-size:1.2rem;">😄</span><span style="color:var(--text-color);">Laugh</span>
                    <span
                        style="background:#6496ff; color:#fff; border-radius:8px; padding:1px 7px; font-weight:700; font-size:0.78rem;">★
                        4</span>
                </div>
                <div
                    style="display:flex; align-items:center; gap:7px; background: rgba(100,150,255,0.08); border:1px solid rgba(100,150,255,0.2); padding:7px 13px; border-radius:12px; font-size:0.85rem;">
                    <span style="font-size:1.2rem;">👍</span><span style="color:var(--text-color);">Thumbs Up</span>
                    <span
                        style="background:#6496ff; color:#fff; border-radius:8px; padding:1px 7px; font-weight:700; font-size:0.78rem;">★
                        3</span>
                </div>
                <div
                    style="display:flex; align-items:center; gap:7px; background: rgba(100,150,255,0.08); border:1px solid rgba(100,150,255,0.2); padding:7px 13px; border-radius:12px; font-size:0.85rem;">
                    <span style="font-size:1.2rem;">👀</span><span style="color:var(--text-color);">Eyes</span>
                    <span
                        style="background:#6496ff; color:#fff; border-radius:8px; padding:1px 7px; font-weight:700; font-size:0.78rem;">★
                        3</span>
                </div>
                <div
                    style="display:flex; align-items:center; gap:7px; background: rgba(180,80,80,0.08); border:1px solid rgba(180,80,80,0.2); padding:7px 13px; border-radius:12px; font-size:0.85rem;">
                    <span style="font-size:1.2rem;">😕</span><span style="color:var(--text-color);">Confused</span>
                    <span
                        style="background:#b45050; color:#fff; border-radius:8px; padding:1px 7px; font-weight:700; font-size:0.78rem;">★
                        2</span>
                </div>
                <div
                    style="display:flex; align-items:center; gap:7px; background: rgba(180,80,80,0.08); border:1px solid rgba(180,80,80,0.2); padding:7px 13px; border-radius:12px; font-size:0.85rem;">
                    <span style="font-size:1.2rem;">👎</span><span style="color:var(--text-color);">Thumbs Down</span>
                    <span
                        style="background:#b45050; color:#fff; border-radius:8px; padding:1px 7px; font-weight:700; font-size:0.78rem;">★
                        1</span>
                </div>
            </div>
        </div>

        <!-- Sección de Comentarios y Reseñas Giscus -->
        <div class="giscus-reviews-container"
            style="max-width: 900px; margin: 40px auto; padding: 30px; border-radius: 24px; background: var(--card-bg); border: 1px solid var(--header-border); backdrop-filter: blur(12px);">
            <h2 style="color: var(--text-color); text-align: center; margin-bottom: 20px;">Comentarios y Calificaciones
            </h2>

            <!-- Panel de resumen de puntuación (Premium Style) -->
            <div id="rating-summary-panel"
                style="margin-bottom: 28px; padding: 25px 30px; border-radius: 20px; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1); display:flex; gap:35px; align-items:center; flex-wrap:wrap; box-shadow: inset 0 2px 10px rgba(0,0,0,0.3);">
                <!-- Puntuación grande a la izquierda -->
                <div style="text-align:center; min-width:110px;">
                    <div id="summary-avg"
                        style="font-size: 3.8rem; font-weight:900; color: #fbbf24; line-height:1; text-shadow: 0 0 15px rgba(251,191,36,0.25);">
                        —</div>
                    <div id="summary-stars" style="margin-top:10px;"></div>
                    <div id="summary-votes"
                        style="font-size:0.85rem; color:var(--text-muted); margin-top:8px; letter-spacing: 0.5px;">
                        Cargando...</div>
                </div>
                <!-- Barras de desglose a la derecha -->
                <div id="summary-breakdown"
                    style="flex:1; min-width:220px; display:flex; flex-direction:column; gap:9px;">
                    <!-- Se inyecta por JS -->
                </div>
            </div>

            <div class="giscus"></div>
        </div>

    </div>

    <div class="footer-wrapper">
        <?php include '../footer.php'; ?>
    </div>

    <script>
        // Calcular promedio de estrellas basado en reacciones
        window.addEventListener('message', (event) => {
            if (event.origin !== 'https://giscus.app') return;
            // Debugging the Giscus Payload
            console.log('Giscus Event Data:', event.data);

            if (event.data && event.data.giscus) {
                // SVG Lucide Star
                const starSvg = `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>`;
                const filledStarSvg = `<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>`;
                // Si no hay discusión todavía, muestra el estado neutro en el panel
                if (event.data.giscus.error === 'Discussion not found' || !event.data.giscus.discussion) {
                    renderSummaryPanel(null, 0, {});
                    return;
                }

                const reactions = event.data.giscus.discussion.reactions || {};
                const weights = { 'HOORAY': 5, 'HEART': 5, 'ROCKET': 5, 'LAUGH': 4, 'THUMBS_UP': 3, 'EYES': 3, 'CONFUSED': 2, 'THUMBS_DOWN': 1 };

                let points = 0, votes = 0;
                for (const [key, reactionObj] of Object.entries(reactions)) {
                    const count = reactionObj.count || 0;
                    if (weights[key] && count > 0) {
                        points += (weights[key] * count);
                        votes += count;
                    }
                }

                // ---- helper: render rating summary panel ----
                function renderSummaryPanel(avg, votes, reactionCounts) {
                    const avgEl = document.getElementById('summary-avg');
                    const starsEl = document.getElementById('summary-stars');
                    const votesEl = document.getElementById('summary-votes');
                    const bdEl = document.getElementById('summary-breakdown');
                    if (!avgEl) return;

                    if (avg === null) {
                        avgEl.textContent = '—';
                        starsEl.innerHTML = `<div style="display:flex;gap:2px;justify-content:center;">${starSvg.repeat(5).replace(/width="24"/g, 'width="16"').replace(/height="24"/g, 'height="16"')}</div>`;
                        votesEl.textContent = 'Sin valoraciones aún';
                        bdEl.innerHTML = '<span style="font-size:0.85rem;color:var(--text-muted);">Sé el primero en calificar. Usa las reacciones de abajo ↓</span>';
                        
                        const heroContainer = document.getElementById('hero-rating-container');
                        if (heroContainer) {
                            heroContainer.innerHTML = `<span id="hero-rating-empty" style='color:var(--text-muted); font-size:0.95rem; opacity:0.8;'>Aún no hay reseñas</span>`;
                        }
                        return;
                    }

                    avgEl.textContent = avg;
                    const pct = (avg / 5) * 100 + '%';
                    starsEl.innerHTML = `
                        <div style="position:relative;display:inline-flex;width:80px;height:16px;">
                            <div style="display:flex;position:absolute;top:0;left:0;color:gray;opacity:0.3;">${starSvg.repeat(5).replace(/width="24"/g, 'width="16"').replace(/height="24"/g, 'height="16"')}</div>
                            <div style="display:flex;position:absolute;top:0;left:0;color:#fbbf24;overflow:hidden;width:${pct};">
                                <div style="display:flex;width:80px;">${filledStarSvg.repeat(5).replace(/width="24"/g, 'width="16"').replace(/height="24"/g, 'height="16"')}</div>
                            </div>
                        </div>`;
                    votesEl.textContent = `${votes} valoración${votes !== 1 ? 'es' : ''}`;

                    // Reaction breakdown — grouped by star tier
                    const tiers = [
                        { label: '5 ★', emojis: ['🎉', '❤️', '🚀'], keys: ['HOORAY', 'HEART', 'ROCKET'], color: '#fbbf24' },
                        { label: '4 ★', emojis: ['😄'], keys: ['LAUGH'], color: '#a78bfa' },
                        { label: '3 ★', emojis: ['👍', '👀'], keys: ['THUMBS_UP', 'EYES'], color: '#60a5fa' },
                        { label: '2 ★', emojis: ['😕'], keys: ['CONFUSED'], color: '#fb923c' },
                        { label: '1 ★', emojis: ['👎'], keys: ['THUMBS_DOWN'], color: '#f87171' },
                    ];
                    const totalVotes = Math.max(votes, 1);
                    bdEl.innerHTML = tiers.map(tier => {
                        const cnt = tier.keys.reduce((s, k) => s + (reactionCounts[k] || 0), 0);
                        const barPct = Math.round((cnt / totalVotes) * 100);
                        const dots = Array.from({ length: cnt }, () =>
                            `<span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:${tier.color};margin-right:3px;"></span>`
                        ).join('');
                        return `
                            <div style="display:flex;align-items:center;gap:10px;font-size:0.82rem;">
                                <span style="min-width:32px;color:${tier.color};font-weight:600;">${tier.label}</span>
                                <div style="flex:1;background:rgba(255,255,255,0.07);border-radius:6px;height:8px;overflow:hidden;">
                                    <div style="width:${barPct}%;height:100%;background:${tier.color};border-radius:6px;transition:width 0.6s;"></div>
                                </div>
                                <span style="min-width:40px;color:var(--text-muted);">${dots || '<span style="opacity:0.3;">—</span>'}</span>
                                <span style="color:var(--text-muted);min-width:22px;">${cnt}</span>
                            </div>`;
                    }).join('');

                    // Actualizar área hero dinámicamente
                    const heroContainer = document.getElementById('hero-rating-container');
                    if (heroContainer) {
                        const heroStarSvg = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>`;
                        const heroFilledStarSvg = `<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>`;
                        heroContainer.innerHTML = `
                            <div style='display:flex; align-items:center; gap:10px;'>
                                <div style='position: relative; display: inline-flex; width: 100px; height: 20px;'>
                                    <div style='display: flex; position: absolute; top:0; left:0; color: gray; opacity: 0.3;'>${heroStarSvg.repeat(5)}</div>
                                    <div id="hero-rating-stars-fill" style='display: flex; position: absolute; top:0; left:0; color: #fbbf24; overflow: hidden; width: ${pct}; transition: width 0.5s ease-out;'>
                                        <div style='display:flex; width: 100px;'>${heroFilledStarSvg.repeat(5)}</div>
                                    </div>
                                </div>
                                <span style='color:var(--text-color); font-size:1.1rem; font-weight:700;'><span id="hero-rating-text">${avg}</span> <span style='font-size:0.9rem; font-weight:normal; opacity:0.7;'>/ 5</span></span>
                            </div>
                        `;
                    }
                }

                // Gather reaction counts
                const reactionCounts = {};
                for (const [key, obj] of Object.entries(reactions)) {
                    reactionCounts[key] = obj.count || 0;
                }

                if (votes > 0) {
                    const avg = (points / votes).toFixed(1);
                    renderSummaryPanel(avg, votes, reactionCounts);

                    // Cache to server
                    const idPrograma = <?= $id ?>;
                    fetch('../admin/actualizar_cache.php?id=' + idPrograma + '&estrellas=' + avg)
                        .then(() => console.log('Caché de estrellas actualizado en BD'));
                } else {
                    renderSummaryPanel(null, 0, {});
                }
            }
        });

        // Manejo de tema dinámico (preservando document.documentElement para Encabezado.php)
        function updateGiscusTheme() {
            const theme = document.documentElement.getAttribute('data-theme') === 'light' ? 'gruvbox_light' : 'dark';
            const iframe = document.querySelector('iframe.giscus-frame');
            if (!iframe) return;
            iframe.contentWindow.postMessage({ giscus: { setConfig: { theme } } }, 'https://giscus.app');
        }

        const giscusObserver = new MutationObserver(updateGiscusTheme);
        giscusObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });

        // Cargar Giscus dinámicamente con el tema correcto desde localStorage
        (function () {
            const savedTheme = localStorage.getItem('theme');
            const giscusTheme = savedTheme === 'light' ? 'gruvbox_light' : 'dark';

            const script = document.createElement('script');
            script.src = 'https://giscus.app/client.js';
            script.setAttribute('data-repo', 'Boosham/Boosham-Blog');
            script.setAttribute('data-repo-id', 'R_kgDOR04UGQ');
            script.setAttribute('data-category', 'Blog');
            script.setAttribute('data-category-id', 'DIC_kwDOR04UGc4C5oO8');
            script.setAttribute('data-mapping', 'specific');
            script.setAttribute('data-term', 'Programa-ID-<?= $id ?>');
            script.setAttribute('data-strict', '1');
            script.setAttribute('data-reactions-enabled', '1');
            script.setAttribute('data-emit-metadata', '1'); // <-- Cambiado a 1 para reacciones
            script.setAttribute('data-input-position', 'top');
            script.setAttribute('data-theme', giscusTheme);
            script.setAttribute('data-lang', 'es');
            script.setAttribute('crossorigin', 'anonymous');
            script.async = true;

            document.querySelector('.giscus').appendChild(script);
        })();
    </script>

    <script>
        /* Lógica de puntos interactivos — idéntica al index */
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
                let color = 'rgba(255, 140, 0, 0.15)';
                if (dist < 180) {
                    const ratio = (180 - dist) / 180;
                    size = p.baseSize + (ratio * 4.5);
                    color = `rgba(255, 140, 0, ${0.15 + ratio * 0.7})`;
                }
                ctx.fillStyle = color;
                ctx.beginPath(); ctx.arc(p.x, p.y, size, 0, Math.PI * 2); ctx.fill();
            });
            requestAnimationFrame(animate);
        }
        window.addEventListener('resize', initCanvas);
        initCanvas(); animate();
    </script>

    <script>
        /* Arrow buttons + Lightbox */
        document.addEventListener("DOMContentLoaded", () => {
            const ul = document.getElementById("cover-flow-cards");
            const prevBtn = document.getElementById("prevBtn");
            const nextBtn = document.getElementById("nextBtn");
            const items = ul ? Array.from(ul.querySelectorAll("li")) : [];


            function updateFlow() {
                if (!ul || items.length < 2) return;
                const containerCenter = ul.scrollLeft + ul.clientWidth / 2;
                // Sensibilidad ajustada para una transición más suave
                const maxRange = ul.clientWidth / 1.5;

                items.forEach(li => {
                    const img = li.querySelector("img");
                    if (!img) return;

                    const rect = li.getBoundingClientRect();
                    const containerRect = ul.getBoundingClientRect();
                    const liCenter = rect.left + rect.width / 2;
                    const containerVisualCenter = containerRect.left + containerRect.width / 2;

                    const dist = liCenter - containerVisualCenter;
                    const absDist = Math.abs(dist);

                    // Ratio: 1 en el centro, 0 lejos
                    const ratio = Math.max(0, 1 - absDist / maxRange);

                    // Solo escalar sin rotar para mantener la proporción 16:9 real
                    const scale = 0.8 + (0.2 * ratio); // 0.8 lateral, 1.0 centro
                    const brightness = 0.4 + (0.6 * ratio); // 0.4 lateral, 1.0 centro
                    const zIndex = Math.round(ratio * 100);

                    // Efecto overlap acercando las tarjetas laterales hacia el centro
                    const translateX = dist < 0 ? (1 - ratio) * 15 : (1 - ratio) * -15;

                    li.style.zIndex = zIndex;
                    // translateZ(0) mantiene el elemento en una capa de hardware estable
                    img.style.transform = `translateX(${translateX}%) scale(${scale}) translateZ(0)`;
                    img.style.filter = `brightness(${brightness})`;
                });
            }

            if (ul && items.length > 1) {
                ul.addEventListener("scroll", updateFlow, { passive: true });
                window.addEventListener("resize", updateFlow);
                updateFlow(); // Init
            }

            if (ul && prevBtn) {
                prevBtn.addEventListener("click", () => {
                    ul.scrollBy({ left: -(ul.querySelector('li')?.offsetWidth || 250), behavior: "smooth" });
                });
            }
            if (ul && nextBtn) {
                nextBtn.addEventListener("click", () => {
                    ul.scrollBy({ left: (ul.querySelector('li')?.offsetWidth || 250), behavior: "smooth" });
                });
            }

            // ---------- LIGHTBOX ----------

            const lightbox = document.getElementById("screenshot-lightbox");
            const lbImg = document.getElementById("lightbox-img");
            const closeBtn = document.getElementById("lightbox-close");

            function openLightbox(src) {
                if (!lightbox || !lbImg) return;
                lbImg.src = src;
                lightbox.classList.remove("closing"); // reset in case it was mid-close
                lightbox.classList.add("open");
                document.body.classList.add("lightbox-open"); // hides header
            }
            function closeLightbox() {
                if (!lightbox || !lightbox.classList.contains("open")) return;
                lightbox.classList.add("closing");
                // Wait for the lb-out animation (220ms) then hide
                const img = lightbox.querySelector("img");
                function onDone() {
                    lightbox.classList.remove("open", "closing");
                    document.body.classList.remove("lightbox-open"); // restore header
                    if (lbImg) lbImg.src = "";
                    if (img) img.removeEventListener("animationend", onDone);
                }
                if (img) {
                    img.addEventListener("animationend", onDone, { once: true });
                } else {
                    setTimeout(onDone, 230);
                }
            }

            // Click any cover-flow image to open lightbox
            const ul2 = document.getElementById("cover-flow-cards");
            if (ul2) {
                ul2.querySelectorAll("li img").forEach(img => {
                    img.addEventListener("click", () => openLightbox(img.dataset.src || img.src));
                });
            }

            // Close button (stopPropagation prevents accidental re-open from bubbling)
            if (closeBtn) closeBtn.addEventListener("click", (e) => { e.stopPropagation(); closeLightbox(); });

            // Click on the dark backdrop to close
            if (lightbox) lightbox.addEventListener("click", (e) => { if (e.target === lightbox) closeLightbox(); });

            // Escape key
            document.addEventListener("keydown", (e) => {
                if (e.key === "Escape" && lightbox && lightbox.classList.contains("open")) closeLightbox();
            });
        });
    </script>

</body>

</html>