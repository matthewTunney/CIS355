<?php 
/* ---------------------------------------------------------------------------
 * filename    : fr_event_create.php
 * author      : George Corser, gcorser@gmail.com
 * description : This program adds/inserts a new event (table: fr_events)
 * ---------------------------------------------------------------------------
 */
session_start();
if(!isset($_SESSION["fr_person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}
error_reporting(0);	
require '../database/database.php';
require 'functions.php';

if ( !empty($_POST)) { // if not first time through

	// initialize user input validation variables
	$dateError = null;
	$timeError = null;
	$locationError = null;
	$descriptionError = null;
	
	$fileName = $_FILES['userfile']['name'];
	$tmpName  = $_FILES['userfile']['tmp_name'];
	$fileSize = $_FILES['userfile']['size'];
	$fileType = $_FILES['userfile']['type'];
	$content = file_get_contents($tmpName);
	
	// initialize $_POST variables
	$date = htmlspecialchars($_POST['event_date']);
	$time = htmlspecialchars($_POST['event_time']);
	$location = htmlspecialchars($_POST['event_location']);
	$description = htmlspecialchars($_POST['event_description']);		
	
	// validate user input
	$valid = true;
	if (empty($date)) {
		$dateError = 'Please enter Date';
		$valid = false;
	}
	if (empty($time)) {
		$timeError = 'Please enter Time';
		$valid = false;
	} 		
	if (empty($location)) {
		$locationError = 'Please enter Location';
		$valid = false;
	}		
	if (empty($description)) {
		$descriptionError = 'Please enter Description';
		$valid = false;
	}
	$types = array('image/jpeg','image/gif','image/png');

	if($filesize > 0) {
		if(in_array($_FILES['userfile']['type'], $types)) {
		}
		else {
			$filename = null;
			$filetype = null;
			$filesize = null;
			$filecontent = null;
			$pictureError = 'improper file type';
			$valid=false;
			
		}
	}

	// insert data
	if ($valid) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO fr_events (event_date, event_time, event_location, event_description, filename,filesize,filetype,filecontent) values(?, ?, ?, ?, ?, ?, ?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($date,$time,$location,$description, $filename, $filesize, $filetype, $filecontent));
		Database::disconnect();
		header("Location: fr_events.php");
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="js/bootstrap.min.js"></script>
	<link rel="icon" href="cardinal_logo.png" type="image/png" />
</head>

<body>
    <div class="container">
		<?php 
			//gets logo
			functions::logoDisplay();
		?>	
		<div class="span10 offset1">
		
			<div class="row">
				<h3>Add New Shift</h3>
			</div>
	
			<form class="form-horizontal" action="fr_event_create.php" method="post">
			
				<div class="control-group <?php echo !empty($dateError)?'error':'';?>">
					<label class="control-label">Date</label>
					<div class="controls">
						<input name="event_date" type="date"  placeholder="Date" value="<?php echo !empty($date)?$date:'';?>">
						<?php if (!empty($dateError)): ?>
							<span class="help-inline"><?php echo $dateError;?></span>
						<?php endif; ?>
					</div>
				</div>
			  
				<div class="control-group <?php echo !empty($timeError)?'error':'';?>">
					<label class="control-label">Time</label>
					<div class="controls">
						<input name="event_time" type="time" placeholder="Time" value="<?php echo !empty($time)?$time:'';?>">
						<?php if (!empty($timeError)): ?>
							<span class="help-inline"><?php echo $timeError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($locationError)?'error':'';?>">
					<label class="control-label">Location</label>
					<div class="controls">
						<input name="event_location" type="text" placeholder="Location" value="<?php echo !empty($location)?$location:'';?>">
						<?php if (!empty($locationError)): ?>
							<span class="help-inline"><?php echo $locationError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($descriptionError)?'error':'';?>">
					<label class="control-label">Description</label>
					<div class="controls">
						<input name="event_description" type="text" placeholder="Description" value="<?php echo !empty($description)?$description:'';?>">
						<?php if (!empty($descriptionError)): ?>
							<span class="help-inline"><?php echo $descriptionError;?></span>
						<?php endif;?>
					</div>
				</div>
					<div class="control-group <?php echo !empty($pictureError)?'error':'';?>">
					<label class="control-label">Picture</label>
					<div class="controls">
						<input type="hidden" name="MAX_FILE_SIZE" value="16000000">
						<input name="userfile" type="file" id="userfile">
						
					</div>
				</div>
				
				<div class="form-actions">
					<button type="submit" class="btn btn-success">Create</button>
					<a class="btn" href="fr_events.php">Back</a>
				</div>
				
			</form>
			
		</div> <!-- div: class="container" -->
				
    </div> <!-- div: class="container" -->
	
</body>
</html>
