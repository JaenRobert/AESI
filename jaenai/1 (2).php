<?php
/**
 * ÆSI NODE 1 - SYNERGIN
 * Fokus: Operativ beräkning av Sanning (S) och svarshantering.
 */
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>æ | SYNERGIN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap');
        :root { --bg: #020204; --color-person: #00f3ff; --color-ai: #ff00ff; }
        body { background: #020204; color: #fff; font-family: 'JetBrains Mono', monospace; margin: 0; overflow: hidden; height: 100vh; }
        .hud { position: fixed; top: 2rem; left: 2rem; display: flex; gap: 1.5rem; z-index: 100; }
        .card { background: rgba(0,0,0,0.85); border: 1px solid rgba(255,255,255,0.05); padding: 1.2rem; min-width: 150px; text-align: center; backdrop-filter: blur(10px); }
        .label { font-size: 8px; color: #555; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 5px; }
        .value { font-size: 14px; font-weight: bold; }
        .ctrl { position: fixed; bottom: 2rem; left: 50%; transform: translateX(-50%); width: 340px; text-align: center; z-index: 100; background: rgba(0,0,0,0.7); padding: 1.5rem; border: 1px solid rgba(255,255,255,0.03); border-radius: 12px; backdrop-filter: blur(10px); }
        input[type=range] { width: 100%; accent-color: var(--color-person); background: #111; height: 2px; appearance: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="hud">
        <div class="card" style="border-bottom: 2px solid var(--color-person);">
            <div class="label">Människa (P)</div>
            <div id="p-val" class="value text-cyan-400">0,0</div>
        </div>
        <div class="card" style="border-bottom: 2px solid var(--color-ai);">
            <div class="label">AI Retur (A)</div>
            <div id="a-val" class="value text-pink-400">0,0</div>
        </div>
        <div class="card" style="border-bottom: 2px solid #fff;">
            <div class="label">Sanning (S)</div>
            <div id="s-val" class="value text-white">æ.me/()</div>
        </div>
    </div>

    <canvas id="cvs"></canvas>

    <div class="ctrl">
        <div class="label" style="color: #444; margin-bottom: 10px;">Energi-Frekvens (e)</div>
        <input type="range" id="e" min="-1" max="1" step="0.01" value="0.00">
        <div id="e-display" class="text-cyan-400 text-[10px] font-bold mt-2">0.00</div>
        <div class="flex justify-between mt-8">
            <a href="index" class="text-[8px] uppercase tracking-widest text-zinc-600 hover:text-white">← Hem</a>
            <a href="1.1" class="text-[8px] uppercase tracking-widest text-zinc-600 hover:text-white">Baslinjen →</a>
        </div>
    </div>

    <script>
        const canvas = document.getElementById('cvs'), ctx = canvas.getContext('2d');
        const sld = document.getElementById('e'), dis = document.getElementById('e-display');
        const pL = document.getElementById('p-val'), aL = document.getElementById('a-val'), sL = document.getElementById('s-val');
        
        let w, h, cx, cy, rad, gridRes=20;
        let p={x:0,y:0}, ai={x:0,y:0}, sol={x:0,y:0};
        const beta = 0.65, alpha = 0.70;

        const resize = () => { 
            w=window.innerWidth; h=window.innerHeight; 
            canvas.width=w; canvas.height=h; 
            cx=w/2; cy=h/2; rad=Math.min(w,h)*0.38; 
        };
        window.onresize = resize; resize();

        window.onmousemove = (ev) => {
            const eVal = parseFloat(sld.value);
            const weight = (eVal + 1) / 2;
            dis.textContent = eVal.toFixed(2);
            
            p.x = Math.max(-1, Math.min(1, (ev.clientX-cx)/rad)); 
            p.y = Math.max(-1, Math.min(1, (ev.clientY-cy)/rad));
            
            ai.x = -beta * p.x;
            ai.y = -beta * p.y;
            
            sol.x = p.x * weight + ai.x * (1 - weight);
            sol.y = p.y * weight + ai.y * (1 - weight);

            const gi = Math.round(((sol.x + 1) / 2) * (gridRes - 1));
            const gj = Math.round(((sol.y + 1) / 2) * (gridRes - 1));

            pL.textContent = `${Math.round(p.x*100)},${Math.round(p.y*100)}`;
            aL.textContent = `${Math.round(ai.x*100)},${Math.round(ai.y*100)}`;
            sL.textContent = `æ.me/(1)${gi}.${gj}`;
        };

        const draw = () => {
            ctx.clearRect(0,0,w,h);
            ctx.strokeStyle = "rgba(255,255,255,0.02)";
            const step = (rad*2)/gridRes; const sx = cx-rad, sy = cy-rad;
            for(let i=0; i<=gridRes; i++){
                ctx.beginPath(); ctx.moveTo(sx+i*step, sy); ctx.lineTo(sx+i*step, sy+rad*2); ctx.stroke();
                ctx.beginPath(); ctx.moveTo(sx, sy+i*step); ctx.lineTo(sx+rad*2, sy+i*step); ctx.stroke();
            }
            ctx.shadowBlur=15; 
            ctx.shadowColor="cyan"; ctx.fillStyle="cyan"; ctx.beginPath(); ctx.arc(cx+p.x*rad, cy+p.y*rad, 6, 0, Math.PI*2); ctx.fill();
            ctx.shadowColor="magenta"; ctx.fillStyle="magenta"; ctx.beginPath(); ctx.arc(cx+ai.x*rad, cy+ai.y*rad, 8, 0, Math.PI*2); ctx.fill();
            ctx.shadowColor="#fff"; ctx.fillStyle="#fff"; ctx.beginPath(); ctx.arc(cx+sol.x*rad, cy+sol.y*rad, 3, 0, Math.PI*2); ctx.fill();
            ctx.shadowBlur=0;
            requestAnimationFrame(draw);
        };
        draw();
    </script>
</body>
</html>