<div class="relative inline-block mb-3 text-sm">

    <button id="colToggleBtn"
        class="px-3 py-2 border hover:bg-gray-50 transition">
        Columns <i class="fa-solid fa-chevron-down fa-xs"></i>
    </button>

    <div id="colDropdown"
        class="hidden absolute z-10 mt-2 w-fit bg-white border shadow-md p-2">

        <?php foreach ($columns as $col => $label): ?>
            <label class="flex items-center gap-2 px-2 py-1 hover:bg-gray-50 cursor-pointer">
                <input type="checkbox" checked data-col="<?= $col ?>" class="accent-red-800">
                <span><?= htmlspecialchars($label) ?></span>
            </label>
        <?php endforeach; ?>

    </div>
</div>
