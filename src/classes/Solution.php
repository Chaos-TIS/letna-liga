<?php
require_once(dirname(__FILE__)."/../includes/functions.php");

class Solution extends Context {
    private $text;
    private $best;
	private $points;
	private $comments;
	private $assignment;
	
	public function __construct($conn, $id, $author, $assignment) {
		parent::__construct($conn, $id, $author);
		$sql_get_solution = "SELECT * FROM solutions WHERE context_id = ".$id;
		$solution = mysqli_query($conn,$sql_get_solution);
		if ($solution != false) {
			$solution_pole = mysqli_fetch_array($solution);

			$this->text = $solution_pole['text'];
			$this->best = $solution_pole['best'];
			$this->assignment = $assignment;
			$this->id=$id;
			$this->author=$author;

			if (is_null($this->assignment)){
        		$selectAssignmentId = "SELECT assignment_id FROM solutions WHERE context_id = {$this->id}";
				if ($result = mysqli_query($conn, $selectAssignmentId))
					if ($row = mysqli_fetch_array($result))
						$this->assignment = new Assignment($conn, $row['assignment_id']);
			}
		
			$sql_get_comment = "SELECT * FROM comments WHERE context_id = ".$solution_pole["context_id"];
			$comment = mysqli_query($conn,$sql_get_comment);
			if ($comment != false) {
				$comment_pole = mysqli_fetch_array($comment);
				$comments = array();
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

    public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}

		return null;
	}

	public function __set($property, $value) {
		if (property_exists($this, $property)) {
			$this->$property = $value;
		}
		return $this;
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
			
			<h2 data-trans-key="solution-edit-page"></h2>
			<textarea name="textVideo" cols="80" rows="3" ></textarea>
			
			<h2 data-trans-key="solution-edit-page"></h2>
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
	 ?>
    <h2>Hodnotenie riešení</h2>
    <h3><span data-trans-key="team-name"></span>: <?php echo $this->author->name; ?></h3>
    <p><?php echo $this->author->description; ?></p>
    <h3 data-trans-key="solution"></h3>
    <p><?php echo $this->text; ?></p>
    
</div>
<?php
	}
	
	public function getComments(){
	
	}
	
	public function getMainComment(){
	
	}
	
	public function save(){
	
	}
	
	public function getTeam(){
   return $this->author;
  }
  
  public function getPoints(){
    return $this->points;
  }
  
  public function getId(){
    return $this->id;
  }

  public function getAssignment(){
  	return $this->assignment;
  }
}
?>