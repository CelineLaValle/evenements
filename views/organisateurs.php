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

<h2>Gestion des Organisateurs</h2>

<!-- Formulaire d'ajout -->
<h3>Ajouter un Organisateur</h3>
<form method="post">
    <input type="text" name="nom" placeholder="Nom" required>
    <input type="text" name="tel" placeholder="Téléphone" required>
    <button type="submit" name="ajouter">Ajouter</button>
</form>

<!-- Formulaire de modification -->
<?php if ($organisateur): ?>
    <h3>Modifier un Organisateur</h3>
    <form method="post">
        <input type="hidden" name="id" value="<?= $organisateur['id'] ?>">
        <input type="text" name="nom" value="<?= htmlspecialchars($organisateur['nom']) ?>" required>
        <input type="text" name="tel" value="<?= htmlspecialchars($organisateur['tel']) ?>" required>
        <button type="submit" name="modifier">Modifier</button>
    </form>
<?php endif; ?>

<!-- Liste des organisateurs -->
<h3>Liste des Organisateurs</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Téléphone</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($organisateurs as $org): ?>
        <tr>
            <td><?= $org['id'] ?></td>
            <td><?= htmlspecialchars($org['nom']) ?></td>
            <td><?= htmlspecialchars($org['tel']) ?></td>
            <td>
                <a href="?page=organisateurs&modifier=<?= $org['id'] ?>">✏️ Modifier</a> |
                <a href="?page=organisateurs&supprimer=<?= $org['id'] ?>">🗑️ Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
