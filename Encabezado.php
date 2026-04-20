<?php
// Utilizar ruta absoluta a la raíz para evitar problemas con URLs amigables profundas
$encabezado_base = '/';
?>

<link rel="stylesheet" href="<?= $encabezado_base ?>style.min.css">

<div class="header-wrapper">
    <header class="glass-header" id="mainHeader">
        <a href="<?= $encabezado_base ?>index.php" class="logo">
            <img width="300" height="30" src="<?= $encabezado_base ?>logo/text_logo.svg" alt="Boosham Blog">
        </a>

        <nav class="nav-menu" id="navMenu">
            <div class="nav-dropdown">
                <a href="<?= $encabezado_base ?>catalog/" class="nav-dropdown-toggle">Programas</a>
                <div class="nav-dropdown-menu">
                    <a href="<?= $encabezado_base ?>catalog/">Ver Todos</a>
                    <?php 
                    // Renderizamos las categorias dinamicamente si la conexion existe
                    if(isset($conn)) {
                        $nav_cat_query = $conn->query("SELECT nombre, slug FROM categorias ORDER BY nombre ASC");
                        if($nav_cat_query && $nav_cat_query->num_rows > 0) {
                            while($nav_cat = $nav_cat_query->fetch_assoc()) {
                                echo '<a href="' . $encabezado_base . 'catalog/?cat=' . htmlspecialchars($nav_cat['slug']) . '">' . htmlspecialchars($nav_cat['nombre']) . '</a>';
                            }
                        }
                    }
                    ?>
                </div>
            </div>
            <a href="#">DMCA</a>
            <a href="#">Blog</a>
            <a href="https://github.com/Boosham/Boosham-Blog/discussions/categories/foro">Foro</a>
            <a href="#">Acerca De...</a>
        </nav>

        <div class="header-actions">
            <button class="theme-toggle" id="themeToggleBtn" aria-label="Cambiar Tema" title="Cambiar Tema">
                <svg id="icon-sun" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
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
                <svg id="icon-moon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
                </svg>
            </button>

            <div class="search-container">
                <input type="text" class="search-bar" id="headerSearchInput" placeholder="Buscar..." autocomplete="off">
                <button class="search-btn" id="headerSearchBtn" aria-label="Buscar">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" />
                    </svg>
                </button>
                <div class="search-results" id="searchResults"></div>
            </div>

            <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Menú">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
        </div>
    </header>
</div>

<script>
    // Lógica para el scroll y tamaño responsivo (Reflow Optimizado)
    let ticking = false;

    function updateHeaderClass() {
        const header = document.getElementById('mainHeader');
        if (window.innerWidth <= 850) {
            header.classList.add('scrolled');
        } else {
            if (window.scrollY > 60) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }
        ticking = false;
    }

    function checkHeader() {
        if (!ticking) {
            window.requestAnimationFrame(updateHeaderClass);
            ticking = true;
        }
    }

    window.addEventListener('scroll', checkHeader, { passive: true });
    window.addEventListener('resize', checkHeader, { passive: true });
    document.addEventListener('DOMContentLoaded', updateHeaderClass);
    updateHeaderClass(); // Ejecutar inmediatamente por si ya cargó

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
                            results.innerHTML = data.map(p => {
                                let fUrl = `${base}programa/lista.php?id=${p.id}`; // fallback
                                if (p.fecha_creacion && p.slug) {
                                    let d = p.fecha_creacion.split(' ')[0].split('-');
                                    if(d.length === 3) {
                                        fUrl = `${base}programa/lista/${d[2]}/${d[1]}/${d[0]}/${p.slug}`;
                                    }
                                }
                                let imgPath = p.imagen_url ? p.imagen_url.replace('../', '/') : '';
                                return `<a href="${fUrl}" class="search-result-item">
                                    <img src="${imgPath}" alt="" class="search-result-img">
                                    <div class="search-result-info">
                                        <div class="search-result-title">${p.titulo}</div>
                                        <div class="search-result-desc">${p.descripcion}</div>
                                    </div>
                                </a>`;
                            }).join('');
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

    // Theme logic (Auto-detection + Cookie Persistence)
    (function () {
        const toggleBtn = document.getElementById('themeToggleBtn');
        const iconSun = document.getElementById('icon-sun');
        const iconMoon = document.getElementById('icon-moon');
        const htmlEl = document.documentElement;

        function setCookie(name, value, days) {
            let expires = "";
            if (days) {
                let date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/; SameSite=Lax";
        }

        function getCookie(name) {
            let nameEQ = name + "=";
            let ca = document.cookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        function applyTheme(theme) {
            if (theme === 'light') {
                htmlEl.setAttribute('data-theme', 'light');
                if (iconMoon) iconMoon.style.display = 'none';
                if (iconSun) iconSun.style.display = 'block';
            } else {
                htmlEl.removeAttribute('data-theme');
                if (iconSun) iconSun.style.display = 'none';
                if (iconMoon) iconMoon.style.display = 'block';
            }
        }

        const systemDark = window.matchMedia('(prefers-color-scheme: dark)');
        
        function initTheme() {
            const savedTheme = getCookie('theme');
            if (savedTheme) {
                applyTheme(savedTheme);
            } else {
                // Auto detect from system
                applyTheme(systemDark.matches ? 'dark' : 'light');
            }
        }

        // Update theme in real-time if system changes (only if no manual cookie is set)
        systemDark.addEventListener('change', e => {
            if (!getCookie('theme')) {
                applyTheme(e.matches ? 'dark' : 'light');
            }
        });

        initTheme();

        toggleBtn.addEventListener('click', () => {
            const isCurrentlyLight = htmlEl.getAttribute('data-theme') === 'light';
            const newTheme = isCurrentlyLight ? 'dark' : 'light';
            applyTheme(newTheme);
            setCookie('theme', newTheme, 365); // Persist for 1 year
        });
    })();

    // Lógica para menú móvil
    (function () {
        const btn = document.getElementById('mobileMenuBtn');
        const nav = document.getElementById('navMenu');

        if (btn && nav) {
            btn.addEventListener('click', () => {
                nav.classList.toggle('active');
            });
        }

        // Dropdown toggle en móvil
        const dropdowns = document.querySelectorAll('.nav-dropdown-toggle');
        dropdowns.forEach(dd => {
            dd.addEventListener('click', (e) => {
                if (window.innerWidth <= 850) {
                    e.preventDefault();
                    e.target.closest('.nav-dropdown').classList.toggle('active');
                }
            });
        });
    })();
</script>
