<?php
	session_start();
	require("./config.php");
    class userFunction {
		

        function __construct() {

        }

        public function userRegister($username, $password, $email, $gender) {
			$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
			$hash_password = hash("sha512", $password);
            $stmt = $mysqli->prepare("INSERT INTO userinfo (username, password, email, gender) VALUES (?, ?, ?, ?)");
            echo $mysqli->error;

            $stmt->bind_param("sssi", $username, $hash_password, $email, $gender);
            if ($stmt->execute()){
				echo "Logged in";	
				$_SESSION["user"] = $usernameFromDb;
				header("Location: main.php?user=".$_SESSION["user"]);
				exit();

				echo "\n Registered";
            } else {
                echo "\n Error : " .$stmt->error;
            }
            $stmt->close();
            $mysqli->close();

        }

        public function userLogin($username, $password) {
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
			$hash = hash("sha512", $password);
            $stmt = $mysqli->prepare("SELECT username, password FROM userinfo WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->bind_result($usernameFromDb, $passwordFromDb);
			$stmt->execute();
            
            
            if ($stmt->fetch()){
                if ($hash == $passwordFromDb){
                    echo "Logged in";
                    
                    $_SESSION["user"] = $usernameFromDb;
                    
                    header("Location: main.php?user=".$_SESSION["user"]);
                    exit();
                    
                } else {
                    echo "Wrong password";
                }
			} 
			else {
                echo "Username does not exist";
            }
            
            $stmt->close();
            $mysqli->close();
		}

		public function updatePassword($username, $password, $password_old){
			$mysql = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
			$hash_password_old = hash("sha512", $password_old);
						if($mysql->connect_error) {
							die("Connection failed: " . $mysql->connect_error);
						}
						$sql = "SELECT password FROM userinfo WHERE username = '$username'";
						$result = $mysql->query($sql);
						
						if ($result->num_rows > 0) {
							while($row = $result->fetch_assoc()) {
								if($row["password"] == $hash_password_old){
									$hash_password = hash("sha512", $password);
									$sql = "UPDATE userinfo SET password='$hash_password' WHERE username='$username'";
									if ($mysql->query($sql) === TRUE) {
										echo "Parool vahetatud!";
									} else {
										echo "Error! " . $mysql->error;
									}
		
								}
								else{
									echo "Vale parool!";
								}
							}
						} else {
							echo "0 results";
						}

						if($result == $hash_password_old){
							$hash_password = hash("sha512", $password);
							$sql = "UPDATE userinfo SET password='$hash_password' WHERE username='$username'";
							if ($mysql->query($sql) === TRUE) {
								echo "Parool vahetatud!";
							} else {
								echo "Error! " . $mysql->error;
							}

						}
						else{
							echo "Vale parool!";
						}
						
						$mysql->close();

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
			
			// Create connection
			$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
			// Check connection
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			} 
			
			$sql_prepared = "SELECT username, img_name, img_privacy FROM userinfo_img WHERE username = '$username'";
			$sql = $sql_prepared;
			$result = $conn->query($sql);
			
			if ($result->num_rows > 0) {
				// output data of each row
				while($row = $result->fetch_assoc()) {
				if($_SESSION["user"] != $row["username"]){
					if($row["img_privacy"]==2){
						
					}
					else{
						echo "<div class='w3-half'>";
						echo "<img src='pics/".$row["img_name"]."' style='width:97%' class='w3-margin-bottom'>";
						echo "</div>";
						
						
					}
				}
				else{
					echo "<div class='w3-half'>";
					echo "<img src='pics/".$row["img_name"]."' style='width:97%' class='w3-margin-bottom'>";
					echo "</div>";
					
					
					
				}
				}
			} else {
				echo "No Pictures Found";
			}
			$conn->close();
			}

		public function addComment($username, $poster, $comment){
			$mysql = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

			if($mysql->connect_error) {
				die("Connection failed: " . $mysql->connect_error);
			}

			$sql = "INSERT INTO comments (username, poster, comment) VALUES('$username','$poster','$comment')";
			if ($mysql->query($sql) === TRUE) {
				echo "Kommenteeritud!";
			} else {
				echo "Error! " . $mysql->error;
			}
			
			$mysql->close();
		}

		public function getComments($username) {
			
			// Create connection
			$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
			// Check connection
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			} 
			
			$sql_prepared = "SELECT poster, comment FROM comments WHERE username = '$username'";
			$sql = $sql_prepared;
			$result = $conn->query($sql);
			
			if ($result->num_rows > 0) {
				// output data of each row
				while($row = $result->fetch_assoc()) {
					echo "<p class='w3-center'><b>".$row["poster"]." : </b>".$row["comment"]."</p>";
					echo "<br></br>";
				
				}
			$conn->close();
			}
			else{
				echo "<p>No Comments</p>";
			}
		}
			

			#Tagastab profiili info, et infot kasutada tee nii: $userProfileInfo = userProfileInfo("kasutajanimi"); $userProfileInfo[0] on kasutajanimi, $userProfileInfo[1] on email jne.
		public function userProfileInfo($username) {
			$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
			$stmt = $mysqli->prepare("SELECT username, email, gender, profile_img FROM userinfo WHERE username = ?");
			$stmt->bind_param("s", $username);
			$stmt->bind_result($usernameFromDb, $emailFromDb, $genderFromDb, $profileimgFromDb);
			$stmt->execute();
				
				
			if ($stmt->fetch()){
				$_SESSION["prof_user"] = $usernameFromDb;
				$_SESSION["prof_email"] = $emailFromDb;
				$_SESSION["prof_gender"] = $genderFromDb;
				#$_SESSION["prof_date"] = $date;
				if($profileimgFromDb == ""){
					$_SESSION["prof_profileimg"]="banaan.png";
				}
				else{
					$_SESSION["prof_profileimg"] = $profileimgFromDb;
				}
			}
					
			else {
				$notice = "Username does not exist";
				header("Location: main.php?user=".$_SESSION["user"]);
			}
				
			$stmt->close();
			$mysqli->close();
			}
			#Töötab samamoodi nagu userUploadedImg
		
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
        
        public function imageInfoToDatabase($username, $filename, $imgPrivacy) {
            $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
            $stmt = $mysqli->prepare("INSERT INTO userinfo_img (username, img_name, img_privacy) VALUES (?, ?, ?)");
            echo $mysqli->error;

            $stmt->bind_param("ssi", $username, $filename, $imgPrivacy);
            if ($stmt->execute()){
                echo "\n Info updated!";
            } else {
                echo "\n Error : " .$stmt->error;
            }
            $stmt->close();
            $mysqli->close();
        }
		
		public function imageProfInfoToDatabase($username, $filename) {
            $mysql = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
			if ($mysql->connect_error) {
				die("Connection failed: " . $mysql->connect_error);
				exit();
			}

			$sql = "UPDATE userinfo SET profile_img='$filename' WHERE username='$username'";
			if ($mysql->query($sql) === TRUE) {
				echo "Record updated successfully";
			} else {
				echo "Error updating record: " . $mysql->error;
			}
			
			$mysql->close();
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

		private function resize_image($image, $origW, $origH, $w, $h){
			$dst = imagecreatetruecolor($w, $h);
			//säilitan png jaoks läbipaistvuse
			imagesavealpha($dst, true);
			$transColor = imagecolorallocatealpha($dst, 0, 0, 0, 127);
			imagefill($dst, 0, 0, $transColor);
			imagecopyresampled($dst, $image, 0, 0, 0, 0, $w, $h, $origW, $origH);
			return $dst;
		}
		#Profiili pildi suurus - 180x180
	    	#Tavapildi suurus  640x480
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
