<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Irish+Grover&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Document</title>
</head>
<body class="bg-[#FDFDFA]">
    <header class="bg-[#87EAE5] h-[135px]">
        <div class="p-4 flex items-center justify-between">
            <!-- Section gauche : logo et boutons -->
            <div class="flex items-center space-x-8">
                <!-- Logo -->
                <img src="./public/assets/logo.png" alt="logo" class="w-131 h-95" />

                <!-- Boutons -->
                <div class="flex items-center space-x-4">
                    <button class="h-[50px] w-[145px] bg-[#82D9E9] text-black text-[20px] py-2 px-4 rounded-[20px] shadow-md font-['Irish_Grover']"><a href="?page=home">Accueil</a></button>
                    <button class="h-[50px] w-[145px] bg-[#82D9E9] text-black text-[20px] py-2 px-4 rounded-[20px] shadow-md font-['Irish_Grover']">Page Admin</button>
                </div>
            </div>

            <!-- Section droite : icône utilisateur -->
            <a href="?page=login"><img src="./public/assets/icone.png" alt="icone" class="fas fa-user text-xl" /></a>
        </div>
        
    </header>
