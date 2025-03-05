<?php

require './config/database.php';

if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {

// Suppression d'un evenement
if (isset($_GET['supprimer'])) {
    $id = $_GET['id'];

    // Préparer la requête de suppression
    $stmt = $pdo->prepare("DELETE FROM evenements WHERE id = ?");
    $stmt->execute([$id]);

    // Redirection après la suppression
    echo '<meta http-equiv="refresh" content="0; url=?page=evenements">';
    exit();
}

// Modifier un evenement
if (isset($_GET['modifier'])) {
    $id = $_GET['id'];

    // Récupérer les informations de l'evenement à modifier
    $stmt = $pdo->prepare("SELECT * FROM evenements WHERE id = ?");
    $stmt->execute([$id]);
    $detailEvent = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($detailEvent) {
        $date = $detailEvent['date'];
        $heure = $detailEvent['heure'];
        $lieu = $detailEvent['lieu'];
        $organisateurs = $detailEvent['organisateurs'];
        $nombre_de_place = $detailEvent['nombre_de_place'];
        $tarif = $detailEvent['tarif'];
    } else {
        echo "Evenement introuvable.";
        exit;
    }
}

// Enregistrer les modifications
if (isset($_POST['modifier'])) {
    $id = $_POST['id'];
    $date = $_POST['date'];
    $heure = $_POST['heure'];
    $lieu = $_POST['lieu'];
    $organisateurs = $_POST['organisateurs'];
    $nombre_de_place = $_POST['nombre_de_place'];
    $tarif = $_POST['tarif'];

    // Mettre à jour l'evenement dans la base de données
    $stmt = $pdo->prepare("UPDATE evenements SET date = ?, heure = ?, lieu = ?, organisateurs = ?, nombre_de_place = ?, tarif = ? WHERE id = ?");
    $stmt->execute([$date, $heure, $lieu, $organisateurs, $nombre_de_place, $tarif, $id]);

    // Rediriger vers la page des organisateurs après la modification
    //header("Refresh: 0; url=?page=page=detail_event&id=$id");

    echo '<meta http-equiv="refresh" content="0; url=?page=evenements">';
    exit();
}}

?>


<!-- Formulaire de modification -->
<?php if (isset($date) && isset($detailEvent)): ?>
    <h3>Modifier l'événement</h3>
    <form method="post">
        <input type="hidden" name="id" value="<?= $detailEvent['id'] ?>">
        <input type="date" name="date" value="<?= htmlspecialchars($date) ?>" required>
        <input type="time" name="heure" value="<?= htmlspecialchars($heure) ?>" required>
        <input type="text" name="lieu" value="<?= htmlspecialchars($lieu) ?>" required>
        <input type="text" name="organisateurs" value="<?= htmlspecialchars($organisateurs) ?>" required>
        <input type="number" name="nombre_de_place" value="<?= htmlspecialchars($nombre_de_place) ?>" required>
        <input type="number" name="tarif" value="<?= htmlspecialchars($tarif) ?>" required>
        <button type="submit" name="modifier">Modifier l'événement</button>
    </form>
<?php endif; ?>