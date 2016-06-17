<?php //edit.php used for modifying orders

	session_start();

    $comments = "";
    $orders = NULL;
    if (isset($_SESSION['orders'])) {
      $orders = $_SESSION['orders'];
    }

	$index = "";

	if (isset($_POST['index'])) {
		$index = sanitizeString($_POST['index']); 
	}

	$row = $orders[intval($index)];
	$comments = $row[3];

	function sanitizeString($var) {
		$var = stripslashes($var);
		$var = htmlentities($var);
		$var = strip_tags($var);
		return $var;
	}
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

    <title>Edit</title>

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
		<form action = "modify.php" method="POST" form class="form-horizontal" role="form" enctype="multipart/form-data">
		    <div class="centercontents">
		      <h2 class="form-signin-heading">Edit</h2>
		    </div>
			<div class="form-group">
	          <div class="centercontents">
	          	<label class="btn btn-primary" for="my-file-selector">
	    			<input id="my-file-selector" type="file" name="file" size='10'>	    			
				</label>
	          </div>
	        </div>
	        <div class="form-group">
	  			<label for="comment">Comments</label>
	  			<textarea class="form-control" rows="5" id="comment" name="comments"><?php echo $comments; ?></textarea>
			</div>
			<div class="centercontents">
				<div class="btn-group">
						<div class="form-group"> 
							<button type="submit" class="btn btn-danger" name="delete">Delete</button>
						</div>
						<div class="form-group"> 
							<button type="submit" class="btn btn-success" name="update">Update</button>
						</div>
						<div class="form-group">
							<?php 
								echo '<input type="hidden" name="index" value=' . $index . '>';
							?>
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
