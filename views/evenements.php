<?php include './views/bouton.php' ?>

<?php
// Vérification du rôle : seul un organisateur peut ajouter un événement

require './config/database.php';

// Vérification si l'utilisateur est un organisateur
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'organisateur') {
    // Rediriger vers la page d'accueil si l'utilisateur n'est pas un organisateur
    header("Location: index.php");
    exit;
}

// Traitement du formulaire d'ajout d'événement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $description = $_POST['description'];
    $date = $_POST['date'];
    $heure = $_POST['heure'];
    $lieu = $_POST['lieu'];
    $nombre_de_place = $_POST['nombre_de_place'];
    $tarif = $_POST['tarif'];

    // L'email de l'organisateur est dans la session
    $organisateur_email = $_SESSION['email'];

    // Récupérer l'ID de l'organisateur
    $stmt = $pdo->prepare("SELECT id FROM user WHERE email = :email");
    $stmt->execute(['email' => $organisateur_email]);
    $organisateur = $stmt->fetch(PDO::FETCH_ASSOC);
    $organisateur_id = $organisateur['id'];  // Récupère l'ID de l'organisateur

    // Insertion dans la table des événements
    $stmt = $pdo->prepare("INSERT INTO evenements (description, date, heure, lieu, organisateurs, nombre_de_place, tarif, organisateur_id) 
                           VALUES (:description, :date, :heure, :lieu, :organisateur_email, :nombre_de_place, :tarif, :organisateur_id)");
    $stmt->execute([
        'description' => $description,
        'date' => $date,
        'heure' => $heure,
        'lieu' => $lieu,
        'organisateur_email' => $organisateur_email,
        'nombre_de_place' => $nombre_de_place,
        'tarif' => $tarif,
        'organisateur_id' => $organisateur_id
    ]);

    // Rediriger après l'ajout de l'événement
    header("Location: ?page=evenements");
    exit;
}
?>

<form method="POST" class="h-screen flex items-center justify-center">
  <div class="text-center">
    <h1 class="text-2xl mb-6 font-['Irish_Grover']">Ajouter un événement</h1>

    <input type="text" name="description" placeholder="Description de l'événement" class="block w-64 mx-auto mb-4 p-2 rounded-[20px] bg-[#82D9E9] text-center text-gray-500 font-['Irish_Grover']">
    <input type="date" name="date" placeholder="Date" class="block w-64 mx-auto mb-4 p-2 rounded-[20px] bg-[#82D9E9] text-center text-gray-500 font-['Irish_Grover']">
    <input type="time" name="heure" placeholder="Heure" class="block w-64 mx-auto mb-4 p-2 rounded-[20px] bg-[#82D9E9] text-center text-gray-500 font-['Irish_Grover']">
    <input type="text" name="lieu" placeholder="Lieu" class="block w-64 mx-auto mb-4 p-2 rounded-[20px] bg-[#82D9E9] text-center text-gray-500 font-['Irish_Grover']">
    <input type="number" name="nombre_de_place" placeholder="Nombre de places" class="block w-64 mx-auto mb-4 p-2 rounded-[20px] bg-[#82D9E9] text-center text-gray-500 font-['Irish_Grover']">
    <input type="number" name="tarif" placeholder="Tarif" class="block w-64 mx-auto mb-4 p-2 rounded-[20px] bg-[#82D9E9] text-center text-gray-500 font-['Irish_Grover']">

    <button class="w-32 mx-auto p-2 rounded-[20px] bg-[#82D9E9] text-center text-black font-['Irish_Grover']">Ajouter</button>
  </div>
</form>