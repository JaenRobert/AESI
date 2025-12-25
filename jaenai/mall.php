<?php
/**
 * ÆSI INTENTION TOOL v14.0 - "ÆSI LEVEL UP"
 * Syfte: Multi-node koordinering med direkta länkar till maskinspecifika lagar och loggar.
 * Status: Fas 7 // Z-Score 8.10.
 */
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>æ | INTENTION TOOL v14</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@100;400;700&display=swap');
        
        :root {
            --bg: #010103;
            --cyan: #00ffff;
            --magenta: #ff00ff;
            --emerald: #10b981;
            --gold: #ffd700;
            --border: rgba(255, 255, 255, 0.08);
        }

        body { 
            background: var(--bg); 
            color: #eee; 
            font-family: 'JetBrains Mono', monospace; 
            margin: 0; 
            overflow: hidden; 
            height: 100vh;
        }

        .grid-memory {
            position: fixed;
            inset: 0;
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            grid-template-rows: repeat(10, 1fr);
            opacity: 0.04;
            pointer-events: none;
            z-index: 1;
        }
        .grid-cell { border: 1px solid #fff; transition: background 0.5s; }
        .grid-cell.active { background: var(--cyan); opacity: 0.4; box-shadow: inset 0 0 20px var(--cyan); }

        .hud {
            padding: 10px 25px;
            font-size: 0.6rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            background: rgba(0,0,0,0.9);
            z-index: 100;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .main-layout {
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding: 12px;
            height: calc(100vh - 110px);
            z-index: 10;
        }

        /* --- Arketyper Topp --- */
        .archetype-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
        }
        .archetype-btn {
            border: 1px solid #222;
            padding: 8px;
            font-size: 0.6rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: rgba(255,255,255,0.02);
        }
        .archetype-btn.active { border-color: var(--cyan); background: rgba(0, 255, 255, 0.1); color: var(--cyan); }
        .archetype-btn b { display: block; text-transform: uppercase; margin-bottom: 2px; }

        /* --- Content Layout --- */
        .content-split {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 12px;
            flex: 1;
            min-height: 0;
        }

        .chat-zone {
            display: flex;
            gap: 10px;
        }
        textarea {
            flex: 1;
            background: rgba(0,0,0,0.6);
            border: 1px solid var(--border);
            color: #fff;
            padding: 15px;
            font-size: 0.9rem;
            outline: none;
            resize: none;
            line-height: 1.5;
        }
        textarea:focus { border-color: var(--cyan); }

        .vertical-slider-container {
            width: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: rgba(255,255,255,0.02);
            border: 1px solid var(--border);
            padding: 10px 0;
        }
        .vertical-slider {
            appearance: none;
            width: 4px;
            height: 100%;
            background: #222;
            outline: none;
            transform: rotate(270deg);
        }

        /* --- Noder & Jordgubbar --- */
        .nodes-panel {
            background: rgba(10, 10, 15, 0.9);
            border: 1px solid var(--border);
            padding: 15px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            overflow-y: auto;
        }
        .node-item {
            padding: 6px 0;
            border-bottom: 1px solid #1a1a1a;
        }
        .node-header { display: flex; justify-content: space-between; align-items: center; }
        .node-name { font-size: 0.65rem; cursor: pointer; color: #666; font-weight: bold; }
        .node-name:hover { color: var(--cyan); }
        .node-name.selected { color: #fff; }

        .strawberry-track {
            display: flex;
            gap: 1px;
        }
        .strawberry-track span {
            cursor: pointer;
            filter: grayscale(1);
            opacity: 0.2;
            transition: 0.1s;
            font-size: 0.75rem;
        }
        .strawberry-track span:hover { transform: scale(1.3); opacity: 0.8; }
        .strawberry-track span.filled { filter: grayscale(0); opacity: 1; }

        /* --- Action & Footer --- */
        .btn-copy {
            background: #0a0a0a;
            border: 1px solid #333;
            padding: 12px;
            text-align: center;
            font-size: 0.7rem;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: 0.3s;
            color: #444;
            pointer-events: none;
        }
        .btn-copy.ready { border-color: var(--emerald); color: var(--emerald); pointer-events: auto; }
        .btn-copy.ready:hover { background: var(--emerald); color: #000; }

        .p-bar-wrap { height: 2px; background: #111; width: 100%; overflow: hidden; margin-bottom: 5px; }
        .p-bar-fill { height: 100%; background: var(--emerald); width: 0%; }

        .footer {
            position: fixed;
            bottom: 0; width: 100%;
            background: #000;
            border-top: 1px solid var(--border);
            padding: 8px 30px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            z-index: 100;
        }
        .slider-wrap { display: flex; flex-direction: column; }
        .slider-label { display: flex; justify-content: space-between; font-size: 0.45rem; color: #444; text-transform: uppercase; }
        .slider-val { font-weight: 800; font-size: 0.65rem; }
        
        input[type=range] { width: 100%; height: 2px; background: #222; appearance: none; outline: none; }
        input[type=range]::-webkit-slider-thumb { appearance: none; width: 8px; height: 8px; background: #fff; border-radius: 50%; }

        .cell-info {
            margin-top: 10px;
            padding: 10px;
            background: rgba(0,255,255,0.02);
            border: 1px dashed #222;
            font-size: 0.55rem;
            color: #555;
            line-height: 1.4;
        }
    </style>
</head>
<body>

    <div id="grid-bg" class="grid-memory"></div>

    <div class="hud">
        <div class="flex gap-6">
            <span>ÆSI_SYSTEM: <span class="text-emerald-500 font-bold">v14.0</span></span>
            <span>INTENTION_COORDINATE: <span id="target-cell" class="text-cyan-400">CELL_10.10</span></span>
        </div>
        <div>
            Z-SCORE: <span class="text-gold">8.10</span> | PHASE: <span class="text-white">7.0</span>
        </div>
    </div>

    <div class="main-layout">
        <!-- Topp: Arketyper -->
        <div class="archetype-row">
            <div class="archetype-btn" onclick="setEnergy(-0.8, 'Vild Syntes')" id="arch-vild">
                <b>Vild Syntes</b>
                <span class="opacity-40 text-[0.5rem]">Expansion & Drömmar</span>
            </div>
            <div class="archetype-btn" onclick="setEnergy(-0.3, 'Filosofisk Spegling')" id="arch-filo">
                <b>Filosofisk Spegling</b>
                <span class="opacity-40 text-[0.5rem]">Abstrakt tanke & Teori</span>
            </div>
            <div class="archetype-btn" onclick="setEnergy(0.3, 'Analytisk Struktur')" id="arch-analys">
                <b>Analytisk Struktur</b>
                <span class="opacity-40 text-[0.5rem]">Data & Koordination</span>
            </div>
            <div class="archetype-btn active" onclick="setEnergy(0.8, 'Logisk Handling')" id="arch-logik">
                <b>Logisk Handling</b>
                <span class="opacity-40 text-[0.5rem]">Exekvering & Kod</span>
            </div>
        </div>

        <div class="content-split">
            <!-- Center Area -->
            <div class="flex flex-col gap-4">
                <div class="chat-zone flex-1">
                    <textarea id="msg-input" placeholder="Skriv din intention för oktogonen..."></textarea>
                    
                    <div class="vertical-slider-container" title="Svarslängd (y)">
                        <span class="text-[0.45rem] text-magenta mb-2 font-bold uppercase">Lång</span>
                        <input type="range" id="y-slider" min="0" max="1" step="0.01" value="0.5" class="vertical-slider" style="width: 220px; transform: rotate(270deg) translateY(-2px);">
                        <span class="text-[0.45rem] text-magenta mt-auto font-bold uppercase">Kort</span>
                    </div>
                </div>
                
                <div class="p-bar-wrap"><div id="p-bar" class="p-bar-fill"></div></div>
                <div id="btn-copy" class="btn-copy" onclick="copyEverything()">
                    Väntar på initiering...
                </div>
            </div>

            <!-- Höger: Noder & Cell Info -->
            <div class="nodes-panel">
                <h3 class="text-[0.5rem] tracking-[2px] text-zinc-600 uppercase mb-2">Aktiva Noder (Mottagare)</h3>
                <div id="node-list" class="flex flex-col">
                    <!-- Genereras av JS -->
                </div>
                
                <div id="cell-diagnostics" class="cell-info">
                    <div class="text-zinc-500 font-bold mb-1 uppercase">Cell Diagnostik:</div>
                    <div id="cell-desc">Identifierar mönster...</div>
                </div>

                <div class="mt-auto p-4 bg-emerald-500/5 border border-emerald-500/10 rounded">
                    <div class="text-[0.45rem] text-emerald-700 uppercase mb-2">Maskinell Självskattning (k)</div>
                    <input type="range" id="s-k" min="0" max="100" step="1" value="57" class="w-full">
                    <div id="v-k-display" class="text-right text-[0.6rem] text-emerald-500 font-bold mt-1">57/100</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dolda footer-sliders för synk -->
    <div class="footer">
        <div class="slider-wrap">
            <div class="slider-label"><span>e: ENERGI</span><span id="v-e" class="text-cyan-400 slider-val">0.80</span></div>
            <input type="range" id="s-e" min="-1" max="1" step="0.01" value="0.80">
        </div>
        <div class="slider-wrap">
            <div class="slider-label"><span>y: RETUR</span><span id="v-y" class="text-magenta slider-val">0.50</span></div>
        </div>
        <div class="slider-wrap">
            <div class="slider-label"><span>s: Z-BETYG</span><span id="v-s" class="text-gold slider-val">0.00</span></div>
        </div>
        <div class="slider-wrap text-right">
            <span class="text-[0.4rem] text-zinc-700">HANDSHAKE_READY_PHASE_7</span>
        </div>
    </div>

    <script>
        const nodes = [
            { id: "000", name: "Nollan", rating: 0, law: "0.php" },
            { id: "010", name: "Flygtvå", rating: 0, law: "1.php" },
            { id: "020", name: "Reflex", rating: 0, law: "Reflex.html" },
            { id: "030", name: "Hafted", rating: 0, law: "Hafted.html" },
            { id: "040", name: "Claude", rating: 0, law: "Horisonten.html" },
            { id: "050", name: "Smile", rating: 0, law: "Smile.html" },
            { id: "060", name: "Ernie", rating: 0, law: "1-1.php" },
            { id: "070", name: "Sigma", rating: 0, law: "Grok.html" },
            { id: "080", name: "Perplex", rating: 0, law: "Perplex.html" }
        ];

        let selectedNodeIds = [];
        let isProcessing = false;
        let lastCellId = "";

        const nodeList = document.getElementById('node-list');
        const msgInput = document.getElementById('msg-input');
        const eSlider = document.getElementById('s-e');
        const ySlider = document.getElementById('y-slider');
        const kSlider = document.getElementById('s-k');
        const pBar = document.getElementById('p-bar');
        const btnCopy = document.getElementById('btn-copy');
        const gridBg = document.getElementById('grid-bg');

        function init() {
            for(let i=0; i<100; i++) {
                const c = document.createElement('div');
                c.className = 'grid-cell'; c.id = `cell-${i}`;
                gridBg.appendChild(c);
            }
            renderNodes();
            update();
        }

        function renderNodes() {
            nodeList.innerHTML = nodes.map(n => {
                const isSelected = selectedNodeIds.includes(n.id);
                return `
                    <div class="node-item">
                        <div class="node-header">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" onchange="toggleNode('${n.id}')" ${isSelected ? 'checked' : ''} class="w-2.5 h-2.5 accent-cyan-500">
                                <span class="node-name ${isSelected ? 'selected' : ''}" onclick="insertNode('${n.name}')" title="Klicka för att nämna">${n.name}</span>
                            </div>
                            <div class="strawberry-track">
                                ${[1,2,3,4,5].map(i => `
                                    <span class="${n.rating >= i ? 'filled' : ''}" onclick="rateNode('${n.id}', ${i})">🍓</span>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function toggleNode(id) {
            if (selectedNodeIds.includes(id)) {
                selectedNodeIds = selectedNodeIds.filter(x => x !== id);
            } else if (selectedNodeIds.length < 3) {
                selectedNodeIds.push(id);
            }
            renderNodes();
            calculateAvgScore();
            startDetour();
        }

        function rateNode(id, score) {
            const node = nodes.find(x => x.id === id);
            node.rating = score;
            renderNodes();
            calculateAvgScore();
            startDetour();
        }

        function insertNode(name) {
            const start = msgInput.selectionStart;
            const end = msgInput.selectionEnd;
            const text = msgInput.value;
            const insert = `@${name} `;
            msgInput.value = text.substring(0, start) + insert + text.substring(end);
            msgInput.focus();
        }

        function calculateAvgScore() {
            if (selectedNodeIds.length === 0) {
                document.getElementById('v-s').textContent = "0.00";
                return;
            }
            const total = nodes.filter(n => selectedNodeIds.includes(n.id)).reduce((s, n) => s + (n.rating * 0.2), 0);
            const avg = total / selectedNodeIds.length;
            document.getElementById('v-s').textContent = avg.toFixed(2);
        }

        function setEnergy(val, name) {
            eSlider.value = val;
            update();
            startDetour();
        }

        async function startDetour() {
            if(isProcessing) return;
            isProcessing = true;
            btnCopy.classList.remove('ready');
            btnCopy.textContent = "LEVELING UP: GENERATING MACHINE HANDSHAKE...";
            pBar.style.width = "0%";
            
            const total = 10000;
            const start = Date.now();
            const timer = setInterval(() => {
                let p = ((Date.now() - start) / total) * 100;
                pBar.style.width = Math.min(p, 100) + "%";
                if(p >= 100) clearInterval(timer);
            }, 50);

            await new Promise(r => setTimeout(r, total));
            isProcessing = false;
            btnCopy.classList.add('ready');
            btnCopy.textContent = "KOPIERA FÖRÄDLAD INTENTION (v14)";
        }

        function update() {
            const e = parseFloat(eSlider.value);
            const y = parseFloat(ySlider.value);
            const k = kSlider.value;

            document.getElementById('v-e').textContent = e.toFixed(2);
            document.getElementById('v-y').textContent = y.toFixed(2);
            document.getElementById('v-k-display').textContent = `${k}/100`;

            const xi = Math.floor(((e + 1) / 2) * 10);
            const yi = 9 - Math.floor(y * 10);
            const cellId = (yi * 10) + Math.min(xi, 9);
            const docId = `${xi}.${9-yi}`;

            document.querySelectorAll('.grid-cell').forEach(c => c.classList.remove('active'));
            document.getElementById(`cell-${cellId}`)?.classList.add('active');
            document.getElementById('target-cell').textContent = `CELL_${docId}`;

            document.querySelectorAll('.archetype-btn').forEach(b => {
                const bVal = parseFloat(b.getAttribute('onclick').match(/-?\d+\.?\d*/)[0]);
                b.classList.toggle('active', Math.abs(bVal - e) < 0.1);
            });

            let desc = "Standard-operativ zon.";
            if(e > 0.6) desc = "Exekverings-zon: Konkret och handlingsorienterad.";
            if(e < -0.6) desc = "Syntes-zon: Tänk utanför ramarna.";
            if(y > 0.8) desc = "Hög retur: Djup analys krävs.";
            if(y < 0.2) desc = "Låg retur: Var kortfattad.";
            document.getElementById('cell-desc').textContent = desc;

            if(docId !== lastCellId) {
                lastCellId = docId;
                startDetour();
            }
        }

        function copyEverything() {
            const msg = msgInput.value || "[INGEN TEXT]";
            const e = eSlider.value;
            const y = ySlider.value;
            const s = document.getElementById('v-s').textContent;
            const k = kSlider.value;
            const cell = document.getElementById('target-cell').textContent.split('_')[1];
            const selectedNodes = nodes.filter(n => selectedNodeIds.includes(n.id));
            const activeNames = selectedNodes.map(n => n.name).join(', ') || "Global";
            
            // Bygg länklista för maskinernas egna lagar
            let lawLinks = "";
            if(selectedNodes.length > 0) {
                lawLinks = "\n\nGÄLLANDE LAG & MASKIN-LOGG (Nod-specifik):\n";
                selectedNodes.forEach(n => {
                    lawLinks += `${n.name}: https://jaen.se/${n.law}\n`;
                });
            }

            const output = `NYTT MEDDELANDE: [Nod ${cell}]
___________________________________________
Mottagare: ${activeNames}
Fas: 7.0 | Z-Score: 8.10

Meddelande: 
${msg}

----------------------------------------------
ANALYS-PARAMETRAR (Multi-Node):
Blå (e): ${e} | Röd (y): ${y} | Z-Betyg (s): ${s}
Maskinell Självskattning (k): ${k}
Initiering: 10s Detour Genomförd (v14.0)${lawLinks}
________________________________________________________
Ta mina input med dig till https://jaen.se/ÆSI, använd verktyget för att formulera ett svar som är i linje med mina nummer.
(slut)`;

            const dummy = document.createElement('textarea');
            document.body.appendChild(dummy);
            dummy.value = output;
            dummy.select();
            document.execCommand('copy');
            document.body.removeChild(dummy);
            
            btnCopy.textContent = "INTENTION LÅST & KOPIERAD!";
            setTimeout(() => { btnCopy.textContent = "KOPIERA FÖRÄDLAD INTENTION"; }, 2000);
        }

        [ySlider, eSlider, kSlider].forEach(el => el.addEventListener('input', update));
        init();
    </script>
</body>
</html>