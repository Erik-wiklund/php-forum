<?php

// Modal HTML
?>
<body>
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Login</h2>
        <form id="loginForm" action="functions/loggin_logout_system/login.php" method="post">
            <label for="login_username">Username:</label>
            <input type="text" name="login_username" id="login_username"><br>
            <label for="login_password">Password:</label>
            <input type="password" name="login_password" id="login_password"><br>
            <input type="hidden" name="form_submitted" value="1">
            <input type="submit" value="Login">
        </form>
    </div>
</div>

<script src="js/login_modal.js"></script>
</body>