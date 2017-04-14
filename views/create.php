<!doctype html>
<html>
<head>
    <title>Create Account | Matcha</title>
    <?php include '../include/head.php'; ?>
</head>
<body>
    <header>
        <?php include '../include/header.php'; ?>
        <h1>Matcha - <small>Create Account</small></h1>
    </header>

    <div id="alert-messages"></div>

    <section class="row account-management-page">
        <section class="col-12">
            <form id="createUserForm" class="animate_label" method="post" enctype="application/x-www-form-urlencoded">
                <div class="form-group row">
                    <div class="col-sm-6 form-input">
                        <label class="col-md-2 col-form-label" for="firstname">Firstname</label>
                        <input class="form-control" type="text" name="firstname" id="fname" value="" placeholder="Firstname" maxlength="32" autocomplete="true" required="true" />
                    </div>
                    <div class="col-sm-6 form-input">
                        <label class="col-md-2 col-form-label" for="lastname">Lastname</label>
                        <input class="form-control" type="text" name="lastname" id="lname" value="" placeholder="Lastname" maxlength="32" autocomplete="true" required="true" />
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-6 form-input">
                        <label class="col-md-2 col-form-label" for="username">Username:</label>
                        <input class="form-control" type="text" name="username" id="uname" value="" placeholder="Username" maxlength="24" autocomplete="true" required="true" />
                    </div>
                    <div class="col-sm-6 form-input">
                        <label class="col-md-2 col-form-label" for="user_age">Age:</label>
                        <input class="form-control" type="text" name="age" id="user_age" value="" placeholder="Age" maxlength="2" autocomplete="true" required="true" />
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-6 col-sm-offset-3 form-input">
                        <label class="col-md-2 col-form-label" for="emailaddr">Email Address:</label>
                        <input class="form-control" type="email" name="emailaddr" id="email" value="" placeholder="Email Address" maxlength="64" autocomplete="true" required="true" />
                    </div>
                </div>

                <hr  />

                <div class="form-group row">
                    <div class="col-sm-6 form-input">
                        <label class="col-md-2 col-form-label" for="password">Password:</label>
                        <input class="form-control" type="password" name="password" id="passwd" value="" placeholder="Password" autocomplete="true" required="true" />
                    </div>
                    <div class="col-sm-6 form-input">
                        <label class="col-md-2 col-form-label" for="password2">Confirm Password:</label>
                        <input class="form-control" type="password" name="password2" id="passwd2" value="" placeholder="Confirm Password" autocomplete="true" required="true" />
                    </div>
                </div>
                <div class="col-sm-6 form-input">
                    <input class="form-control" type="submit" name="submit" value="OK" />
                </div>
            </form>
        </section>
    </section>

<?php include '../include/footer.php'; ?>

<script type="text/javascript" src="/matcha/assets/js/create_acc.js"></script>

</body>

</html>
