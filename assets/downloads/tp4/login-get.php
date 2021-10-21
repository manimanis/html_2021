<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <?php

  if (isset($_GET['user']) && isset($_GET['pass'])) {
    $user = $_GET['user'];
    $pass = $_GET['pass'];

    if ($user == "admin" && $pass == "admin") {
      echo "<p>Logged in $user</p>";
    } else {
      echo "<p>Login incorrect.</p>";
    }
  } else {
    echo "<p>Login incorrect.</p>";
  }
  ?>
</body>

</html>