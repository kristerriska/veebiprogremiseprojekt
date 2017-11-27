<?php
	require("../../../config.php");
	require("functions.php");
	//echo $serverHost;
	
	//kui on juba sisseloginud
	if(isset($_SESSION["userId"])){
		header("Location: main.php");
		exit();
	}
	$signupFirstName = "";
	$signupFamilyName = "";
	$signupEmail = "";
	
	$loginEmail = "";
	$notice="!";
	$signupFirstNameError = "";
	$signupFamilyNameError = "";
	$signupEmailError = "";
	$signupPasswordError = "";
	
	$loginEmailError ="";
	
	if(isset($_POST["loginButton"])){
		//kas on kasutajanimi sisestatud
		if (isset ($_POST["loginEmail"])){
			if (empty ($_POST["loginEmail"])){
				$loginEmailError ="NB! Sisselogimiseks on 	vajalik kasutajatunnus (e-posti aadress)!";
			} else {
				$loginEmail = $_POST["loginEmail"];
			}
		}
		
		if(!empty($loginEmail) and !empty($_POST["loginPassword"])){
			//echo "Alustan sisselogimist!";
			//$hash = hash("sha512", $_POST["loginEmail"]);
			$notice = signIn($loginEmail, $_POST["loginPassword"]);
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
	
	//kontrollime, kas kirjutati perekonnanimi
	if (isset ($_POST["signupFamilyName"])){
		if (empty($_POST["signupFamilyName"])){
			$signupFamilyNameError ="NB! Väli on kohustuslik!";
		} else {
			$signupFamilyName = test_input($_POST["signupFamilyName"]);
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
	
	//UUE KASUTAJA ANDMEBAASI KIRJUTAMINE, kui kõik on olemas	
	if (empty($signupFirstNameError) and empty($signupFamilyNameError) and empty($signupEmailError) and empty($signupPasswordError)){
		echo "Hakkan salvestama!";
		//krüpteerin parooli
		$signupPassword = hash("sha512", $_POST["signupPassword"]);
		//echo "\n Parooli " .$_POST["signupPassword"] ." räsi on: " .$signupPassword;
		signUp($signupFirstName, $signupFamilyName, $signupEmail, $signupPassword);
	}
	
	}
?>
<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Logige sisse/Registreerige</title>
  
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
        <input name="loginEmail" type="email" required="required" value="<?php echo $loginEmail; ?>">
        <label>E-mail</label>
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
        <label>Eesnimi</label>
		<div class="bar"></div>
      </div>
	  <div class="input-container">
        <input name="signupFamilyName" type="text" value="<?php echo $signupFamilyName; ?>" required="required">
		<label>Perekonnanimi</label>
        <div class="bar"></div>
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
