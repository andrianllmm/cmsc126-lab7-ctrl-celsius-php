<form method="GET" class="mb-4 flex gap-2">
    <input
        type="text"
        name="q"
        placeholder="Search student ID"
        class="border px-3 py-2 w-full"
        value="<?= $_GET['q'] ?? '' ?>">

    <button class="flex items-center gap-2 bg-red-800 text-white px-4 py-2">
        <i class="fa-solid fa-magnifying-glass"></i>
        <span class="hidden md:inline">Search</span>
    </button>
</form>
