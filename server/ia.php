<?php
$categoria = $_GET['categoria'] ?? 'azar';
$command = escapeshellcmd("python ../ai/predictor.py " . $categoria);
$output = shell_exec($command);
echo $output;
?>