<?php
require_once '../../config.php';
require_once '../../lib/Parsedown.php';

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
$row = null;

if (isset($conn) && $slug !== '') {
    $stmt = $conn->prepare("
        SELECT p.titulo, d.html_content 
        FROM programas p 
        INNER JOIN descargas_programa d ON p.id = d.programa_id 
        WHERE p.slug = ? AND p.tiene_descargas = 1
    ");
    if ($stmt) {
        $stmt->bind_param("s", $slug);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
    }
}

if (empty($row)) {
    http_response_code(404);
    require_once '../../404/404.php';
    exit;
}

// Renderizar Markdown a HTML usando Parsedown
$Parsedown = new Parsedown();
$Parsedown->setSafeMode(false); // Permitir HTML embebido (botones, picture, etc.)
$Parsedown->setBreaksEnabled(true); 
$Parsedown->setUrlsLinked(true);
$renderedHtml = $Parsedown->text($row['html_content']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Descargas: <?= htmlspecialchars($row['titulo']) ?></title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800&display=swap" rel="stylesheet">
    <!-- GitHub Markdown CSS (Shared Root) -->
    <link rel="stylesheet" href="/css/github-markdown.css">
    <style>
        :root {
            --orange-btn: #ff8c00;
        }

        #dotsCanvas {
            position: fixed;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            z-index: 0;
            pointer-events: none;
        }

        body {
            margin: 0; padding: 0;
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

        .info-extra-content {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        [data-theme="light"] .info-extra-content {
            background: #ffffff;
            border-color: #e5e7eb;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
        }

        .markdown-body {
            background: transparent !important;
            color: inherit !important;
            font-size: 16px;
        }

        .markdown-body strong, .markdown-body b {
            font-weight: 700 !important;
            color: inherit !important;
        }

        /* Enlaces */
        .markdown-body a {
            color: #58a6ff !important;
            text-decoration: underline !important;
        }
        [data-theme="light"] .markdown-body a {
            color: #0969da !important;
        }

        /* Tablas y Code Blocks */
        .markdown-body table tr {
            background-color: #0d1117 !important;
            border-top: 1px solid #3d444d !important;
            color: #c9d1d9 !important;
        }
        .markdown-body table tr:nth-child(2n) {
            background-color: #161b22 !important;
        }
        .markdown-body table th, 
        .markdown-body table td {
            border: 1px solid #3d444d !important;
        }

        [data-theme="light"] .markdown-body table tr {
            background-color: #fff !important;
            border-top-color: #d1d9e0 !important;
            color: #1f2328 !important;
        }
        [data-theme="light"] .markdown-body table tr:nth-child(2n) {
            background-color: #f6f8fa !important;
        }

        /* Code blocks */
        .markdown-body pre {
            background-color: #161b22 !important;
            color: #e6edf3 !important;
            padding: 16px;
            border-radius: 8px;
            overflow: auto;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
        .markdown-body code {
            background-color: rgba(110, 118, 129, 0.4) !important;
            color: #e6edf3 !important;
            padding: 0.2em 0.4em;
            border-radius: 6px;
        }
        
        [data-theme="light"] .markdown-body pre {
            background-color: #f6f8fa !important;
            color: #1f2328 !important;
        }

        .markdown-body h1, .markdown-body h2 {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        [data-theme="light"] .markdown-body h1,
        [data-theme="light"] .markdown-body h2 {
            border-bottom: 1px solid rgba(0, 0, 0, 0.1) !important;
        }

        /* Centrado de titulo de pagina */
        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-color);
            margin: 0;
        }
    </style>
</head>

<body data-theme="dark">

    <canvas id="dotsCanvas"></canvas>

    <?php include '../../Encabezado.php'; ?>

    <div class="container">
        <div class="page-header">
            <h1>Descargar <?= htmlspecialchars($row['titulo']) ?></h1>
            <p style="opacity: 0.7; margin-top: 10px;">Selecciona uno de los métodos de descarga a continuación.</p>
        </div>

        <div class="info-extra-content">
            <article class="markdown-body">
                <?= $renderedHtml ?>
            </article>
        </div>
    </div>

    <div class="footer-wrapper" style="position: relative; z-index: 2; padding-top: 60px;">
        <?php include '../../footer.php'; ?>
    </div>

    <script>
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
