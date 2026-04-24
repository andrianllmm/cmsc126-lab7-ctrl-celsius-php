<?php
$columns = [
    0 => 'Image',
    1 => 'Name',
    2 => 'Email',
    3 => 'Age',
    4 => 'Course',
    5 => 'Year',
    6 => 'Graduated',
    7 => 'Actions',
];
?>

<?php require BASE_PATH . '/views/components/column_toggle.php'; ?>

<div class="overflow-x-auto">
    <table class="min-w-full border text-sm">
        <thead class="bg-gray-100 text-left">
            <tr>
                <?php foreach ($columns as $col => $label): ?>
                    <th data-col="<?= $col ?>"
                        class="px-4 py-2 border-b">

                        <?= $col === 0 || $col === 7 ? '' : htmlspecialchars($label) ?>
                    </th>
                <?php endforeach; ?>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($students as $s): ?>
                <tr class="hover:bg-gray-50">

                    <td data-col="0" class="px-4 py-2 border-b">
                        <img
                            src="<?= asset_upload($s['image_path']) ?>"
                            alt="Student Image"
                            class="w-10 h-10 rounded-full object-cover">
                    </td>

                    <td data-col="1" class="px-4 py-2 border-b">
                        <a href="<?= BASE_URL ?>/student/<?= $s['id'] ?>"
                            class="text-red-800 hover:underline flex items-center gap-1">
                            <?= htmlspecialchars($s['name']) ?>
                            <i class="fa-solid fa-arrow-up-right-from-square fa-xs"></i>
                        </a>
                    </td>

                    <td data-col="2" class="px-4 py-2 border-b">
                        <a href="mailto:<?= htmlspecialchars($s['email']) ?>" class="text-red-800 hover:underline">
                            <i class="fa-regular fa-envelope fa-xs"></i>
                            <?= htmlspecialchars($s['email']) ?>
                        </a>
                    </td>

                    <td data-col="3" class="px-4 py-2 border-b">
                        <?= $s['age'] ?>
                    </td>

                    <td data-col="4" class="px-4 py-2 border-b">
                        <?= htmlspecialchars($s['course_name']) ?>
                    </td>

                    <td data-col="5" class="px-4 py-2 border-b">
                        <?= $s['year_level'] ?>
                    </td>

                    <td data-col="6" class="px-4 py-2 border-b">
                        <?php if ($s['graduation_status']): ?>
                            <i class="fa-solid fa-circle-check text-green-700" title="Graduated"></i>
                        <?php else: ?>
                            <i class="fa-solid fa-circle-xmark text-gray-400" title="Not Graduated"></i>
                        <?php endif; ?>
                    </td>

                    <td data-col="7" class="px-4 py-2 border-b">
                        <div class="flex justify-end gap-2">

                            <a href="<?= BASE_URL ?>/edit/<?= $s['id'] ?>"
                                class="bg-yellow-600 text-white px-3 py-1 text-xs">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>

                            <a href="<?= BASE_URL ?>/delete/<?= $s['id'] ?>"
                                onclick="return confirm('Delete this record?')"
                                class="bg-red-700 text-white px-3 py-1 text-xs">
                                <i class="fa-regular fa-trash-can"></i>
                            </a>

                        </div>
                    </td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
