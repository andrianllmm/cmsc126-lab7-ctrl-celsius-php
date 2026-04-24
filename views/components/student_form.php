<?php
$student = $student ?? null;
$isEdit = isset($student);
?>

<form method="POST"
    action="<?= BASE_URL . ($isEdit ? '/update/' . $student['id'] : '/store') ?>"
    class="space-y-4"
    enctype="multipart/form-data"> <div>
        <label class="block text-sm font-bold mb-1">Full Name</label>
        <input name="name"
            type="text"
            maxlength="40"
            value="<?= $student['name'] ?? '' ?>"
            class="border p-2 w-full"
            placeholder="Name" required>
    </div>

    <div>
        <label class="block text-sm font-bold mb-1">Age</label>
        <input name="age"
            type="number"
            min="0"
            max="99"
            value="<?= $student['age'] ?? '' ?>"
            class="border p-2 w-full"
            placeholder="Age" required>
    </div>

    <div>
        <label class="block text-sm font-bold mb-1">Email Address</label>
        <input name="email"
            type="email"
            maxlength="40"
            value="<?= $student['email'] ?? '' ?>"
            class="border p-2 w-full"
            placeholder="Email" required>
    </div>

    <div>
        <label class="block text-sm font-bold mb-1">Course</label>
        <input name="course"
            type="text"
            maxlength="40"
            value="<?= $student['course'] ?? '' ?>"
            class="border p-2 w-full"
            placeholder="Course" required>
    </div>

    <div>
        <label class="block text-sm font-bold mb-1">Year Level</label>
        <select name="year_level" class="border p-2 w-full" required>
            <option value="">Select Year Level</option>
            <?php for ($i = 1; $i <= 4; $i++): ?>
                <option value="<?= $i ?>" <?= (isset($student['year_level']) && $student['year_level'] == $i) ? 'selected' : '' ?>>
                    Year <?= $i ?>
                </option>
            <?php endfor; ?>
        </select>
    </div>

    <div class="flex items-center space-x-2">
        <input name="graduation_status"
            type="checkbox"
            value="1"
            id="grad_status"
            <?= (isset($student['graduation_status']) && $student['graduation_status']) ? 'checked' : '' ?>>
        <label for="grad_status" class="text-sm font-bold">Graduated?</label>
    </div>

    <div>
        <label class="block text-sm font-bold mb-1">Student Image</label>
        <input name="student_image"
            type="file"
            accept="image/*"
            class="border p-2 w-full">
        <?php if ($isEdit && !empty($student['image_path'])): ?>
            <p class="text-xs text-gray-500 mt-1">Current file: <?= $student['image_path'] ?></p>
        <?php endif; ?>
    </div>

    <button type="submit" class="bg-red-800 text-white px-4 py-2 rounded hover:bg-red-900 transition">
        <?= $isEdit ? 'Update Student' : 'Create Student' ?>
    </button>

</form>
