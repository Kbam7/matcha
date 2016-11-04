<?php
session_start();
if (isset($_SESSION['logged_on_user'])) {
    ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Chat Room</title>
    <?php include '../include/head.php'; ?>
  </head>
  <body>
    <header>
        <?php include '../include/header.php'; ?>
    </header>

    <div id="error-messages"></div>

    <section class="jumbo-intro">
        <div class="jumbotron">
            <div class="container">
                <h2>Latest News!</h2>
                <p>This is a template for a simple marketing or informational website. It includes a large callout called a jumbotron and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
                <p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more &raquo;</a></p>
            </div>
        </div>
    </section> <!-- /.jumbo-intro -->
    <section class="container">
        <div class="row">

            <?php include '../include/gallery.php'; ?>

        </div> <!-- /.row -->
    </section> <!-- /.container -->

<?php
    include '../include/footer.php';
} else {
    $_SESSION['errors'] = array('Please log in before accessing this website');
    header('Location: ../index.php');
}
?>
