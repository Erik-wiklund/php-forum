<!-- Modal HTML -->
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Login</h2>
        <form action="login.php" method="post">
            <label for="login_username">Username:</label>
            <input type="text" name="login_username" id="login_username"><br>
            <label for="login_password">Password:</label>
            <input type="password" name="login_password" id="login_password"><br>
            <input type="hidden" name="form_submitted" value="1">
            <input type="submit" value="Login">
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modal = document.getElementById("loginModal");
        const closeBtn = document.getElementsByClassName("close")[0];

        // Open the modal when the login link is clicked
        const loginLink = document.getElementById("loginLink");
        loginLink.onclick = function() {
            modal.style.display = "block";
        }

        // Close the modal when the close button is clicked
        closeBtn.onclick = function() {
            modal.style.display = "none";
        }

        // Close the modal when clicking outside the modal content
        window.onclick = function(event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        }
    });
</script>

