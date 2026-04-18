(function (window) {
    var JSONP = { get: function() {} }; 
    var CORS = { request: function() {}, calledByExtension: function() { return false; }, _callbacks: {}, _callbackId: 0 };
    
    function getGlobalNamespace() { return window && window.INSTALL_SCOPE ? window.INSTALL_SCOPE : window; }
    
    var Class = function (methods) {
        var ret = function () {
            if (ret.$prototyping) return this; 
            if (typeof this.initialize == 'function')
                return this.initialize.apply(this, arguments);
        }; 
        if (methods.Extends) { 
            ret.parent = methods.Extends; 
            methods.Extends.$prototyping = true; 
            ret.prototype = new methods.Extends; 
            methods.Extends.$prototyping = false; 
        }
        for (var key in methods) if (methods.hasOwnProperty(key))
            ret.prototype[key] = methods[key]; 
        return ret;
    };

    var Vector = new Class({
        initialize: function (x, y) { if (typeof x == 'object') { this.x = x.x; this.y = x.y; } else { this.x = x; this.y = y; } }, 
        cp: function () { return new Vector(this.x, this.y); }, 
        mul: function (factor) { this.x *= factor; this.y *= factor; return this; }, 
        mulNew: function (factor) { return new Vector(this.x * factor, this.y * factor); }, 
        div: function (factor) { this.x /= factor; this.y /= factor; return this; }, 
        divNew: function (factor) { return new Vector(this.x / factor, this.y / factor); }, 
        add: function (vec) { this.x += vec.x; this.y += vec.y; return this; }, 
        addNew: function (vec) { return new Vector(this.x + vec.x, this.y + vec.y); }, 
        sub: function (vec) { this.x -= vec.x; this.y -= vec.y; return this; }, 
        subNew: function (vec) { return new Vector(this.x - vec.x, this.y - vec.y); }, 
        rotate: function (angle) { var x = this.x, y = this.y; this.x = x * Math.cos(angle) - Math.sin(angle) * y; this.y = x * Math.sin(angle) + Math.cos(angle) * y; return this; }, 
        rotateNew: function (angle) { return this.cp().rotate(angle); }, 
        setAngle: function (angle) { var l = this.len(); this.x = Math.cos(angle) * l; this.y = Math.sin(angle) * l; return this; }, 
        setAngleNew: function (angle) { return this.cp().setAngle(angle); }, 
        setLength: function (length) { var l = this.len(); if (l) this.mul(length / l); else this.x = this.y = length; return this; }, 
        setLengthNew: function (length) { return this.cp().setLength(length); }, 
        normalize: function () { var l = this.len(); if (l == 0) return this; this.x /= l; this.y /= l; return this; }, 
        normalizeNew: function () { return this.cp().normalize(); }, 
        angle: function () { return Math.atan2(this.y, this.x); }, 
        collidesWith: function (rect) { return this.x > rect.x && this.y > rect.y && this.x < rect.x + rect.width && this.y < rect.y + rect.height; }, 
        len: function () { var l = Math.sqrt(this.x * this.x + this.y * this.y); if (l < 0.005 && l > -0.005) return 0; return l; }, 
        is: function (test) { return typeof test == 'object' && this.x == test.x && this.y == test.y; }, 
        dot: function (v2) { return this.x * v2.x + this.y * v2.y; }, 
        distanceFrom: function (vec) { return Math.sqrt(Math.pow((this.x - vec.x), 2), Math.pow(this.y - vec.y, 2)); }
    });

    var Rect = new Class({ 
        initialize: function (x, y, w, h) { this.pos = new Vector(x, y); this.size = { width: w, height: h }; }, 
        hasPoint: function (point) { return point.x > this.getLeft() && point.x < this.getRight() && point.y > this.getTop() && point.y < this.getBottom(); }, 
        setLeft: function (left) { this.pos.x = left + this.size.width / 2; }, 
        setTop: function (top) { this.pos.y = top + this.size.height / 2; }, 
        getLeft: function () { return this.pos.x - this.size.width / 2; }, 
        getTop: function () { return this.pos.y - this.size.height / 2; }, 
        getRight: function () { return this.pos.x + this.size.width / 2; }, 
        getBottom: function () { return this.pos.y + this.size.height / 2; }, 
        cp: function () { return new Rect(this.pos.x, this.pos.y, this.size.width, this.size.height); } 
    });

    var Fx = new Class({
        initialize: function () { this.listeners = []; this.tweens = {}; this.running = {}; }, 
        addListener: function (listener) { this.listeners.push(listener); }, 
        add: function (key, props) {
            props = props || {}; props.duration = props.duration || 500; props.transition = props.transition || Tween.Linear; 
            if (!props.tweens) { var start = props.start || 0; var end = typeof props.end == 'undefined' ? 1 : props.end; props.tweens = [[start, end]]; }
            this.tweens[key] = props;
        }, 
        update: function (time) {
            time = typeof time === 'number' ? time : now(); 
            for (var key in this.tweens) {
                if (!this.running[key]) { this.tweenStart(key, time); continue; }
                var tween = this.tweens[key]; var tdelta = time - this.running[key].startTime; 
                if (tdelta > tween.duration) { this.tweenFinished(tween, key); continue; }
                var delta = tween.transition(tdelta / tween.duration); 
                var changes = []; for (var i = 0, t; t = tween.tweens[i]; i++) { var x = delta * (t[1] - t[0]) + t[0]; changes.push(x); }
                this.fire(key, changes, delta);
            }
        }, 
        tweenStart: function (key, time) {
            this.running[key] = { startTime: time }; var values = []; for (var i = 0, tween; tween = this.tweens[key].tweens[i]; i++) values.push(tween[0]); this.fire(key, values, 0);
        }, 
        tweenFinished: function (tween, key) {
            var values = []; for (var i = 0, t; t = tween.tweens[i]; i++) values.push(t[1]); this.fire(key, values, 1); if (!tween.repeats) { delete this.running[key]; delete this.tweens[key]; return; }
            this.tweenStart(key, now());
        }, 
        fire: function (key, values, delta) {
            for (var i = 0, listener; listener = this.listeners[i]; i++) listener.set.call(listener, key, values, delta);
        }
    }); 
    
    var Tween = { Linear: function (x) { return x; }, Quadratic: function (x) { return x * x; }, Quintic: function (x) { return x * x * x; } };

    var GameGlobals = { 
        FPS: 60, 
        useAnimationFrame: false, 
        path: function () { 
            var args = Array.prototype.slice.call(arguments).join("");
            return "/404/" + args; 
        }, 
        hasCanvas: true, 
        bulletColor: 'white' 
    }; 
    window.GameGlobals = GameGlobals;

    function now() { return (new Date()).getTime(); }
    function bind(bound, func) { return function () { return func.apply(bound, arguments); }; }
    function stopEvent(e) { if (e.stopPropogation) e.stopPropogation(); if (e.preventDefault) e.preventDefault(); e.returnValue = false; }
    function code(name) { var table = { 38: 'up', 40: 'down', 37: 'left', 39: 'right', 27: 'esc' }; if (table[name]) return table[name]; return String.fromCharCode(name); }
    function random(min, max) { return Math.floor(Math.random() * (max - min + 1) + min); }
    
    function getRect(element) {
        var rect = element.getBoundingClientRect(); 
        var sx = window.pageXOffset; 
        var sy = window.pageYOffset; 
        return { width: rect.width, height: rect.height, left: rect.left + sx, top: rect.top + sy };
    }

    function getScrollSize() { 
        var doc = document.documentElement; 
        var body = document.body; 
        return { x: Math.max(doc.scrollWidth, body.scrollWidth, doc.clientWidth), y: Math.max(doc.scrollHeight, body.scrollHeight, doc.clientHeight) }; 
    }

    function setStyles(element, props) {
        for (var key in props) {
            var val = props[key]; if (typeof val === "number" && key !== "opacity" && key !== "zIndex") val = val + 'px'; element.style[key] = val;
        }
    }

    function newElement(tag, props) {
        var el = document.createElement(tag); 
        for (var key in props) { if (key === 'styles') { setStyles(el, props[key]); } else { el[key] = props[key]; } }
        return el;
    }

    var requestAnimFrame = (function () { 
        return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || function (callback) { window.setTimeout(callback, 1000 / 60); }; 
    })();

    var KickAss = new Class({
        initialize: function (options) {
            this.players = []; this.elements = []; this.weaponClass = Weapons[1].cannonClass; 
            this.scrollPos = new Vector(0, 0); this.scrollSize = new Vector(0, 0); this.windowSize = { width: 0, height: 0 }; 
            this.updateWindowInfo(); 
            this.bulletManager = new BulletManager(this); 
            this.explosionManager = new ExplosionManager(this); 
            this.ui = new UIManager(this); 
            this.bombManager = new BombManager(this); 
            this.menuManager = new MenúManager(this); 
            this.menuManager.create(); 
            this.sessionManager = new SessionManager(this); 
            this.lastUpdate = now(); this.keyMap = {}; 
            this.keydownEvent = bind(this, this.keydown); 
            this.keyupEvent = bind(this, this.keyup); 
            this.multiplier = 10;
            addEvent(document, 'keydown', this.keydownEvent); 
            addEvent(document, 'keyup', this.keyupEvent);
        }, 
        begin: function () {
            this.addPlayer(); this.sessionManager.isPlaying = true; 
            if (GameGlobals.useAnimationFrame) { requestAnimFrame(bind(this, this.loop)); } 
            else { this.loopTimer = window.setInterval(bind(this, this.loop), 1000 / GameGlobals.FPS); }
        }, 
        keydown: function (e) {
            var c = code(e.keyCode); this.keyMap[c] = true; 
            if (['left', 'right', 'up', 'down', 'esc', ' '].indexOf(c) !== -1) stopEvent(e);
            // El juego ya no se destruye con Esc como solicitó el usuario
        }, 
        keyup: function (e) { var c = code(e.keyCode); this.keyMap[c] = false; }, 
        loop: function () {
            var currentTime = now(); var tdelta = (currentTime - this.lastUpdate) / 1000; 
            this.updateWindowInfo(); 
            for (var i = 0, player; player = this.players[i]; i++) { player.update(tdelta); }
            this.bulletManager.update(tdelta); this.bombManager.update(tdelta); 
            this.explosionManager.update(tdelta); this.ui.update(tdelta); 
            this.sessionManager.update(tdelta); this.lastUpdate = currentTime; 
            if (GameGlobals.useAnimationFrame) { requestAnimFrame(bind(this, this.loop)); }
        }, 
        addPlayer: function () {
            var player = new Player(this); player.setShip(Ships.Standard); this.players.push(player);
        }, 
        registerElement: function (el) { this.elements.push(el); }, 
        unregisterElement: function (el) { var idx = this.elements.indexOf(el); if (idx !== -1) this.elements.splice(idx, 1); }, 
        isKickAssElement: function (el) {
            for (var i = 0, element; element = this.elements[i]; i++) { if (el === element || (element.contains && element.contains(el))) return true; }
            return false;
        }, 
        isKeyPressed: function (key) { return !!this.keyMap[key]; }, 
        updateWindowInfo: function () {
            this.windowSize = { width: document.documentElement.clientWidth, height: document.documentElement.clientHeight }; 
            this.scrollPos.x = window.pageXOffset || document.documentElement.scrollLeft; 
            this.scrollPos.y = window.pageYOffset || document.documentElement.scrollTop; 
            this.scrollSize = getScrollSize();
        }, 
        hideAll: function () { for (var i = 0, el; el = this.elements[i]; i++) { el.style.visibility = 'hidden'; } }, 
        showAll: function () { for (var i = 0, el; el = this.elements[i]; i++) { el.style.visibility = 'visible'; } }, 
        changeWeapon: function (weapon) {
            this.weaponClass = weapon.cannonClass; 
            for (var i = 0, player; player = this.players[i]; i++) { player.setCannons(weapon.cannonClass); }
        }, 
        fireBomb: function () { this.bombManager.blow(); }, 
        destroy: function () {
            removeEvent(document, 'keydown', this.keydownEvent); removeEvent(document, 'keyup', this.keyupEvent); 
            for (var i = 0, player; player = this.players[i]; i++) { player.destroy(); }
            this.bulletManager.destroy(); this.explosionManager.destroy(); this.menuManager.destroy(); 
            if (!GameGlobals.useAnimationFrame) { clearInterval(this.loopTimer); }
            window.KICKASSGAME = false;
        }
    });

    var MenúManager = new Class({
        initialize: function (game) { this.game = game; this.numPoints = 0; }, 
        create: function () {
            this.container = document.createElement('div'); this.container.id = 'kickass-menu'; this.container.className = 'KICKASSELEMENT';
            document.body.appendChild(this.container); 
            this.container.innerHTML = '<div id="kickass-pointstab" class="KICKASSELEMENT">' +
                '<div id="kickass-pointstab-wrapper" class="KICKASSELEMENT">' + 
                '<div id="kickass-points" class="KICKASSELEMENT">' + this.numPoints + '</div>' + 
                '</div></div>';
            this.points = document.getElementById('kickass-points'); 
            this.game.registerElement(this.container);
            var all = this.container.getElementsByTagName('*'); for (var i = 0; i < all.length; i++) { this.game.registerElement(all[i]); }
        }, 
        showBombMenú: function () {}, hideBombMenú: function () {},
        addPoints: function (killed, pos) {
            var points = killed * this.game.multiplier; this.numPoints += points; this.points.innerHTML = this.numPoints; 
            this.game.ui.addPointsBubbleAt(pos, points);
        }, 
        destroy: function () {
            var all = this.container.getElementsByTagName('*'); for (var i = 0; i < all.length; i++) { this.game.unregisterElement(all[i]); }
            this.game.unregisterElement(this.container); this.container.parentNode.removeChild(this.container);
        }
    });

    var UIManager = new Class({
        initialize: function (game) { this.UNIQID = 0; this.game = game; this.pointBubbles = {}; this.fx = new Fx(); this.fx.addListener(this); },
        update: function (tdelta) { this.fx.update(); }, 
        set: function (key, value, delta) { 
            var id = key.split('-')[1]; 
            if (this.pointBubbles[id]) { 
                var bubble = this.pointBubbles[id]; 
                bubble.style.top = value[0] + 'px'; bubble.style.opacity = value[1]; 
                if (delta == 1 && bubble.parentNode) { bubble.parentNode.removeChild(bubble); delete this.pointBubbles[id]; } 
            }
        }, 
        addPointsBubbleAt: function (pos, points) { 
            var id = 'bubble' + (this.UNIQID++); var y = this.game.scrollPos.y + pos.y; 
            var bubble = newElement('span', { innerHTML: points, className: 'KICKASSELEMENT', styles: { position: 'absolute', font: "20px Arial", fontWeight: "bold", opacity: "1", color: "white", textShadow: "0 0 5px #000", top: y, zIndex: "10000000" } }); 
            bubble.style.left = pos.x + 'px'; document.body.appendChild(bubble); this.pointBubbles[id] = bubble; 
            this.fx.add('bubble-' + id, { tweens: [[y, y - 40], [1, 0]] }); 
        },
        showMessage: function() {} 
    });

    var BulletManager = new Class({
        initialize: function (game) { this.game = game; this.enemyIndex = []; this.nextUpdate = 0; }, 
        update: function (tdelta) {
            this.nextUpdate -= tdelta; if (this.nextUpdate < 0) { this.updateEnemyIndex(); }
        }, 
        updateEnemyIndex: function () {
            var all = document.getElementsByTagName('*'); this.enemyIndex = []; 
            for (var i = 0, el; el = all[i]; i++) { if (this.isDestroyable(el)) { this.enemyIndex.push(el); } }
            this.nextUpdate = 2.0;
        }, 
        isDestroyable: function (element) {
            if (element.nodeType !== 1 || element == document.documentElement || element == document.body) return false;
            if (element.className && typeof element.className === 'string' && element.className.indexOf('KICKASSELEMENT') !== -1) return false;
            if (element.style.visibility == 'hidden' || element.style.display == 'none') return false;
            if (element.tagName === 'IMG' && element.className.indexOf('naves-enemigas') !== -1) return true;
            return false; // Solo naves por ahora para evitar destruir la web accidentalmente
        }, 
        isDestroyableFromCollision: function (element) { 
             if (element.tagName === 'IMG' && element.className.indexOf('naves-enemigas') !== -1) return true;
             return false;
        },
        destroy: function () {}
    });

    var SessionManager = new Class({
        initialize: function (game) { this.isPlaying = false; }, 
        update: function (tdelta) {}
    });

    var ExplosionManager = new Class({
        initialize: function (game) { this.game = game; this.explosions = []; }, 
        update: function (tdelta) {
            var time = now(); for (var i = 0; i < this.explosions.length; i++) {
                var explosion = this.explosions[i];
                if (time - explosion.bornAt > 500) { explosion.destroy(); this.explosions.splice(i, 1); i--; continue; }
                explosion.update(tdelta);
            }
        }, 
        addExplosion: function (pos) { var explosion = new ParticleExplosion(pos); this.explosions.push(explosion); }, 
        destroy: function () { for (var i = 0; i < this.explosions.length; i++) this.explosions[i].destroy(); this.explosions = []; }
    });

    var Cannon = new Class({
        initialize: function (player, game, x, y, angle) { this.player = player; this.game = game; this.pos = new Vector(x, y); this.angle = angle || 0; }, 
        update: function (tdelta) { this.game.hideAll(); this.checkCollisions(tdelta); this.game.showAll(); }, 
        checkCollision: function (bullet) {
            var hit = bullet.checkCollision(); 
            if (!hit) return false; 
            this.game.explosionManager.addExplosion(bullet.pos); 
            this.game.menuManager.addPoints(10, bullet.pos); 
            if (hit.parentNode) hit.parentNode.removeChild(hit);
            return true;
        }, 
        createBullet: function (bulletClass) { 
            var pos = this.player.pos.cp().add(this.pos.cp().rotate(this.player.dir.angle() + Math.PI / 2)); 
            var dir = this.player.dir.cp().rotate(this.angle); 
            var bullet = new bulletClass(pos, dir); bullet.game = this.game; bullet.initCanvas(); 
            return bullet; 
        }
    });

    var BallCannon = new Class({
        Extends: Cannon, initialize: function () { Cannon.prototype.initialize.apply(this, arguments); this.lastFired = 0; this.bullets = []; }, 
        update: function (tdelta) { this.removeOld(); Cannon.prototype.update.call(this, tdelta); }, 
        removeOld: function () { var time = now(); for (var i = 0; i < this.bullets.length; i++) { if (time - this.bullets[i].bornAt > 2000) { this.bullets[i].destroy(); this.bullets.splice(i, 1); i--; } } }, 
        checkCollisions: function (tdelta) { for (var i = 0; i < this.bullets.length; i++) { var b = this.bullets[i]; b.update(tdelta); if (this.checkCollision(b)) { b.destroy(); this.bullets.splice(i, 1); i--; } } }, 
        shootPressed: function () { 
            if (now() - this.lastFired < 150) return; 
            if (this.bullets.length >= 10) return; // Limitar a 10 cohetes en pantalla
            this.lastFired = now(); 
            var b = this.createBullet(Bullet); 
            this.bullets.push(b); 
        },
        shootReleased: function () {},
        destroy: function () { for (var i = 0; i < this.bullets.length; i++) this.bullets[i].destroy(); this.bullets = []; }
    });

    var Bullet = new Class({
        initialize: function (pos, dir) { this.pos = pos.cp(); this.dir = dir; this.vel = new Vector(700, 700); this.bornAt = now(); }, 
        initCanvas: function () { this.sheet = new Sheet(new Rect(this.pos.x, this.pos.y, 5, 5)); this.sheet.drawBullet(); }, 
        update: function (tdelta) { this.pos.add(this.vel.setAngle(this.dir.angle()).mulNew(tdelta)); this.sheet.setPosition(this.pos); }, 
        checkCollision: function () {
            var element = document.elementFromPoint(this.pos.x, this.pos.y); 
            if (element && element.nodeType == 3) element = element.parentNode; 
            return (element && this.game.bulletManager.isDestroyableFromCollision(element)) ? element : false;
        }, 
        destroy: function () { this.sheet.destroy(); }
    });

    var ParticleExplosion = new Class({
        initialize: function (pos) { this.bornAt = now(); this.pos = pos.cp(); this.particles = []; this.generateParticles(); this.sheet = new Sheet(new Rect(pos.x, pos.y, 100, 100)); }, 
        update: function (tdelta) {
            for (var i = 0, p; p = this.particles[i]; i++) p.pos.add(p.vel.mulNew(tdelta));
            this.sheet.clear(); this.sheet.drawExplosion(this.particles);
        }, 
        generateParticles: function () { for (var i = 0; i < 20; i++) { this.particles.push({ vel: (new Vector(random(-100, 100), random(-100, 100))), pos: new Vector(0, 0), color: ['#FFD700', '#FF4500'][random(0, 1)] }); } }, 
        destroy: function () { this.sheet.destroy(); }
    });

    var Player = new Class({
        initialize: function (game) { 
            this.game = game; 
            this.pos = new Vector(200, 200); 
            this.vel = new Vector(0, 0); 
            this.acc = new Vector(0, 0); 
            this.dir = new Vector(0, 1); 
            this.currentRotation = 0; 
            this.friction = 0.85; // Slightly higher friction for smoother stopping
            this.terminalVelocity = 800; // Lower top speed
        }, 
        setShip: function (ship) {
            this.ship = ship; this.verts = []; for (var i = 0; i < ship.points.length; i++) this.verts.push(new Vector(ship.points[i][0], ship.points[i][1])); this.verts.push(this.verts[0]);
            this.cannons = []; for (var i = 0; i < ship.cannons.length; i++) { var c = ship.cannons[i]; var cannon = new BallCannon(this, this.game, c.p.x, c.p.y, c.a); this.cannons.push(cannon); }
            this.sheet = new Sheet(new Rect(100, 100, 50, 50));
        }, 
        setCannons: function(cannonClass) {},
        update: function (tdelta) {
            if (this.game.isKeyPressed('left')) this.currentRotation = -Math.PI * 1.5; // Lower rotation speed
            else if (this.game.isKeyPressed('right')) this.currentRotation = Math.PI * 1.5; 
            else this.currentRotation = 0;
            
            if (this.game.isKeyPressed('up')) this.acc = (new Vector(300, 0)).setAngle(this.dir.angle()); // Lower acceleration
            else this.acc = new Vector(0, 0);

            if (this.game.isKeyPressed(' ')) { for (var i = 0; i < this.cannons.length; i++) this.cannons[i].shootPressed(); }

            this.dir.setAngle(this.dir.angle() + this.currentRotation * tdelta); 
            var fAcc = this.acc.mulNew(tdelta).sub(this.vel.mulNew(tdelta * this.friction)); 
            this.vel.add(fAcc); this.pos.add(this.vel.mulNew(tdelta));
            this.checkBounds(); 
            this.sheet.clear(); this.sheet.setAngle(this.dir.angle() + Math.PI / 2); this.sheet.setPosition(this.pos); this.sheet.drawPlayer(this.verts);
            for (var i = 0; i < this.cannons.length; i++) this.cannons[i].update(tdelta);
        }, 
        checkBounds: function () {
            var w = this.game.windowSize.width; var h = this.game.windowSize.height;
            if (this.pos.x > w) this.pos.x = 0; else if (this.pos.x < 0) this.pos.x = w;
            if (this.pos.y > h) this.pos.y = 0; else if (this.pos.y < 0) this.pos.y = h;
        }, 
        destroy: function () { this.sheet.destroy(); }
    });

    var BombManager = new Class({ initialize: function (game) { this.game = game; }, update: function () {}, blow: function () {} });

    var SheetCanvas = new Class({
        initialize: function (rect) {
            this.canvas = document.createElement('canvas'); this.canvas.className = 'KICKASSELEMENT';
            setStyles(this.canvas, { position: 'absolute', zIndex: '1000000', pointerEvents: 'none' }); 
            this.ctx = this.canvas.getContext('2d'); this.rect = rect; this.updateCanvas(); document.body.appendChild(this.canvas);
        }, 
        updateCanvas: function () {
            if (this.canvas.width != this.rect.size.width) this.canvas.width = this.rect.size.width; 
            if (this.canvas.height != this.rect.size.height) this.canvas.height = this.rect.size.height; 
            this.canvas.style.left = (this.rect.pos.x - this.rect.size.width / 2) + 'px'; 
            this.canvas.style.top = (this.rect.pos.y - this.rect.size.height / 2) + 'px';
        }, 
        clear: function () { this.ctx.clearRect(0, 0, this.rect.size.width, this.rect.size.height); }, 
        destroy: function () { if (this.canvas.parentNode) this.canvas.parentNode.removeChild(this.canvas); }
    });

    var Sheet = new Class({
        initialize: function (rect) { this.rect = rect; this.drawer = new SheetCanvas(rect); this.angle = 0; }, 
        clear: function () { this.drawer.clear(); }, 
        setPosition: function (pos) { this.rect.pos = pos.cp(); this.drawer.updateCanvas(); }, 
        setAngle: function (angle) { this.angle = angle; }, 
        drawPlayer: function (verts) {
            var ctx = this.drawer.ctx; ctx.save(); ctx.translate(this.rect.size.width / 2, this.rect.size.height / 2); ctx.rotate(this.angle);
            ctx.fillStyle = 'white'; ctx.strokeStyle = 'black'; ctx.lineWidth = 1.5; ctx.beginPath(); ctx.moveTo(verts[0].x, verts[0].y);
            for (var i = 1; i < verts.length; i++) ctx.lineTo(verts[i].x, verts[i].y); ctx.fill(); ctx.stroke(); ctx.restore();
        }, 
        drawBullet: function () {
            var ctx = this.drawer.ctx; ctx.fillStyle = 'white'; ctx.beginPath(); ctx.arc(this.rect.size.width / 2, this.rect.size.height / 2, 2.5, 0, Math.PI * 2); ctx.fill();
        }, 
        drawExplosion: function (particles) {
            var ctx = this.drawer.ctx; for (var i = 0; i < particles.length; i++) { var p = particles[i]; ctx.fillStyle = p.color; ctx.fillRect(this.rect.size.width / 2 + p.pos.x, this.rect.size.height / 2 + p.pos.y, 3, 3); }
        }, 
        destroy: function () { this.drawer.destroy(); }
    });

    var Ships = { Standard: { points: [[-10, 10], [0, -15], [10, 10]], cannons: [{ p: { x: 0, y: -15 }, a: 0 }] } };
    var Weapons = { 1: { name: 'Cannon', cannonClass: BallCannon } };

    function addEvent(obj, type, fn) { obj.addEventListener(type, fn, false); }
    function removeEvent(obj, type, fn) { obj.removeEventListener(type, fn, false); }

    var initKickAss = function() {
        window.KICKASSGAME = new KickAss();
        window.KICKASSGAME.begin();
        
        // Detect mobile (Android/iOS)
        var isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
        if (isMobile) {
            createMobileControls();
        }
    };

    function createMobileControls() {
        var container = document.createElement('div');
        container.id = 'mobile-controls';
        container.className = 'KICKASSELEMENT';
        document.body.appendChild(container);
        
        var controls = [
            { id: 'm-left', key: 'left', icon: '<i data-lucide="chevron-left"></i>', side: 'left' },
            { id: 'm-right', key: 'right', icon: '<i data-lucide="chevron-right"></i>', side: 'left' },
            { id: 'm-up', key: 'up', icon: '<i data-lucide="move-up"></i>', side: 'right' },
            { id: 'm-fire', key: ' ', icon: '<i data-lucide="flame"></i>', side: 'right' }
        ];
        
        controls.forEach(function(ctrl) {
            var btn = document.createElement('div');
            btn.id = ctrl.id;
            btn.className = 'mobile-button KICKASSELEMENT ' + ctrl.side;
            btn.innerHTML = ctrl.icon;
            container.appendChild(btn);
            
            var start = function(e) { e.preventDefault(); window.KICKASSGAME.keyMap[ctrl.key] = true; };
            var end = function(e) { e.preventDefault(); window.KICKASSGAME.keyMap[ctrl.key] = false; };
            
            btn.addEventListener('touchstart', start);
            btn.addEventListener('touchend', end);
            btn.addEventListener('touchcancel', end);
        });

        // Inicializar iconos de Lucide
        if (window.lucide) {
            window.lucide.createIcons();
        }
    }

    initKickAss();

})(window);
