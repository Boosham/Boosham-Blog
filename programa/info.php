<?php
require_once '../config.php';

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
$row = null;

if (isset($conn) && $slug !== '') {
    $stmt = $conn->prepare("
        SELECT p.titulo, i.html_content 
        FROM programas p 
        INNER JOIN info_programa i ON p.id = i.programa_id 
        WHERE p.slug = ? AND p.tiene_info_extra = 1
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
    require_once '../404/404.php';
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Extra: <?= htmlspecialchars($row['titulo']) ?></title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --orange-btn: #ff8c00; }
        
        #dotsCanvas {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            z-index: 0; pointer-events: none;
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
            position: relative; z-index: 2;
            max-width: 1200px; margin: 0 auto;
            padding: 120px 20px 40px;
        }

        .footer-wrapper {
            position: relative; z-index: 2;
            padding: 60px 20px 40px;
        }

        /* Contenedor del contenido extra */
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
    </style>
</head>
<body data-theme="dark">

    <canvas id="dotsCanvas"></canvas>

    <?php include '../Encabezado.php'; ?>

    <div class="container">
        <!-- Render the injected HTML -->
        <div class="info-extra-content">
            <?= $row['html_content'] ?>
        </div>
    </div>

    <div class="footer-wrapper">
        <?php include '../footer.php'; ?>
    </div>

    <script>
        // Dots animation matching main site
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
