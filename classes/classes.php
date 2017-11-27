<?php

    class userFunction {
		

        function __construct() {

        }

        public function userRegister($username, $password, $email, $gender) {
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$hash_password = hash("sha512", $password);
            $stmt = $mysqli->prepare("INSERT INTO userinfo (username, password, email, gender) VALUES (?, ?, ?, ?)");
            echo $mysqli->error;

            $stmt->bind_param("sssis", $username, $hash_password, $email, $gender);
            if ($stmt->execute()){
                mkdir("/users/img/".$_SESSION["user"], 0777);
		mkdir("/users/img/".$_SESSION["user"]."/profile_img", 0777);
                echo "\n Registered";
            } else {
                echo "\n Error : " .$stmt->error;
            }
            $stmt->close();
            $mysqli->close();

        }

        public function userLogin($username, $password) {
	    $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
            $stmt = $mysqli->prepare("SELECT username, password FROM userinfo WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->bind_result($usernameFromDb, $passwordFromDb);
            $stmt->execute();
            
            
            if ($stmt->fetch()){
                $hash = hash("sha512", $password);
                if ($hash == $passwordFromDb){
                    $notice = "Logged in";
                    
                    
                    $_SESSION["user"] = $usernameFromDb;
                    $directToProfile = "Location: profile.php?user=".$_SESSION["user"];
                    header($directToProfile);
                    exit();
                    
                } else {
                    $notice = "Wrong password";
                }
            } else {
                $notice = "Username does not exist";
            }
            
            $stmt->close();
            $mysqli->close();
        }


	}
	
	class getFromDatabase {

		function __construct(){

		}
		#Tagastab kõik pildinimed mis on kasutajanimega seotud. et neid näidata tee nii: $userUploadedimg = userUploadedImg("kasutajanimi");
		#$image_loop = 1;
		#while($image_loop == 1){
		#	$i=0;
		#	if(isset($userUploadedimg[i])){
		#		-siia pildi kuvamise kood-
		#	} else {
		#		$image_loop = 0;
		#		}
		#	}
		#See kood tagastab järjest kõik pildi nimed mis andmebaasis on, nende abil saab kaustadest pildifailid kätte.
		public function userUploadedImg($username) {
			$result = array();
			$i = 0;
			// Create connection
			$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
			// Check connection
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			} 
			
			$sql_prepared = "SELECT username, img_name FROM userinfo_img WHERE username = ".$username;
			$sql = $sql_prepared;
			$result = $conn->query($sql);
			
			if ($result->num_rows > 0) {
				// output data of each row
				while($row = $result->fetch_assoc()) {
					$result[$i] = $row["img_name"];
					$i = $i+1;
				}
				return $result;
			} else {
				echo "0 results";
			}
			$conn->close();
			}
			#Tagastab profiili info, et infot kasutada tee nii: $userProfileInfo = userProfileInfo("kasutajanimi"); $userProfileInfo[0] on kasutajanimi, $userProfileInfo[1] on email jne.
		public function userProfileInfo($username) {
			$stmt = $mysqli->prepare("SELECT username, email, gender, profile_img FROM userinfo WHERE username = ?");
			$stmt->bind_param("s", $username);
			$stmt->bind_result($usernameFromDb, $emailFromDb, $genderFromDb, $profileimgFromDb);
			$stmt->execute();
				
				
			if ($stmt->fetch()){
				$result = array($usernameFromDb, $emailFromDb, $genderFromDb, $profileimgFromDb);

				return $result;
			}
					
			else {
				$notice = "Username does not exist";
			}
				
			$stmt->close();
			$mysqli->close();
			}


	}

    class Photoupload {
		private $tempName;
		private $imageFileType;
		private $myTempImage;
		private $myImage;
		public $exifToImage;
		
		function __construct($name, $type){
			$this->tempName = $name;
			$this->imageFileType = $type;
        }
        
        public function imageInfoToDatabase($user, $filename) {
            $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
            $stmt = $mysqli->prepare("INSERT INTO userinfo_img (username, img_name) VALUES (?, ?)");
            echo $mysqli->error;

            $stmt->bind_param("ss", $username, $filename);
            if ($stmt->execute()){
                echo "\n Info updated!";
            } else {
                echo "\n Error : " .$stmt->error;
            }
            $stmt->close();
            $mysqli->close();
        }
		
		private function createImage(){
			if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg"){
				$this->myTempImage = imagecreatefromjpeg($this->tempName);
			}
			if($this->imageFileType == "png"){
				$this->myTempImage = imagecreatefrompng($this->tempName);
			}
			if($this->imageFileType == "gif"){
				$this->myTempImage = imagecreatefromgif($this->tempName);
			}
		}
		#Profiili pildi suurus - 180x180
		public function resizeImage($width, $height){
			$this->createImage();
			$imageWidth = imagesx($this->myTempImage);
			$imageHeight = imagesy($this->myTempImage);
			
			$sizeRatio = 1;
			if($imageWidth > $imageHeight){
				$sizeRatio = $imageWidth / $width;
			} else {
				$sizeRatio = $imageHeight / $height;
			}
			$this->myImage = $this->resize_image($this->myTempImage, $imageWidth, $imageHeight, round($imageWidth / $sizeRatio), round($imageHeight / $sizeRatio));
		}
	    	
		public function readExif(){
			@$exif = exif_read_data($this->tempName, "ANY_TAG", 0, true);
			if(!empty($exif["DateTimeOriginal"])){
				$this->exifToImage = "Picture taken: " .$exif["DateTimeOriginal"];
			} else {
				$this->exifToImage = "Date not known";
			}
		}
		
		#Igale kasutajale omanimeline kaust!!!! See tehakse registreerides ja on /users/img/$_SESSION["user"] ehk kasutaja nimi
	    	#Profiilipildid lähevad /users/img/$_SESSION["user"]/profile_img
		public function savePhoto($directory, $fileName){
			$target_file = $directory .$fileName;
			if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg"){
				if(imagejpeg($this->myImage, $target_file, 90)){
					$notice = "true";
				} else {
					$notice = "false";
				}
			}
			if($this->imageFileType == "png"){
				if(imagepng($this->myImage, $target_file, 6)){
					$notice = "true";
				} else {
					$notice = "false";
				}
			}
			if($this->imageFileType == "gif"){
				if(imagegif($this->myImage, $target_file)){
					$notice = "true";
				} else {
					$notice = "false";
				}
			}
			return $notice;
		}
		
		
		public function clearImages(){
			imagedestroy($this->myTempImage);
			imagedestroy($this->myImage);
		}
		
	}


?>
