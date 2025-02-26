<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <div class="flex flex-wrap items-center justify-center w-full gap-20 p-8">
        <p class="flex w-[35%] bg-[#82D9E9] h-[300px] rounded-[20px]"></p>
        <p class="flex w-[35%] bg-[#82D9E9] h-[300px] rounded-[20px]"></p>
        <p class="flex w-[35%] bg-[#82D9E9] h-[300px] rounded-[20px]"></p>
        <p class="flex w-[35%] bg-[#82D9E9] h-[300px] rounded-[20px]"></p>
    </div>

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'organisateur'): ?>
        <a href="?page=evenements">
            <button class="h-[50px] w-[200px] bg-[#82D9E9] text-black text-[20px] py-2 px-4 rounded-[20px] shadow-md font-['Irish_Grover']">
                Ajouter un événement
            </button>
        </a>
    <?php endif; ?>


</body>

</html>