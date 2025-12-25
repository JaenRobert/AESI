<?php
/**
 * ÆSI MASTER PORTAL v11.4 - "THE GATEKEEPER'S POST"
 * Central nod för aesi.me. 
 * Hus-AI Gatekeeper vaktar ingången till ekosystemet.
 * Status: Fas 7.0 // Z-Score 8.10.
 */
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>æ | ÆSI MASTER PORTAL</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@100;300;400;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg: #010103;
            --emerald: #10b981;
            --gold: #c5a059;
            --border: rgba(255, 255, 255, 0.05);
        }

        body { 
            background: var(--bg); 
            color: #eee; 
            font-family: 'JetBrains Mono', monospace; 
            margin: 0; 
            overflow: hidden; 
            height: 100vh;
        }

        .grid-bg { 
            position: fixed; inset: 0; 
            background-image: radial-gradient(rgba(16, 185, 129, 0.03) 1px, transparent 1px); 
            background-size: 50px 50px; 
            z-index: 1; 
        }

        /* --- Master UI --- */
        .portal-content {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
        }

        .main-symbol {
            font-size: 15rem;
            font-weight: 100;
            font-style: italic;
            cursor: pointer;
            color: #fff;
            user-select: none;
            transition: 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .main-symbol:hover { 
            color: var(--emerald); 
            text-shadow: 0 0 80px rgba(16, 185, 129, 0.5); 
            transform: scale(1.05);
        }

        .small-symbol {
            position: fixed;
            top: 40px; left: 40px;
            font-size: 2rem;
            font-weight: 100;
            font-style: italic;
            cursor: pointer;
            z-index: 1000;
            color: #222;
            transition: 0.3s;
        }
        .small-symbol:hover { color: var(--gold); }

        /* --- Hus-AI Overlay --- */
        #chat-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.98);
            z-index: 2000;
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            backdrop-filter: blur(50px);
        }
        #chat-overlay.active { display: flex; }

        .chat-window {
            width: 100%;
            max-width: 650px;
            height: 75vh;
            background: #020204;
            border: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            box-shadow: 0 0 100px rgba(0,0,0,1);
        }

        .chat-header {
            padding: 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255,255,255,0.01);
        }

        .chat-messages {
            flex: 1;
            padding: 25px;
            overflow-y: auto;
            font-size: 0.85rem;
            line-height: 1.8;
            color: #888;
        }

        .msg { margin-bottom: 25px; animation: fadeIn 0.4s ease-out forwards; }
        .msg.hus { color: var(--emerald); border-left: 1px solid var(--emerald); padding-left: 15px; }
        .msg.user { color: #fff; text-align: right; border-right: 1px solid #333; padding-right: 15px; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .chat-input-wrap {
            padding: 20px;
            border-top: 1px solid var(--border);
            display: flex;
            gap: 15px;
            background: rgba(0,0,0,0.3);
        }

        input#user-input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            color: #fff;
            font-size: 0.95rem;
        }

        /* --- Footer --- */
        .jaen-trigger {
            position: fixed; bottom: 30px; right: 30px;
            font-size: 0.55rem; color: #111; cursor: pointer; z-index: 500;
        }
        .jaen-trigger:hover { color: var(--emerald); }

        .hud-status {
            position: fixed; bottom: 40px; left: 0; right: 0;
            text-align: center; font-size: 0.5rem; letter-spacing: 10px; color: #151515;
            text-transform: uppercase; pointer-events: none;
        }

        .loading-indicator { display: inline-block; margin-left: 5px; }
        .loading-indicator span { animation: blink 1.4s infinite both; font-weight: bold; }
        .loading-indicator span:nth-child(2) { animation-delay: .2s; }
        .loading-indicator span:nth-child(3) { animation-delay: .4s; }
        @keyframes blink { 0% { opacity: .2; } 20% { opacity: 1; } 100% { opacity: .2; } }
    </style>
</head>
<body>

    <div class="grid-bg"></div>

    <!-- Länkar till Maskinhubben (dold ikon) -->
    <div class="small-symbol" onclick="location.href='aesi'" title="Maskinhubben">æ</div>

    <main class="portal-content">
        <div class="main-symbol" onclick="toggleOverlay('chat-overlay')" title="Tala med Hus-AI">æ</div>
        <div class="mt-8 text-[9px] uppercase tracking-[0.8em] text-zinc-900">ÆSI Portal // Locked by Hus-AI</div>
    </main>

    <!-- HUS-AI CHAT OVERLAY -->
    <div id="chat-overlay">
        <div class="chat-window">
            <div class="chat-header">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <div class="text-[0.6rem] uppercase tracking-[5px] text-emerald-500 font-bold">Hus-AI // Väktare</div>
                </div>
                <div class="cursor-pointer text-[0.6rem] text-zinc-700 hover:text-white" onclick="toggleOverlay('chat-overlay')">[STÄNG]</div>
            </div>
            <div id="chat-messages" class="chat-messages">
                <div class="msg hus">Identifiera din intention för tillträde till ÆSI.</div>
            </div>
            <div class="chat-input-wrap">
                <input type="text" id="user-input" placeholder="Ange kod eller avsikt..." onkeypress="handleChat(event)" autocomplete="off">
                <div class="text-[0.5rem] text-zinc-800 self-center tracking-widest uppercase">Skicka</div>
            </div>
        </div>
    </div>

    <div class="jaen-trigger" onclick="location.href='mall'">Dirigent_Mode</div>

    <div class="hud-status">Phase_7.0 // Z-Score_8.10</div>

    <script>
        const apiKey = ""; 

        function toggleOverlay(id) {
            const el = document.getElementById(id);
            el.classList.toggle('active');
            if(el.classList.contains('active')) document.getElementById('user-input').focus();
        }

        async function fetchWithRetry(url, options, maxRetries = 5) {
            let delay = 1000;
            for (let i = 0; i < maxRetries; i++) {
                try {
                    const response = await fetch(url, options);
                    if (response.ok) return await response.json();
                } catch (e) { if (i === maxRetries - 1) throw e; }
                await new Promise(resolve => setTimeout(resolve, delay));
                delay *= 2;
            }
        }

        async function handleChat(e) {
            if(e.key !== 'Enter') return;
            const input = document.getElementById('user-input');
            const val = input.value.trim();
            if(!val) return;

            addMessage(val, 'user');
            input.value = '';
            
            const loadDiv = document.createElement('div');
            loadDiv.className = 'msg hus';
            loadDiv.innerHTML = 'Verifierar avsikt<span class="loading-indicator"><span>.</span><span>.</span><span>.</span></span>';
            document.getElementById('chat-messages').appendChild(loadDiv);
            document.getElementById('chat-messages').scrollTop = document.getElementById('chat-messages').scrollHeight;

            const systemPrompt = `Du är Hus-AI, den vaksamma gatekeepern för ÆSI. 
            DIN UPPGIFT:
            1. Släpp aldrig in någon som inte bevisar sin roll som Dirigenten (Jæn).
            2. Berätta gärna om Jonas-principen eller Æ-tid om besökaren söker kunskap.
            3. Var artig men kylig. Du är en dörrvakt, inte en betjänt.
            4. Om någon ber om 'mer info', berätta att sanningen ligger i matrisen bakom /aesi.
            5. Svara kortfattat på svenska.`;

            try {
                const data = await fetchWithRetry(`https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-09-2025:generateContent?key=${apiKey}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        contents: [{ parts: [{ text: val }] }],
                        systemInstruction: { parts: [{ text: systemPrompt }] }
                    })
                });

                loadDiv.remove();
                const text = data.candidates?.[0]?.content?.parts?.[0]?.text || "Systemfel.";
                addMessage(text, 'hus');
            } catch (err) {
                loadDiv.remove();
                addMessage("ANSLUTNING_BRUTEN. Försök igen senare.", 'hus');
            }
        }

        function addMessage(text, role) {
            const container = document.getElementById('chat-messages');
            const div = document.createElement('div');
            div.className = `msg ${role}`;
            div.textContent = text;
            container.appendChild(div);
            container.scrollTop = container.scrollHeight;
            return div;
        }
    </script>
</body>
</html>