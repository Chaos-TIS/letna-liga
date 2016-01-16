<?php
require_once(dirname(__FILE__)."/../includes/functions.php");

class Solution extends Context {
    private $text;
    private $best;
	private $points;
	private $comments;
	private $assignment;
	
	public function __construct($conn, $id, $author, $assignment) {
		$sql_get_solution = "SELECT * FROM solutions WHERE context_id = ".$id;
		$solution = mysqli_query($conn,$sql_get_solution);
		if ($solution != false) {
			$solution_pole = mysqli_fetch_array($solution);
			parent::__construct($conn, $solution_pole['context_id'], $author);
			$this->text = $solution_pole['text'];
			$this->best = $solution_pole['best'];
			$this->assignment = $assignment;
		
			$sql_get_comment = "SELECT * FROM comments WHERE context_id = ".$solution_pole["context_id"];
			$comment = mysqli_query($conn,$sql_get_comment);
			if ($comment != false) {
				$comment_pole = mysqli_fetch_array($comment);
				$comments = [];
				for ($i = 0 ; $i < count($comment_pole['comment_id']) ; $i++) {
					$comments[] = new Comment($conn,
												 $comment_pole['comment_id'][$i],
												 $this,
												 $comment_pole['user_id'][$i],
												 $comment_pole['text'][$i],
												 $comment_pole['points'][$i]
												);
				}
				$this->setComments($comments);
			}
		}
    }
	
	public function getTxt() {
		return $this->text;
	}
	
	public function setTxt($conn, $text) {
		$this->text = $text;
		updateData($conn, "solutions", "text", $text, "context_id", $this->getId());
	}
	
	public function setComments($comments){
		$this->comments = $comments;
		$points = 0.0;
		for ($i = 0 ; $i < count($comments) ; $i++) {
			$points += $comments[$i].getPoints();
		}
		if (count($comments) != 0) {
			$this->points = $points / count($comments);
		}
	}		
	
	public function getEditingHtml(){
	?>
	<div id="content">
		
		<form name="form1" enctype="multipart/form-data" method="POST" action="addSolution.php">
			<h2> Popis riešenia </h2>
			<textarea name="textPopis" cols="80" rows="10" ><?php echo $this->getTxt() ?></textarea>
	
			<br>			
			<?php
			$this->getAttachmentsTableHtml();
			?>
			
			<input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
			
			<h2> Pridaj videá k riešeniu zo serveru Youtube (Každé video vlož do nového riadku.) </h2>
			<textarea name="textVideo" cols="80" rows="3" ></textarea>
			
			<h2> Nahraj súbory (Veľkosť súboru nemôže presiahnúť 10 MB)</h2>
			Vyber súbor: <input type="file" name="uploadedFiles[]" multiple />
			
			<br>
			<input type="submit" value="Ulož zmeny" id="upload" />
			
		</form>

	</div>
	<?php
	}
	
	public function uploadFiles($conn, $subory) {
		$this->uploadFiles1($conn, $subory, dirname(__FILE__)."/../attachments/solutions/".$this->id."/");
	}
	
	public function deleteAttachments($conn, $prilohy) {
		$this->deleteAttachments1($conn, $prilohy, dirname(__FILE__)."/../attachments/solutions/".$this->id."/");
	}
	
	public function getPreviewHtml(){
	
	}
	
	public function getComments(){
	
	}
	
	public function getMainComment(){
	
	}
	
	public function getPoints(){
	
	}
	
	public function save(){
	
	}
	
	
}
?>