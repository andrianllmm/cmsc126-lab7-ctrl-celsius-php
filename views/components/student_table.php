<div class="overflow-x-auto">
    <table class="min-w-full border text-sm">
        <thead class="bg-gray-100 text-left">
            <tr>
                <th class="px-4 py-2 border-b"></th>
                <th class="px-4 py-2 border-b">Name</th>
                <th class="px-4 py-2 border-b">Age</th>
                <th class="px-4 py-2 border-b">Email</th>
                <th class="px-4 py-2 border-b">Course</th>
                <th class="px-4 py-2 border-b">Year</th>
                <th class="px-4 py-2 border-b">Graduated</th>
                <th class="px-4 py-2 border-b text-end"></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($students as $s): ?>
                <tr class="hover:bg-gray-50">

                    <td class="px-4 py-2 border-b">
                        <img
                            src="<?= asset_upload($s['image_path']) ?>"
                            alt="Student Image"
                            class="w-10 h-10 rounded-full object-cover">
                    </td>

                    <td class="px-4 py-2 border-b">
                        <?= htmlspecialchars($s['name']) ?>
                    </td>

                    <td class="px-4 py-2 border-b">
                        <?= $s['age'] ?>
                    </td>

                    <td class="px-4 py-2 border-b">
                        <?= htmlspecialchars($s['email']) ?>
                    </td>

                    <td class="px-4 py-2 border-b">
                        <?= htmlspecialchars($s['course_name']) ?>
                    </td>

                    <td class="px-4 py-2 border-b">
                        <?= $s['year_level'] ?>
                    </td>

                    <td class="px-4 py-2 border-b">
                        <?php if ($s['graduation_status']): ?>
                            <i class="fa-solid fa-circle-check text-green-700" title="Graduated"></i>
                        <?php else: ?>
                            <i class="fa-solid fa-circle-xmark text-gray-400" title="Not Graduated"></i>
                        <?php endif; ?>
                    </td>

                    <td class="px-4 py-2 border-b">
                        <div class="flex justify-end gap-2">

                            <a href="<?= BASE_URL ?>/edit/<?= $s['id'] ?>"
                                class="bg-yellow-600 text-white px-3 py-1 text-xs">
                                Edit
                            </a>

                            <a href="<?= BASE_URL ?>/delete/<?= $s['id'] ?>"
                                onclick="return confirm('Delete this record?')"
                                class="bg-red-700 text-white px-3 py-1 text-xs">
                                Delete
                            </a>

                        </div>
                    </td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
