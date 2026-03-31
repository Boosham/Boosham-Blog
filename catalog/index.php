<?php
require_once '../config.php';

// Obtener programas
$programas = [];
$res = $conn->query("SELECT * FROM programas ORDER BY id DESC");
if($res){
    while($row = $res->fetch_assoc()){
        $programas[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Programas</title>
    <link rel="icon" type="image/svg+xml" href="../favicon.svg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            background-color: var(--bg-color); transition: 0.3s;
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
        .filters {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 50px;
        }
        .filter-btn {
            background: var(--card-bg-hover);
            border: 1px solid var(--card-border);
            color: var(--text-color);
            padding: 10px 25px;
            border-radius: 50px;
            cursor: pointer;
            transition: 0.3s;
            font-size: 0.95rem;
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

        /* LIGHT MODE OVERRIDES */
        [data-theme="light"] .program-icon {
            background-color: #b8a994 !important; /* Color madera para placeholders */
            border-color: #a3927b !important;
        }
    </style>
</head>
<body>

<?php include '../Encabezado.php'; ?>

<div class="catalog-container">
    <div class="catalog-header">
        <h1>Catálogo de Aplicaciones</h1>
        <p style="color: var(--text-muted); max-width: 600px; margin: 0 auto; font-size: 1.1rem;">Encuentra las mejores herramientas y programas para optimizar tu flujo de trabajo.</p>
    </div>

    <div class="filters">
        <button class="filter-btn active">Todas</button>
        <button class="filter-btn">Lorem</button>
        <button class="filter-btn">Ipsum</button>
        <button class="filter-btn">Dolor</button>
        <button class="filter-btn">Sit</button>
        <button class="filter-btn">Amet</button>
    </div>

    <div class="catalog-grid">
        <?php if(empty($programas)): ?>
            <p style="text-align: center; grid-column: 1 / -1; color: var(--text-muted);">No hay programas disponibles en este momento.</p>
        <?php else: ?>
            <?php foreach($programas as $p): ?>
                <?php 
                $desc = !empty($p['descripcion']) ? $p['descripcion'] : 'Sin descripción disponible.';
                
                $estrellas = isset($p['estrellas_cache']) ? (float)$p['estrellas_cache'] : 0;
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
                ?>
                <a href="../programa/lista.php?id=<?= $p['id'] ?>" class="program-card">
                    <?php if(!empty($p['imagen_url'])): ?>
                        <img src="<?= htmlspecialchars($p['imagen_url']) ?>" alt="Icono" class="program-icon">
                    <?php else: ?>
                        <div class="program-icon" style="display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,0.3); font-size:2rem; font-weight:800;">???</div>
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

</body>
</html>
