<?php

// Inclure la configuration de la base de données
require './config/database.php';

// Nombre d'événements par page
$evenementsParPage = 4;

// Récupérer la page actuelle pour la pagination
$pagination = isset($_GET['pagination']) && $_GET['pagination'] > 0 ? (int)$_GET['pagination'] : 1;

// Définir le tri par défaut (date si aucun tri défini)
$tri = isset($_GET['tri']) ? $_GET['tri'] : 'date';

// Vérifier que le tri est valide pour éviter les injections SQL
$colonnes_valides = ['date', 'heure'];
$tri = in_array($tri, $colonnes_valides) ? $tri : 'date';

// Calculer l'offset (toujours positif ou 0)
$offset = max(0, ($pagination - 1) * $evenementsParPage);

// Récupérer le nombre total d'événements pour la pagination
$stmtTotal = $pdo->query("SELECT COUNT(*) FROM evenements");
$totalEvents = $stmtTotal->fetchColumn();

// Calculer le nombre total de pages
$totalPages = ceil($totalEvents / $evenementsParPage);

// Vérifier si la page actuelle est supérieure au nombre total de pages
if ($pagination > $totalPages) {
    $pagination = $totalPages;
}

// Récupérer les événements triés et paginés
if ($tri == 'heure') {
    $sql = "SELECT * FROM evenements ORDER BY TIME(heure) ASC, DATE(date) ASC LIMIT :limit OFFSET :offset";
} else {
    $sql = "SELECT * FROM evenements ORDER BY DATE(date) ASC, TIME(heure) ASC LIMIT :limit OFFSET :offset";
}
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $evenementsParPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$evenements = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!-- Affichage des événements -->
<div class="flex flex-wrap items-center justify-center w-full gap-10 p-8">
    <?php foreach ($evenements as $event): ?>
        <div class="w-[35%] bg-[#82D9E9] h-[300px] rounded-[20px] p-4 shadow-lg flex flex-col justify-between hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold"><?= htmlspecialchars($event['description']) ?></h2>
                    <p class="text-sm"><strong>Date :</strong> <?= date('d/m/Y', strtotime($event['date'])) ?></p>
                    <p class="text-sm"><strong>Heure :</strong> <?= date('H:i', strtotime($event['heure'])) ?></p>
                    <p class="text-sm"><strong>Lieu :</strong> <?= htmlspecialchars($event['lieu']) ?></p>
                </div>
                <div class="ml-auto">
                    <img src="<?= htmlspecialchars($event['image']) ?>" alt="Image de l'événement" class="w-[200px] h-[200px] rounded-xl shadow-lg object-cover">
                </div>
            </div>

            <!-- Bouton cliquable avec lien autour -->
            <a href="?page=detail_event&id=<?= $event['id']?>" class="w-full">
                <button class="mt-4 w-full bg-white text-[#82D9E9] font-bold py-2 px-4 rounded-lg shadow">
                    Voir l'événement
                </button>
            </a>
        </div>
    <?php endforeach; ?>
</div>

<!-- Pagination -->
<div class="mb-4 flex justify-center items-center mt-4 space-x-4">
    <?php if ($pagination > 1) : ?>
        <a href="?page=home&pagination=<?= $pagination - 1 ?>&tri=<?= $tri ?>" 
           class="px-4 py-2 bg-[#82D9E9] rounded-lg shadow-md">Précédent</a>
    <?php endif; ?>

    <!-- Sélecteur avec "X / Y" -->
    <form method="GET">
        <input type="hidden" name="page" value="home">
        <input type="hidden" name="tri" value="<?= $tri ?>">
        <select name="pagination" class="px-3 py-2 border rounded-lg" onchange="this.form.submit()">
            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <option value="<?= $i ?>" <?= $i == $pagination ? 'selected' : '' ?>>
                    <?= $i ?> / <?= $totalPages ?>
                </option>
            <?php endfor; ?>
        </select>
    </form>

    <?php if ($pagination < $totalPages) : ?>
        <a href="?page=home&pagination=<?= $pagination + 1 ?>&tri=<?= $tri ?>" 
           class="px-4 py-2 bg-[#82D9E9] rounded-lg shadow-md">Suivant</a>
    <?php endif; ?>
</div>

<!-- Formulaire de tri -->
<form method="GET" class="flex items-center space-x-4 p-4 bg-[#f1f1f1] rounded-lg shadow-lg">
    <input type="hidden" name="page" value="home">

    <label for="tri" class="text-lg font-semibold text-gray-700">Trier par :</label>

    <select name="tri" onchange="this.form.submit()" class="border border-gray-300 p-2 rounded-lg bg-white text-gray-700 focus:ring-2 focus:ring-[#82D9E9]">
        <option value="date" <?= $tri == 'date' ? 'selected' : '' ?> class="text-gray-700">Date</option>
        <option value="heure" <?= $tri == 'heure' ? 'selected' : '' ?> class="text-gray-700">Heure</option>
    </select>
</form>
