
<?php 

if (isset($_GET['message'])) {
    if ($_GET['message'] == "inscription_reussie") {
        echo "<div style='background-color: #d4edda; color: #155724; padding: 10px; text-align: center; border-radius: 5px;'>
                Inscription réussie !
              </div>";
    } elseif ($_GET['message'] == "erreur_inscription") {
        echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; text-align: center; border-radius: 5px;'>
                Erreur lors de l'inscription. Veuillez réessayer.
              </div>";
    } elseif ($_GET['message'] == "erreur_inscription_email_utilise") {
        echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; text-align: center; border-radius: 5px;'>
                Cet email est déjà utilisé.
              </div>";
    }
}

include "./templates/header.php"; 

    $page = $_GET["page"]??"home";


    // Vérifier si la page demandée existe
$page_path = "./views/$page.php";  // Définir la variable de chemin
    // Si la page existe, l'inclure, sinon rediriger vers 404
if (file_exists($page_path)) {
    include $page_path;
} else {
    // Rediriger vers la page 404 si la page n'existe pas
    include "./views/404.php";
}

    include "./templates/footer.php";
?>


