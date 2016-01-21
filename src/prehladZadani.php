<?php
include 'includes/functions_zadania.php';
page_head("PrehladZadani");
page_nav();
/*
$conn = db_connect();
$_SESSION["loggedUser"] = Organisator::getFromDatabaseByID($conn, 1);*/

if (!isset($_SESSION['loggedUser']))
  get_login_form();
  else
  get_logout_button();

if (isset($_POST["send"])){
     set_date($_POST["datum"],$_POST["start"],$_POST["stop"]) ; }

if (isset($_SESSION['loggedUser'])&&($_SESSION['loggedUser']->getShortName() == 'organisator' || $_SESSION['loggedUser']->getShortName() == 'admin')) {
	prehlad_zadani_nezverejnene($_SESSION['loggedUser']->getShortName());
	prehlad_zadani_zverejnene();
	?>
	<form action="addAssignment.php">
		<input type="submit" value="Nové zadanie">
	</form>
	<?php
}else{
	prehlad_zadani_zverejnene();
}

page_footer()
?>