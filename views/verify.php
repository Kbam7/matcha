<?php
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

    <div id="error-messages"></div>
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
                    $client->run('MATCH (u:User) WHERE u.email={email} AND u.hash={hash} AND u.active=0 SET u.active = 1',
                                            ['email' => $email, 'hash' => $hash]);
                    $msg = '<div class="alert alert-success">Your account was successfully activated!</div><div class="alert alert-success"><a href="/matcha/index.php" class="btn btn-default">Log in</a></div>';
                } else {
                    $msg = '<div class="alert alert-danger">There was an error activating your account!</div>';
                }
            } else {
                $msg = '<div class="alert alert-danger">Could not find any records from the DB when activating your account!</div>';
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

    echo "<script type=\"text/javascript\">displayError('".$msg."');</script>";

    ?>

</body>

</html>
