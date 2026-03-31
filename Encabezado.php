<?php
// Detectar la ruta base relativa según desde donde se incluye el archivo
$encabezado_base = '';
if (
    strpos($_SERVER['SCRIPT_FILENAME'], DIRECTORY_SEPARATOR . 'programa' . DIRECTORY_SEPARATOR) !== false ||
    strpos($_SERVER['SCRIPT_FILENAME'], DIRECTORY_SEPARATOR . 'catalog' . DIRECTORY_SEPARATOR) !== false
) {
    $encabezado_base = '../';
}
?>
<style>
    :root {
        --orange-btn: #F97316;
        --bg-color: #050505;
        --text-color: #ededed;
        --text-muted: #a1a1aa;
        --hero-subtitle: #a1a1aa;
        --card-bg: #151515;
        --carousel-bg: #151515;
        --card-border: #262626;
        --card-text: #e0e0e0;
        --card-bg-hover: #1a1a1a;
        --header-bg: rgba(15, 15, 15, 0.6);
        --header-border: rgba(255, 255, 255, 0.08);
        --header-text: rgba(255, 255, 255, 0.7);
        --header-text-hover: #ffffff;
        --search-bg: rgba(255, 255, 255, 0.05);
        --search-text: rgba(255, 255, 255, 0.5);
        --search-bg-focus: rgba(255, 255, 255, 0.15);
        --btn-reg-bg: rgba(255, 255, 255, 0.05);
        --btn-reg-text: rgba(255, 255, 255, 0.8);
        --dropdown-bg: rgba(20, 20, 20, 0.95);
        --logo-filter: invert(0);
        --hero-bg: url('<?= $encabezado_base ?>fondos/wallpaper-index-dark.png');
        --footer-bg: rgba(15, 15, 15, 0.4);
        --footer-border: rgba(255, 255, 255, 0.08);
        --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        --hero-logo-bg: transparent;
        --hero-logo-shadow: none;
        --hero-text-bg: #151515;
        --hero-text-shadow: none;
        --hero-gradient-overlay: linear-gradient(to bottom, rgba(5, 5, 5, 0) 0%, rgba(5, 5, 5, 0.7) 100%);
    }


    [data-theme="light"] {
        /* FONDO PRINCIPAL - Crema calido que complementa la madera del barco */
        --bg-color: #ebe5dc;

        /* TEXTO - Azul marino oscuro para legibilidad */
        --text-color: #1a2d3d;
        --text-muted: #4a5c6a;

        /* HERO - Color oscuro con buena legibilidad */
        --hero-subtitle: #1a2d3d;

        /* CARDS - Crema claro (más clarito pero no blanco) */
        --card-bg: #f0e6d2;
        --carousel-bg: #f0e6d2;
        --card-border: #d4cfc7;
        --card-text: #2c3e4a;
        --card-bg-hover: #faf3e3;

        /* HEADER */
        --header-bg: rgba(235, 229, 220, 0.85);
        --header-border: rgba(180, 160, 140, 0.2);
        --header-text: #4a5c6a;
        --header-text-hover: #1a2d3d;

        /* BUSQUEDA - Crema claro transparente */
        --search-bg: rgba(245, 242, 237, 0.6);
        --search-text: #4a5c6a;
        --search-bg-focus: rgba(245, 242, 237, 0.9);

        /* BOTONES - Crema claro transparente */
        --btn-reg-bg: rgba(245, 242, 237, 0.6);
        --btn-reg-text: #4a5c6a;

        /* DROPDOWN - Crema claro transparente */
        --dropdown-bg: rgba(245, 242, 237, 0.95);

        /* LOGO - CSS Filter to make the original white SVG look like midnight blue (#1a2d3d) */
        --logo-filter: brightness(0) saturate(100%) invert(14%) sepia(40%) saturate(880%) hue-rotate(168deg) brightness(98%) contrast(93%);

        --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
        --hero-logo-bg: #ffffff;
        --hero-logo-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
        --hero-text-bg: rgba(245, 242, 237, 0.85);
        --hero-text-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        --hero-gradient-overlay: linear-gradient(to bottom, rgba(235, 229, 220, 0) 0%, rgba(235, 229, 220, 0.6) 100%);
        /* HERO BACKGROUND */
        --hero-bg: url('<?= $encabezado_base ?>fondos/wallpaper-index-light.png');

        /* FOOTER */
        --footer-bg: rgba(235, 229, 220, 0.9);
        --footer-border: rgba(180, 160, 140, 0.2);
    }



    /* Theme Toggle Button Styles */
    .theme-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--search-bg);
        border: 1px solid var(--header-border);
        color: var(--text-color);
        cursor: pointer;
        transition: 0.3s;
    }

    .theme-toggle:hover {
        background: rgba(128, 128, 128, 0.2);
    }

    /* Estilos extraídos literalmente de Encabezado.html proporcionado por el usuario */
    body {
        margin: 0;
        padding: 0;
        background-color: var(--bg-color);
        font-family: 'Inter', sans-serif;
        color: var(--text-color);
        transition: background-color 0.3s, color 0.3s;
    }


    /* Contenedor flotante */
    .header-wrapper {
        position: fixed;
        top: 25px;
        left: 0;
        right: 0;
        display: flex;
        justify-content: center;
        z-index: 1000;
        padding: 0 20px;
    }

    .glass-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        max-width: 900px;
        padding: 14px 30px;
        background: var(--header-bg);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid var(--header-border);
        border-radius: 100px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        gap: 12px;
    }

    /* Cuando la barra de búsqueda tiene foco (solo en header grande) el pill se expande */
    .glass-header:not(.scrolled):has(.search-bar:focus) {
        max-width: 975px;
    }

    /* Animación al bajar el scroll */
    .glass-header.scrolled {
        max-width: 750px;
        padding: 8px 20px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid var(--header-border);
    }

    .glass-header.scrolled .nav-menu {
        gap: 20px;
    }

    .glass-header.scrolled .nav-menu a {
        font-size: 0.8rem;
    }

    .glass-header.scrolled .search-bar {
        padding: 6px 30px 6px 12px;
        font-size: 0.78rem;
        width: 100px;
        /* Ancho base pequeño */
    }

    .glass-header.scrolled .search-bar:focus {
        width: 115px;
        /* Ajuste preciso para no solapar Blog */
    }

    .glass-header.scrolled .btn-register,
    .glass-header.scrolled .btn-cta {
        padding: 7px 18px;
        font-size: 0.78rem;
    }

    .glass-header.scrolled .header-actions {
        gap: 8px;
    }

    .logo {
        text-decoration: none;
        display: flex;
        align-items: center;
        flex-shrink: 0;
        /* El logo nunca se comprime */
    }

    .logo img {
        height: 30px;
        width: auto;
        transition: height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        filter: var(--logo-filter);
    }

    .glass-header.scrolled .logo img {
        height: 22px;
    }

    .nav-menu {
        display: flex;
        gap: 25px;
        position: relative;
    }

    .nav-menu>a,
    .nav-menu>.nav-dropdown {
        white-space: nowrap;
    }

    .nav-menu a {
        text-decoration: none;
        color: var(--header-text);
        font-size: 0.9rem;
        font-weight: 500;
        transition: 0.3s;
        white-space: nowrap; /* Mantiene el texto en una línea durante la animación */
    }

    .nav-menu a:hover {
        color: var(--text-color);
    }

    /* Dropdown de Programas */
    .nav-dropdown {
        position: relative;
    }

    .nav-dropdown-toggle {
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .nav-dropdown-toggle::after {
        content: '';
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 5px solid var(--header-text);
        transition: transform 0.3s;
    }

    .nav-dropdown:hover .nav-dropdown-toggle::after {
        transform: rotate(180deg);
    }

    .nav-dropdown-menu {
        position: absolute;
        top: calc(100% + 20px);
        left: 50%;
        transform: translateX(-50%);
        background: var(--dropdown-bg);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 12px;
        min-width: 220px;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s, visibility 0.3s, transform 0.3s;
        transform: translateX(-50%) translateY(10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
    }

    .nav-dropdown:hover .nav-dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(0);
    }

    .nav-dropdown-menu a {
        display: block;
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 0.85rem;
        color: var(--header-text);
        /* Usa la variable del tema — azul marino en light, blanco en dark */
        transition: background 0.2s, color 0.2s;
    }

    .nav-dropdown-menu a:hover {
        background: rgba(128, 128, 128, 0.12);
        color: var(--text-color);
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 12px; 
        flex-shrink: 0;
        white-space: nowrap;
    }

    .glass-header.scrolled .header-actions {
        gap: 8px; /* Restaurado el espaciado compacto original */
    }

    .search-container {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-bar {
        background: var(--search-bg);
        border: 1px solid var(--header-border);
        color: var(--text-color);
        padding: 8px 35px 8px 15px;
        border-radius: 50px;
        font-size: 0.85rem;
        outline: none;
        transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1), background 0.3s; /* Sincronizado con la animación del encabezado */
        width: 160px;
    }

    .search-bar::placeholder {
        color: var(--search-text);
    }

    /* Header grande: la barra de búsqueda se expande suavemente */
    .search-bar:focus {
        background: var(--search-bg-focus);
        border-color: rgba(255, 255, 255, 0.4);
        width: 180px; /* Ancho original restaurado */
    }

    /* En modo scrolled, el ancho se pisa arriba para ser más pequeño */

    .search-btn {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .search-btn svg {
        width: 16px;
        height: 16px;
        stroke: var(--text-color);
        transition: fill 0.3s;
    }

    .search-btn:hover svg {
        fill: var(--text-color);
    }

    /* Resultados de búsqueda */
    .search-results {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        background: var(--dropdown-bg);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        min-width: 300px;
        max-height: 350px;
        overflow-y: auto;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s, visibility 0.3s, transform 0.3s;
        transform: translateY(10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
        z-index: 1001;
    }

    .search-results.active {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .search-result-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        text-decoration: none;
        color: var(--text-color);
        transition: background 0.2s;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .search-result-item:last-child {
        border-bottom: none;
    }

    .search-result-item:first-child {
        border-radius: 16px 16px 0 0;
    }

    .search-result-item:last-child {
        border-radius: 0 0 16px 16px;
    }

    .search-result-item:only-child {
        border-radius: 16px;
    }

    .search-result-item:hover {
        background: rgba(255, 255, 255, 0.08);
    }

    .search-result-img {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        object-fit: cover;
        background: #222;
        border: 1px solid #333;
        flex-shrink: 0;
    }

    .search-result-info {
        flex: 1;
        min-width: 0;
    }

    .search-result-title {
        font-size: 0.9rem;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .search-result-desc {
        font-size: 0.75rem;
        color: #888;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .search-no-results {
        padding: 20px 16px;
        text-align: center;
        color: #888;
        font-size: 0.85rem;
    }


</style>

<div class="header-wrapper">
    <header class="glass-header" id="mainHeader">
        <a href="<?= $encabezado_base ?>index.php" class="logo">
            <img src="<?= $encabezado_base ?>logo/text_logo.svg" alt="Boosham Blog">
        </a>

        <nav class="nav-menu">
            <div class="nav-dropdown">
                <a href="<?= $encabezado_base ?>catalog/" class="nav-dropdown-toggle">Programas</a>
                <div class="nav-dropdown-menu">
                    <a href="<?= $encabezado_base ?>catalog/">Lorem Ipsum</a>
                    <a href="<?= $encabezado_base ?>catalog/">Dolor Sit Amet</a>
                    <a href="<?= $encabezado_base ?>catalog/">Consectetur</a>
                    <a href="<?= $encabezado_base ?>catalog/">Adipiscing Elit</a>
                    <a href="<?= $encabezado_base ?>catalog/">Sed Do Eiusmod</a>
                </div>
            </div>
            <a href="#">DMCA</a>
            <a href="#">Blog</a>
            <a href="#">Foro</a>
            <a href="#">Acerca De...</a>
        </nav>

        <div class="header-actions">
            <div class="theme-toggle" id="themeToggleBtn" aria-label="Cambiar Tema" title="Cambiar Tema">
                <svg id="icon-sun" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    style="display: none;">
                    <circle cx="12" cy="12" r="4" />
                    <path d="M12 2v2" />
                    <path d="M12 20v2" />
                    <path d="m4.93 4.93 1.41 1.41" />
                    <path d="m17.66 17.66 1.41 1.41" />
                    <path d="M2 12h2" />
                    <path d="M20 12h2" />
                    <path d="m6.34 17.66-1.41 1.41" />
                    <path d="m19.07 4.93-1.41 1.41" />
                </svg>
                <svg id="icon-moon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
                </svg>
            </div>
            <div class="search-container">
                <input type="text" class="search-bar" id="headerSearchInput" placeholder="Buscar programas..."
                    autocomplete="off">
                <button class="search-btn" id="headerSearchBtn" type="button" aria-label="Buscar">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" />
                    </svg>
                </button>
                <div class="search-results" id="searchResults"></div>
            </div>
        </div>
    </header>
</div>

<script>
    // Script original para el scroll
    window.addEventListener('scroll', () => {
        const header = document.getElementById('mainHeader');
        if (window.scrollY > 60) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // Live search
    (function () {
        const input = document.getElementById('headerSearchInput');
        const results = document.getElementById('searchResults');
        const searchBtn = document.getElementById('headerSearchBtn');
        const base = '<?= $encabezado_base ?>';
        let debounceTimer;

        function doSearch() {
            const q = input.value.trim();
            if (q.length < 1) {
                results.classList.remove('active');
                results.innerHTML = '';
                return;
            }

            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                fetch(base + 'api/search.php?q=' + encodeURIComponent(q))
                    .then(r => r.json())
                    .then(data => {
                        if (data.length === 0) {
                            results.innerHTML = '<div class="search-no-results">No se encontraron resultados</div>';
                        } else {
                            results.innerHTML = data.map(p =>
                                `<a href="${base}programa/lista.php?id=${p.id}" class="search-result-item">
                                    <img src="${p.imagen_url}" alt="" class="search-result-img">
                                    <div class="search-result-info">
                                        <div class="search-result-title">${p.titulo}</div>
                                        <div class="search-result-desc">${p.descripcion}</div>
                                    </div>
                                </a>`
                            ).join('');
                        }
                        results.classList.add('active');
                    })
                    .catch(() => {
                        results.innerHTML = '<div class="search-no-results">Error al buscar</div>';
                        results.classList.add('active');
                    });
            }, 250);
        }

        input.addEventListener('input', doSearch);

        searchBtn.addEventListener('click', () => {
            const q = input.value.trim();
            if (q.length > 0) {
                window.location.href = base + 'catalog/?q=' + encodeURIComponent(q);
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                const q = input.value.trim();
                if (q.length > 0) {
                    window.location.href = base + 'catalog/?q=' + encodeURIComponent(q);
                }
            }
        });

        // Cerrar resultados al hacer clic fuera
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.search-container')) {
                results.classList.remove('active');
            }
        });
    })();

    // Theme logic
    (function () {
        const toggleBtn = document.getElementById('themeToggleBtn');
        const iconSun = document.getElementById('icon-sun');
        const iconMoon = document.getElementById('icon-moon');
        const htmlEl = document.documentElement;

        function setTheme(theme) {
            if (theme === 'light') {
                htmlEl.setAttribute('data-theme', 'light');
                iconMoon.style.display = 'none';
                iconSun.style.display = 'block';
            } else {
                htmlEl.removeAttribute('data-theme');
                iconSun.style.display = 'none';
                iconMoon.style.display = 'block';
            }
            localStorage.setItem('theme', theme);
        }

        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            setTheme(savedTheme);
        }

        toggleBtn.addEventListener('click', () => {
            const currentTheme = htmlEl.getAttribute('data-theme');
            if (currentTheme === 'light') {
                setTheme('dark');
            } else {
                setTheme('light');
            }
        });
    })();
</script>