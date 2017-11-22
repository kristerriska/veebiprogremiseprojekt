<?php

    class userFunction {


        function __construct() {

            $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

        }

        public userRegister($username, $password, $email, $gender) {
            $stmt = $mysqli->prepare("INSERT INTO userinfo (username, password, email, gender) VALUES (?, ?, ?, ?)");
            echo $mysqli->error;

            $stmt->bind_param("sssi", $username, $password, $email, $gender);
            if ($stmt->execute()){
                mkdir("/users/img/".$_SESSION["user"], 0777);
                echo "\n Registered";
            } else {
                echo "\n Error : " .$stmt->error;
            }
            $stmt->close();
            $mysqli->close();

        }

        public userLogin($username, $password) {

            $stmt = $mysqli->prepare("SELECT username, password password FROM userinfo WHERE username = ?");
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

            $stmt = $mysqli->prepare("INSERT INTO userinfo_img (username, img_name) VALUES (?, ?)");
            echo $mysqli->error;

            $stmt->bind_param("ssi", $username, $filename);
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
		
		private function resize_image($image, $origW, $origH, $w, $h){
			$dst = imagecreatetruecolor($w, $h);
			imagesavealpha($dst, true);
			$transColor = imagecolorallocatealpha($dst, 0, 0, 0, 127);
			imagefill($dst, 0, 0, $transColor);
			imagecopyresampled($dst, $image, 0, 0, 0, 0, $w, $h, $origW, $origH);
			return $dst;
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
		
		public function saveOriginal($directory, $fileName){
			$target_file = $directory .$fileName;
			if (move_uploaded_file($this->tempName, $target_file)) {
				$notice .= "true";
			} else {
				$notice .= "false";
			}
		}
		
		public function clearImages(){
			imagedestroy($this->myTempImage);
			imagedestroy($this->myImage);
		}
		
	}


?>
