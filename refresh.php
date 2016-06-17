<?php //refresh.php update the session variables for the orders table
	session_start();

	$email_address = "";
    if (isset($_SESSION['email_address'])) {
      $email_address = $_SESSION['email_address'];
    }

	require_once 'login.php';
    $connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
    if ($connection->connect_error) die($connection->connect_error);


    $orders = format_table($connection, $email_address);
    $_SESSION['orders'] = $orders;

   	echo '<div class="alert alert-success">Refreshing...</div>';
    echo '<script>setTimeout(function(){window.location.href="../../home.php"},2000);</script>';

    $connection->close();
	
echo <<<_END
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Login</title>

    <!-- Bootstrap core CSS -->
    <link href="../../dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="starter-template.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="../../dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
_END;

    function format_table($connection, $email_address) {
    	$array = array();
    	$query = "SELECT * FROM files WHERE email_address='$email_address'";
    	$result = $connection->query($query);
    	if (!$result) die ($connection->error);
    	for ($i = 0; $i < $result->num_rows; $i++) {
    		$row = $result->fetch_array(MYSQLI_NUM);
    		$new_array = array($row[1], $row[2], $row[3], $row[4], $row[5]);
    		array_push($array, $new_array);
    	}
    	return $array;
    }

	function get_post($connection, $var) {
		return $connection->real_escape_string($_POST[$var]);
	}

	function sanitizeString($var) {
		$var = stripslashes($var);
		$var = htmlentities($var);
		$var = strip_tags($var);
		return $var;
	}

	function sanitizeMySQL($connection, $var) {
		$var = $connection->real_escape_string($var);
		$var = sanitizeString($var);
		return $var;
	}
?>