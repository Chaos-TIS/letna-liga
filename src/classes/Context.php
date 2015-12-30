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
	
	public function getId() {
		return $this->id;
	}
	
	public function setAttachments($conn) {
		$this->attachments = [];
		$sql_get_images = "SELECT * FROM images WHERE context_id = ".$this->id;
		$images = mysqli_query($conn,$sql_get_images);	
		if ($images != false) {
			while($images_pole = mysqli_fetch_array($images)) {
				$this->attachments[] = new Image($images_pole['image_id'],
												   $this->id,
												   $images_pole['original_name']
												  );
			}
		}
		$sql_get_programs = "SELECT * FROM programs WHERE context_id = ".$this->id;
		$programs = mysqli_query($conn,$sql_get_programs);	
		if ($programs != false) {			
			while($programs_pole = mysqli_fetch_array($programs)) {
				$this->attachments[] = new Program($programs_pole['program_id'],
													  $this->id,
													  $programs_pole['original_name']
													 );
			}
		}
		$sql_get_videos = "SELECT * FROM videos WHERE context_id = ".$this->id;
		$videos = mysqli_query($conn,$sql_get_videos);	
		if ($videos != false) {
			while($videos_pole = mysqli_fetch_array($videos)) {
				$this->attachments[] = new Video($videos_pole['video_id'],
												   $this->id,
												   $videos_pole['link']
												  );
			}
		}
	}
	
	public function uploadFiles1($conn, $files, $kde) {
		$fileCount = count($files["name"]);
		if ($fileCount + count($this->attachments) > 100) {
			echo "[ERROR] Počet príloh presiahol maximálny povolený počet (100).";
			return;
		}
		for ($i = 0; $i < $fileCount; $i++) {
			$subor = $files["name"][$i];
			$ext = pathinfo($subor, PATHINFO_EXTENSION);
			if (checkUploadFile($ext,$files["size"][$i]))
			{
				$typ = "program";
				if ($ext == "jpg" or $ext == "png" or $ext == "gif") {
					$typ = "image";
				}
				if (mysqli_query($conn,"INSERT INTO ".$typ."s (context_id, original_name) VALUES (".$this->id.",\"".$subor."\")")) {
					$target_file = $kde.$typ."s/".mysqli_insert_id($conn).".".$ext;
					if (!file_exists($kde)) {
						mkdir($kde, 0777, true);
					}
					if (move_uploaded_file($files["tmp_name"][$i], $target_file)) {
						echo "[OK] Nahratie Súboru: ".$subor."<br>";
					} else {
						mysqli_query($conn,"DELETE FROM ".$typ."s WHERE ".$typ."_id = ".mysqli_insert_id($conn));
						echo "[ERROR] Problém s uložením na server: ".$subor."<br>";
					}
				} else {
					echo "[ERROR] Problém s uložením do databázy: ".$subor." ".mysqli_error($conn)."<br>";
				}		
			} else {
				echo "[ERROR] Nahratie Súboru: ".$subor." Nahrať je možné len súbory menšie ako 10MB <br>";
			}
		}
	}
	
	public function uploadVideo($conn, $videa) {
		mysqli_query($conn,"DELETE FROM videos WHERE location_id = ".$this->id);
		$pattern = '/[;," "\n]/';
		$pole = preg_split($pattern, $videa);
		for ($i = 0; $i < count($pole); $i++) {
			if (strlen($pole[$i]) > 11) {
				$video = substr(trim($pole[$i]), -11);
				if (mysqli_query($conn,"INSERT INTO videos (context_id,link) VALUES (".$this->id.",\"".$video."\")")) {
					echo "[OK] Video ".$video." <br>";
				} else {
					echo "[ERROR] Video: chyba pri vkladaní do databázy ".$video." <br>".mysqli_error($conn);
				}
			}		
		}
	}
    
}
?>