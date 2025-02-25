
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
    }
}

include "./templates/header.php"; 

    $page = $_GET["page"]??"home";
    include ("./views/$page.php");

    include "./templates/footer.php";
?>


