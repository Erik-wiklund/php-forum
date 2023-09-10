<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .confirmation-container {
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            text-align: center;
        }

        .confirmation-icon {
            font-size: 48px;
            color: #4CAF50;
        }

        h1 {
            font-size: 24px;
            margin-top: 10px;
        }

        p {
            font-size: 18px;
            margin-top: 10px;
        }

        a {
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
            cursor: pointer;
            /* Add cursor pointer to make it obvious it's clickable */
        }
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
        }

        .close {
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php include "includes/login_modal.php"; ?>
    <div class="confirmation-container">
        <div class="confirmation-icon">&#10004;</div>
        <h1>Registration Successful</h1>
        <p>Your account has been successfully registered.</p>
        <p><a href="index.php">Home</a> to get back to homepage</p>
        <?php if (!isset($_SESSION['username'])) { ?>
           <p> <a id="loginLink" style="cursor: pointer;">Login</a> to access website and forums</p>
        <?php } ?>
    </div>

    <!-- JavaScript to open the login modal -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get a reference to the login link by its ID
        var loginLink = document.getElementById('loginLink');
        
        // Add a click event listener to the login link
        loginLink.addEventListener('click', function() {
            // Trigger the login modal to display
            var loginModal = document.getElementById('loginModal');
            loginModal.style.display = 'block';
        });
    });
</script>

</body>

</html>