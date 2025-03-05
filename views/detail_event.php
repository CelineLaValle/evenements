<?php

require './config/database.php';

// Vérifie si l'ID est présent dans l'URL
if (isset($_GET['id'])) {
    $eventId = (int)$_GET['id'];

    // Requête pour récupérer les détails de l'événement
    $stmt = $pdo->prepare("SELECT * FROM evenements WHERE id = :id");
    $stmt->bindValue(':id', $eventId, PDO::PARAM_INT);
    $stmt->execute();
    $detailEvent = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($detailEvent) {
        // Affichage des détails de l'événement
        ?>
        <div class="container mx-auto p-8 flex gap-8 items-center">
            <!-- Image de l'événement -->
            <div class="w-1/2">
                <img src="<?= htmlspecialchars($detailEvent['image']) ?>" alt="Image de l'événement" class="w-full h-auto rounded-xl shadow-lg object-cover">
            </div>

            <!-- Détails de l'événement -->
            <div class="w-1/2 flex flex-col gap-6">
                <!-- Bloc titre -->
                <div class="bg-[#D9F1F2] p-6 rounded-xl shadow-lg text-center">
                    <h1 class="text-3xl font-bold"><?= htmlspecialchars($detailEvent['description']) ?></h1>
                </div>

                <!-- Bloc infos -->
                <div class="bg-[#D9F1F2] p-6 rounded-xl shadow-lg">
                    <p class="text-lg"><strong>Date :</strong> <?= date('d/m/Y', strtotime($detailEvent['date'])) ?></p>
                    <p class="text-lg"><strong>Heure :</strong> <?= htmlspecialchars($detailEvent['heure']) ?></p>
                    <p class="text-lg"><strong>Lieu :</strong> <?= htmlspecialchars($detailEvent['lieu']) ?></p>
                    <p class="text-lg"><strong>Organisateur :</strong> <?= htmlspecialchars($detailEvent['organisateurs']) ?></p>
                    <p class="text-lg"><strong>Nombre de places :</strong> <?= htmlspecialchars($detailEvent['nombre_de_place']) ?></p>
                    <p class="text-lg"><strong>Tarif :</strong> <?= htmlspecialchars($detailEvent['tarif']) ?> €</p>
                </div>
                <?php
                // Inclure les boutons d'inscription/désinscription
                require 'inscription_event.php';
                ?>
            </div>
        </div>
        <?php
    } else {
        // Message d'erreur si l'événement n'est pas trouvé
        echo "<p class='text-red-500'>Événement non trouvé.</p>";
    }
} else {
    // Message d'erreur si l'ID n'est pas fourni
    echo "<p class='text-red-500'>ID d'événement non fourni.</p>";
}

