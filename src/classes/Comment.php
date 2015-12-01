<?php
require_once("includes/functions.php");

class Comment {
    private $id;
    private $author;
	private $text;
	private $solution;
	private $points;

    public function __construct($conn, $id, $author, $text, $solution, $points){
		$this->id = $id;
		$this->author = new Organisator($conn, $author);
        $this->text = $text;
		$this->solution = $solution;
        $this->points = $points;
    }
	
	public function save(){
	
	}
}
?>