<?php
//session_start();
require_once(dirname(__FILE__)."/includes/functions.php");
page_head("Letná liga FLL - Pridanie riešenia");
page_nav();

//***************
$conn = db_connect();
$_SESSION["team"] = Team::getFromDatabaseByID($conn,6);
$_SESSION["assignment"] = new Assignment($conn,1);
//***************

if (!isset($_SESSION["assignment"]) || $_SESSION["assignment"] == null) die("Nie je vybrane zadanie!");
if (!$_SESSION["assignment"]->isAfterDeadline()) die("Zadanie je po deadline!");
if (!isset($_SESSION["team"]) || $_SESSION["team"] == null) die("Nie si prihlásený!");

$sql_get_solution = "SELECT c.context_id as 'context_id' FROM solutions s, contexts c WHERE s.context_id = c.context_id AND s.assignment_id = ".$_SESSION["assignment"]->getId()." AND c.user_id = ".$_SESSION["team"]->getId();
//$conn = db_connect();
$solution = mysqli_query($conn,$sql_get_solution);

if (mysqli_num_rows($solution) == 0) {
	$cid = new_solution($conn, $_SESSION["team"]->getId(),$_SESSION["assignment"]->getId());
}
else {
	$cid = mysqli_fetch_array($solution)['context_id'];
}

$solution = new Solution($conn, $cid, $_SESSION["team"], $_SESSION["assignment"]);

if (isset($_POST['checkbox'])) {
	foreach($_POST['checkbox'] as $value){
		$pole = explode(";", $value);
		$sql = "SELECT * FROM ".$pole[0]."s WHERE ".$pole[0]."_id='".$pole[1]."'";
		$result = mysqli_query($conn, $sql);
		if ($result) {
			if ($pole[0] != "video") {
				$attachment = mysqli_fetch_array($result);
				$ext = pathinfo($attachment['original_name'], PATHINFO_EXTENSION);
			}
			$sql = "DELETE FROM ".$pole[0]."s WHERE ".$pole[0]."_id='".$pole[1]."'";
			$result = mysqli_query($conn, $sql);
			if ($result) {
				if ($pole[0] != "video") {
					if (unlink("attachments/solutions/".$cid."/".$pole[0]."s/".$pole[1].".".$ext)) {
						echoMessage("m-attachment-deleted");
					}
					else {
						echoError("err-attachment-deletion");
					}
				}
				else {
					echoMessage("m-attachment-deleted");
				}
			}
			else {
				echoError("err-attachment-db-deletion", mysqli_error($conn));
			}
		}
		else {
			echoError("err-attachment-not-in-db");
		}
	}
}

if (isset($_POST['textPopis']) && $_POST['textPopis'] != $solution->getTxt()) {
	$solution->setTxt($_POST['textPopis']);	
}

if (isset($_POST['textVideo']) && $_POST['textVideo'] != "" ) {
	$solution->uploadVideo($conn, $_POST['textVideo']);
}

if (isset($_FILES['uploadedFiles'])) {
	$fileCount = count($_FILES['uploadedFiles']["name"]);
	if ($fileCount != 0 && $_FILES['uploadedFiles']["name"][0] != "") {
		$solution->uploadFiles($conn, $_FILES['uploadedFiles']);
	}
}

$solution->setAttachments($conn);
mysqli_close($conn);
$solution->getEditingHtml();

page_footer();
?>