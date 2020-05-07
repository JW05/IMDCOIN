<?php

include_once __DIR__ . "/classes/User.php";

if (!empty($_POST)) {

    session_start();
    if ($_POST['captcha'] != $_SESSION['digit']) {
        die("Sorry, u heeft de verkeerde Captcha ingegeven.");
    }

    session_destroy();

    if (!empty($_POST)) {

        $email = $_POST['email'];
        $password = $_POST['password'];

        if (!empty($email) && !empty($password)) {
            if (User::login($email, $password)) {
                session_start();
                $_SESSION["user"] = $email;
                header("Location: index.php");
            } else {
                $error = "Uw email of wachtwoord is onjuist.";
            }
        } else {

            $error = "Email en wachtwoord is verplicht.";
        }
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">

</head>
<body

    <div id="login">
    <h1>Welkom bij de IMD Buddy App</h1>
        <form action="" method="post">
                    <h2>Sign In</h2>

                    <?php if (isset($succes)): ?>
                    <div>
                        <p>
                            <?php echo $succes; ?>
                        </p>
                    </div>
                    <?php endif;?>

                    <?php if (isset($error)): ?>
                    <div class="error">
                        <p>
                            <?php echo $error; ?>
                        </p>
                    </div>
                    <?php endif;?>

                    <div class="input">
                        <label for="Email">Email</label>
                        <input type="text" id="Email" name="email">
                    </div>
                    <div class="input">
                        <label for="Password">Password</label>
                        <input type="password" id="Password" name="password">
                    </div>

                    <p><img src="captcha.php" width="120" height="30" border="1" alt="CAPTCHA"></p>
                    <p><input type="text" size="6" maxlength="5" name="captcha" value=""><br>
                    Schrijf hier de cijfers van hierboven in</p>

                    <div class="submit">
                        <input type="submit" value="Sign in" class="btn btn--primary">
                    </div>
        </form>
        <p>Nog geen account? <a href="register.php">Maak er een aan!</a></p>
        <p></p>
    </div>
</body>
</html>

<!--
    [feature 2] inloggen kan op een veilige manier
              *  toon een foutmelding indien inloggen niet gelukt is (zie screenshot)
              *  valideer al wat kan mislopen in dit formulier via PHP
              *  uitloggen is mogelijk
-->

