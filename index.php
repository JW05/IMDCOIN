<?php

include_once __DIR__ . "/classes/User.php";

session_start();
if (isset($_SESSION["user"])) {
    $email = $_SESSION["user"];
    $coinsUser = User::toonCoins($email);

    if (!empty($_POST)) {
        try {
            $amount = $_POST["amount"];
            $friend = $_POST["friend"];
            if ($coinsUser > $amount) {

                if (User::findFriend($friend)) {
                    echo $email;
                    User::sendMoney($amount, $friend);
                    User::loseMoney($amount, $email);
                    $_SESSION["friend"] = $friend;
                    $_SESSION["amount"] = $amount;
                    header('Location: succesTransfer.php');
                } else {
                    $error = "De persoon die u zocht is niet gevonden.";
                }

            } else {
                $error = "U heeft niet genoeg coins";
            }

        } catch (\Throwable $th) {
            $error = $th->getMessage();
        }
    }

} else {
    header('Location: login.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMDCoins</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif;?>
    <div class="app">
        <a href="logout.php">Uitloggen</a>
        <div class="app__search">
            <input type="text" class="input--search coin__input" placeholder="search in transactions">
        </div>
        <h1 class="app__title">Current IMDCoins</h1>
        <h1 class="app__title" ><?php echo $coinsUser ?></h1>
        <div class="app__send">
            <h2 class="app__send__subtittle">Tos a coin to</h2>
            <form action="" method="post">

                <input type="text" placeholder="Amount" id="amount" name="amount">
                <input type="text" placeholder="Friend" id="friend" name="friend">
                <br>
                <input type="submit" value="Send">
            </form>
        </div>

       <h2 class="app__subtittle">Recent transactions</h2>

    </div>



</body>
</html>
