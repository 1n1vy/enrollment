
<?php extract($filters); ?>

<form method="GET" style="margin-bottom: 20px;">
    <label>Arrange Result</label>
    <select name="sort_by">
        <option value="">N/A</option>
        <option value="subject_code" <?= $sort_by === 'subject_code' ? 'selected' : '' ?>>Course Code</option>
        <option value="subject_name" <?= $sort_by === 'subject_name' ? 'selected' : '' ?>>Course Name</option>
    </select>

    <select name="sort_order">
        <option value="asc" <?= $sort_order === 'asc' ? 'selected' : '' ?>>Ascending</option>
        <option value="desc" <?= $sort_order === 'desc' ? 'selected' : '' ?>>Descending</option>
    </select>

    <div style="margin-top: 10px;">
        <label><input type="checkbox" name="hide_schedule" <?= $hide_schedule ? 'checked' : '' ?>> Remove Schedule Info</label><br>
        <label><input type="checkbox" name="show_leclab" <?= $show_leclab ? 'checked' : '' ?>> Show Lec/Lab Units</label><br>
        <label><input type="checkbox" name="hide_college" <?= $hide_college ? 'checked' : '' ?>> Remove Offering College</label><br>
        <label><input type="checkbox" name="hide_capacity" <?= $hide_capacity ? 'checked' : '' ?>> Remove Max/Min Capacity Info</label><br>
        <label><input type="checkbox" name="show_student_breakdown" <?= $show_student_breakdown ? 'checked' : '' ?>> Show Regular/Irregular Student enrolled</label>
    </div>

    <!-- Preserve current filters -->
    <input type="hidden" name="schoolyear1" value="<?= htmlspecialchars($startYear) ?>">
    <input type="hidden" name="schoolyear2" value="<?= htmlspecialchars($endYear) ?>">
    <input type="hidden" name="subject_status" value="<?= htmlspecialchars($subject_status) ?>">
    <input type="hidden" name="department" value="<?= htmlspecialchars($department) ?>">
    <input type="hidden" name="course" value="<?= htmlspecialchars($course) ?>">

    <br>
    <button type="submit">Apply</button>
</form>
