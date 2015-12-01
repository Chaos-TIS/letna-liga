<?php
require_once(dirname(__FILE__)."/../includes/functions.php");

class Team extends User {
    protected $name;
    protected $description;
    protected $skLeague;

	public function __construct($id, $mail, $name, $description, $skLeague){
		parent::__construct($id, $mail);
		$this->name = $name;
		$this->description = $description;
		$this->skLeague = $skLeague;
	}

    public function getFromDatabaseByID($conn, $id){
		$sql_get_team = "SELECT u.mail as mail,t.name as name, t.description as desc, t.sk_league as sk FROM users u, teams t WHERE t.user_id = u.user_id AND u.user_id = ".$id;
		$team = mysqli_query($conn,$sql_get_team);
		if ($team != false) {
			$team_pole = mysqli_fetch_array($team);
			parent::__construct($id, $team_pole['mail']);

			$this->name 		= $team_pole['name'];
			$this->description 	= $team_pole['desc'];
			$this->sk_league 	= $team_pole['sk'];
		}
    }

    public function getSolutionOf($assignment) {
		//TODO
	}
}
?>