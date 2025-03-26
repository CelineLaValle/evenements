<?php
require './config/database.php';

// Vérifier si l'utilisateur est connecté et a le rôle "utilisateur"
if (isset($_SESSION['id_user']) && $_SESSION['role'] === 'utilisateur') {


    // Récupérer l'ID de l'événement depuis l'URL
    if (isset($_GET['id'])) {
        $eventId = (int)$_GET['id'];

        // Vérifiez si l'utilisateur est déjà inscrit à cet événement
        $stmtCheckInscription = $pdo->prepare("SELECT * FROM inscription WHERE id_evenements = :id_evenements AND id_user = :id_user");
        $stmtCheckInscription->bindValue(':id_evenements', $eventId, PDO::PARAM_INT);
        $stmtCheckInscription->bindValue(':id_user', $_SESSION['id_user'], PDO::PARAM_INT);
        $stmtCheckInscription->execute();
        $inscrit = $stmtCheckInscription->fetch(PDO::FETCH_ASSOC);





        if ($inscrit) {
            // Si l'utilisateur est inscrit, afficher le bouton pour se désinscrire
?>
            <form method="POST">
                <input type="hidden" name="id_evenements" value="<?= $eventId ?>">
                <p>Vous êtes inscrit à cet événement !</p>
                <button type="submit" name="unregister" class="bg-red-500 text-white py-2 px-4 rounded-lg">Se désinscrire</button>
            </form>
        <?php
        } else {
            // Sinon, afficher le bouton pour s'inscrire
        ?>
            <form method="POST">
                <input type="hidden" name="id_evenements" value="<?= $eventId ?>">
                <button type="submit" name="register" class="bg-green-500 text-white py-2 px-4 rounded-lg">S'inscrire</button>
            </form>
<?php
        }

        // Traitement de l'inscription
        if (isset($_POST['register'])) {
            // Si l'utilisateur n'est pas encore inscrit, on l'ajoute
            if (!$inscrit) {
                $stmtInscription = $pdo->prepare("INSERT INTO inscription (id_evenements, id_user) VALUES (:id_evenements, :id_user)");
                $stmtInscription->bindValue(':id_evenements', $eventId, PDO::PARAM_INT);
                $stmtInscription->bindValue(':id_user', $_SESSION['id_user'], PDO::PARAM_INT);
                $stmtInscription->execute();
                
                header("Location: ?page=detail_event&id=$eventId");
        

                // Message de confirmation
                echo "<p class='text-green-500'>Vous êtes inscrit à cet événement !</p>";
            } else {
                echo "<p class='text-yellow-500'>Vous êtes déjà inscrit à cet événement.</p>";
            }
        }

        // Traitement de la désinscription
        if (isset($_POST['unregister'])) {
            // Si l'utilisateur est inscrit, on le supprime de la table des inscriptions
            if ($inscrit) {
                $stmtDesinscription = $pdo->prepare("DELETE FROM inscription WHERE id_evenements = :id_evenements AND id_user = :id_user");
                $stmtDesinscription->bindValue(':id_evenements', $eventId, PDO::PARAM_INT);
                $stmtDesinscription->bindValue(':id_user', $_SESSION['id_user'], PDO::PARAM_INT);
                $stmtDesinscription->execute();

                header("Location: ?page=detail_event&id=$eventId");

                // Message de confirmation
                echo "<p class='text-red-500'>Vous êtes désinscrit de cet événement.</p>";
            } else {
                echo "<p class='text-yellow-500'>Vous n'êtes pas inscrit à cet événement.</p>";
            }
        }
    }
} else {
    // Si l'utilisateur n'est pas connecté ou n'a pas le bon rôle, afficher un message d'erreur
    echo "<p class='text-red-500'>Vous devez être connecté en tant qu'utilisateur pour vous inscrire.</p>";
}
?>