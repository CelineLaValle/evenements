<?php
require_once './config/database.php';
require_once "./utils/create_user.php";

// Ajouter un utilisateur
if (isset($_POST['ajouter'])) {
    // Récupérer les données et sécuriser contre XSS
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $mot_de_passe = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hachage du mot de passe

    // Vérifier si l'email est déjà utilisé
    if (emailExiste($pdo, $email)) {
        header("Location: index.php?page=utilisateurs&message=erreur_inscription_email_utilise");
        exit();
    } else {
        // Ajouter l'utilisateur
        if (ajouterUtilisateur($pdo, $nom, $prenom, $email, $mot_de_passe)) {
            header("Location: index.php?page=utilisateurs&message=inscription_reussie");
            exit();
        } else {
            header("Location: index.php?page=utilisateurs&message=erreur_inscription");
            exit();
        }
    }
}

// Supprimer un utilisateur
if (isset($_GET['supprimer'])) {
    $stmt = $pdo->prepare("DELETE FROM user WHERE id_user = ?");
    $stmt->execute([$_GET['supprimer']]);
    header("Location: ?page=utilisateurs");
    exit();
}

// Suspendre ou réactiver un utilisateur
if (isset($_GET['suspendre'])) {
    $id = (int)$_GET['suspendre'];

    // Récupérer l'état actuel
    $stmt = $pdo->prepare("SELECT suspendu FROM user WHERE id_user = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Inverser l'état de suspension
        $nouvelEtat = $user['suspendu'] ? 0 : 1;

        // Mettre à jour la base de données
        $stmt = $pdo->prepare("UPDATE user SET suspendu = ? WHERE id_user = ?");
        $stmt->execute([$nouvelEtat, $id]);

        header("Location: ?page=utilisateurs");
        exit();
    }
}

// Modifier un utilisateur (récupération des données)
$utilisateur = null;
if (isset($_GET['modifier'])) {
    $stmt = $pdo->prepare("SELECT * FROM user WHERE id_user = ?");
    $stmt->execute([$_GET['modifier']]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Enregistrer les modifications
if (isset($_POST['modifier'])) {
    modifierUtilisateur($pdo,  $_POST['id'], $_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['password']);
    header("Location: ?page=utilisateurs");
    exit();
}

// Récupérer tous les utilisateurs
$stmt = $pdo->query("SELECT * FROM user");
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<?php include './views/bouton.php'; ?>

<!-- Formulaire d'ajout -->
<div class="m-6">
<h3 class="text-2xl font-semibold mt-4 mb-4">Ajouter un Utilisateur</h3>
<form method="POST" class="mb-5 space-y-2">
    <input type="text" name="nom" placeholder="Nom" required class="border border-gray-700 p-2 rounded">
    <input type="text" name="prenom" placeholder="Prénom" required class="border border-gray-700 p-2 rounded">
    <input type="email" name="email" placeholder="Email" required class="border border-gray-700 p-2 rounded">
    <input type="password" name="password" placeholder="Mot De Passe" required class="border border-gray-700 p-2 rounded">
    <button type="submit" name="ajouter" class="bg-[#82D9E9] text-black px-4 py-2 rounded-[20px] shadow-md font-['Irish_Grover']">Ajouter</button>
</form>
</div>

<!-- Formulaire de modification -->
<?php if ($utilisateur): ?>
    <div class="m-6">
    <h3 class="text-2xl font-semibold mt-4 mb-4">Modifier un Utilisateur</h3>
    <form method="post" class="mb-5 space-y-2">
        <input type="hidden" name="id" value="<?= $utilisateur['id_user'] ?>">
        <input type="text" name="nom" value="<?= htmlspecialchars($utilisateur['nom']) ?>" required class="border border-gray-700 p-2 rounded">
        <input type="text" name="prenom" value="<?= htmlspecialchars($utilisateur['prenom']) ?>" required class="border border-gray-700 p-2 rounded">
        <input type="email" name="email" value="<?= htmlspecialchars($utilisateur['email']) ?>" required class="border border-gray-700 p-2 rounded">
        <input type="password" name="password" required class="border border-gray-700 p-2 rounded">
        <!-- Champ pour modifier le rôle -->
        <select name="role" required class="border border-gray-700 p-2 rounded">
            <option value="utilisateur" <?= $utilisateur['role'] === 'utilisateur' ? 'selected' : '' ?>>Utilisateur</option>
            <option value="admin" <?= $utilisateur['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>
        <button type="submit" name="modifier" class="bg-[#82D9E9] text-black px-4 py-2 rounded-[20px] shadow-md font-['Irish_Grover']">Modifier</button>
    </form>
    </div>
<?php endif; ?>

<!-- Liste des utilisateurs -->
<h3 class="text-xl font-semibold m-6">Liste des Utilisateurs</h3>
<div class="m-12">
    <div class="overflow-x-auto">
        <table class="w-full bg-white shadow-md rounded-lg overflow-hidden border border-gray-700">
            <thead class="bg-[#87EAE5] text-black border-b-2 border-gray-700">
                <tr>
                    <th class="py-3 px-4 border-r border-gray-700">ID</th>
                    <th class="py-3 px-4 border-r border-gray-700">Nom</th>
                    <th class="py-3 px-4 border-r border-gray-700">Prénom</th>
                    <th class="py-3 px-4 border-r border-gray-700">Email</th>
                    <th class="py-3 px-4 border-gray-700">Action</th>
                </tr>
                </thead>
                <?php foreach ($utilisateurs as $index => $org): ?>
                    <tr class="border-b-2 border-gray-700 <?= $index % 2 === 0 ? 'bg-[#E9BDCC] hover:bg-[#D79CAF]' : 'bg-[#D9F1F2] hover:bg-[#B8E0E2]' ?>">
            <td class="py-3 px-4 border-r border-gray-700"><?= $org['id_user'] ?></td>
            <td class="py-3 px-4 border-r border-gray-700"><?= htmlspecialchars($org['nom']) ?></td>
            <td class="py-3 px-4 border-r border-gray-700"><?= htmlspecialchars($org['prenom']) ?></td>
            <td class="py-3 px-4 border-r border-gray-700"><?= htmlspecialchars($org['email']) ?></td>
            <td class="py-3 px-4 border-gray-700 flex justify-center space-x-2">
                <a href="?page=utilisateurs&modifier=<?= $org['id_user'] ?>" class="h-[50px] w-[145px] bg-[#82D9E9] text-black text-[16px] py-2 px-4 rounded-[20px] shadow-md font-['Irish_Grover'] text-center flex items-center justify-center">✏️ Modifier</a>
                <a href="?page=utilisateurs&supprimer=<?= $org['id_user'] ?>" class="h-[50px] w-[145px] bg-[#E9AFA3] text-white text-[16px] py-2 px-4 rounded-[20px] shadow-md font-['Irish_Grover'] text-center flex items-center justify-center">🗑️ Supprimer</a>

                <?php if ($org['suspendu']) : ?>
                    <a href="?page=utilisateurs&suspendre=<?= $org['id_user'] ?>" class="text-green-500 h-[50px] w-[145px] bg-[#82D9E9] text-[16px] py-2 px-4 rounded-[20px] shadow-md font-['Irish_Grover'] text-center flex items-center justify-center">✅ Réactiver</a>
                <?php else : ?>
                    <a href="?page=utilisateurs&suspendre=<?= $org['id_user'] ?>" class="text-red-500 h-[50px] w-[145px] bg-[#82D9E9] text-[16px] py-2 px-4 rounded-[20px] shadow-md font-['Irish_Grover'] text-center flex items-center justify-center">🚫 Suspendre</a>
                <?php endif; ?>
            </td>
            </tr>
        <?php endforeach; ?>
        </table>
    </div>
</div>