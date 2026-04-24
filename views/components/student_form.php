<?php
$student = $student ?? null;
$isEdit = isset($student);
?>

<form method="POST" action="<?= $isEdit ? '/update/' . $student['id'] : '/store' ?>" class="space-y-4">

    <input name="name"
        value="<?= $student['name'] ?? '' ?>"
        class="border p-2 w-full"
        placeholder="Name">

    <input name="email"
        value="<?= $student['email'] ?? '' ?>"
        class="border p-2 w-full"
        placeholder="Email">

    <button class="bg-red-800 text-white px-4 py-2">
        <?= $isEdit ? 'Update' : 'Create' ?>
    </button>

</form>
