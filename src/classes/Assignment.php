<?php
require_once(dirname(__FILE__)."/../includes/functions.php");

class Assignment extends Context {
    private $name_sk;
    private $name_eng;
	private $text_sk;
	private $text_eng;
	private $timeOfPublishing;
	private $deadline;
	private $solutions;
	private $text_id_name;
	private $text_id_desc;

    public function __construct($conn, $id) {
		$sql_get_assignment = "SELECT * FROM assignments a, contexts c WHERE c.context_id = a.context_id AND c.context_id = ".$id;
		$assignment = mysqli_query($conn,$sql_get_assignment);
		if ($assignment != false) {
			$assignment_pole = mysqli_fetch_array($assignment);
			parent::__construct($conn, $assignment_pole['context_id'], Organisator::getFromDatabaseByID($conn, $assignment_pole['user_id']));
			
			$this->timeOfPublishing = $assignment_pole['begin'];
			$this->deadline 		= $assignment_pole['end'];
			
			$this->text_id_name = $assignment_pole['text_id_name'];
			$this->text_id_desc = $assignment_pole['text_id_description'];
			
			$sql_get_text = "SELECT * FROM texts WHERE text_id = ".$this->text_id_name;
			$text = mysqli_query($conn,$sql_get_text);
			if ($text != false) {
				$text_pole = mysqli_fetch_array($text);
				$this->name_sk 	= $text_pole['sk'];
				$this->name_eng = $text_pole['eng'];
			}
			
			$sql_get_text = "SELECT * FROM texts WHERE text_id = ".$this->text_id_desc;
			$text = mysqli_query($conn,$sql_get_text);
			if ($text != false) {
				$text_pole = mysqli_fetch_array($text);				
				$this->text_sk 	= $text_pole['sk'];
				$this->text_eng = $text_pole['eng'];
			}
			$this->setSolutions($conn);
		}
    }
	
	public function setSolutions($conn) {
		$this->solutions = array(); // TODO
		$sql_get_solutions = "SELECT c.user_id as 'user_id', c.context_id as 'context_id' FROM solutions s, contexts c WHERE c.context_id = s.context_id AND s.assignment_id = ".$this->id;  
		$solutions = mysqli_query($conn,$sql_get_solutions);
		if ($solutions != false) {
		    while ($solutions_row = mysqli_fetch_assoc($solutions)) {	      
				 array_push($this->solutions,new Solution($conn, $solutions_row['context_id'], Team::getFromDatabaseByID($conn, $solutions_row['user_id']), $this));
      } 
		}   		
	}
	
	public function uploadFiles($conn, $subory) {
		$this->uploadFiles1($conn, $subory, dirname(__FILE__)."/../attachments/assignments/".$this->id."/");
	}
	
	public function deleteAttachments($conn, $prilohy) {
		$this->deleteAttachments1($conn, $prilohy, dirname(__FILE__)."/../attachments/assignments/".$this->id."/");
	}
	
	public function getEditingHtml(){
	?>
	<div id="content">
		
		<form name="form1" enctype="multipart/form-data" method="POST" action="addAssignment.php?cid=<?php echo $this->getId() ?>">
			<h2> Názov zadania (SK) </h2>
			<input type="text" name="skName" value="<?php echo $this->getSkName() ?>">
			<h2> Názov zadania (ENG) </h2>
			<input type="text" name="engName" value="<?php echo $this->getEngName() ?>">
			<h2> Popis zadania (SK) </h2>
			<textarea name="skTextPopis" cols="80" rows="10" ><?php echo $this->getSkTxt() ?></textarea>
			<h2> Popis zadania (ENG) </h2>
			<textarea name="engTextPopis" cols="80" rows="10" ><?php echo $this->getEngTxt() ?></textarea>
	
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
	
	public function getSkName() {
		return $this->name_sk;
	}
	
	public function setSkName($conn, $text) {
		$this->name_sk = $text;
		updateData($conn, "texts", "sk", $text, "text_id", $this->text_id_name);
	}
	
	public function getEngName() {
		return $this->name_eng;
	}
	
	public function setEngName($conn, $text) {
		$this->name_eng = $text;
		updateData($conn, "texts", "eng", $text, "text_id", $this->text_id_name);
	}
	
	public function getSkTxt() {
		return $this->text_sk;
	}
	
	public function setSkTxt($conn, $text) {
		$this->text_sk = $text;
		updateData($conn, "texts", "sk", $text, "text_id", $this->text_id_desc);
	}
	
	public function getEngTxt() {
		return $this->text_eng;
	}
	
	public function setEngTxt($conn, $text) {
		$this->text_eng = $text;
		updateData($conn, "texts", "eng", $text, "text_id", $this->text_id_desc);
	}
	
	public function getPreviewHtml(){
	 ?>
	  <h2> <?php  echo $this->name_sk?> </h2>  
    <strong>Riešenie možno odovzdávať do:  <?php  echo $this->deadline;?></strong>  
    <div> <?php echo $this->text_sk; ?> </div> 
    <h3>Riešenia:</h3>
    <ul>
    <?php
    if(Date("Y-m-d H:i:s")>$this->deadline){ 
      for($i=0;$i<count($this->solutions);$i++){ 
        
        $team = $this->solutions[$i];
        $team2= $team->getTeam();
        $team3 = $team2->getName();
        ?>
        <li><a href="solution.php?id=<?php echo $team->getId(); ?>"> <?php echo $team3; ?> </a> </li> 
        <?php                  
      }
    }
    else if (isset($_SESSION['loggedUser'])){
      echo "eeha6532";
  				if(is_a($_SESSION['loggedUser'], 'Team')){
          echo "eeha";
          }
    }
    ?>
    </ul>
    <?php
	}
	
	public function getResultTableRowHTML(){
	
	}
	
	public function getSolutions(){
		return $this->solutions;
	}
	
	public function isPublished(){
		return $this->timeOfPublishing != null;
	}
	
	public function isAfterDeadline(){
		$deadline = strtotime($this->deadline);
		$cur_time = strtotime(date("c"));
		return $deadline < $cur_time;//!!!!!!!!!!!!!!!!!!!!!!!!!!!
	}
	
	public function save(){
	
	}
	
	
	
}
?>