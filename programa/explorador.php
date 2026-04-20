<?php
if (!isset($conn)) {
    require_once '../config.php';
}

$items = [];
$current_path = 'programa/lista';

if ($dia == 0) {
    // Nivel 1: Listar días
    $res = $conn->query("SELECT DISTINCT DAY(fecha_creacion) as val FROM programas ORDER BY val ASC");
    if($res) {
        while($r = $res->fetch_assoc()) {
            if ($r['val']) {
                $d = str_pad($r['val'], 2, '0', STR_PAD_LEFT);
                $items[] = [
                    'name' => $d,
                    'type' => 'dir',
                    'link' => "/programa/lista/$d/",
                    'date' => '-'
                ];
            }
        }
    }
} elseif ($mes == 0) {
    // Nivel 2: Listar meses
    $d = str_pad($dia, 2, '0', STR_PAD_LEFT);
    $current_path .= "/$d";
    $stmt = $conn->prepare("SELECT DISTINCT MONTH(fecha_creacion) as val FROM programas WHERE DAY(fecha_creacion) = ? ORDER BY val ASC");
    $stmt->bind_param("i", $dia);
    $stmt->execute();
    $res = $stmt->get_result();
    while($r = $res->fetch_assoc()) {
        $m = str_pad($r['val'], 2, '0', STR_PAD_LEFT);
        $items[] = [
            'name' => $m,
            'type' => 'dir',
            'link' => "/programa/lista/$d/$m/",
            'date' => '-'
        ];
    }
    $stmt->close();
} elseif ($anio == 0) {
    // Nivel 3: Listar años
    $d = str_pad($dia, 2, '0', STR_PAD_LEFT);
    $m = str_pad($mes, 2, '0', STR_PAD_LEFT);
    $current_path .= "/$d/$m";
    $stmt = $conn->prepare("SELECT DISTINCT YEAR(fecha_creacion) as val FROM programas WHERE DAY(fecha_creacion) = ? AND MONTH(fecha_creacion) = ? ORDER BY val DESC");
    $stmt->bind_param("ii", $dia, $mes);
    $stmt->execute();
    $res = $stmt->get_result();
    while($r = $res->fetch_assoc()) {
        $a = $r['val'];
        $items[] = [
            'name' => $a,
            'type' => 'dir',
            'link' => "/programa/lista/$d/$m/$a/",
            'date' => '-'
        ];
    }
    $stmt->close();
} else {
    // Nivel 4: Listar programas
    $d = str_pad($dia, 2, '0', STR_PAD_LEFT);
    $m = str_pad($mes, 2, '0', STR_PAD_LEFT);
    $current_path .= "/$d/$m/$anio";
    $stmt = $conn->prepare("SELECT slug, titulo, fecha_creacion FROM programas WHERE DAY(fecha_creacion) = ? AND MONTH(fecha_creacion) = ? AND YEAR(fecha_creacion) = ? ORDER BY titulo ASC");
    $stmt->bind_param("iii", $dia, $mes, $anio);
    $stmt->execute();
    $res = $stmt->get_result();
    while($r = $res->fetch_assoc()) {
        $items[] = [
            'name' => $r['slug'],
            'title' => $r['titulo'],
            'type' => 'dir',
            'link' => "/programa/lista/$d/$m/$anio/{$r['slug']}/",
            'date' => date('d/m/Y H:i', strtotime($r['fecha_creacion']))
        ];
    }
    $stmt->close();
}

$parent_link = '';
if ($anio > 0) {
    $parent_link = "/programa/lista/" . str_pad($dia, 2, '0', STR_PAD_LEFT) . "/" . str_pad($mes, 2, '0', STR_PAD_LEFT) . "/";
} elseif ($mes > 0) {
    $parent_link = "/programa/lista/" . str_pad($dia, 2, '0', STR_PAD_LEFT) . "/";
} elseif ($dia > 0) {
    $parent_link = "/programa/lista/";
}

?>
<!DOCTYPE html>
<html data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Índice de /<?php echo htmlspecialchars($current_path); ?>/</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body { font-size: 15px; color: #222; background: #F7F7F7; margin-top: 55px; }
        a, a:hover, a:visited, a:focus { text-decoration: none !important; }
        .filename, td, th { white-space: nowrap }
        .navbar-brand { font-weight: bold; }
        .go-back { font-size: 1.2em; color: #007bff; }
        .main-nav { padding: 0.2rem 1rem; box-shadow: 0 4px 5px 0 rgba(0,0,0,.14), 0 1px 10px 0 rgba(0,0,0,.12), 0 2px 4px -1px rgba(0,0,0,.2); background: white; }
        .table td, .table th { vertical-align: middle !important; }
        .table-sm td, .table-sm th { padding: .4rem; }
        .table-bordered td, .table-bordered th { border: 1px solid #f1f1f1; }
        .filename { max-width: 420px; overflow: hidden; text-overflow: ellipsis; }
        .filename a { color: #222222; }
        i.fa.fa-folder-o { color: #0157b3; margin-right: 5px; }
        i.fa.fa-file-code-o { color: #8892BF; margin-right: 5px; }
        .inline-actions>a>i { font-size: 1em; margin-left: 5px; background: #3785c1; color: #fff; padding: 3px 4px; border-radius: 3px; }
        .bread-crumb { color: #cccccc; font-style: normal; }
        .path { margin-bottom: 10px; }
    </style>
</head>
<body class="navbar-fixed">

<nav class="navbar navbar-expand-lg navbar-light bg-light navbar-fixed-top main-nav fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="/programa/lista/">Índice de /<?php echo htmlspecialchars($current_path); ?>/</a>
    </div>
</nav>

<div class="container-fluid">
    <div class="path">
        <div class="card mb-2">
            <div class="card-body" style="padding: 10px 15px;">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/programa/lista/"><i class="fa fa-home"></i></a></li>
                        <?php 
                        $paths = explode('/', $current_path);
                        $built = '';
                        foreach($paths as $p) {
                            if ($p == 'programa' || $p == 'lista') continue;
                            $built .= "/$p";
                            echo '<li class="breadcrumb-item"><a href="/programa/lista'.$built.'/">'.htmlspecialchars($p).'</a></li>';
                        }
                        ?>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm">
            <thead class="thead-white">
                <tr>
                    <th style="width: 70%">Name</th>
                    <th style="width: 25%">Modified</th>
                    <th style="width: 5%">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($parent_link !== ''): ?>
                <tr>
                    <td class="border-0"><a href="<?php echo $parent_link; ?>"><i class="fa fa-chevron-circle-left go-back"></i> ..</a></td>
                    <td class="border-0"></td>
                    <td class="border-0"></td>
                </tr>
                <?php endif; ?>

                <?php foreach($items as $i): ?>
                <tr>
                    <td>
                        <div class="filename">
                            <?php if ($i['type'] === 'dir'): ?>
                                <a href="<?php echo htmlspecialchars($i['link']); ?>"><i class="fa fa-folder-o"></i> <?php echo htmlspecialchars($i['name']); ?></a>
                            <?php else: ?>
                                <a href="<?php echo htmlspecialchars($i['link']); ?>" title="<?php echo htmlspecialchars($i['title']); ?>">
                                    <!-- PHP elefante usando ruta raw o fa-file-code-o en FontAwesome 4-->
                                    <i class="fa fa-file-code-o"></i> 
                                    <?php echo htmlspecialchars($i['name']); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td><?php echo $i['date']; ?></td>
                    <td class="inline-actions">
                        <a title="DirectLink" href="<?php echo htmlspecialchars($i['link']); ?>"><i class="fa fa-link" aria-hidden="true"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <?php if (empty($items)): ?>
                <tr>
                    <td colspan="3"><em>Folder is empty</em></td>
                </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td class="gray fs-7" colspan="3">
                        <span class="badge text-bg-light border-radius-0"><?php echo count($items); ?> items</span>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

</body>
</html>
