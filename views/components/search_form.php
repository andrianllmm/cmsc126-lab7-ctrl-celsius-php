<form method="GET" class="mb-4 flex gap-2">
    <input
        type="text"
        name="q"
        placeholder="Search by student ID, name, or email"
        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:border-red-800 focus:ring-2 focus:ring-red-800/10 transition"
        value="<?= $_GET['q'] ?? '' ?>">

    <button class="flex items-center gap-2 bg-red-800 text-white px-4 py-2 rounded-lg hover:bg-red-900 transition">
        <i class="fa-solid fa-magnifying-glass fa-xs"></i>
        <span class="hidden md:inline">Search</span>
    </button>
</form>
