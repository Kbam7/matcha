<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Camagru</title>
<?php include './include/head.php'; ?>
</head>
<body>
    <header>
        <?php include './include/header.php'; ?>
    </header>
    <section class="jumbo-intro">

        <!--
            **
            ** Error display logic. Only some files use this.
            ** The rest use the error div (else)
         -->
        <?php if (isset($_SESSION['errors'])): ?>
        <div id="alert-messages">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <div class="alert alert-warning">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Warning!</strong> <?php echo $error ?>
                </div>
            <?php
                endforeach;
                unset($_SESSION['errors']);
            ?>
        </div>
        <?php else: ?>
            <div id="alert-messages"></div>
        <?php endif; ?>

        <div class="jumbotron">
            <div class="container">
                <h1>Welcome!</h1>
                <hr class="clearfix col-sm-12" />
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at nisl euismod nisi facilisis bibendum.
                    Vivamus ultricies quam id nunc ullamcorper, id suscipit purus volutpat.
                    Donec porttitor massa vitae metus pharetra, vel viverra justo lobortis.</p>
                <p><a class="btn btn-primary btn-lg" href="#" role="button">Register Now &raquo;</a></p>
            </div>
        </div>
    </section> <!-- /.jumbo-intro -->
    <section class="container">
        <div class="row">
            <div class="col-md-4">
                <h2>Heading</h2>
                <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
            </div>
            <div class="col-md-4">
                <h2>Heading</h2>
                <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
            </div>
            <div class="col-md-4">
                <h2>Heading</h2>
                <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
            </div>
        </div> <!-- /.row -->
    </section> <!-- /.container -->

<?php include './include/footer.php'; ?>
