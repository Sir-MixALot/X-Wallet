<div class="header">
    <div class="col-1-2-1"><a class="logo" title="to main page" href="/main">X-Wallet</a></div>
    <div class="col-1-2-2">
        <a title="to settings page" href="settings">Settings</a>
        <a href="/account/logout">LogOut</a>
    </div>
</div>
<div class="s_content">
    <?php
    if($_SESSION['wallet']['acc_id'] == 1){
        echo "<h2>Single account</h2>";
    }else{
        echo "<h2>Family account</h2>";
    }
    ?>
    <h2>Change your data</h2>
    <div class="wrapper">
        <div class="child">
            <form action="/settings/change_login" class="formElem" method="POST">
            <h2>New login</h2>
            <input type="text" name="login">
            <button type="submit" name="enter">Done</button>
            </form>
        </div>

        <div class="child">
            <form action="/settings/change_pass" class="formElem" method="POST">
            <h2>New password</h2>
            <input type="password" name="password">
            <button type="submit" name="enter">Done</button>
            </form>
        </div>

        <div class="child">
            <form action="/settings/total_amount" class="formElem" method="POST">
            <h2>Change total amount</h2>
            <input type="text" name="total_amount">
            <button type="submit" name="enter">Done</button>
            </form>
        </div>
        <a href="account/delete">Delete account</a>
    </div>
</div>
