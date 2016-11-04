<!doctype html>
<html>
<head>
  <title>Modify Account | Camagru</title>
<?php include '../include/header.php'; ?>
</head>
<body>
  <header class="global-style">
    <h1><a href="index.php">Change Password</a></h1>
  </header>
  <section class="global-style">
    <form method="post" action="modify_acc.php">
      <div class="form-input">
        <label class="input" for="login">Username:</label>
        <input type="text" name="login" id="user-login" value="" />
      </div>
      <div class="form-input">
        <label class="input" for="user-passwd">Old Password:</label>
        <input type="password" name="oldpw" id="user-passwd1" value="" />
      </div>
      <div class="form-input">
        <label class="input" for="user-passwd">New Password:</label>
        <input type="password" name="newpw" id="user-passwd2" value="" />
      </div>
      <div class="form-input">
        <input type="submit" name="submit" value="OK" />
      </div>
    </form>
  </section>

<?php include '../include/footer.php'; ?>

</body>

</html>
