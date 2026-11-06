<?php
// Script pour simuler automatiquement un tour de jeu à intervalle régulier

$script_to_run = __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'simuler_tour.php';

$default_interval = 60; 

set_time_limit(0);

$interval = isset($argv[1]) && is_numeric($argv[1]) ? (int)$argv[1] : $default_interval;

if (!file_exists($script_to_run)) {
    echo "Erreur : Le script '" . $script_to_run . "' est introuvable.\n";
    echo "Vérifiez que auto_simulate.php est bien dans le dossier racine du projet (ex: htdocs/virtual_trader-test/) et que le dossier 'public' existe.\n";
    exit(1);
}

echo "Lancement de la simulation automatique (toutes les {$interval} secondes)...\n";
echo "Script cible : {$script_to_run}\n";
echo "Appuyez sur Ctrl+C pour arrêter.\n";

while (true) {
    echo "[" . date("Y-m-d H:i:s") . "] Simulation d'un nouveau tour...\n";

    $php_executable = 'php';
    $command = $php_executable . ' ' . escapeshellarg($script_to_run);
    shell_exec($command);
    
    echo "[" . date("Y-m-d H:i:s") . "] Tour simulé. Prochaine simulation dans {$interval} secondes.\n";
    sleep($interval);
}

?>

