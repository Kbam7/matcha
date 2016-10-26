<?php
session_start();
include('auth.php');

$login = $_POST['login'];
$passwd = $_POST['passwd'];
if ($_POST['submit'] !== "OK"){
  echo "ERROR - No okay received from 'submit' input\n";
  header('Location: index.html');
}
else if ($login === "" || $login === null || $passwd === "" || $passwd === null){
  echo "ERROR -- No login or password\n";
  header('Location: index.html');
}
else if (auth($login, $passwd)){
  $_SESSION['logged_on_user'] = $login;
?>
<html>
  <head>
    <title>Chat Room</title>
    <meta charset="utf-8" />
    <style>
      body {
        font-family: sans-serif;
        background-color: #222;
        color: #fff;
        text-align: center;
      }
      header {
          clear: both;
          padding: 0px 0px 60px;
          margin: 30px 10px;
      }
      header a {
          float: left;
          padding: 20px;
          margin: 0px 50px 0px 0px;
          background-color: #ff6600;
          color: #fff;
          text-decoration: none;
      }
      h1 {
          font-size: 34px;
          margin: -10px 0px 0px 30px;
          padding: 20px 0px 20px 60px;
          text-transform: capitalize;
          float: left;
          border-left: 2px solid #fff;
      }
      .chat-messages {
        width: 90%;
        max-width: 1200px;
        margin: auto;
      }
      iframe.chat {
          height: 550px;
          width: 100%;
          border: 2px solid #ff6600;
          box-shadow: 0px 0px 10px #f60 inset;
      }
      iframe.speak {
        height: 50px;
        width: 100%;
        border: 2px solid #ff6600;
        border-top: 0px solid #ff6600;
      }
    </style>
  </head>
  <body>
    <header>
      <a href="logout.php">LOGOUT</a>
      <h1>The hangout</h1>
    </header>
    <section class="chat-messages">
      <iframe name="chat" class="chat" src="chat.php">
      </iframe>
      <iframe name="speak" class="speak" src="speak.php" scrolling="no" autofocus>
      </iframe>
    </section> <!-- /chat-messages -->
  </body>
</html>

<?php
}
else{
  $_SESSION['logged_on_user'] = "";
  echo "NO USER - ERROR\n";
  header('Location: index.html');
}
?>
