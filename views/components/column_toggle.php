<div class="relative inline-block mb-3 text-sm">

    <button id="colToggleBtn"
        class="px-3.5 py-2.5 border border-gray-300 rounded-lg text-gray-800 bg-white hover:bg-gray-50 focus:outline-none focus:border-red-800 focus:ring-2 focus:ring-red-800/10 transition flex items-center gap-2">
        Columns <i class="fa-solid fa-chevron-down fa-xs"></i>
    </button>

    <div id="colDropdown"
        class="hidden absolute z-10 mt-2 w-fit bg-white border border-gray-200 rounded-lg shadow-md p-2">

        <?php foreach ($columns as $col => $label): ?>
            <label class="flex items-center gap-2 px-2.5 py-1.5 rounded-md hover:bg-gray-50 cursor-pointer">
                <input type="checkbox" checked data-col="<?= $col ?>" class="accent-red-800">
                <span class="text-gray-800"><?= htmlspecialchars($label) ?></span>
            </label>
        <?php endforeach; ?>

    </div>
</div>
