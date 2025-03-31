<?php include './views/bouton.php';



require './config/database.php';

if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <a href="?page=add_event">
        <button class="h-[50px] w-[250px] bg-[#E9BDCC] text-black text-[20px] py-2 px-4 rounded-[20px] shadow-md font-['Irish_Grover'] mt-6 ml-6">
            Ajouter un événement
        </button>
    </a>
<?php endif; ?>


<?php // Récupération des evenements
$stmt = $pdo->query("SELECT * FROM evenements");
$evenements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="m-12">
    <div class="overflow-x-auto mb-6">
        <table class="w-full bg-white shadow-md rounded-lg overflow-hidden border border-gray-700">
            <thead class="bg-[#87EAE5] text-black border-b-2 border-gray-700">
                <tr class="border-gray-700">
                    <th class="py-3 px-4 border-r border-gray-700">ID</th>
                    <th class="py-3 px-4 border-r border-gray-700">Description</th>
                    <th class="py-3 px-4 border-r border-gray-700">Date</th>
                    <th class="py-3 px-4 border-r border-gray-700">Heure</th>
                    <th class="py-3 px-4 border-r border-gray-700">Lieu</th>
                    <th class="py-3 px-4 border-r border-gray-700">Organisateurs</th>
                    <th class="py-3 px-4 border-r border-gray-700">Nombre De Place</th>
                    <th class="py-3 px-4 border-r border-gray-700">Tarif</th>
                    <th class="py-3 px-4 border-r border-gray-700">Image</th>
                    <?php if ($isAdmin): ?>
                        <th class="py-3 px-4">Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <?php foreach ($evenements as $index => $org): ?>
                <tr class="border-b-2 border-gray-700 <?= $index % 2 === 0 ? 'bg-[#E9BDCC] hover:bg-[#D79CAF]' : 'bg-[#D9F1F2] hover:bg-[#B8E0E2]' ?>">
                    <td class="py-3 px-4 border-r border-gray-700"><?= $org['id'] ?></td>
                    <td class="py-3 px-4 border-r border-gray-700"><?= htmlspecialchars($org['description']) ?></td>
                    <td class="py-3 px-4 border-r border-gray-700"><?= htmlspecialchars($org['date']) ?></td>
                    <td class="py-3 px-4 border-r border-gray-700"><?= htmlspecialchars($org['heure']) ?></td>
                    <td class="py-3 px-4 border-r border-gray-700"><?= htmlspecialchars($org['lieu']) ?></td>
                    <td class="py-3 px-4 border-r border-gray-700"><?= htmlspecialchars($org['organisateurs']) ?></td>
                    <td class="py-3 px-4 border-r border-gray-700"><?= htmlspecialchars($org['nombre_de_place']) ?></td>
                    <td class="py-3 px-4 border-r border-gray-700"><?= htmlspecialchars($org['tarif']) ?></td>
                    <td class="py-3 px-4 border-r border-gray-700"><?= htmlspecialchars($org['image']) ?></td>
                    <td class="py-3 px-4 flex space-x-2">
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { ?>
                            <!-- Boutons Modifier et Supprimer -->
                            <a href="?page=modif_event&id=<?= $org['id'] ?>&modifier" class="h-[50px] w-[145px] bg-[#82D9E9] text-black text-[16px] py-2 px-4 rounded-[20px] shadow-md font-['Irish_Grover'] text-center flex items-center justify-center">✏️ Modifier</a>
                            <a href="?page=modif_event&id=<?= $org['id'] ?>&supprimer" class="h-[50px] w-[145px] bg-[#E9AFA3] text-white text-[16px] py-2 px-4 rounded-[20px] shadow-md font-['Irish_Grover'] text-center flex items-center justify-center">🗑️ Supprimer</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>