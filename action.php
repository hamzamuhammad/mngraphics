<?php //action.php lets the admin do all sorts of things to an order

	$file_name = "";
	if (isset($_POST['file_name'])) {
		$file_name = sanitizeString($_POST['file_name']);
	}

	require_once 'login.php';
	$connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
	if ($connection->connect_error) die($connection->connect_error);

	$query = "SELECT * FROM files WHERE file_name='$file_name'";
	$result = $connection->query($query);
    $row = $result->fetch_array(MYSQLI_NUM);	
	$comments = $row[4];
	$status = $row[3];

	function sanitizeString($var) {
		$var = stripslashes($var);
		$var = htmlentities($var);
		$var = strip_tags($var);
		return $var;
	}

	$connection->close();
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Action</title>

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
		<form action = "update.php" method="POST" form class="form-horizontal" role="form" enctype="multipart/form-data">
		    <div class="centercontents">
		      <h2 class="form-signin-heading">Action</h2>
		    </div>
			<div class="form-group">
	          <div class="centercontents">
	          	<button type="button" class="btn btn-info">
	          		<?php echo '<a href="/uploads/' . $file_name . '" download> Download File </a>' ?>  			
				</button>
	          </div>
	        </div>
	  		<label for="comment">Comments</label>
	  		<div class="form-group">
	  			<textarea class="form-control" rows="5" id="comment" name="comments" readonly><?php echo $comments; ?></textarea>
			</div>
			<div class="centercontents">
				</div class = "form-group">
				<?php 
					$array = array("pending", "received", "on hold", "in progress", "completed");
					for ($i = 0; $i < count($array); $i++) {
						if ($array[$i] === $status) {
							echo '<label class="radio-inline"><input type="radio" value=' . $i . ' name="status" checked="checked">'. $status . '</label>';
						}
						else {					
							echo '<label class="radio-inline"><input type="radio" value=' . $i . ' name="status">'. $array[$i] . '</label>';
						}
					}
				?>
				</div>
			</div>
			<div class="form-group">
                <?php echo '<input type="hidden" name="file_name" value='. $file_name .'>' ?>
            </div>
			<div class="centercontents">
				<div class="btn-group">
						<div class="form-group"> 
							<button type="submit" class="btn btn-danger" name="delete">Delete</button>
						</div>
						<div class="form-group"> 
							<button type="submit" class="btn btn-success" name="update">Update</button>
						</div>
				</div>
			</div>
		</form>
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