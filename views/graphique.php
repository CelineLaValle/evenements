<?php include './views/bouton.php' ?>
<?php
// Connexion à la base de données
require_once './config/database.php';


// Récupérer les données des utilisateurs et organisateurs
$queryOrganisateurs = "SELECT COUNT(*) FROM organisateurs";
$queryUtilisateurs = "SELECT COUNT(*) FROM user";

$organisateurs = $pdo->query($queryOrganisateurs)->fetchColumn();
$utilisateurs = $pdo->query($queryUtilisateurs)->fetchColumn();

// Calculer les pourcentages
$total = $organisateurs + $utilisateurs;
if ($total > 0) {
    $organisateurs_percent = ($organisateurs / $total) * 100;
    $utilisateurs_percent = ($utilisateurs / $total) * 100;
} else {
    $organisateurs_percent = 0;
    $utilisateurs_percent = 0;
}

// Calculer les angles pour les arcs
$organisateurs_angle = $organisateurs_percent * 3.6; // Convertir pourcentage en angle
$utilisateurs_angle = $utilisateurs_percent * 3.6; // Convertir pourcentage en angle

// Calculer les points d'arc (en coordonnées polaires)
function calculateArcPoints($cx, $cy, $r, $angleStart, $angleEnd) {
    // Convertir les angles en radians
    $angleStart = deg2rad($angleStart);
    $angleEnd = deg2rad($angleEnd);

    // Calculer les coordonnées des points de départ et de fin
    $xStart = $cx + $r * cos($angleStart);
    $yStart = $cy + $r * sin($angleStart);

    $xEnd = $cx + $r * cos($angleEnd);
    $yEnd = $cy + $r * sin($angleEnd);

    return [$xStart, $yStart, $xEnd, $yEnd];
}
?>


<div class="flex justify-center items-center min-h-screen">
    <!-- Graphique SVG -->
    <svg xmlns="http://www.w3.org/2000/svg" width="600" height="600" viewBox="0 0 600 600">
        <circle cx="300" cy="300" r="240" stroke="black" stroke-width="20" fill="none" />
        
        <?php
        // Organisateurs
        list($x1, $y1, $x2, $y2) = calculateArcPoints(300, 300, 240, 0, $organisateurs_angle);
        ?>
        <path d="M300,300 L<?= $x1 ?> <?= $y1 ?> A240,240 0 <?= ($organisateurs_angle > 180) ? 1 : 0 ?>,1 <?= $x2 ?> <?= $y2 ?> Z" 
              fill="#87EAE5" />
        
        <?php
        // Utilisateurs
        list($x1, $y1, $x2, $y2) = calculateArcPoints(300, 300, 240, $organisateurs_angle, $organisateurs_angle + $utilisateurs_angle);
        ?>
        <path d="M300,300 L<?= $x1 ?> <?= $y1 ?> A240,240 0 <?= ($utilisateurs_angle > 180) ? 1 : 0 ?>,1 <?= $x2 ?> <?= $y2 ?> Z" 
              fill="#E9BDCC" />

            <?= round($organisateurs_percent + $utilisateurs_percent, 2) ?>%
        </text> -->
    </svg>

    <!-- Légende -->
    <div class="flex justify-center mt-4 space-x-10">
        <div class="flex items-center">
            <div class="w-6 h-6 bg-[#87EAE5] mr-2"></div>
            <span>Organisateurs (<?= round($organisateurs_percent, 2) ?>%)</span>
        </div>
        <div class="flex items-center">
            <div class="w-6 h-6 bg-[#E9BDCC] mr-2"></div>
            <span>Utilisateurs (<?= round($utilisateurs_percent, 2) ?>%)</span>
        </div>
    </div>

</div>