<?php
    require("functions.php");
    require("config.php");
    require("classes/classes.php");
	//kui pole sisse logitud, liigume login lehele
	if(!isset($_SESSION["user"])){
		header("Location: login.php");
		exit();
    }
    
    #Profiili info saamine SISSE LOGITUD KASUTAJA PÕHJAL
  $getProfileInfo = new getFromDatabase();
  $getProfileInfo->userProfileInfo($_SESSION["user"]);
	
	//väljalogimine
	if(isset($_GET["logout"])){
		session_destroy(); //lõpetab sessiooni
		header("Location: login.php");
	}
	#$picsDir = "../../veebiprogrammeerimine-kursus-1/pics/";
	#$picFiles = [];
	#$picFileTypes = ["jpg", "jpeg", "png", "gif"];
	
	#$allFiles = array_slice(scandir($picsDir), 2);
	
	#foreach ($allFiles as $file){
	#	$fileType = pathinfo($file, PATHINFO_EXTENSION);
	#	if (in_array($fileType, $picFileTypes) == true){
	#		array_push($picFiles, $file);
	#	}
	#}
	
	#var_dump($picFiles);
	#$fileCount = count($picFiles);
	#$picNumber = mt_rand(0, ($fileCount - 1));
    #$picFile = $picFiles[$picNumber];

    $target_dir = "pics/";
	$target_file = "";
	$uploadOk = 1;
	
	$imageFileType = "";
	$maxWidth = 180;
	$maxHeight = 180;
	$marginVer = 10;
	$marginHor = 10;
	$notice = "";
    
    if(isset($_POST["submit"])) {
			if(!empty($_FILES["fileToUpload"]["name"])){
				
				//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
				$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]))["extension"]);
				$timeStamp = microtime(1) *10000;
				//$target_file = $target_dir . pathinfo(basename($_FILES["fileToUpload"]["name"]))["filename"] ."_" .$timeStamp ."." .$imageFileType;
				$target_file = "USERPROF".$_SESSION["user"].$timeStamp ."." .$imageFileType;
			
				$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
				if($check !== false) {
					$notice= "Fail on pilt - " . $check["mime"] . ". ";
					$uploadOk = 1;
				} else {
					$notice= "See pole pildifail. ";
					$uploadOk = 0;
				}
			
				//Kas selline pilt on juba üles laetud
				if (file_exists($target_file)) {
					$notice= "Kahjuks on selle nimega pilt juba olemas. ";
					$uploadOk = 0;
				}
				//Piirame faili suuruse
				if ($_FILES["fileToUpload"]["size"] > 1000000) {
					$notice= "Pilt on liiga suur! ";
					$uploadOk = 0;
				}
				
				//Piirame failitüüpe
				if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
					$notice= "Vabandust, vaid jpg, jpeg, png ja gif failid on lubatud! ";
					$uploadOk = 0;
				}
				
				//Kas saab laadida?
				if ($uploadOk == 0) {
					$notice= "Vabandust, pilti ei laetud üles! ";
				//Kui saab üles laadida
				} else {		
									
					//kasutame klassi
					$myPhoto = new Photoupload($_FILES["fileToUpload"]["tmp_name"], $imageFileType);
					$myPhoto->readExif();
					$myPhoto->resizeImage($maxWidth, $maxHeight);
					$notice = $myPhoto->savePhoto($target_dir, $target_file);
					if($notice == "true"){
						$notice= "Pilt laeti üles!";
						$myPhoto->imageProfInfoToDatabase($_SESSION["user"], $target_file);
					} else {
						$notice= "Pildi üleslaadimine ebaõnnestus!";
					}
					$myPhoto->clearImages();
					unset($myPhoto);
					
				}
			
            }
		}
		var_dump($_POST);
		if(isset($_POST["submitpass"])){
			if(!empty($_POST["textpass_old"])){
				if(!empty($_POST["textpass"])){
					if(strlen($_POST["textpass"]) < 8){
						echo "Liiga lühika salasõna!";
					}
					else
					{
					$passchange = new userFunction();
					$passchange->updatePassword($_SESSION["user"], $_POST["textpass"], $_POST["textpass_old"]);
					}
				}
				else{
					echo "Sisestage uus salasõna!";
				}
			}
			else{
				echo "Sisestage vana salasõna!";
			}
		}

?>
<!DOCTYPE html>
<html>
<head>
	<title>
		<?php echo $_SESSION["prof_user"]?> Profiil
	</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" type="image/png" href="http://greeny.cs.tlu.ee/~salonorm/veebiprog_iseseisev/favicon.PNG">
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
  <a href="main.php" class="w3-bar-item w3-button-active w3-hide-small w3-padding-large w3-large w3-hover-white"><i class="fa fa-arrow-circle-left w3-margin-right"></i>Tagasi</a>
  <a href="?logout=1" class="w3-bar-item-float-right w3-button w3-hide-small w3-padding-large w3-hover-white w3-large"><i class="fa fa-sign-out w3-margin-right"></i>Logi välja</a>
  <a href="#" class="w3-bar-item w3-button-active w3-hide-small w3-padding-large w3-large w3-theme-d4"><i class="fa fa-user-circle w3-margin-right"></i>Minu Kasutaja</a>
  <a href="mypictures.php" class="w3-bar-item w3-button-active w3-hide-small w3-padding-large w3-large w3-hover-white"><i class="fa fa-photo w3-margin-right"></i>Minu Pildid</a>
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
        <img src="<?php echo "pics/".$_SESSION["prof_profileimg"]; ?>" alt="Profiil" class="w3-left w3-circle w3-margin-right" style="width:100px">
        <span class="w3-right w3-opacity"></span>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
		<input name="fileToUpload" type="file">
		<input name="submit" type="submit" value="Vaheta/Lisa profiilipilt"  class="w3-button w3-theme">
        </form>
        <br><br><br><br><h4>Kasutaja andmed</h4>
        <hr class="w3-clear">
        <p>Kasutaja: <?php echo $_SESSION["prof_user"]?> </p>
		<p>E-mail: <?php echo $_SESSION["prof_email"]; ?> </p>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label><b>Vana parool</b></label><br></br><input type="password" name="textpass_old" ><br></br>
		<label><b>Uus Parool</b></label><br></br><input type="password" name="textpass" >
		<br></br>
		<input type="submit" name="submitpass" value="Vaheta Parool" class="w3-button w3-theme">
					
		</form>
        <div class="w3-row-padding" style="margin:0 -16px">
        </div>
      </div> 
      
    <!-- Lõpp Põhikolumn -->
    </div>
	
	<!-- Vasak kolumn -->
    <div class="w3-col m3">
      <!-- Profiil -->
      <div class="w3-card w3-round w3-white w3-center">
        <div class="w3-container">
         <h4 class="w3-center"><?php echo $_SESSION["prof_user"]?></h4>
         <p class="w3-center"><img src="<?php echo "pics/".$_SESSION["prof_profileimg"]; ?>" class="w3-circle" style="height:106px;width:106px" alt="banaan"></p>
         <hr>
         <p><i class="fa fa-birthday-cake fa-fw w3-margin-right w3-text-theme"></i>Liitus <?php echo date('j. F, Y',strtotime($_SESSION['prof_date']));?></p>
        </div>
      </div>
      <br>
      <br>
	<!-- Lõpp vasak kolumn -->
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