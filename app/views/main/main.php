<?php

use app\models\Main;

$model = new Main;
$dashboardData = $model->getDashboardData();
$isTodayFilter = !isset($_GET['filter']) || $_GET['filter'] === 'today';
$isWeekFilter = !isset($_GET['filter']) || $_GET['filter'] === 'week';
$isMonthFilter = !isset($_GET['filter']) || $_GET['filter'] === 'month';
$isAllFilter = !isset($_GET['filter']) || $_GET['filter'] === 'all';
?>

<div class="header">
    <div class="col-1-2-1"><a class="logo" title="to main page" href="/main">X-Wallet</a></div>
    <div class="col-1-2-2">
        <a title="to settings page" href="settings">Settings</a>
        <a href="/account/logout">LogOut</a>
    </div>
</div>
<div class="s_content">
    <div class="m_content">
        <h2>Hello, <?php echo $_SESSION['account']['login'];?></h2>
        <h2>Your total amount: <?php echo $_SESSION['wallet']['total_amount'];?>byn</h2>
        <h2>Accumulated money: <?php echo $_SESSION['wallet']['accumulated'];?>byn</h2>
    </div>
        <div class="wrapper">
            <div class="dashboard-wrapper">
                <div class="sidebar">
                    <form action="/main/note" method="POST">
                    <h2>Type</h2>
                    <select name="type" id="type-select">
                        <option selected disabled>Choose type</option>
                        <option value="1">credit</option>
                        <option value="2">food</option>
                        <option value="3">alcohol</option>
                        <option value="4">apartment rent</option>
                        <option value="5">household expenses</option>
                        <option value="6">clothes</option>
                        <option value="7">other</option>
                        <option value="8">not spent</option>
                    </select>
                    <h2>Amount</h2>
                    <p><input type="text" name="amount" placeholder="Enter amount"></p>
                    <button type='submit'>Add</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="dashboard">
            <form class="filter-wrapper" action="/main">
            <div class="radio">
                <input type="radio" id="contactChoice1" name="filter" value="today" <?= $isTodayFilter == true ? 'checked' : '' ?>>
                <label for="contactChoice1">Today</label>

                <input type="radio" id="contactChoice2" name="filter" value="week"  <?= $isWeekFilter == true ? 'checked' : '' ?>>
                <label for="contactChoice2">Week</label>

                <input type="radio" id="contactChoice3" name="filter" value="month" <?= $isMonthFilter == true ? 'checked' : '' ?>>
                <label for="contactChoice3">Month</label>

                <input type="radio" id="contactChoice4" name="filter" value="all"   <?= $isAllFilter == true ? 'checked' : '' ?>>
                <label for="contactChoice4">All time</label>
            </div>
            <button type='submit'>Filter</button>
            </form>
            <table class="GeneratedTable">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($dashboardData as $row): ?>
                    <tr>
                        <td><?= $row['type'] ?></td>
                        <td><?= $row['amount'] ?>byn</td>
                        <td><?= $row['date'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
</div>