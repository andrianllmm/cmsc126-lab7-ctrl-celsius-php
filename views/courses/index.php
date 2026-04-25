<?php require BASE_PATH . '/views/layout/header.php'; ?>

<h1 class="text-xl mb-4">Courses</h1>

<div class="border border-gray-200 rounded-lg p-4 bg-white">
    <ul class="space-y-1">
        <?php foreach ($courses as $course): ?>
            <li class="px-3 py-2 rounded-md hover:bg-gray-50 transition text-gray-800">
                <?= htmlspecialchars($course['course_name']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php require BASE_PATH . '/views/layout/footer.php'; ?>
