<?php
include 'includes/functions_reg.php';
page_head("Registration");
page_nav();
session_unset();
$val = new Validate();
$reg = new Reg();
if (isset($_POST["type"]) && $_POST["type"] == 0){
  if( isset($_POST["uname"])&& $val->validate_name($_POST["uname"]) &&
      isset($_POST["email"])&& $val->validate_mail($_POST["email"]) &&
      isset($_POST["pass"])&&  $val->required_pass($_POST["pass"]) &&
      isset($_POST["pass2"])&& $val->required_pass($_POST["pass2"]) &&
      isset($_POST["os"])&&
      isset($_POST["type"])&&
      isset($_POST["liga"]))
  {
    $_SESSION['uname'] = addslashes($_POST["uname"]);
    $_SESSION['email'] = strtolower(addslashes($_POST["email"]));   
    $_SESSION['pass']  = md5(addslashes($_POST["pass"]));
    $_SESSION['pass2'] = md5(addslashes($_POST["pass2"]));
    $_SESSION['os']    = addslashes($_POST["os"]);
    $_SESSION['type']  = addslashes($_POST["type"]);
    $_SESSION['liga']  = addslashes($_POST["liga"]); 
    if($_SESSION['type']==0){
      if($val->meno($_SESSION['uname'])&& $val->email($_SESSION['email']) && $val->validate_pass($_SESSION['pass'],$_SESSION['pass2'])){
        $reg->registruj($_SESSION['email'],$_SESSION['pass'],$_SESSION['type'],$_SESSION['uname'],$_SESSION['os'],$_SESSION['liga']);
        session_unset();
        session_destroy();
      }
    }
  }
}else{
  if( isset($_POST["email"])&& $val->validate_mail($_POST["email"]) &&
      isset($_POST["pass"])&&  $val->required_pass($_POST["pass"]) &&
      isset($_POST["pass2"])&& $val->required_pass($_POST["pass2"]) &&
      isset($_POST["type"]))
  {
    $_SESSION['email'] = strtolower(addslashes($_POST["email"]));   
    $_SESSION['pass']  = md5(addslashes($_POST["pass"]));
    $_SESSION['pass2'] = md5(addslashes($_POST["pass2"]));
    $_SESSION['type']  = addslashes($_POST["type"]); 
    if($val->email($_SESSION['email']) && $val->validate_pass($_SESSION['pass'],$_SESSION['pass2'])){
        $reg->registruj($_SESSION['email'],$_SESSION['pass'],$_SESSION['type']);
        session_unset();
        session_destroy();
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
  <td><span class='error1'style="color: green; text-align: center;font-size:30px;font-family:calibri"><?php echo $reg->GetMessage(); ?></span></td>
  </tr>
  <tr>
  <td><span class='error2'style="color: red; text-align: center;font-size:30px;font-family:calibri"><?php echo $reg->GetErrorMessage(); ?></span></td>
  </tr>
  <tr>
  <td><input type="radio" checked name="type" value=0<?php if (isset($_POST['type']) && $_POST["type"]==0) echo ' checked'; ?>>Súťažný tím</td>
  <td><input type="radio" name="type" value=1<?php if (isset($_POST['type']) && $_POST["type"]==1) echo ' checked'; ?>>Rozhodca</td>
  </tr>
  <tr>
  <td><span class='error'style="color: red; text-align: center;font-size:20px;font-family:calibri"><?php echo $val->GetErrorMessage(); ?></span></td>
  </tr>
  <tr>
  <td>Meno:</td>
  <td><input type="text" name="uname" id="uname" value="<?php if (isset($_POST["uname"])) echo $_POST["uname"];?>" placeholder="Meno"  /></td>
  </tr>
  <tr>
  <td>Email:</td>
  <td><input type="email" name="email" value="<?php if (isset($_POST["email"])) echo $_POST["email"];?>" placeholder="Email" /></td>
  </tr>
  <tr>
  <td>Heslo:</td>
  <td><input type="password" name="pass"  placeholder="Heslo"  /></td>
  </tr>
  <tr>
  <td>Zopakuj heslo:</td>
  <td><input type="password" name="pass2" placeholder="Zopakuj heslo"  /></td>
  </tr>
  <tr>
  <td>Napíš nám niečo o sebe:</td>
  <td><textarea cols="25" rows="3" name="os" id="os" ><?php if (isset($_POST["os"])) echo $_POST["os"];?></textarea></td>
  </tr>
  <tr>
  <td><input type="radio" checked name="liga" value=1<?php if (isset($_POST['liga']) && $_POST["liga"]==1) echo ' checked'; ?>>Slovak league</td>
  <td><input type="radio" name="liga" value=0<?php if (isset($_POST['liga']) && $_POST["liga"]==0) echo ' checked'; ?>>Open league</td>
  </tr>
  <tr>
  <td><input type="submit" name="registrovat" value="Registrovat"></td>
  </tr>
  </table>
  </form>

  <script>
    $("[name=type]").change(function (){
      var disabled = $("[name=type]:checked").val() == 1;
      $("#uname").attr("disabled", disabled);
      $("#os").attr("disabled", disabled);
      $("[name=liga]").attr("disabled", disabled);
    });
    $(document).ready(function(){
      $("[name=type]").change();
    });
  </script>

<?php
page_footer()
?>

