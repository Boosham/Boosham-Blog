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
            align-items: center;
            gap: 40px;
            margin-bottom: 50px;
            background: var(--header-bg);
            border: 1px solid var(--header-border);
            border-radius: 20px;
            padding: 40px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
        }

        .hero-software img,
        .program-icon-placeholder {
            width: 200px;
            height: 200px;
            border-radius: 20px;
            object-fit: cover;
            border: 1px solid var(--header-border);
            background-color: var(--card-bg);
            /* Fondo base coherente con el tema */
        }

        .hero-details h1 {
            font-size: 3rem;
            margin: 0 0 15px 0;
            color: var(--text-color);
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
            /* El color madera que el usuario está editando */
            border-color: #a3927b !important;
            color: #bfa082 !important;
        }
    </style>
</head>

<body>

    <canvas id="dotsCanvas"></canvas>

    <?php include '../Encabezado.php'; ?>

    <div class="container">
        <div class="hero-software">
            <?php if (!empty($row['imagen_url']) && strpos($row['imagen_url'], 'via.placeholder') === false): ?>
                <img src="<?= htmlspecialchars($row['imagen_url']) ?>" alt="Icono del Software">
            <?php else: ?>
                <div class="program-icon-placeholder"
                    style="width:200px; height:200px; border-radius:20px; border:1px solid var(--card-border); display:flex; align-items:center; justify-content:center; font-size:3rem; font-weight:800; flex-shrink:0;">
                    ???</div>
            <?php endif; ?>
            <div class="hero-details">
                <h1><?= htmlspecialchars($row['titulo'] ?? 'Sin Título') ?></h1>
                <p><?= htmlspecialchars($row['descripcion'] ?? 'Sin descripción disponible.') ?></p>
            </div>
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
            <div class="section-card">
                <h2>Reviews / Calificaciones</h2>
                <p><?= htmlspecialchars(!empty($row['reviews']) ? $row['reviews'] : "★ ★ ★ ★ ★ (5 Estrellas)\nEs una maravilla técnica que cambiará vidas.") ?>
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

        <!-- Sección de Comentarios Giscus -->
        <div class="comments-container" style="max-width: 900px; margin: 60px auto 0; padding: 20px; background: var(--card-bg); border: 1px solid var(--header-border); border-radius: 20px; backdrop-filter: blur(10px);">
            <div class="giscus"></div>
        </div>

    </div>

    <div class="footer-wrapper">
        <?php include '../footer.php'; ?>
    </div>

    <script>
        function updateGiscusTheme() {
            const theme = document.documentElement.getAttribute('data-theme') === 'light' ? 'gruvbox_light' : 'dark';
            const iframe = document.querySelector('iframe.giscus-frame');
            if (!iframe) return;
            iframe.contentWindow.postMessage({ giscus: { setConfig: { theme } } }, 'https://giscus.app');
        }

        // Observar cambios de tema en <html> (controlado por Encabezado.php)
        const giscusObserver = new MutationObserver(updateGiscusTheme);
        giscusObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
    </script>

    <script>
        // Cargar Giscus dinámicamente con el tema correcto desde localStorage (mismo que Encabezado.php)
        (function() {
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
            script.setAttribute('data-emit-metadata', '0');
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

</body>

</html>