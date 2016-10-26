<?php
session_start();
date_default_timezone_set('Africa/Johannesburg');
if ($_SESSION['logged_on_user'] === "")
  exit("ERROR\n");
if ($_POST['submit'] === "OK" && $_POST['msg'] && $_SESSION['logged_on_user'] != "")
{
  $path = "../private/";
  $file = $path."chat";
  if (!file_exists($path))
    mkdir($path, 0777);
  if (file_exists($file))
  {
    $fp = fopen($file, 'c+');
    if (flock($fp, LOCK_EX)){
      $messages = unserialize(file_get_contents($file));
      flock($fp, LOCK_UN);
    }
    else
      echo "speak.php -- Could not get lock on file\n";
    fclose($fp);
  }
  $messages[] = array('login' => $_SESSION["logged_on_user"], 'time' => date("H:i"),
                      'timestamp' => date("d/M/Y-H:i:s"), 'msg' => $_POST['msg']);
  file_put_contents($file, serialize($messages), LOCK_EX);
}


?>
<html>
<head>
  <style>
    body {
      margin: 0;
    }
    form {
      height: 50px;
      width: 101%;
    }
    textarea {
        width: 90%;
        height: 50px;
        padding: 12px 20px;
        box-sizing: border-box;
        border: 2px solid #ff6600;
        background-color: #f8f8f8;
        resize: none;
    }
    textarea.message:focus {
      outline: 0;
    }
    input[type="submit"] {
        position: relative;
        width: 10%;
        height: 50px;
        top: -18px;
        margin-left: -6px;
        border: 0;
        background-color: #ff6600;
        color: #fff;
        font-size: 20px;
        cursor: pointer;
    }
  </style>
  <script langage="javascript">
    top.frames['chat'].location = 'chat.php';
  </script>
</head>
<body>
  <form method="post" action="speak.php">
    <textarea name="msg" class="message"></textarea>
    <input type="submit" name="submit" value="OK" />
  </form>
</body>
</html>
