<?php

require './config/database.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Vérification de l'accès
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et validation des données
    $description = $_POST['description'] ?? null;
    $date = $_POST['date'] ?? null;
    $heure = $_POST['heure'] ?? null;
    $lieu = $_POST['lieu'] ?? null;
    $nombre_de_place = $_POST['nombre_de_place'] ?? null;
    $tarif = $_POST['tarif'] ?? null;
    $organisateur_email = $_SESSION['email'] ?? null;

    if (!$description || !$date || !$heure || !$lieu || !$nombre_de_place || !$tarif) {
        die("❌ Tous les champs sont requis.");
    }

    // Récupération de l'ID de l'organisateur
    $stmt = $pdo->prepare("SELECT id_user FROM user WHERE email = :email");
    $stmt->execute(['email' => $organisateur_email]);
    $organisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$organisateur) {
        die("❌ Organisateur non trouvé.");
    }

    $organisateur_id = $organisateur['id'];

    // Gestion de l'upload d'image
    $imagePath = null;
    if (!empty($_FILES["image"]["name"]) && $_FILES["image"]["error"] === 0) {
        $uploadDir = __DIR__ . "/../uploads/";
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
            die("❌ Impossible de créer le dossier 'uploads/'.");
        }
        if (!is_writable($uploadDir)) {
            die("❌ Le dossier 'uploads/' n'a pas les bonnes permissions.");
        }

        $fileName = time() . "_" . basename($_FILES["image"]["name"]);
        $uploadFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $uploadFile)) {
            $imagePath = "uploads/" . $fileName;
        } else {
            die("❌ Échec du téléchargement de l'image.");
        }
    }

    // Insertion dans la base de données
    $stmt = $pdo->prepare("
        INSERT INTO evenements (description, date, heure, lieu, organisateurs, nombre_de_place, tarif, organisateur_id, image) 
        VALUES (:description, :date, :heure, :lieu, :organisateur_email, :nombre_de_place, :tarif, :organisateur_id, :image)
    ");
    
    $stmt->execute([
        'description' => $description,
        'date' => $date,
        'heure' => $heure,
        'lieu' => $lieu,
        'organisateur_email' => $organisateur_email,
        'nombre_de_place' => $nombre_de_place,
        'tarif' => $tarif,
        'organisateur_id' => $organisateur_id,
        'image' => $imagePath
    ]);

    header("Location: ./index.php?message=ajout_reussi");
    exit;
}
?>

<form method="POST" enctype="multipart/form-data" class="h-screen flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-2xl mb-6 font-['Irish_Grover']">Ajouter un événement</h1>

        <input type="text" name="description" placeholder="Description de l'événement" class="block w-64 mx-auto mb-4 p-2 rounded-[20px] bg-[#82D9E9] text-center">
        <input type="date" name="date" class="block w-64 mx-auto mb-4 p-2 rounded-[20px] bg-[#82D9E9] text-center">
        <input type="time" name="heure" class="block w-64 mx-auto mb-4 p-2 rounded-[20px] bg-[#82D9E9] text-center">
        <input type="text" name="lieu" placeholder="Lieu" class="block w-64 mx-auto mb-4 p-2 rounded-[20px] bg-[#82D9E9] text-center">
        <input type="number" name="nombre_de_place" placeholder="Nombre de places" class="block w-64 mx-auto mb-4 p-2 rounded-[20px] bg-[#82D9E9] text-center">
        <input type="number" name="tarif" placeholder="Tarif" class="block w-64 mx-auto mb-4 p-2 rounded-[20px] bg-[#82D9E9] text-center">
        <input type="file" name="image" accept="image/*" class="block w-64 mx-auto mb-4 p-2 rounded-[20px] bg-[#82D9E9] text-center">

        <button class="w-32 mx-auto p-2 rounded-[20px] bg-[#82D9E9] text-center">Ajouter</button>
    </div>
</form>