<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Camagru</title>
<?php include './include/header.php'; ?>
</head>
<body>
    <header>
        <div class="navbar">
            <a href="home.php" class="brand"><h1>Camagru - <small>Take a photo, have some fun!</small></h1></a>
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
    <section class="row account-management-page">
        <?php if (isset($_SESSION['errors'])): ?>
        <div id="error-messages">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <p class="danger"><?php echo $error ?></p>
            <?php
                endforeach;
                unset($_SESSION['errors']);
            ?>
        </div>
        <?php else: ?>
            <div id="error-messages"></div>
        <?php endif; ?>
        <section class="col-7 login-form">
            <form id="loginForm" method="post" action="php/login.php">
                <div class="form-input">
                    <label class="input_label" for="login">Username:</label>
                    <input type="text" name="login" id="user-login" placeholder="Username" maxlength="24" title="Username" required="true" /> <!--  pattern="^[a-zA-Z]\B[a-zA-Z0-9]{4,18}[a-zA-Z0-9]\b$"   pattern="(?!.*[\.\-\_]{2,})^[a-zA-Z0-9\.\-\_]{3,24}$"   -->
                </div>
                <div class="form-input">
                    <label class="input_label" for="passwd">Password:</label>
                    <input type="password" name="passwd" id="user-passwd" placeholder="Password" title="Password" required="true" /> <!--     pattern="^(((?=.*[a-z])(?=.*[A-Z]))|((?=.*[a-z])(?=.*[0-9]))|((?=.*[A-Z])(?=.*[0-9])))(?=.{6,})"    -->
                </div>
                <div class="form-input">
                    <input type="submit" name="submit" value="OK" />
                </div>
            </form>
        </section>
        <section class="col-4 options">
            <div class="create-account">
                <h2>Are you new around here?</h2>
                <p>
                    Create a free account now and get in on all the latest news and gossip!
                </p>
                <a class="btn border border-3 white rounded hover-text-blue text-22" href="create.php" title="Create an Account">Create Account</a>
            </div>
        </section>
    </section>

<?php include './include/footer.php'; ?>
