<?php
require_once("includes/functions.php");

class Assignment extends Context {
    private $name_sk;
    private $name_eng;
	private $text_sk;
	private $text_eng;
	private $timeOfPublishing;
	private $deadline;
	private $solutions;

    public function __construct($conn, $id) {
		$sql_get_assignment = "SELECT * FROM assignments WHERE context_id = ".$id;
		$assignment = mysqli_query($conn,$sql_get_assignment);
		if ($assignment != false) {
			$assignment_pole = mysqli_fetch_array($assignment);
			parent::__construct($assignment_pole['context_id'], new Organisator($conn, $assignment_pole['user_id']));
			
			$this->timeOfPublishing = $assignment_pole['begin'];
			$this->deadline 		= $assignment_pole['end'];
			
			$sql_get_text = "SELECT * FROM texts WHERE text_id = ".$assignment_pole['text_id_name'];
			$text = mysqli_query($conn,$sql_get_text);
			if ($text != false) {
				$text_pole = mysqli_fetch_array($text);
				$this->name_sk 	= $text_pole['sk'];
				$this->name_eng = $text_pole['eng'];
			}
			
			$sql_get_text = "SELECT * FROM texts WHERE text_id = ".$assignment_pole['text_id_description'];
			$text = mysqli_query($conn,$sql_get_text);
			if ($text != false) {
				$text_pole = mysqli_fetch_array($text);				
				$this->text_sk 	= $text_pole['sk'];
				$this->text_eng = $text_pole['eng']
			}
			
			$this->setSolutions($conn);
    }
	
	public function setSolutions($conn) {
		$this->solutions = [] // TODO
		$sql_get_solutions = "SELECT c.user_id, c.context_id FROM solutions s, contexts c WHERE c.context_id = s.context_id AND s.assignment_id = ".$this->id;
		$solutions = mysqli_query($conn,$sql_get_solutions);
		if ($solutions != false) {
			$solutions_pole = mysqli_fetch_array($solutions);
			for ($i = 0 ; i < count($solutions_pole['user_id']) ; i++) {
				$this->solutions.append(new Solution($conn, $solutions_pole['context_id_id'], new Team($solutions_pole['user_id']), $this));
			}
		
		}
		
	}
	
	public function getEditingHtml(){
	
	}
	
	public function getPreviewHtml(){
	
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
		return $this->deadline >= "systemovy čas";
	}
	
	public function save(){
	
	}
	
	
}
?>