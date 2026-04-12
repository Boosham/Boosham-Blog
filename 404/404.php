<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BSOD 404 - Error de Sistema</title>
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <style>
        /* CSS INTEGRADO */
        body {
            font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;
            background: #3973aa;
            color: #fefeff;
            height: 100vh;
            margin: 0;
            overflow: hidden;
            /* Evita scroll innecesario */
        }

        #page {
            display: table;
            height: 100%;
            margin: 0 auto;
            width: 70%;
            font-size: 1.9vw;
        }

        #container {
            display: table-cell;
            vertical-align: middle;
        }

        h1,
        h2,
        h3,
        h4,
        h5 {
            font-weight: 300;
            padding: 0;
            margin: 25px 0;
            margin-top: 0;
        }

        h1 {
            font-size: 6.5em;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 1.5em;
        }

        h4 {
            font-size: 1.4em;
            line-height: 1.5em;
        }

        h5 {
            line-height: 1.1em;
            font-size: 1.3em;
        }

        #details {
            display: flex;
            flex-flow: row;
            flex-wrap: nowrap;
            padding-top: 10px;
        }

        #qr {
            flex: 0 1 auto;
        }

        #image {
            background: white;
            padding: 5px;
            line-height: 0;
        }

        #image img {
            width: 9.8em;
            height: 9.8em;
        }

        #stopcode {
            padding-left: 10px;
            flex: 1 1 auto;
        }

        @media (min-width: 840px) {
            #page {
                font-size: 140%;
                width: 800px;
            }
        }

        /* --- RESPONSIVO MÓVILES --- */
        @media (max-width: 839px) {
            #page {
                font-size: 18px;
                /* Tamaño absoluto, mucho más amigable */
                width: 90%;
            }

            h1 {
                font-size: 4.5em;
                /* Ligeramente más chico para que encaje mejor */
                margin-top: 10px;
            }

            #details {
                flex-direction: column;
                /* Apila QR sobre texto */
                align-items: center;
                text-align: center;
                gap: 15px;
            }

            #stopcode {
                padding-left: 0;
            }

            /* Ajuste juego móvil interactivo transparente */
            #mobile-controls {
                bottom: 20px;
                padding: 0 15px;
            }

            .mobile-button {
                width: 65px;
                height: 65px;
                font-size: 24px;
            }

            #m-left {
                left: 10px;
            }

            #m-right {
                left: 85px;
            }

            #m-fire {
                right: 10px;
            }

            #m-up {
                right: 10px;
                bottom: 95px;
            }
        }

        /* LOCAL CSS FOR SCORE BOX */
        #kickass-pointstab {
            position: fixed !important;
            bottom: 20px !important;
            right: 20px !important;
            background: rgba(0, 0, 0, 0.75) !important;
            padding: 15px 25px !important;
            border-radius: 12px !important;
            color: #fff !important;
            font-family: 'Segoe UI', Arial, sans-serif !important;
            z-index: 1000000 !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            min-width: 150px !important;
            text-align: center !important;
            backdrop-filter: blur(5px);
        }

        #kickass-points {
            font-size: 32px !important;
            font-weight: 800 !important;
            margin-bottom: 5px !important;
            display: block !important;
            color: #4CAF50 !important;
            /* Verde neón para el puntaje */
        }

        #kickass-esctoquit {
            font-size: 13px !important;
            opacity: 0.9 !important;
            display: block !important;
            letter-spacing: 0.5px !important;
        }

        /* Ocultar elementos innecesarios de KickAss */
        #kickass-hello-sunshine,
        #kickass-weapons-menu,
        #kickass-bomb-menu,
        #kickass-pointstab-menu,
        .kickass-share-buttons {
            display: none !important;
        }

        /* MOBILE CONTROLS CSS */
        #mobile-controls {
            position: fixed;
            bottom: 30px;
            left: 0;
            width: 100%;
            height: 180px;
            z-index: 2000000;
            pointer-events: none;
            display: flex;
            justify-content: space-between;
            padding: 0 40px;
            box-sizing: border-box;
        }

        .mobile-button {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            pointer-events: auto;
            user-select: none;
            -webkit-user-select: none;
            backdrop-filter: blur(5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .mobile-button:active {
            background: rgba(255, 255, 255, 0.4);
            transform: scale(0.9);
        }

        #m-left {
            position: absolute;
            bottom: 10px;
            left: 30px;
        }

        #m-right {
            position: absolute;
            bottom: 10px;
            left: 130px;
        }

        #m-up {
            position: absolute;
            bottom: 100px;
            right: 30px;
            background: rgba(76, 175, 80, 0.4);
        }

        #m-fire {
            position: absolute;
            bottom: 10px;
            right: 30px;
            background: rgba(244, 67, 54, 0.4);
        }
    </style>
</head>

<body>

    <!-- ESTRUCTURA HTML -->
    <div id="page">
        <div id="container">
            <h1>:(</h1>
            <h2>La URL solicitada no se encontró en la web, estamos recolectando la información del error, mientras
                tanto juega con la nave</h2>
            <h2><span id="percentage">0</span>% complete</h2>

            <div id="details">
                <div id="qr">
                    <div id="image">
                        <!-- Imagen del código QR -->
                        <img src="Codigo QR.png" alt="QR Code" />
                    </div>
                </div>
                <div id="stopcode">
                    <h4>
                        <a href='index.php'
                            style='color:white;text-decoration:underline;font-weight:bold;font-size:1.2em;z-index:9999;position:relative;'>Para
                            volver a la página principal clickea este botón</a>
                    </h4>
                    <h5>If you call a support person, give them this info:
                        <br />Código de Error: 404
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT INTEGRADO -->
    <script>
        var percentageElement = document.getElementById("percentage");
        var percentage = 0;

        function process() {
            // Suma un valor aleatorio entre 0 y 9
            percentage += Math.floor(Math.random() * 10);

            if (percentage > 100) {
                percentage = 100;
            }

            percentageElement.innerText = percentage;

            // Si no ha llegado a 100, sigue ejecutándose
            if (percentage < 100) {
                processInterval();
            }
        }

        function processInterval() {
            // Tiempo aleatorio entre 500ms y 1000ms para el siguiente incremento
            setTimeout(process, Math.random() * (1000 - 500) + 500);
        }

        // Iniciar el proceso al cargar la página
        processInterval();
    </script>

    <script src="404.js"></script>
    <script>
        // Generador de naves (Migrado desde 404.js)
        setInterval(function () {
            // Solo generamos si el juego está activo
            if (window.KICKASSGAME && window.KICKASSGAME.sessionManager && window.KICKASSGAME.sessionManager.isPlaying) {
                var img = document.createElement('img');
                img.src = 'naves.png'; // Ruta absoluta corregida
                img.className = 'naves-enemigas';
                img.style.position = 'absolute';
                img.style.left = Math.random() * (window.innerWidth - 100) + 'px';
                img.style.top = Math.random() * (window.innerHeight - 100) + 'px';
                img.style.width = '80px';
                img.style.zIndex = '900';
                img.style.pointerEvents = 'auto'; // Habilitado para que las balas (elementFromPoint) puedan chocar
                document.body.appendChild(img);

                // Registrar la nueva nave en el motor del juego
                if (window.KICKASSGAME && window.KICKASSGAME.bulletManager) {
                    window.KICKASSGAME.bulletManager.updateEnemyIndex();
                }
            }
        }, 3000);
    </script>
</body>

</html>