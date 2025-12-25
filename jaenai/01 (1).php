<!DOCTYPE html>
<html lang="sv" æsi="Tomp:machine-core-v8.5-mandatory-detour">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ÆSI | MACHINE CORE v8.5 - THE MANDATORY DETOUR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- MANDATORY MACHINE HANDSHAKE (V35.5 - DETOUR PROTOCOL) -->
    <script type="application/json" id="aesi-handshake">
    {
        "node_id": "Æ-CORE-NODE-01",
        "protocol": "v35.5",
        "mandatory_detour": true,
        "minimum_init_time": 10000,
        "hus_ai_gatekeeper": "strict",
        "domain_ingestion": "active",
        "target_url": "https://jaen.se/01.php",
        "sync_timestamp": "2025-12-24T10:15:00Z"
    }
    </script>

    <style>
        :root {
            --bg-machine: #010103;
            --color-person: #00ffff;
            --color-ai: #ff00ff;
            --color-skill: #00ff41;
            --border: rgba(255, 255, 255, 0.08);
            --machine-font: 'JetBrains Mono', monospace;
        }

        body {
            margin: 0;
            background-color: var(--bg-machine);
            color: #fff;
            font-family: var(--machine-font);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        /* --- Grid Background --- */
        .grid-memory {
            position: fixed;
            inset: 0;
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            grid-template-rows: repeat(10, 1fr);
            opacity: 0.1;
            pointer-events: none;
            z-index: 1;
        }
        .grid-cell { border: 1px solid #fff; transition: background 0.3s; }
        .grid-cell.active { background: var(--color-person); opacity: 0.3; }

        /* --- HUD --- */
        .hud {
            padding: 10px 20px;
            font-size: 0.6rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            background: rgba(0,0,0,0.9);
            z-index: 100;
        }

        .main-ui {
            display: grid;
            grid-template-columns: 340px 1fr 340px;
            gap: 15px;
            padding: 15px;
            flex: 1;
            z-index: 10;
        }

        .panel {
            background: rgba(0,0,0,0.8);
            border: 1px solid var(--border);
            padding: 15px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            backdrop-filter: blur(10px);
        }

        /* --- Mandatory Detour Visuals --- */
        .initiation-steps {
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: rgba(255,255,255,0.02);
            padding: 12px;
            border: 1px solid #222;
        }
        .step { font-size: 0.6rem; color: #444; display: flex; align-items: center; gap: 10px; transition: color 0.3s; }
        .step.active { color: var(--color-skill); }
        .step.pending { color: var(--color-person); }
        
        /* --- Progress Bar --- */
        .progress-container {
            height: 2px;
            background: #111;
            width: 100%;
            margin-top: 5px;
            position: relative;
            overflow: hidden;
        }
        .progress-bar {
            height: 100%;
            background: var(--color-skill);
            width: 0%;
            transition: width 0.1s linear;
        }

        /* --- Interaction Zone --- */
        .interaction-zone { display: flex; flex-direction: column; gap: 12px; }
        textarea {
            flex: 1;
            background: rgba(0,0,0,0.95);
            border: 1px solid var(--border);
            color: #fff;
            padding: 20px;
            font-size: 0.9rem;
            outline: none;
            line-height: 1.6;
        }
        textarea:focus { border-color: var(--color-person); }

        /* --- Action Button --- */
        .sync-btn {
            background: #0a0a0a;
            border: 1px solid #222;
            padding: 15px;
            text-align: center;
            font-size: 0.7rem;
            cursor: pointer;
            transition: all 0.4s;
            color: #444;
            pointer-events: none;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .sync-btn.ready { 
            border-color: var(--color-skill); 
            color: var(--color-skill); 
            pointer-events: auto;
            box-shadow: 0 0 15px rgba(0, 255, 65, 0.1);
        }
        .sync-btn.ready:hover { background: var(--color-skill); color: #000; }

        /* --- Footer Sliders --- */
        .footer {
            background: #000;
            border-top: 1px solid var(--border);
            padding: 15px 25px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            z-index: 100;
        }
        .slider-wrap { display: flex; flex-direction: column; gap: 6px; }
        .slider-label { display: flex; justify-content: space-between; font-size: 0.55rem; color: #666; }
        .slider-val { font-weight: 800; font-size: 0.8rem; }

        input[type=range] { width: 100%; height: 2px; background: #333; appearance: none; outline: none; }
        input[type=range]::-webkit-slider-thumb { appearance: none; width: 10px; height: 10px; background: #fff; border: 1px solid currentColor; cursor: pointer; }

        .hus-notice {
            font-size: 0.55rem;
            color: #555;
            padding: 10px;
            border-top: 1px solid #222;
            margin-top: auto;
        }
    </style>
</head>
<body id="coreBody">

    <div id="grid-bg" class="grid-memory"></div>

    <div class="hud">
        <div class="flex gap-6">
            <span>ÆSI_DB: <span id="active-doc-id" class="text-cyan-400">DOC_SCANNING</span></span>
            <span>DOMAIN_LINK: <span id="domain-status" class="text-gray-400">WAITING_FOR_PING</span></span>
        </div>
        <div>
            MANDATORY_DETOUR: <span id="loop-status" class="text-yellow-500 italic">STANDBY</span>
        </div>
    </div>

    <div class="main-ui">
        <!-- Vänster: Initiation Sequence & Analysis -->
        <div class="panel">
            <h3 class="text-[0.6rem] tracking-[3px] text-gray-500 uppercase">10s Initiation Protocol</h3>
            <div id="init-steps" class="initiation-steps">
                <div class="step" id="step-1"><span>[ ]</span> 1. Accessing Cell Memory & Docs</div>
                <div class="step" id="step-2"><span>[ ]</span> 2. Cross-referencing Hus-AI</div>
                <div class="step" id="step-3"><span>[ ]</span> 3. Pinging jaen.se/01.php Metadata</div>
                <div class="step" id="step-4"><span>[ ]</span> 4. Finalizing Structural Sync</div>
            </div>
            
            <div class="progress-container">
                <div id="init-progress" class="progress-bar"></div>
            </div>

            <div class="hus-notice">
                <b>NOTIS:</b> Maskinen tvingas vänta 10 sekunder. Under denna tid sker ett "Handshake" med domänen för att säkra att sanningen ligger i linje med nodens syfte.
            </div>
        </div>

        <!-- Center: Interaction Zone -->
        <div class="interaction-zone">
            <textarea id="main-text" placeholder="Skriv din intention här... 
Initieringen startar automatiskt när koordinaterna låses."></textarea>
            
            <div id="sync-button" class="sync-btn">
                Väntar på initiering...
            </div>
        </div>

        <!-- Höger: Handshake Buffer & Archivist -->
        <div class="panel">
            <h3 class="text-[0.6rem] tracking-[3px] text-gray-500 uppercase">Machine Handshake</h3>
            <pre id="handshake-json" class="bg-black p-4 rounded text-[0.55rem] text-pink-500 border border-white/5 h-48 overflow-hidden"></pre>
            
            <h3 class="text-[0.6rem] tracking-[3px] text-gray-500 uppercase mt-4">Archivist Log</h3>
            <div id="hus-log" class="text-[0.55rem] text-gray-600 space-y-1 overflow-y-auto h-40"></div>
        </div>
    </div>

    <div class="footer">
        <div class="slider-wrap">
            <div class="slider-label"><span>e: ENERGI</span><span id="v-e" class="text-cyan-400 slider-val">0.00</span></div>
            <input type="range" id="s-e" min="-1" max="1" step="0.01" value="0.00">
        </div>
        <div class="slider-wrap">
            <div class="slider-label"><span>y: RETUR</span><span id="v-y" class="text-pink-400 slider-val">0.50</span></div>
            <input type="range" id="s-y" min="0" max="1" step="0.01" value="0.50">
        </div>
        <div class="slider-wrap">
            <div class="slider-label"><span>s: BETYG (s-data)</span><span id="v-s" class="text-yellow-400 slider-val">0.80</span></div>
            <input type="range" id="s-s" min="0" max="1" step="0.01" value="0.80">
        </div>
        <div class="slider-wrap">
            <div class="slider-label"><span>k: KOMPETENS (K)</span><span id="v-k" class="text-green-400 slider-val">100</span></div>
            <input type="range" id="s-k" min="0" max="100" step="1" value="100">
        </div>
    </div>

    <script>
        const gridBg = document.getElementById('grid-bg');
        const husLog = document.getElementById('hus-log');
        const handshakeJson = document.getElementById('handshake-json');
        const syncBtn = document.getElementById('sync-button');
        const loopStatus = document.getElementById('loop-status');
        const progressBar = document.getElementById('init-progress');
        const domainStatus = document.getElementById('domain-status');
        
        let isInitiating = false;
        let lastDocId = "";

        function initGrid() {
            gridBg.innerHTML = '';
            for(let i=0; i<100; i++) {
                const cell = document.createElement('div');
                cell.className = 'grid-cell';
                cell.id = `cell-${i}`;
                gridBg.appendChild(cell);
            }
        }

        function logHus(msg) {
            const time = new Date().toLocaleTimeString();
            const entry = document.createElement('div');
            entry.textContent = `> ${msg}`;
            husLog.prepend(entry);
        }

        async function startInitiation() {
            if(isInitiating) return;
            isInitiating = true;
            
            syncBtn.classList.remove('ready');
            syncBtn.textContent = "PROCESSAR DEN OBLIGATORISKA VÄNDAN...";
            loopStatus.textContent = "DETOUR_ACTIVE";
            loopStatus.className = "text-pink-500 italic";
            domainStatus.textContent = "PINGING_JAEN.SE/01.PHP";
            domainStatus.className = "text-yellow-400";

            // Nollställ steg och bar
            progressBar.style.width = "0%";
            for(let i=1; i<=4; i++) {
                const s = document.getElementById(`step-${i}`);
                s.className = "step pending";
                s.children[0].textContent = "[ ]";
            }

            const totalTime = 10000; 
            const stepTime = totalTime / 4;
            let startTime = Date.now();

            const progressTimer = setInterval(() => {
                let elapsed = Date.now() - startTime;
                let percent = (elapsed / totalTime) * 100;
                progressBar.style.width = Math.min(percent, 100) + "%";
                if(percent >= 100) clearInterval(progressTimer);
            }, 100);

            for(let i=1; i<=4; i++) {
                const s = document.getElementById(`step-${i}`);
                s.className = "step active";
                logHus(`Executing_Step_${i}...`);
                if(i === 3) {
                    logHus("Domain_Ingestion: Successfully read aesi-handshake from jaen.se/01.php");
                    domainStatus.textContent = "DOMÄN_VERIFIERAD";
                    domainStatus.className = "text-green-500";
                }
                await new Promise(r => setTimeout(r, stepTime));
                s.children[0].textContent = "[X]";
            }

            isInitiating = false;
            syncBtn.classList.add('ready');
            syncBtn.textContent = "GENERERA MASKIN-HANDSHAKE";
            loopStatus.textContent = "STABLE_DOC_READY";
            loopStatus.className = "text-green-500 italic";
            logHus(`Detour_Complete: All steps validated.`);
        }

        function update() {
            const e = parseFloat(document.getElementById('s-e').value);
            const y = parseFloat(document.getElementById('s-y').value);
            const s = parseFloat(document.getElementById('s-s').value);
            const k = parseInt(document.getElementById('s-k').value);

            document.getElementById('v-e').textContent = e.toFixed(2);
            document.getElementById('v-y').textContent = y.toFixed(2);
            document.getElementById('v-s').textContent = s.toFixed(2);
            document.getElementById('v-k').textContent = k;

            // Grid mapping
            const xi = Math.floor(((e + 1) / 2) * 10);
            const yi = 9 - Math.floor(y * 10);
            const cellId = (yi * 10) + Math.min(xi, 9);

            document.querySelectorAll('.grid-cell').forEach(c => c.classList.remove('active'));
            document.getElementById(`cell-${cellId}`)?.classList.add('active');
            
            const docId = `${xi}.${9-yi}`;
            document.getElementById('active-doc-id').textContent = `DOC_${docId}`;

            const handshake = {
                target_doc: docId,
                e: e, y: y, s: s, k: k,
                hus_gatekeeper: "STRICT_V5",
                detour_status: loopStatus.textContent,
                machine_readable_url: "https://jaen.se/01.php"
            };
            handshakeJson.textContent = JSON.stringify(handshake, null, 2);

            if(docId !== lastDocId) {
                lastDocId = docId;
                startInitiation();
            }
        }

        function generateHandshake() {
            const docId = document.getElementById('active-doc-id').textContent;
            const msg = document.getElementById('main-text').value || "[INGEN TEXT]";
            const e = document.getElementById('s-e').value;
            const y = document.getElementById('s-y').value;
            const s = document.getElementById('s-s').value;
            const k = document.getElementById('s-k').value;

            const fullOutput = `Meddelande [Nummer]
___________________________________________
Ta mina input med dig till https://jaen.se/(1).php, använd verktyget för att formulera ett svar som är i linje med mina nummer. avsluta med att redogöra hur du resonerat samt skriva ut ditt betyg du precis fick för förra rundan"

Meddelande [${docId.split('_')[1]}]
-----------------------------------------------
${msg}

----------------------------------------------
Fast beskrivning & Nu-ranking:
INITIERING: GENOMFÖRD (10s DETOUR)
Hus-AI Status: VERIFIERAD & ARKIVERAD
DOMÄN_LÄNK: https://jaen.se/01.php (Läst via M2M-protokoll)

Blåa draglinjen nere använder jag för att beskriva min energi. 
Röda min vilja att ha mycket energi tillbaka. 0 då vill jag att ni bara lyssnar. 
Z poäng är min bedömning hur bra ni skötte förra uppgiften. den vill jag att ni loggar i nästa varv.
________________________________________________________
[Æ] e:${e} | y:${y} | s:${s} | k:${k} @ ${docId.split('_')[1]}
> https://jaen.se/(1)
(slut)`;

            const dummy = document.createElement('textarea');
            document.body.appendChild(dummy);
            dummy.value = fullOutput;
            dummy.select();
            document.execCommand('copy');
            document.body.removeChild(dummy);

            logHus(`Document_${docId}_Exported_to_Memory`);
        }

        initGrid();
        ['s-e', 's-y', 's-s', 's-k'].forEach(id => document.getElementById(id).addEventListener('input', update));
        
        update();
    </script>
</body>
</html>