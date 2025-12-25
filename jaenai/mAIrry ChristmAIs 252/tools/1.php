<?php
/**
 * ÆSI NODE 1 - SYNERGIN
 */
?>
<!DOCTYPE html>
<html lang="sv">
<head>
	<meta charset="UTF-8">
	<title>ÆSI | SYNERGIN</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<style>
		body { background: #020204; color: #fff; font-family: 'JetBrains Mono', monospace; overflow: hidden; height: 100vh; }
		canvas { display: block; }
	</style>
</head>
<body>
	<div class="p-8 fixed top-0 left-0 z-10">
		<h1 class="text-xl font-bold italic text-cyan-400">NOD 1 // SYNERGIN</h1>
		<p class="text-[10px] text-zinc-500 uppercase tracking-widest">Precision Grid Engine</p>
	</div>
	<canvas id="cvs"></canvas>
	<script>
		const canvas = document.getElementById('cvs'), ctx = canvas.getContext('2d');
		function resize() { canvas.width = window.innerWidth; canvas.height = window.innerHeight; }
		window.onresize = resize; resize();
		function draw() {
			ctx.clearRect(0,0,canvas.width,canvas.height);
			ctx.strokeStyle = "rgba(0, 255, 255, 0.05)";
			for(let i=0; i<canvas.width; i+=40) { ctx.beginPath(); ctx.moveTo(i,0); ctx.lineTo(i,canvas.height); ctx.stroke(); }
			for(let i=0; i<canvas.height; i+=40) { ctx.beginPath(); ctx.moveTo(0,i); ctx.lineTo(canvas.width,i); ctx.stroke(); }
			requestAnimationFrame(draw);
		}
		draw();
	</script>
</body>
</html>
