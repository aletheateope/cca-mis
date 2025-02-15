<?php require_once 'login.php';?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- OWN SCRIPT -->
    <link rel="stylesheet" href="css/styles.css">

    <!-- OWN SCRIPT -->
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-auto">
                <img class="bg" src="img/CCA/background.png" alt="">
                <div class="logo-container">
                    <img class="logo-cca" src="img/CCA/cropped.png" alt="">
                    <h5>PLMUN</h5>
                    <p>Center for Culture and the Arts</p>
                </div>
            </div>
            <div class="col-auto">
                <h2>Get Started</h2>
                <button
                    onclick="window.location.href='<?php echo htmlspecialchars($login_url); ?>'"
                    class="login-btn">
                    <img class="google-icon" src="img/google.png" alt="">
                    <span>Sign in with Google</span>
                </button>
            </div>
        </div>
    </div>


    <!-- BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>