<?php require BASE_PATH . '/views/layout/header.php'; ?>

<div class="max-w-3xl mx-auto p-6">
    <div class="mb-6">
        <a href="<?= BASE_URL ?>/">
            <button class="bg-red-800 text-white px-2 py-1">
                <i class="fa-solid fa-arrow-left fa-xs"></i>
                Back
            </button>
        </a>
    </div>

    <div class="flex items-center gap-4">
        <img src="<?= asset_upload($student['image_path']) ?>" class="w-24 h-24 rounded-full object-cover">

        <div>
            <h1 class="text-2xl font-bold"><?= htmlspecialchars($student['name']) ?></h1>
            <a href="mailto:<?= htmlspecialchars($student['email']) ?>" class="text-gray-600 hover:underline flex items-center gap-1">
                <i class="fa-regular fa-envelope fa-xs"></i>
                <?= htmlspecialchars($student['email']) ?>
            </a>
        </div>
    </div>

    <div class="mt-6 space-y-2">
        <p><strong>Course:</strong> <?= htmlspecialchars($student['course_name']) ?></p>
        <p><strong>Age:</strong> <?= $student['age'] ?></p>
        <p><strong>Year Level:</strong> <?= $student['year_level'] ?></p>
        <p><strong>Status:</strong>
            <?= $student['graduation_status'] ? 'Graduated' : 'Not Graduated' ?>
        </p>
    </div>

    <div class="mt-6 flex gap-2">

        <a href="<?= BASE_URL ?>/edit/<?= $student['id'] ?>"
            class="bg-yellow-600 text-white px-3 py-2 text-sm flex items-center gap-2 hover:bg-yellow-700">

            <i class="fa-regular fa-pen-to-square"></i>
            Edit
        </a>

        <a href="<?= BASE_URL ?>/delete/<?= $student['id'] ?>"
            onclick="return confirm('Delete this student?')"
            class="bg-red-700 text-white px-3 py-2 text-sm flex items-center gap-2 hover:bg-red-800">

            <i class="fa-regular fa-trash-can"></i>
            Delete
        </a>

    </div>
</div>

<?php require BASE_PATH . '/views/layout/footer.php'; ?>
