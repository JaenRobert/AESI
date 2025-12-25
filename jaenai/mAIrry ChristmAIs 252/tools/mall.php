<?php
// Intention Tool – Modul för att skicka meddelanden
header('Content-Type: text/html; charset=utf-8');
echo '<h1>Intention Tool</h1>';
echo '<form method="post"><input name="msg" placeholder="Skriv din intention..."><button>Skicka</button></form>';
if($_SERVER['REQUEST_METHOD']==='POST' && !empty($_POST['msg'])){
    echo '<p>Intention mottagen: '.htmlspecialchars($_POST['msg']).'</p>';
}
