<table class="min-w-full bg-white">
    <thead>
        <tr class="uppercase text-sm">
            <th colspan="3" class="text-left">Course Status: <span>OPEN</span></th>
            <th colspan="10" class="text-left">Total Open Subjects: <span><?= $data['counts']['open'] ?></span></th>
        </tr>
        <tr class="uppercase text-sm">
            <th colspan="3" class="text-left">Course Status: <span>CLOSED</span></th>
            <th colspan="10" class="text-left">Total Closed Subjects: <span><?= $data['counts']['closed'] ?></span></th>
        </tr>
        <tr class="uppercase text-sm leading-normal">
            <th colspan="3" class="text-left">Course Status: <span>DISSOLVED</span></th>
            <th colspan="3" class="text-left">Total Dissolved Subjects: <span><?= $data['counts']['dissolved'] ?></span></th>
            <th colspan="3" class="text-left">Total Lec/Lab: <span id="total-leclab">0/0</span></th>
        </tr>
        <tr class="header-row">
            <th>OFFERING COLLEGE</th>
            <th style="width: 524px;">COURSE CODE (DESCRIPTION)</th>
            <th style="width: 350px;">SECTION / SCHEDULE</th>
            <th>LEC/LAB</th>
            <th>Units</th>
            <th>MIN CAPACITY</th>
            <th>MAX CAPACITY (ROOM)</th>
            <th><input type="checkbox" id="selectAll"> Select All</th>
        </tr>
    </thead>
    <tbody>