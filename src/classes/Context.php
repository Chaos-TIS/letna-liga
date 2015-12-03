<?php
require_once(dirname(__FILE__)."/../includes/functions.php");

abstract class Context{
    protected $id;
    protected $author;
    protected $attachments;

    public function __construct($conn, $id, $author){
        $this->id = $id;
        $this->author = $author;
		$this->setAttachments($conn);
    }
	
	public function getAttachments() {
		return $this->attachments;
	}
	
	public function setAttachments($conn) {
		$this->attachments = [];//TODO
		
		$sql_get_images = "SELECT * FROM images WHERE location_id = ".$this->id;
		$images = mysqli_query($conn,$sql_get_images);	
		if ($images != false) {
			$images_pole = mysqli_fetch_array($images);
			
			for ($i = 0 ; i < count($images_pole['image_id']) ; i++) {
				$this->attachments.append(new Image([$images_pole['image_id'][$i],
												   $this,
												   [$images_pole['original_name'][$i],
												   [$images_pole['extension'][$i]
												  )
										 );
			}
		}
		
		$sql_get_programs = "SELECT * FROM programs WHERE location_id = ".$this->id;
		$programs = mysqli_query($conn,$sql_get_programs);	
		if ($programs != false) {
			$programs_pole = mysqli_fetch_array($programs);
			
			for ($i = 0 ; i < count($programs_pole['program_id']) ; i++) {
				$this->attachments.append(new Program([$programs_pole['program_id'][$i],
													  $this,
													  [$programs_pole['location_id'][$i]
													 )
										 );
			}
		}
		
		$sql_get_videos = "SELECT * FROM videos WHERE location_id = ".$this->id;
		$videos = mysqli_query($conn,$sql_get_videos);	
		if ($videos != false) {
			$videos_pole = mysqli_fetch_array($videos);
			
			for ($i = 0 ; i < count($videos_pole['video_id']) ; i++) {
				$this->attachments.append(new Video([$videos_pole['video_id'][$i],
												   $this,
												   [$videos_pole['location_id'][$i],
												   [$videos_pole['link'][$i]
												  )
										 );
			}
		}
	}
	
	public function uploadProgram($conn, $subory) {
		$fileCount = count($subory["name"]);
		if ($fileCount == 1  && pathinfo($subory["name"][0], PATHINFO_EXTENSION) == "zip") {
			if (checkUploadFile($subory["size"][0]))
			{
				$target_file = "files/".$this->id.".zip";
				if (move_uploaded_file($subory["tmp_name"][0], $target_file)) {
					echo "[OK] Nahratie súboru <br>";
				} else {
					echo "[ERROR] Nahranie súborov: Problém s uložením na server: <br>";
				}
			} else {
				echo "[ERROR] Nahranie súborov: Celková veľkosť súborov je viac ako 10MB <br>";
			}
		} else {
			$ok = True;
			$vel = 0;
			$zip = new ZipArchive;
			if ($zip->open("files/".$this->id.".zip",ZipArchive::OVERWRITE)) {		
				for ($i=0; $i<$fileCount; $i++) {
					$subor = $subory["name"][$i];
					$vel += $subory["size"][$i];
					if (!checkUploadFile($subory["size"][$i]))
					{
						$ok = False;
						break;
					}
				}
				if ($ok && ($vel < 10000000)) {
					for ($i=0; $i<$fileCount; $i++) {
						$zip->addFile($subory['tmp_name'][$i],$subory['name'][$i]);
					}
					echo "[OK] Nahranie súborov <br>";
				} else {
					echo "[ERROR] Nahranie súborov: Celková veľkosť súborov je viac ako 10MB <br>";
				}
				$zip->close();
			}
		}
	}
	
	public function uploadImage($conn, $obrazky) {
		for ($i = 0; $i < count($obrazky); $i++) {
			$subor = $obrazky["name"][$i];
			$ext = pathinfo($obrazky["name"][$i], PATHINFO_EXTENSION);
			if (checkUploadImage($ext,$obrazky["type"][$i],$obrazky["size"][$i]))
			{
				if (mysqli_query($conn,"INSERT INTO images (location_id) VALUES (".$this->id.")")) {
					$target_file = "images/".mysqli_insert_id($conn).".".$ext;
					if (move_uploaded_file($obrazky["tmp_name"][$i], $target_file)) {
						echo "[OK] Nahratie Obrázka: ".$subor."<br>";
					} else {
						mysqli_query($conn,"DELETE FROM images WHERE image_id = ".mysqli_insert_id($conn));
						echo "[ERROR] Problém s uložením na server: ".$subor."<br>";
					}
				} else {
					echo "[ERROR] Problém s uložením do databázy: ".$subor." ".mysqli_error($conn)."<br>";
				}		
			} else {
				echo "[ERROR] Nahratie Obrázka: ".$subor." Nahrať je možné len súbory s príponou jpg, png alebo gif s veľkosťou menšou ako 10MB <br>";
			}
		}
	}
	
	public function uploadVideo($conn, $videa) {
		mysqli_query($conn,"DELETE FROM videos WHERE location_id = ".$zaznam["context_id"]);
		$pattern = '/[;," "\n]/';
		$pole = preg_split($pattern, $videa);
		for ($i = 0; $i < count($pole); $i++) {
			if (strlen($pole[$i]) > 11) {
				$video = substr(trim($pole[$i]), -11);
				if (mysqli_query($conn,"INSERT INTO videos (location_id,link) VALUES (".$this->id.",\"".$video."\")")) {
					echo "[OK] Video ".$video." <br>";
				} else {
					echo "[ERROR] Video: chyba pri vkladaní do databázy ".$video." <br>".mysqli_error($conn);
				}
			}		
		}
	}
    
}
?>