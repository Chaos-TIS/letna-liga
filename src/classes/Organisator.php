<?php
class Organisator extends User {
	
	private $admin;
	private $validated;

    public function __construct($id, $mail){
        parent::__construct($id, $mail);
    }
	
    public function getFromDatabaseByID($conn, $id){
		$sql_get_organisator = "SELECT * FROM organisators WHERE user_id = ".$id;
		$sql_get_user = "SELECT * FROM users WHERE user_id = ".$id;
		$organisator = mysqli_query($conn,$sql_get_organisator);
		$user = mysqli_query($conn,$sql_get_user);
		if ($organisator != false && $user != false) {
			$organisator_pole = mysqli_fetch_array($organisator);
			$user_pole = mysqli_fetch_array($user);
			parent::__construct($id, $user_pole['mail']);
			$this->admin = $organisator_pole['admin'];
			$this->validated = $organisator_pole['validated'];
		}
    }
	
	public function isAdmin() {
		return $this->admin;
	}
	
}
?>