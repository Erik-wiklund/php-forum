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
