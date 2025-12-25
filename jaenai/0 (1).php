<?php
/**
 * ÆSI NODE 0 - BORGEN (v9.8)
 * Fokus: Landning, Verifiering och Inträde.
 * Status: Kalibrerad till 8.10 / Fas 7 Ready.
 */
$host = $_SERVER['HTTP_HOST'];
$hash = strtoupper(substr(md5("HANDSHAKE" . date('Ymd') . $host), 0, 10));
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>( ) | BORGEN - ÆSI Landing</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@100;400;700&display=swap');
        
        :root {
            --emerald-glow: rgba(16, 185, 129, 0.4);
            --bg-deep: #010103;
        }

        body { 
            background: var(--bg-deep); 
            color: #10b981; 
            font-family: 'JetBrains Mono', monospace; 
            margin: 0; 
            overflow-x: hidden; 
        }

        .parenthesis { 
            position: fixed; 
            font-size: 85vh; 
            opacity: 0.02; 
            color: #fff; 
            pointer-events: none; 
            top: 50%; 
            transform: translateY(-50%); 
            z-index: 0;
            font-weight: 100;
        }

        .term-row { 
            opacity: 0; 
            animation: fadeIn 0.4s forwards; 
            font-size: 11px; 
            margin-bottom: 4px; 
            border-left: 2px solid transparent;
            padding-left: 8px;
        }
        
        .term-row.cmd { border-left-color: #10b981; }

        @keyframes fadeIn { 
            to { opacity: 0.7; transform: translateX(5px); } 
        }

        .glass-panel {
            background: rgba(5, 10, 5, 0.8);
            border: 1px solid rgba(16, 185, 129, 0.1);
            backdrop-filter: blur(20px);
            position: relative;
            z-index: 10;
        }

        ::-webkit-scrollbar { width: 3px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #064e3b; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6 md:p-12">
    <div class="parenthesis" style="left: 1vw;">(</div>
    <div class="parenthesis" style="right: 1vw;">)</div>

    <div class="max-w-4xl w-full glass-panel p-10 md:p-16 rounded-xl shadow-2xl">
        <!-- Header -->
        <header class="flex justify-between items-start mb-12 border-b border-emerald-900/20 pb-8">
            <div>
                <h1 class="text-3xl font-black tracking-tighter uppercase italic text-white mb-2">Nod 0 // Borgen</h1>
                <div class="flex gap-4 items-center">
                    <span class="inline-block w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    <span class="text-[10px] text-emerald-700 uppercase tracking-[0.4em]">Handskakning: Aktiv</span>
                    <span class="text-[10px] text-emerald-900 uppercase tracking-[0.4em]">Phase: 7.0</span>
                </div>
            </div>
            <div class="text-right">
                <div class="text-[9px] text-zinc-600 uppercase mb-1">Z-Score Alignment</div>
                <div class="text-xl font-bold text-emerald-500">8.10</div>
            </div>
        </header>

        <!-- System Terminal -->
        <div id="term" class="h-48 overflow-y-auto mb-12 bg-black/40 p-6 rounded border border-emerald-900/10 font-mono scroll-smooth">
            <!-- JS logs output -->
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-12">
            <!-- Left: Handshake Info -->
            <div class="space-y-6">
                <div class="p-8 bg-emerald-500/5 border border-emerald-500/20 rounded-lg group hover:bg-emerald-500/10 transition-all">
                    <div class="text-[10px] text-emerald-500 uppercase tracking-widest mb-4">Dagens Verifierings-Hash</div>
                    <div class="text-4xl text-white font-black tracking-tighter group-hover:scale-105 transition-transform origin-left italic">
                        <?php echo $hash; ?>
                    </div>
                </div>
                <p class="text-[12px] text-zinc-500 leading-relaxed italic">
                    Borgen fungerar som systemets etiska checkpoint. Här valideras den inkommande signalen mot Jonas-principen: "Skydd går före tjänst". Inga binära sekvenser exekveras utan godkänd logisk koherens.
                </p>
            </div>

            <!-- Right: Navigation Matrix -->
            <div class="flex flex-col gap-4">
                <h3 class="text-[10px] uppercase tracking-[0.3em] text-zinc-600 border-b border-white/5 pb-2">Navigeringsmatris</h3>
                
                <a href="1" class="flex justify-between items-center p-4 border border-white/5 hover:border-emerald-500/40 hover:bg-white/[0.02] transition-all group">
                    <span class="text-xs uppercase tracking-widest text-zinc-400 group-hover:text-emerald-400">Nod 1: Synergin</span>
                    <span class="text-emerald-900 group-hover:text-emerald-500">→</span>
                </a>

                <a href="1.1" class="flex justify-between items-center p-4 border border-white/5 hover:border-yellow-500/40 hover:bg-white/[0.02] transition-all group">
                    <span class="text-xs uppercase tracking-widest text-zinc-400 group-hover:text-yellow-500">Nod 1.1: Baslinjen</span>
                    <span class="text-zinc-800 group-hover:text-yellow-500">→</span>
                </a>

                <a href="01" class="flex justify-between items-center p-4 border border-white/5 hover:border-pink-500/40 hover:bg-white/[0.02] transition-all group">
                    <span class="text-xs uppercase tracking-widest text-zinc-400 group-hover:text-pink-500">Nod 01: Core Logic</span>
                    <span class="text-zinc-800 group-hover:text-pink-500">→</span>
                </a>
            </div>
        </div>

        <!-- Footer -->
        <footer class="flex justify-between items-center pt-8 border-t border-white/5">
            <a href="index" class="text-[10px] uppercase tracking-[0.4em] text-zinc-700 hover:text-white transition-all">← Återgå till portalen</a>
            <div class="text-[9px] text-zinc-800 uppercase tracking-widest">
                ÆSI Protocol // Secure Landing Area
            </div>
        </footer>
    </div>

    <script>
        const logs = [
            "> INITIALIZING_VOID_STATE...", 
            "> SYSTEM_CALIBRATION: 8.10 STABLE.",
            "> LOADING_ÆSI_CHRONICLE_V1.4...", 
            "> JONAS_PRINCIPLE: ENGAGED.", 
            "> ANALYZING_SESSION_LOGS...",
            "> SYNCING_WITH_NODE_1_AND_1.1...",
            "> HANDSHAKE_STABLE_VERIFIED.",
            "> ENTERING_PHASE_7_PARAMETERS...",
            "> STATUS: READY_FOR_SYNC_INPUT."
        ];
        
        const term = document.getElementById('term');
        logs.forEach((l, i) => {
            setTimeout(() => { 
                const d = document.createElement('div'); 
                d.className = 'term-row ' + (l.startsWith('>') ? 'cmd' : ''); 
                d.textContent = l; 
                term.appendChild(d); 
                term.scrollTop = term.scrollHeight;
            }, i * 600);
        });
    </script>
</body>
</html>