
<?php
include_once __DIR__ . "/classes/User.php";

if (!empty($_POST)) {
    try {
        $user = new User();
        $user->setFirstname($_POST['firstname']);
        $user->setLastname($_POST['lastname']);
        $user->setEmail($_POST['email']);
        $user->setPassword($_POST['password']);
        $user->setUsername($_POST['username']);

        $user->checkDuplicate();
        $user->createUser();
        $success = "Account Created!";

        session_start();
        $_SESSION['user'] = $_POST['email'];

        header('Location: index.php');

    } catch (\Throwable $th) {
        $error = $th->getMessage();
    }
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap-grid.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap-reboot.css">
    <link rel="stylesheet" href="css/register.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <title>Register</title>
</head>
<body>


    <main>

        <h2>Register for the IMD Buddy App</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif;?>

        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif;?>

        <form action="" method="post">
            <div>
                <label for="Firstname"></label>
                <input type="text" id="Firstname" name="firstname" placeholder="Firstname">
            </div>

            <div>
                <label for="Lastname"></label>
                <input type="text" id="Lastname" name="lastname" placeholder="Lastname">
            </div>

            <div>
                <label for="Username"></label>
                <input type="text" id="Username" name="username" placeholder="Username">
                <span id="availability"></span>
            </div>

            <div>
                <label for="Email"></label>
                <input type="text" id="Email" name="email" placeholder="Email">
            </div>

            <div>
                <label for="Password"></label>
                <input type="password" id="Password" name="password" placeholder="Password">
            </div>

            <div>
                <input id="submit" type="submit" value="Sign Up">
            </div>
        </form>

    </main>

<script>
$(document).ready(function(){
   $('#Username').blur(function(){

     var username = $(this).val();

     $.ajax({
      url:'checkUsername.php',
      method:"POST",
      data:{user_name:username},
      success:function(data)
      {
       if(data != '0')
       {
        $('#availability').html('<span class="text-danger">Username niet beschikbaar</span>');
        //$('#submit').attr("disabled", true);
       }
       else
       {
        $('#availability').html('<span class="text-success">Username beschikbaar</span>');
        $('#submit').attr("disabled", false);
       }
      }
     })

  });
 });
</script>

</body>
</html>