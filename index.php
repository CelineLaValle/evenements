
<?php include "./templates/header.php"; 

    $page = $_GET["page"]??"home";
    include ("./views/$page.php");

    include "./templates/footer.php";
?>