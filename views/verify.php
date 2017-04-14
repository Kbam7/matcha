<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../php/auth.php';

require_once '../vendor/autoload.php';
use GraphAware\Neo4j\Client\ClientBuilder;

?>
<!doctype html>
<html>
<head>
    <title>Verify Account | Camagru</title>
    <?php include '../include/head.php'; ?>
</head>
<body>
    <header>
        <?php include '../include/header.php'; ?>
    </header>

    <div id="alert-messages"></div>
<?php
    if (isset($_GET['email']) && !empty($_GET['email']) and isset($_GET['hash']) && !empty($_GET['hash'])) {
        $email = $_GET['email']; // Set email variable
        $hash = $_GET['hash']; // Set hash variable

        try {
            $client = ClientBuilder::create()
                        ->addConnection('default', 'http://neo4j:123456@localhost:7474')
                        ->build();

            $result = $client->run('MATCH (u:User) WHERE u.email={email} AND u.hash={hash} AND u.active=0 '
                                    .'RETURN count(u) AS n_users;',
                                    ['email' => $email, 'hash' => $hash]);

            if ($record = $result->getRecord()) {
                $n_users = $record->get('n_users');
                if ($n_users === 1) {
                    $result = $client->run('MATCH (u:User) WHERE u.email={email} AND u.hash={hash} AND u.active=0 SET u.active = 1 RETURN u',
                                            ['email' => $email, 'hash' => $hash]);
                    $record = $result->getRecord();
                    $user = $record->get('u')->values();

                    if ($auth($user['username'], $user['password'], true)) {
                        $_SESSION['logged_on_user'] = $user;
                        session_regenerate_id(true);
                        header('Location: ../views/dashboard.php');
                    } else {
                        $msg = '<div class="alert alert-danger">Your account was successfully activated but <br />an error occurred when trying to login. <br />Please try again.</div>';
                    }
                } else {
                    $msg = '<div class="alert alert-danger">There was an error activating your account! '.$n_users.'</div>';
                }
            } else {
                $msg = '<div class="alert alert-danger">Could not find your account. Make sure you have created an account and followed your activation link.</div>';
            }
        } catch (Exception $e) {
            $msg = '<b><u>Error Message :</u></b><br /> '.$e.' <br /><br /> <b><u>For error details, check :</u></b><br /> '.dirname(__DIR__).'/log/errors.log</div>';
            error_log($e, 3, dirname(__DIR__).'/log/errors.log');
        }
    } else {
        // Invalid approach
        $msg = '<div class="alert alert-warning">You got here without the right stuff. Please create an account and then click the link in your activation email.</div>';
    }

    include '../include/footer.php';

    echo "<script type=\"text/javascript\">displayAlertMessage('".$msg."', true);</script>";

    ?>

</body>

</html>
