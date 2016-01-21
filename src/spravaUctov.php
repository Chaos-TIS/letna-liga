<?php
include 'includes/functions_editAcc.php';
page_head("Správa účtov");
page_nav();
if (!isset($_SESSION['loggedUser']))
  get_login_form();
  else
  get_logout_button();

  if (isset($_POST["zrus"])){
      zmaz_acc($_POST['zrus']);}

if (isset($_POST["active"])){
      set_jury($_POST['active']);}

if ($_GET['id'] == '0'){
sprava_uctov();	
}else{
	sprava_uctov_jury();
}     

page_footer()
?>

