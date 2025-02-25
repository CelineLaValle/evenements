<?php $current_page = $_GET['page'] ?>

<div class="flex items-center space-x-4">
<button class="<?php echo $current_page === 'graphique' ? 'bg-[#D9F1F2]' : 'bg-[#82D9E9]'; ?> h-[50px] w-[145px] text-black text-[16px] py-2 px-4 rounded-[20px] shadow-md font-['Irish_Grover']">
            <a href="?page=graphique">Graphique</a>
        </button>
        <button class="<?php echo $current_page === 'evenements' ? 'bg-[#D9F1F2]' : 'bg-[#82D9E9]'; ?> h-[50px] w-[145px] text-black text-[16px] py-2 px-4 rounded-[20px] shadow-md font-['Irish_Grover']">
            <a href="?page=evenements">Évènements</a>
        </button>
        <button class="<?php echo $current_page === 'organisateurs' ? 'bg-[#D9F1F2]' : 'bg-[#82D9E9]'; ?> h-[50px] w-[145px] text-black text-[16px] py-2 px-4 rounded-[20px] shadow-md font-['Irish_Grover']">Organisateurs</button>
        <button class="<?php echo $current_page === 'utilisateurs' ? 'bg-[#D9F1F2]' : 'bg-[#82D9E9]'; ?> h-[50px] w-[145px] text-black text-[16px] py-2 px-4 rounded-[20px] shadow-md font-['Irish_Grover']">Utilisateurs</button>
</div>