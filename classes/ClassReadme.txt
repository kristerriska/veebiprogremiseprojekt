CLASS README

class userFunction 

function userRegister(username, password, email, gender, profileimgname)
# Saades sisendid �hendab ennast mysql databaseiga
# Registreerib kasutaja andmebaasi (NB ei kr�pteeri salas�na, seda peab tegema enne sisendi andmist sellele funktsioonile) ja loob kausta /users/img/"username"
function userLogin(username, password)
# Saades sisendid �hendab ennast mysql andmebaasiga ja kontrollib salas�na(sellele funktsioonile on dekr�pteerimine sisse kirjutatud)
# Kui kasutaja saab sisse logitud, viib ta kasutaja enda profiililehele milleks on /profile.php?user="kasutajanimi"

class getFromDatabase

function userUploadedImg(username)
#Saades sisendi, j�rjestab k�ik pildid mis selle kasutajanimega seotud on (v.a profiilipilt) array v�ljana.
#$userUploadedimg = userUploadedImg("kasutajanimi");
#$image_loop = 1;
		#while($image_loop == 1){
		#	$i=0;
		#	if(isset($userUploadedimg[i])){
		#		-siia pildi kuvamise kood-
		#	} else {
		#		$image_loop = 0;
		#		}
		#	}
kood selle array v��rtuste k�tte saamiseks

function userProfileInfo(username)
#Tagastab profiili info, samamoodi arrayna
#kui $userProfileInfo = userProfileInfo("kasutajanimi")
#siis v�ljund peaks olema selline
# $userProfileInfo[0] = kasutajanimi
# $userProfileInfo[1] = email
# $userProfileInfo[2] = gender
# $userProfileInfo[3] = profileimgname