<?php //modify.php, called from edit.php
    
    session_start();

    $orders = NULL;
    if (isset($_SESSION['orders'])) {
      $orders = $_SESSION['orders'];
    }

    require_once 'login.php';
    $connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
    if ($connection->connect_error) die($connection->connect_error);

    $index = "";
    $new_comments = "";

    if (isset($_POST['index']) && isset($_POST['comments'])) {
        $index = sanitizeMySQL($connection, $_POST['index']); 
        $new_comments = sanitizeMySQL($connection, $_POST['comments']);
    }

    $row = $orders[intval($index)];
    $file_name = $row[0];
    $new_file_name = $_FILES['file']['name'];

    if (isset($_POST['update'])) { //if we want to update order
        if ($_FILES['file']['name'] != "") { //if file was chosen, 
            $query = "UPDATE files SET old_file_name='$new_file_name' WHERE file_name='$file_name'";
            $connection->query($query);
            //$query->close(); //HAVE TO EVENTUALLY FIX THIS
            move_uploaded_file($_FILES['file']['tmp_name'], "uploads/$file_name");
        }
        //now we ALWAYS update comments
        $query = "UPDATE files SET comments='$new_comments' WHERE file_name='$file_name'";
        $connection->query($query);
        //$query->close(); //HAVE TO FIX THIS AS WELL
        echo '<div class="alert alert-success">Successfully updated order!</div>';
        echo '<script>setTimeout(function(){window.location.href="../../home.php"},2000);</script>';
    }
    else if (isset($_POST['delete'])) { //delete the entire order
        $query = "DELETE FROM files WHERE file_name='$file_name'";
        $connection->query($query);        
        //have to delete the file, AND reduce the count by 1. 
        //$query->close(); //FIX THIS SHIT AS WELL
        $fh = fopen("count.txt", 'r+') or die("File does not exist or you lack permission to open it");
        $line = fgets($fh);
        $count = intval($line);
        $file_count = $count - 1;
        fseek($fh, 0);
        fwrite($fh, $file_count) or die("Could not write to file");
        fclose($fh);
        //delete file
        if (!unlink("uploads/$file_name")) echo "Could not delete file";
        else {
            echo '<div class="alert alert-success">Successfully deleted order!</div>';
            echo '<script>setTimeout(function(){window.location.href="../../home.php"},2000);</script>';
        }
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