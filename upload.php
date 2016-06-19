<?php //upload.php

    session_start();

    $email_address = "";
    if (isset($_SESSION['email_address'])) {
      $email_address = $_SESSION['email_address'];
    }

	require_once 'login.php';
	$connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
	if ($connection->connect_error) die($connection->connect_error);

	if (!($_FILES['file']['name'] === "")) {
		$comments = sanitizeMySQL($connection, $_POST['comments']); 
        $old_file_name = $_FILES['file']['name'];
		$fh = fopen("count.txt", 'r+') or die("File does not exist or you lack permission to open it");
		$line = fgets($fh);
		$count = intval($line);
		$file_count = $count + 1;
		$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
		$new_file_name = $file_count . '.' . $extension;
		move_uploaded_file($_FILES['file']['tmp_name'], "uploads/$new_file_name");
		fseek($fh, 0);
		fwrite($fh, $file_count) or die("Could not write to file");
		fclose($fh);
        //have to send email of alert, and place all of this shit into the database
        $to = 'mmussadaq@gmail.com'; //put whatever email we want here!
        $subject = '<html>
                    <head>
                      <title>New Order Submitted!</title>
                    </head>
                    <body>
                      <p>Please click <a href="http://www.mngraphics.com">here</a> following link to go to your admin console:</p>                      
                    </body>
                    </html>
                    ';
        $headers = 'From: webmaster@mandngraphics.com' . "\r\n" .
            'Reply-To: webmaster@mandngraphics.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        //mail($to, $subject, $message, $headers); COMMENT THIS BACK IN WHEN YOU MOVED THIS TO AWS
        //now, we put info in our MySQL table!
        $timestamp; //THIS SHOULD BE SUBSTITUTED FOR FILE_COUNT!
        $status; //have to decide what this shit should be, for now its a string constant
        $query = "INSERT INTO files(email_address, file_name, date_uploaded, status, comments, old_file_name) 
        VALUES('$email_address', '$new_file_name', '$file_count', 'pending', '$comments', '$old_file_name')";
        $result = $connection->query($query);
        if (!$result) die($connection->error);
        //$result->close();
		echo '<div class="alert alert-success">Successfully submitted order request!</div>';
    	echo '<script>setTimeout(function(){window.location.href="../../home.php"},2000);</script>';
	}
    else {
        echo '<div class="alert alert-danger">No file selected!</div>';
        echo '<script>setTimeout(function(){window.location.href="../../neworder.php"},2000);</script>';
    }

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