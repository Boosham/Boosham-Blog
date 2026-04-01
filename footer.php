<footer class="site-footer">
    <div class="footer-top">
        <div class="footer-logo">
            <div class="footer-logo-icon"></div>
            Boosham Blog
        </div>
        <div class="footer-status">
            CURRENT STATUS <span class="status-indicator"></span>
        </div>
    </div>

    <div class="footer-grid">
        <div>
            <h4>Learn</h4>
            <ul>
                <li>Developer guides</li>
                <li>SDK & API reference</li>
                <li>Samples</li>
                <li>Libraries</li>
                <li>GitHub</li>
            </ul>
        </div>
        <div>
            <h4>Stay connected</h4>
            <ul>
                <li>Check out the blog</li>
                <li>Find us on Reddit</li>
                <li>Follow on X</li>
                <li>Subscribe on YouTube</li>
            </ul>
        </div>
        <div>
            <h4>Support</h4>
            <ul>
                <li>Contact support</li>
                <li>Stack Overflow</li>
                <li>Slack community</li>
                <li>Release notes</li>
            </ul>
        </div>
        <div>
            <h4>Tools for developers</h4>
            <ul>
                <li>Android</li>
                <li>Chrome</li>
                <li>Firebase</li>
                <li>Google Cloud Platform</li>
            </ul>
        </div>
    </div>
    
    <div class="footer-bottom">
        <div class="footer-bottom-brand">
            <span class="brand-name">Boosham Blog</span>
            <span class="brand-desc">for Developers</span>
        </div>
        <div class="footer-bottom-links">
            <a href="#">Terms</a>
            <a href="#">Privacy</a>
            <a href="#">Manage Cookies</a>
        </div>
    </div>
</footer>

<style>
    .site-footer {
        background: var(--footer-bg); 
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--footer-border); 
        border-radius: 28px; 
        max-width: 1400px; 
        margin: 0 auto; 
        padding: 60px 50px; 
        box-shadow: 0 30px 60px rgba(0,0,0,0.2);
        position: relative;
        z-index: 10;
    }

    .footer-top {
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        margin-bottom: 70px;
    }

    .footer-logo {
        font-size: 1.8rem; 
        font-weight: 800; 
        color: var(--text-color); 
        display: flex; 
        align-items: center; 
        gap: 12px;
    }

    .footer-logo-icon {
        width: 32px; 
        height: 32px; 
        background: linear-gradient(135deg, #ff8c00, #ff4500); 
        border-radius: 8px;
    }

    .footer-status {
        color: var(--text-muted); 
        font-size: 0.7rem; 
        letter-spacing: 1.5px; 
        display: flex; 
        align-items: center; 
        gap: 10px; 
        font-weight: 600;
    }

    .status-indicator {
        width: 9px; 
        height: 9px; 
        background: #00ff88; 
        border-radius: 50%; 
        box-shadow: 0 0 12px #00ff88;
    }

    .footer-grid {
        display: grid; 
        grid-template-columns: repeat(4, 1fr); 
        gap: 50px; 
        margin-bottom: 90px;
    }

    .footer-grid h4 {
        color: var(--text-color); 
        font-size: 0.95rem; 
        margin-bottom: 25px; 
        font-weight: 700;
        margin-top: 0;
    }

    .footer-grid ul {
        list-style: none; 
        padding: 0; 
        color: var(--text-muted); 
        font-size: 0.85rem; 
        line-height: 2.4;
        margin: 0;
    }

    .footer-bottom {
        padding-top: 35px; 
        border-top: 1px solid var(--footer-border); 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        color: var(--text-muted); 
        font-size: 0.8rem;
    }

    .footer-bottom-brand {
        display: flex; 
        align-items: center; 
        gap: 15px;
    }

    .brand-name {
        color: var(--text-color); 
        font-weight: 700;
    }

    .brand-desc {
        color: var(--text-muted);
    }

    .footer-bottom-links {
        display: flex; 
        gap: 25px;
    }

    .footer-bottom-links a {
        color: var(--text-muted); 
        text-decoration: none; 
        transition: 0.2s;
    }

    /* Efecto hover sutil para los enlaces del footer */
    footer a:hover, footer li:hover {
        color: var(--orange-btn) !important;
        cursor: pointer;
    }

    /* --- RESPONSIVO --- */
    @media (max-width: 900px) {
        .footer-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
        }
    }

    @media (max-width: 600px) {
        .site-footer {
            padding: 40px 25px;
            border-radius: 20px;
        }
        
        .footer-top {
            flex-direction: column;
            gap: 20px;
            align-items: flex-start;
            margin-bottom: 40px;
        }
        
        .footer-grid {
            grid-template-columns: 1fr;
            gap: 30px;
            margin-bottom: 50px;
        }

        .footer-grid h4 {
            margin-bottom: 15px;
        }

        .footer-grid ul {
            line-height: 2.0;
        }
        
        .footer-bottom {
            flex-direction: column;
            gap: 20px;
            align-items: flex-start;
            padding-top: 25px;
        }
        
        .footer-bottom-brand {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
        
        .footer-bottom-links {
            flex-wrap: wrap;
            gap: 15px;
        }
    }
</style>