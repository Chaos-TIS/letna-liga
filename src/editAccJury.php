<?php
include 'includes/functions_editAcc.php';
page_head("EditAccUser");
page_nav();
session_unset();
$val = new Validate();
$edit = new EditJury();
$udaje=daj_udaje_rozhodcu($_GET['id']);
$_SESSION['email'] = $udaje["mail"];   

if(   isset($_POST["email"])&& $val->validate_mail($_POST["email"]) &&
      isset($_POST["pass"])&&  $val->required_pass($_POST["pass"]) &&
      isset($_POST["pass2"])&& $val->required_pass($_POST["pass2"])) {
        if ($_SESSION['email'] != $_POST["email"]) {
          if ($val->validate_pass($_POST["pass"],$_POST["pass2"])){
            if ($val->email($_POST["email"])) {
              $edit->editujJury($_POST["email"],$_POST["pass"]);
            }
          }
        }else {
          if ($val->validate_pass($_POST["pass"],$_POST["pass2"])){
            $edit->editujJury($_POST["email"],$_POST["pass"]);
          }
        }
}
if (!isset($_SESSION['loggedUser']))
  get_login_form();
  else
  get_logout_button();



?>

</br>
<form method="post">
  <table align="center" width="60%" border="0" id="display">
  <tr>
  <td><span class='error1'style="color: green; text-align: center;font-size:30px;font-family:calibri"><?php echo $edit->GetMessage(); ?></span></td>
  </tr>
  <tr>
  <td><span class='error2'style="color: red; text-align: center;font-size:30px;font-family:calibri"><?php echo $edit->GetErrorMessage(); ?></span></td>
  </tr>
  <tr>
  <td data-trans-key="edit-jury-form"></td>
  <td><input type="email" name="email" value="<?php if (isset($udaje["mail"])) echo $udaje["mail"];?>" data-trans-key="edit-jury-form" /></td>
  </tr>
  <tr>
  <td><span class='error'style="color: red; text-align: center;font-size:20px;font-family:calibri"><?php echo $val->GetErrorMessage(); ?></span></td>
  </tr>
  <tr>
  <td data-trans-key="edit-jury-form"></td>
  <td><input type="password" name="pass" data-trans-key="edit-jury-form" /></td>
  </tr>
  <tr>
  <td data-trans-key="edit-jury-form"></td>
  <td><input type="password" name="pass2" data-trans-key="edit-jury-form" /></td>
  </tr>
  <tr>
  <td><input type="submit" name="save" data-trans-key="edit-jury-form"></td>
  </tr>
  </table>
  </form>


  <?php

page_footer()
?>
