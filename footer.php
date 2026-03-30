<footer style="
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
">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 70px;">
        <div style="font-size: 1.8rem; font-weight: 800; color: var(--text-color); display: flex; align-items: center; gap: 12px;">
            <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #ff8c00, #ff4500); border-radius: 8px;"></div>
            Boosham Blog
        </div>
        <div style="color: var(--text-muted); font-size: 0.7rem; letter-spacing: 1.5px; display: flex; align-items: center; gap: 10px; font-weight: 600;">
            CURRENT STATUS <span style="width: 9px; height: 9px; background: #00ff88; border-radius: 50%; box-shadow: 0 0 12px #00ff88;"></span>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 50px; margin-bottom: 90px;">
        <div>
            <h4 style="color: var(--text-color); font-size: 0.95rem; margin-bottom: 25px; font-weight: 700;">Learn</h4>
            <ul style="list-style: none; padding: 0; color: var(--text-muted); font-size: 0.85rem; line-height: 2.4;">
                <li>Developer guides</li>
                <li>SDK & API reference</li>
                <li>Samples</li>
                <li>Libraries</li>
                <li>GitHub</li>
            </ul>
        </div>
        <div>
            <h4 style="color: var(--text-color); font-size: 0.95rem; margin-bottom: 25px; font-weight: 700;">Stay connected</h4>
            <ul style="list-style: none; padding: 0; color: var(--text-muted); font-size: 0.85rem; line-height: 2.4;">
                <li>Check out the blog</li>
                <li>Find us on Reddit</li>
                <li>Follow on X</li>
                <li>Subscribe on YouTube</li>
            </ul>
        </div>
        <div>
            <h4 style="color: var(--text-color); font-size: 0.95rem; margin-bottom: 25px; font-weight: 700;">Support</h4>
            <ul style="list-style: none; padding: 0; color: var(--text-muted); font-size: 0.85rem; line-height: 2.4;">
                <li>Contact support</li>
                <li>Stack Overflow</li>
                <li>Slack community</li>
                <li>Release notes</li>
            </ul>
        </div>
        <div>
            <h4 style="color: var(--text-color); font-size: 0.95rem; margin-bottom: 25px; font-weight: 700;">Tools for developers</h4>
            <ul style="list-style: none; padding: 0; color: var(--text-muted); font-size: 0.85rem; line-height: 2.4;">
                <li>Android</li>
                <li>Chrome</li>
                <li>Firebase</li>
                <li>Google Cloud Platform</li>
            </ul>
        </div>
    </div>
    
    <div style="padding-top: 35px; border-top: 1px solid var(--footer-border); display: flex; justify-content: space-between; align-items: center; color: var(--text-muted); font-size: 0.8rem;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <span style="color: var(--text-color); font-weight: 700;">Boosham Blog</span>
            <span style="color: var(--text-muted);">for Developers</span>
        </div>
        <div style="display: flex; gap: 25px;">
            <a href="#" style="color: var(--text-muted); text-decoration: none; transition: 0.2s;">Terms</a>
            <a href="#" style="color: var(--text-muted); text-decoration: none; transition: 0.2s;">Privacy</a>
            <a href="#" style="color: var(--text-muted); text-decoration: none; transition: 0.2s;">Manage Cookies</a>
        </div>
    </div>
</footer>

<style>
    /* Efecto hover sutil para los enlaces del footer */
    footer a:hover, footer li:hover {
        color: var(--orange-btn) !important;
        cursor: pointer;
    }
</style>