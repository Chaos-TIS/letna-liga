<?php
include 'includes/functions_editAcc.php';
page_head("EditAccUser");
page_nav();
session_unset();
$val = new Validate();
$edit = new Edit();
$udaje=daj_udaje_uctu($_GET['id']);
$_SESSION['uname'] = $udaje["name"];
$_SESSION['email'] = $udaje["mail"];   
$_SESSION['os']    = $udaje["description"];
$_SESSION['liga']  = $udaje["sk_league"]; 

if( isset($_POST["uname"])&& $val->validate_name($_POST["uname"]) &&
      isset($_POST["email"])&& $val->validate_mail($_POST["email"]) &&
      isset($_POST["pass"])&&  $val->required_pass($_POST["pass"]) &&
      isset($_POST["pass2"])&& $val->required_pass($_POST["pass2"]) &&
      isset($_POST["os"])&&
      isset($_POST["liga"])) {
        if($_SESSION['uname']!=$_POST["uname"]) {
          if ($val->validate_pass($_POST["pass"],$_POST["pass2"])) {
            if($val->meno($_POST["uname"])) {
              $edit->edituj($_POST["email"],$_POST["pass"],$_POST["uname"],$_POST["os"],$_POST["liga"]);
            }
          }
        }elseif ($_SESSION['email'] != $_POST["email"]) {
          if ($val->validate_pass($_POST["pass"],$_POST["pass2"])){
            if ($val->email($_POST["email"])) {
              $edit->edituj($_POST["email"],$_POST["pass"],$_POST["uname"],$_POST["os"],$_POST["liga"]);
            }
          }
        }else {
          if ($val->validate_pass($_POST["pass"],$_POST["pass2"])){
            $edit->edituj($_POST["email"],$_POST["pass"],$_POST["uname"],$_POST["os"],$_POST["liga"]);
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
  <td data-trans-key="edit-team-form"></td>
  <td><input type="text" name="uname" id="uname" value="<?php if (isset($udaje["name"])) echo $udaje["name"];?>" data-trans-key="edit-team-form"/></td>
  </tr>
  <tr>
  <td data-trans-key="edit-team-form"></td>
  <td><input type="email" name="email" value="<?php if (isset($udaje["mail"])) echo $udaje["mail"];?>" data-trans-key="edit-team-form"/></td>
  </tr>
  <tr>
  <td data-trans-key="edit-team-form"></td>
  <td><input type="password" name="pass" data-trans-key="edit-team-form"/></td>
  </tr>
  <tr>
  <td data-trans-key="edit-team-form"></td>
  <td><input type="password" name="pass2" data-trans-key="edit-team-form"/></td>
  </tr>
  <tr>
  <td data-trans-key="edit-team-form"></td>
  <td><textarea cols="25" rows="3" name="os" id="os" ><?php if (isset($udaje["description"])) echo $udaje["description"];?></textarea></td>
  </tr>
  <tr>
  <td><input type="radio" checked name="liga" value=1<?php if (isset($udaje['sk_league']) && $udaje["sk_league"]==1) echo ' checked'; ?>><span data-trans-key="edit-team-form"></span></td>
  <td><input type="radio" name="liga" value=0<?php if (isset($udaje['sk_league']) && $udaje["sk_league"]==0) echo ' checked'; ?>><span data-trans-key="edit-team-form"></span></td>
  </tr>
  <tr>
  <td><input type="submit" name="save" data-trans-key="edit-team-form"></td>
  </tr>
  </table>
  </form>


  <?php
echoMessage($edit->GetMessage());
$err = $val->GetErrorMessage();
echoError(!empty($err) ? $err : $edit->GetErrorMessage());
page_footer()
?>