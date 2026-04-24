<?php require BASE_PATH . '/views/layout/header.php'; ?>

<h1 class="text-xl mb-4">Courses</h1>

<div class="border p-4">
    <ul class="space-y-2">
        <?php foreach ($courses as $course): ?>
            <li class="px-3 py-2 hover:bg-gray-50">
                <?= htmlspecialchars($course['course_name']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>


<?php require BASE_PATH . '/views/layout/footer.php'; ?>
