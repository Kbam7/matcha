<?php

session_start();

require_once '../vendor/autoload.php';
use GraphAware\Neo4j\Client\ClientBuilder;

if (isset($_SESSION['logged_on_user'])) {
    $user = $_SESSION['logged_on_user'];

    if (!isset($_GET['img'])) {
        die('No image found');
    }
    $img = $_GET['img'].'.png';

    $client = ClientBuilder::create()->addConnection('default', 'http://neo4j:123456@localhost:7474')->build();
    $result = $client->run('MATCH (img:Image {filename:{filename}})<-[:UPLOADED]-(u:User) RETURN img, u.username AS user', ['filename' => $img]);
    $record = $result->getRecord();

    $img = $record->get('img')->values();
//    $img_owner = $record->get('user')->value();?>
<!DOCTYPE html>
<html>
  <head>
    <title>Matcha | Home</title>
    <?php include '../include/head.php'; ?>
  </head>
  <body>
    <header>
        <?php include '../include/header.php'; ?>
    </header>

    <div id="error-messages"></div>

    <section class="jumbo-intro" id="userImage">
        <div class="jumbotron">
            <div class="container">
                <div class="main-image">
                    <img src="/matcha/assets/uploads/<?php echo $img['filename']; ?>" title="<?php echo $img['title']; ?>" alt="<?php echo $img['title']; ?>" />
                </div>
                <h2><?php echo $img['title']; ?> <small><?php echo date('j F Y, g:i a', $img['timestamp']) ?></small></h2>
                <p><?php echo $img['description']; ?></p>
            </div>
        </div>
    </section> <!-- /.jumbo-intro -->
    <section class="container">
        <div class="row">

            <!-- // DISPLAY COMMENTS HERE -->

        </div> <!-- /.row -->
    </section> <!-- /.container -->

<?php include '../include/footer.php'; ?>

</body>

</html>

<?php

} else {
    $_SESSION['errors'] = array('Please log in before accessing this website');
    header('Location: ../index.php');
}

/*

<h2><?php echo $img['title']; ?> <span class="label label-default"><small><?php echo date('j F Y, g:i a', $img['timestamp']) ?></small></span></h2>
<p><?php echo $img['description']; ?></p>
<div class="main-image">
    <img src="/matcha/assets/uploads/<?php echo $img['filename']; ?>" title="<?php echo $img['title']; ?>" alt="<?php echo $img['title']; ?>" />
</div>

*/

?>
