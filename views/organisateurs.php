<?php
require './config/database.php';

// Ajouter un organisateur
if (isset($_POST['ajouter'])) {
    if (!empty($_POST['nom']) && !empty($_POST['tel'])) {
        $stmt = $pdo->prepare("INSERT INTO organisateurs (nom, tel) VALUES (?, ?)");
        $stmt->execute([$_POST['nom'], $_POST['tel']]);
    }
    header("Location: ?page=organisateurs");
    exit();
}

// Supprimer un organisateur
if (isset($_GET['supprimer'])) {
    $stmt = $pdo->prepare("DELETE FROM organisateurs WHERE id = ?");
    $stmt->execute([$_GET['supprimer']]);
    header("Location: ?page=organisateurs");
    exit();
}

// Modifier un organisateur (récupération des données)
$organisateur = null;
if (isset($_GET['modifier'])) {
    $stmt = $pdo->prepare("SELECT * FROM organisateurs WHERE id = ?");
    $stmt->execute([$_GET['modifier']]);
    $organisateur = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Enregistrer les modifications
if (isset($_POST['modifier'])) {
    if (!empty($_POST['id']) && !empty($_POST['nom']) && !empty($_POST['tel'])) {
        $stmt = $pdo->prepare("UPDATE organisateurs SET nom = ?, tel = ? WHERE id = ?");
        $stmt->execute([$_POST['nom'], $_POST['tel'], $_POST['id']]);
    }
    header("Location: ?page=organisateurs");
    exit();
}

// Récupérer tous les organisateurs
$stmt = $pdo->query("SELECT * FROM organisateurs");
$organisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<?php include './views/bouton.php'; ?>

<!-- Formulaire d'ajout -->
 <div class="m-6">
<h2 class="text-2xl font-semibold mt-4 mb-4">Ajouter un Organisateur</h2>
<form method="post" class="mb-5 space-y-2">
    <input type="text" name="nom" placeholder="Nom" required class="border border-gray-700 p-2 rounded">
    <input type="text" name="tel" placeholder="Téléphone" required class="border border-gray-700 p-2 rounded">
    <button type="submit" name="ajouter" class="bg-[#82D9E9] text-black px-4 py-2 rounded-[20px] shadow-md font-['Irish_Grover']">Ajouter</button>
</form>
 </div>

<!-- Formulaire de modification -->
<?php if ($organisateur): ?>
    <div class="m-6">
    <h3 class="text-2xl font-semibold mt-4 mb-4">Modifier un Organisateur</h3>
    <form method="post" class="mb-5 space-y-2">
        <input type="hidden" name="id" value="<?= $organisateur['id'] ?>">
        <input type="text" name="nom" value="<?= htmlspecialchars($organisateur['nom']) ?>" required class="border border-gray-700 p-2 rounded">
        <input type="text" name="tel" value="<?= htmlspecialchars($organisateur['tel']) ?>" required class="border border-gray-700 p-2 rounded">
        <button type="submit" name="modifier" class="bg-[#82D9E9] text-black px-4 py-2 rounded-[20px] shadow-md font-['Irish_Grover']">Modifier</button>
    </form>
    </div>
<?php endif; ?>

<!-- Liste des organisateurs -->
<h3 class="text-xl font-semibold m-6">Liste des Organisateurs</h3>
<div class="m-12">
    <div class="overflow-x-auto">
        <table class="w-full bg-white shadow-md rounded-lg overflow-hidden border border-gray-700">
            <thead class="bg-[#87EAE5] text-black border-b-2 border-gray-700">
                <tr>
                    <th class="py-3 px-4 border-r border-gray-700">ID</th>
                    <th class="py-3 px-4 border-r border-gray-700">Nom</th>
                    <th class="py-3 px-4 border-r border-gray-700">Téléphone</th>
                    <th class="py-3 px-4 border-gray-700">Actions</th>
                </tr>
            </thead>
            <?php foreach ($organisateurs as $index => $org): ?>
                <tr class="border-b-2 border-gray-700 <?= $index % 2 === 0 ? 'bg-[#E9BDCC] hover:bg-[#D79CAF]' : 'bg-[#D9F1F2] hover:bg-[#B8E0E2]' ?>">
                    <td class="py-3 px-4 border-r border-gray-700"><?= $org['id'] ?></td>
                    <td class="py-3 px-4 border-r border-gray-700"><?= htmlspecialchars($org['nom']) ?></td>
                    <td class="py-3 px-4 border-r border-gray-700"><?= htmlspecialchars($org['tel']) ?></td>
                    <td class="py-3 px-4 border-gray-700 flex justify-center space-x-2">
                        <a href="?page=organisateurs&modifier=<?= $org['id'] ?>" class="h-[50px] w-[145px] bg-[#82D9E9] text-black text-[16px] py-2 px-4 rounded-[20px] shadow-md font-['Irish_Grover'] text-center flex items-center justify-center">✏️ Modifier</a>
                        <a href="?page=organisateurs&supprimer=<?= $org['id'] ?>" class="h-[50px] w-[145px] bg-[#E9AFA3] text-white text-[16px] py-2 px-4 rounded-[20px] shadow-md font-['Irish_Grover'] text-center flex items-center justify-center">🗑️ Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>