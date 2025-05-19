<h2 class="text-xl font-bold mb-1">
    Enrollment Comparison - <?= $yearFrom ?> vs <?= $yearTo ?>
</h2>
<p class="text-sm text-gray-600 mb-4">
    Education Level: <strong><?= htmlspecialchars($levelName) ?></strong>
</p>

<table class="min-w-full bg-white border">
    <thead>
        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
            <th rowspan="2" class="py-2 px-6">College Programs</th>
            <th colspan="3"><?= $yearFrom ?></th>
            <th colspan="3"><?= $yearTo ?></th>
            <th rowspan="2" class="py-1 px-6">Total INC</th>
            <th rowspan="2" class="py-1 px-6">Total Non-INC</th>
            <th rowspan="2" class="py-1 px-6">Grand Total</th>
        </tr>
        <tr class="bg-gray-200 text-gray-600 uppercase text-sm">
            <th>INC</th>
            <th>Non-INC</th>
            <th>Total</th>
            <th>INC</th>
            <th>Non-INC</th>
            <th>Total</th>
        </tr>
    </thead>

    <tbody class="text-gray-600 text-sm">
    <?php foreach ($data as $department => $courses): ?>
        <tr class="uppercase text-sm leading-normal">
            <th colspan="10" class="py-2 px-4 text-left text-white" style="background-color: #174069;">
                <?= strtoupper(htmlspecialchars($department)) ?>
            </th>
        </tr>
        <?php
            $sub_2025 = ['inc' => 0, 'non_inc' => 0, 'total' => 0];
            $sub_2024 = ['inc' => 0, 'non_inc' => 0, 'total' => 0];

            foreach ($courses as $course => $years):
                $yFrom = $years[$yearFrom] ?? ['inc' => 0, 'non_inc' => 0, 'total' => 0];
                $yTo   = $years[$yearTo] ?? ['inc' => 0, 'non_inc' => 0, 'total' => 0];

                $row_inc = $yFrom['inc'] + $yTo['inc'];
                $row_non = $yFrom['non_inc'] + $yTo['non_inc'];
                $row_total = $yFrom['total'] + $yTo['total'];

                $sub_2025['inc'] += $yFrom['inc'];
                $sub_2025['non_inc'] += $yFrom['non_inc'];
                $sub_2025['total'] += $yFrom['total'];

                $sub_2024['inc'] += $yTo['inc'];
                $sub_2024['non_inc'] += $yTo['non_inc'];
                $sub_2024['total'] += $yTo['total'];
        ?>
            <tr class="border-b border-gray-200 hover:bg-gray-100">
                <td class="py-3 px-6 text-left"><?= htmlspecialchars($course) ?></td>
                <td class="text-center"><?= $yFrom['inc'] ?></td>
                <td class="text-center"><?= $yFrom['non_inc'] ?></td>
                <td class="text-center"><?= $yFrom['total'] ?></td>
                <td class="text-center"><?= $yTo['inc'] ?></td>
                <td class="text-center"><?= $yTo['non_inc'] ?></td>
                <td class="text-center"><?= $yTo['total'] ?></td>
                <td class="text-center"><?= $row_inc ?></td>
                <td class="text-center"><?= $row_non ?></td>
                <td class="text-center"><?= $row_total ?></td>
            </tr>
        <?php endforeach; ?>
        <tr class="bg-gray-200 text-gray-600 uppercase text-sm">
            <th class="text-right"><b>Subtotal:</b></th>
            <th class="text-center"><b><?= $sub_2025['inc'] ?></b></th>
            <th class="text-center"><b><?= $sub_2025['non_inc'] ?></b></th>
            <th class="text-center"><b><?= $sub_2025['total'] ?></b></th>
            <th class="text-center"><b><?= $sub_2024['inc'] ?></b></th>
            <th class="text-center"><b><?= $sub_2024['non_inc'] ?></b></th>
            <th class="text-center"><b><?= $sub_2024['total'] ?></b></th>
            <th class="text-center"><b><?= $sub_2025['inc'] + $sub_2024['inc'] ?></b></th>
            <th class="text-center"><b><?= $sub_2025['non_inc'] + $sub_2024['non_inc'] ?></b></th>
            <th class="text-center"><b><?= $sub_2025['total'] + $sub_2024['total'] ?></b></th>
        </tr>
    <?php endforeach; ?>
    
    <tr class="bg-gray-200 text-red-700 uppercase text-sm">
        <th class="text-right"><b>GRAND TOTAL:</b></th>
        <th class="text-center"><b><?= $totals[$yearFrom]['inc'] ?></b></th>
        <th class="text-center"><b><?= $totals[$yearFrom]['non_inc'] ?></b></th>
        <th class="text-center"><b><?= $totals[$yearFrom]['total'] ?></b></th>
        <th class="text-center"><b><?= $totals[$yearTo]['inc'] ?></b></th>
        <th class="text-center"><b><?= $totals[$yearTo]['non_inc'] ?></b></th>
        <th class="text-center"><b><?= $totals[$yearTo]['total'] ?></b></th>
        <th class="text-center"><b><?= $totals['overall']['inc'] ?></b></th>
        <th class="text-center"><b><?= $totals['overall']['non_inc'] ?></b></th>
        <th class="text-center"><b><?= $totals['overall']['total'] ?></b></th>
    </tr>
    </tbody>
</table>
