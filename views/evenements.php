<?php include './views/bouton.php'; 
    

        
        require './config/database.php';

        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="?page=add_event">
                <button class="h-[50px] w-[250px] bg-[#82D9E9] text-black text-[20px] py-2 px-4 rounded-[20px] shadow-md font-['Irish_Grover'] mb-5 ml-4">
                    Ajouter un événement
                </button>
            </a>
        <?php endif; ?>


       <?php // Récupération des evenements
$stmt = $pdo->query("SELECT * FROM evenements");
$evenements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
        <table>
            <tr>
                <th>ID</th>
                <th>Description</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Lieu</th>
                <th>Organisateurs</th>
                <th>Nombre De Place</th>
                <th>Tarif</th>
                <th>Image</th>
            </tr>
            <?php foreach ($evenements as $org): ?>
                <tr>
                    <td><?= $org['id'] ?></td>
                    <td><?= htmlspecialchars($org['description']) ?></td>
                    <td><?= htmlspecialchars($org['date']) ?></td>
                    <td><?= htmlspecialchars($org['heure']) ?></td>
                    <td><?= htmlspecialchars($org['lieu']) ?></td>
                    <td><?= htmlspecialchars($org['organisateurs']) ?></td>
                    <td><?= htmlspecialchars($org['nombre_de_place']) ?></td>
                    <td><?= htmlspecialchars($org['tarif']) ?></td>
                    <td><?= htmlspecialchars($org['image']) ?></td>
                    <td>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { ?>
<!-- Boutons Modifier et Supprimer -->
<a href="?page=modif_event&id=<?= $org['id'] ?>&modifier">✏️ Modifier</a>
<a href="?page=modif_event&id=<?= $org['id'] ?>&supprimer">🗑️ Supprimer</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>