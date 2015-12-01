<?php
require_once(dirname(__FILE__)."/../includes/functions.php");

class Solution extends Context {
    private $text
    private $best;
	private $points;
	private $comments;
	private $assignment;
	
	public function __construct($conn, $id, $author, $assignment) {
		$sql_get_solution = "SELECT * FROM solutions WHERE context_id = ".$id;
		$solution = mysqli_query($conn,$sql_get_solution);
		if ($solution != false) {
			$solution_pole = mysqli_fetch_array($solution);
			parent::__construct($solution_pole['context_id'], $author);
			$this->text = $solution_pole['text'];
			$this->best = $solution_pole['best'];
			$this->assignment = $assignment;
		
			$sql_get_comment = "SELECT * FROM comments s WHERE context_id".$solution_pole["context_id"];
			$comment = mysqli_query($conn,$sql_get_solution);
			if ($comment != false) {
				$comment_pole = mysqli_fetch_array($comment);
				$comments = [];//TODO
				for ($i = 0 ; i < count($comment_pole['comment_id']) ; i++) {
					$comments.append(new Comment($conn,
												 [$comment_pole['comment_id'][$i],
												 $this,
												 [$comment_pole['user_id'][$i],
												 [$comment_pole['text'][$i],
												 [$comment_pole['points'][$i]
												));
				}
				$this->setComments($comments);
			}
		}
    }
	
	public function setComments($comments){
		$this->comments = $comments;
		$points = 0.0;
		for ($i = 0 ; i < count($comments) ; i++) {
			$points += $comments[$i].getPoints();
		}
		$this->points = $points / count($comments);
	}		
	
	public function getEditingHtml(){
	
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