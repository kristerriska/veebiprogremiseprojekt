CLASS README

class userFunction 

function userRegister(username, password, email, gender, profileimgname)
# Saades sisendid ühendab ennast mysql databaseiga
# Registreerib kasutaja andmebaasi ja loob kausta /users/img/"username"
function userLogin(username, password)
# Saades sisendid ühendab ennast mysql andmebaasiga ja kontrollib salasõna
# Kui kasutaja saab sisse logitud, viib ta kasutaja enda profiililehele milleks on /profile.php?user="kasutajanimi"

class getFromDatabase

function userUploadedImg(username)
#Saades sisendi, järjestab kõik pildid mis selle kasutajanimega seotud on (v.a profiilipilt) array väljana.
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
kood selle array väärtuste kätte saamiseks

function userProfileInfo(username)
#Tagastab profiili info, samamoodi arrayna
#kui $userProfileInfo = userProfileInfo("kasutajanimi")
#siis väljund peaks olema selline
# $userProfileInfo[0] = kasutajanimi
# $userProfileInfo[1] = email
# $userProfileInfo[2] = gender
# $userProfileInfo[3] = profileimgname
# $userProfileInfo[4] = userbio
