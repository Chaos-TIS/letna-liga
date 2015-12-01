<?php
require_once("includes/functions.php");

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
	
	public function uploadProgram() {
		
	}
	
	public function uploadImage() {
		
	}
	
	public function uploadVideo() {
		
	}
    
}
?>