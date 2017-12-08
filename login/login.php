<?php
require("config.php");
require("functions.php");
require("./classes/classes.php");
//echo $serverHost;
//kui on juba sisseloginud
if(isset($_SESSION["user"])){
	header("Location: main.php?user=".$_SESSION["user"]);
	exit();
}
$signupFirstName = "";
$signupFamilyName = "";
$signupEmail = "";
$gender = "";

$loginUser = "";
$notice="!";
$signupFirstNameError = "";
$signupFamilyNameError = "";
$signupEmailError = "";
$signupPasswordError = "";
$signupGenderError = "";

$loginUserError ="";

if(isset($_POST["loginButton"])){
	//kas on kasutajanimi sisestatud
	if (isset ($_POST["loginUser"])){
		if (empty ($_POST["loginUser"])){
			$loginUserError ="NB! Sisselogimiseks on 	vajalik kasutajatunnus";
		} else {
			$loginUser = $_POST["loginUser"];
		}
	}
	
	if(!empty($_POST["loginUser"]) and !empty($_POST["loginPassword"])){
		echo "Alustan sisselogimist!";
		//$hash = hash("sha512", $_POST["loginEmail"]);
		$loginPassword = $_POST["loginPassword"];
		$login = new userFunction();
		$login->userLogin($loginUser, $loginPassword);
		unset($login);

		//$notice = signIn($loginEmail, $hash);
	}
	
}//if loginButton

//kas klikiti kasutaja loomise nupul
if(isset($_POST["signupButton"])){

//kontrollime, kas kirjutati eesnimi
if (isset ($_POST["signupFirstName"])){
	if (empty($_POST["signupFirstName"])){
		$signupFirstNameError ="NB! Väli on kohustuslik!";
	} else {
		$signupFirstName = test_input($_POST["signupFirstName"]);
	}
}

//kontrollime, kas kirjutati kasutajanimeks email
if (isset ($_POST["signupEmail"])){
	if (empty ($_POST["signupEmail"])){
		$signupEmailError ="NB! Väli on kohustuslik!";
	} else {
		$signupEmail = test_input($_POST["signupEmail"]);
		
		$signupEmail = filter_var($signupEmail, FILTER_SANITIZE_EMAIL);
		$signupEmail = filter_var($signupEmail, FILTER_VALIDATE_EMAIL);
	}
}

if (isset ($_POST["signupPassword"])){
	if (empty ($_POST["signupPassword"])){
		$signupPasswordError = "NB! Väli on kohustuslik!";
	} else {
		//polnud tühi
		if (strlen($_POST["signupPassword"]) < 8){
			$signupPasswordError = "NB! Liiga lühike salasõna, vaja vähemalt 8 tähemärki!";
		}
	}
}

if (isset($_POST["gender"]) && !empty($_POST["gender"])){ //kui on määratud ja pole tühi
		$gender = intval($_POST["gender"]);
	} else {
		$signupGenderError = " (Palun vali sobiv!) Määramata!";
}

//UUE KASUTAJA ANDMEBAASI KIRJUTAMINE, kui kõik on olemas	
if (empty($signupFirstNameError) and empty($signupFamilyNameError) and empty($signupEmailError) and empty($signupPasswordError)){
	echo "Hakkan salvestama!";
	//krüpteerin parooli
	//$signupPassword = hash("sha512", $_POST["signupPassword"]);
	//echo "\n Parooli " .$_POST["signupPassword"] ." räsi on: " .$signupPassword;
	$register = new userFunction();
	$register->userRegister($signupFirstName, $_POST["signupPassword"], $signupEmail, $gender);
	unset($register);
}

}
?>
<!DOCTYPE html>
<html lang="en" >
<head profile="http://www.w3.org/2005/10/profile">
  <meta charset="UTF-8">
  <title>Logige sisse/Registreerige</title>
<link rel="icon" type="image/png" href="http://greeny.cs.tlu.ee/~riskkris/veebiprogremiseprojekt/login/favicon.png">
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
  <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900'>
  <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>
  <link rel="stylesheet" href="css/style.css">

  
</head>

<body>
  

</div>
<div class="container">
  <div class="card"></div>
  <div class="card">
    <h1 class="title">Sisselogimine</h1>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <div class="input-container">
        <input name="loginUser" type="text" required="required" value="<?php echo $loginUser; ?>">
        <label>Kasutajanimi</label>
        <div class="bar"></div>
      </div>
      <div class="input-container">
        <input name="loginPassword" type="password" required="required">
		<label>Parool</label>
        <div class="bar"></div>
      </div>
      <div class="button-container">
        <button name="loginButton" type="submit" value="loginButton"><span>Logi Sisse<?php echo $notice; ?></span></button>
      </div>
    </form>
  </div>
  <div class="card alt">
    <div class="toggle"></div>
    <h1 class="title">Registreerumine
      <div class="close"></div>
    </h1>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <div class="input-container">
        <input name="signupFirstName" type="text" value="<?php echo $signupFirstName; ?>" required="required">
        <label>Kasutajanimi</label>
		<div class="bar"></div>
      </div>
	  <div class="input-container">
		<select name="gender">
			<option value="1" selected>Mees</option>
			<option value="2">Naine</option>
		</select>
	  </div>
	  <div class="input-container">
        <input name="signupEmail" type="email" value="<?php echo $signupEmail; ?>" required="required">
		<label>E-mail</label>
        <div class="bar"></div>
      </div>
      <div class="input-container">
        <input name="signupPassword" type="password" required="required">
        <label>Parool</label>
        <div class="bar"></div>
      </div>
      <div class="button-container">
        <button name="signupButton" type="submit" value="signupButton"><span>Registreeruge</span></button>
      </div>
    </form>
  </div>
</div>
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

    <script  src="js/index.js"></script>

</body>
</html>
