<?php
	require("functions.php");
	//kui pole sisse logitud, liigume login lehele
	if(!isset($_SESSION["userId"])){
		header("Location: login.php");
		exit();
	}

	$signupFirstName = "";
	//väljalogimine
	if(isset($_GET["logout"])){
		session_destroy(); //lõpetab sessiooni
		header("Location: login.php");
	}
	$picsDir = "../../veebiprogrammeerimine-kursus-1/pics/";
	$picFiles = [];
	$picFileTypes = ["jpg", "jpeg", "png", "gif"];
	
	$allFiles = array_slice(scandir($picsDir), 2);
	
	foreach ($allFiles as $file){
		$fileType = pathinfo($file, PATHINFO_EXTENSION);
		if (in_array($fileType, $picFileTypes) == true){
			array_push($picFiles, $file);
		}
	}
	
	var_dump($picFiles);
	$fileCount = count($picFiles);
	$picNumber = mt_rand(0, ($fileCount - 1));
	$picFile = $picFiles[$picNumber];
?>
<!DOCTYPE html>
<html>
<head>
	<title>
		<?php echo $_SESSION["firstname"] ." " .$_SESSION["lastname"]; ?>
	</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/w3.css">
		<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-blue-grey.css">
		<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans'>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style>
	html,body,h1,h2,h3,h4,h5 {font-family: "Open Sans", sans-serif}
	</style>
	</head>
<body class="w3-theme-l5">

<!-- Navbar -->
<div class="w3-top">
 <div class="w3-bar w3-theme-d2 w3-left-align w3-large">
  <a href="http://greeny.cs.tlu.ee/~riskkris/veebiprogremiseprojekt/login/main.php" class="w3-bar-item w3-button-active w3-hide-small w3-padding-large w3-large w3-hover-white"><i class="fa fa-arrow-circle-left w3-margin-right"></i>Tagasi</a>
  <a href="?logout=1" class="w3-bar-item-float-right w3-button w3-hide-small w3-padding-large w3-hover-white w3-large"><i class="fa fa-sign-out w3-margin-right"></i>Logi välja</a>
  <a href="#" class="w3-bar-item w3-button-active w3-hide-small w3-padding-large w3-large w3-theme-d4"><i class="fa fa-user-circle w3-margin-right"></i>Minu Kasutaja</a>
  <a href="http://greeny.cs.tlu.ee/~riskkris/veebiprogremiseprojekt/login/mypictures.php" class="w3-bar-item w3-button-active w3-hide-small w3-padding-large w3-large w3-hover-white"><i class="fa fa-photo w3-margin-right"></i>Minu Pildid</a>
 </div>
</div>

<!-- Navbar on small screens -->
<div id="navDemo" class="w3-bar-block w3-theme-d2 w3-hide w3-hide-large w3-hide-medium w3-large">
  <a href="#" class="w3-bar-item w3-button w3-padding-large">Link 1</a>
  <a href="#" class="w3-bar-item w3-button w3-padding-large">Link 2</a>
  <a href="#" class="w3-bar-item w3-button w3-padding-large">Link 3</a>
  <a href="#" class="w3-bar-item w3-button w3-padding-large">My Profile</a>
</div>

<!-- Page Container -->
<div class="w3-container w3-content" style="max-width:1600px;margin-top:50px">    
  <!-- Algus -->
  <div class="w3-row">   
    
    <!-- Põhikolumn -->
    <div class="w3-col m7" style="margin-top:10px">
      
      <div class="w3-container w3-card w3-white w3-round w3-margin"><br>
        <img src="./pics/banaan.png" alt="Profiil" class="w3-left w3-circle w3-margin-right" style="width:100px">
        <span class="w3-right w3-opacity"></span><br>
        <br><br><br><br><h4>Kasutaja andmed</h4>
        <hr class="w3-clear">
		<label>Eesnimi: </label><input class="w3-inputbox w3-border" name="userName" type="text"value="<?php echo $signupFirstName; ?>"><br><br>
		<label>Perekonnanimi: </label><input class="w3-inputbox w3-border" name="userName" type="text"value="<?php echo $signupFirstName; ?>"><br><br>
		<label>E-mail: </label><input class="w3-inputbox w3-border" name="userName" type="text"value="<?php echo $signupFirstName; ?>"><br><br>
		<a href="#" class="w3-button w3-theme-d1 w3-margin-bottom"><i class="fa fa-times w3-margin-right"></i>Tühista</a>
		<a href="#" class="w3-button w3-theme-d1 w3-margin-bottom"><i class="fa fa-magic w3-margin-right"></i>Salvesta muudatused</a>
        <div class="w3-row-padding" style="margin:0 -16px">
        </div>
      </div> 
      
    <!-- Lõpp Põhikolumn -->
    </div>
  <!-- Lõpp -->
  </div>
  
<!-- End Page Container -->
</div>
<br>

 <!--<a href="#" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white" title="My Account"><img src="./pics/banaan.png" class="w3-circle" style="height:25px;width:25px" alt="Profiil"></a>-->
<script>
// Accordion
function myFunction(id) {
    var x = document.getElementById(id);
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
        x.previousElementSibling.className += " w3-theme-d1";
    } else { 
        x.className = x.className.replace("w3-show", "");
        x.previousElementSibling.className = 
        x.previousElementSibling.className.replace(" w3-theme-d1", "");
    }
}

// Used to toggle the menu on smaller screens when clicking on the menu button
function openNav() {
    var x = document.getElementById("navDemo");
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
    } else { 
        x.className = x.className.replace(" w3-show", "");
    }
}
</script>

</body>
</html> 
