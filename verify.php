<!doctype html>
<html>
<head>
    <title>Verify Account | Camagru</title>
<?php include './include/header.php'; ?>
</head>
<body>
    <header>
        <div class="navbar">
            <a href="index.php" class="brand"><h1>Camagru - <small>Verify Account</small></h1></a>
            <ul class="menu pull-right">
              <li class="divider"></li>
              <li class="logout-btn">
                  <?php if (isset($_SESSION['logged_on_user'])): ?>
                      <a href="php/logout.php" title="Logout of Account">LOGOUT</a>
                  <?php endif; ?>
              </li>
            </ul>
        </div>
    </header>

    <div id="error-messages"></div>
<?php
    if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])){

        include 'config/database.php';

        // Verify data
        $email = $_GET['email']; // Set email variable
        $hash = $_GET['hash']; // Set hash variable

        try {
            $dbname = 'camagru';
            $conn = new PDO("$DB_DSN;dbname=$dbname", $DB_USER, $DB_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = $conn->prepare("SELECT `email`, `hash`, `active` FROM `users` WHERE email=:email AND hash=:hash AND active='0';");
            $sql->execute(['email' => $email, 'hash' => $hash]);

            if ($sql->rowCount() > 0) {
                $sql = $conn->prepare("UPDATE `users` SET `active`='1' WHERE email=:email AND hash=:hash AND active='0';");
                $sql->execute(['email' => $email, 'hash' => $hash]);
                // success
                $msg = "<p class=\"success\">Your account was successfully activated!</p><p class=\"success\"><a class=\"btn border border-3 white rounded hover-text-blue text-22\">LOG IN</a></p>";
            } else {
                $msg = "<p class=\"danger\">There was an error activating your account!".$sql["email"]."</p>";
            }
        } catch (PDOException $e) {
            $msg = "<b><u>Error Message :</u></b><br /> ".$e." <br /><br /> <b><u>For error details, check :</u></b><br /> ".dirname(__DIR__)."/log/errors.log</p>";
            error_log($e, 3, dirname(__DIR__).'/log/errors.log');
        }
        $conn = null;
    }else{
        // Invalid approach
        $msg =  "<p class=\"warning\">You got here without the right stuff. Please create an account and then click the link in your activation email.</p>";
    }
    echo "<script type=\"text/javascript\">displayError('".$msg."');</script>";
?>

<?php include './include/footer.php'; ?>
