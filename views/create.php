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
                <div class="col-6 gutter-right-10 form-input">
                    <label class="input_label" for="firstname">Firstname: <span class="require">*</span></label>
                    <input type="text" name="firstname" id="fname" value="" placeholder="Firstname" maxlength="32" autocomplete="true" required="true" />
                </div>
                <div class="col-6 gutter-left-10 form-input">
                    <label class="input_label" for="lastname">Lastname: <span class="require">*</span></label>
                    <input type="text" name="lastname" id="lname" value="" placeholder="Lastname" maxlength="32" autocomplete="true" required="true" />
                </div>

                <div class="col-6 gutter-right-10 form-input">
                    <label class="input_label" for="username">Username: <span class="require">*</span></label>
                    <input type="text" name="username" id="uname" value="" placeholder="Username" maxlength="24" autocomplete="true" required="true" />
                </div>
                <div class="col-6 gutter-right-10 form-input">
                    <label class="input_label" for="user_age">Age: <span class="require">*</span></label>
                    <input type="text" name="age" id="user_age" value="" placeholder="Age" maxlength="2" autocomplete="true" required="true" />
                </div>
                <div class="col-6 gutter-left-10 form-input">
                    <label class="input_label" for="emailaddr">Email Address: <span class="require">*</span></label>
                    <input type="email" name="emailaddr" id="email" value="" placeholder="Email Address" maxlength="64" autocomplete="true" required="true" />
                </div>

                <div class="col-6 gutter-right-10 form-input">
                    <label class="input_label" for="password">Password: <span class="require">*</span></label>
                    <input type="password" name="password" id="passwd" value="" placeholder="Password" autocomplete="true" required="true" />
                </div>
                <div class="col-6 gutter-left-10 form-input">
                    <label class="input_label" for="password2">Confirm Password: <span class="require">*</span></label>
                    <input type="password" name="password2" id="passwd2" value="" placeholder="Confirm Password" autocomplete="true" required="true" />
                </div>
                <div class="col-6 gutter-left-10 form-input">
                    <input type="submit" name="submit" value="OK" />
                </div>
            </form>
        </section>
    </section>

<?php include '../include/footer.php'; ?>

<script type="text/javascript" src="/matcha/assets/js/create_acc.js"></script>

</body>

</html>
