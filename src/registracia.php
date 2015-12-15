<?php
include 'includes/functions.php';
page_head("Registration");
page_nav();
session_unset();
if(isset($_POST["uname"])&& 
   isset($_POST["email"])&&
   isset($_POST["pass"])&&  
   isset($_POST["pass2"])&&
   isset($_POST["os"])&&
   isset($_POST["type"])&&
   isset($_POST["liga"]))
{
  $_SESSION['uname'] = addslashes($_POST["uname"]);
	$_SESSION['email'] = strtolower(addslashes($_POST["email"]));   
	$_SESSION['pass'] = md5(addslashes($_POST["pass"]));
  $_SESSION['pass2'] = md5(addslashes($_POST["pass2"]));
  $_SESSION['os'] = addslashes($_POST["os"]);
  $_SESSION['type'] = addslashes($_POST["type"]);
  $_SESSION['liga'] = addslashes($_POST["liga"]);
}
if(isset($_SESSION['uname'])&& ($_SESSION['pass']==$_SESSION['pass2'] ))
{
  if ($link = db_connect())
  { 
    $sql = "SELECT u.mail
    FROM users as u 
    WHERE u.mail = '".$_SESSION['email']."'";
    $result = mysqli_query($link,$sql);
    if (mysqli_num_rows($result) == 0)
    {
      $sql = "SELECT t.name
      FROM teams as t 
      WHERE t.name = '".$_SESSION['uname']."'";
      $result = mysqli_query($link,$sql);  
      if (mysqli_num_rows($result) == 0 ){
        registruj();
        session_unset();
        session_destroy();
      }
      else
      {
        echo '<p class="chyba">Zadane meno sa nachádza v Databáze.</p>' . "\n";
        form();
      }
    }
    else
    {
      echo '<p class="chyba">Zadaný email sa nachádza v Databáze.</p>' . "\n";
      form();
    }
  }
  else
  {
    echo '<p class="chyba">NEpodarilo sa spojiť s databázovým serverom!</p>';
  }
}
else
{
  if(isset($_SESSION['pass']))
  {
    if ($_SESSION['pass']!=$_SESSION['pass2'] )
    {
      echo '<p class="chyba">Heslá sa nezhodujú</p>';
    }
  }
  if(isset($_POST["registrovat"]) )
  {
    echo '<p class="chyba">Nevyplnili ste všetky povinné údaje</p>';
  }
  
  form();
}
page_footer()
?>

<?php
function form() 
{
?>

<h1>Letná liga FLL</h1>
  <?php if (!isset($_SESSION['loggedUser']))
  get_login_form();
  else
  get_logout_button();
?>

<form method="post">
  <table align="left" width="40%" border="0">
  <tr>
  <td><input type="radio" checked name="type" value=0<?php if (isset($_POST['type']) && $_POST["type"]==0) echo ' checked'; ?>>Súťažný tím</td>
  <td><input type="radio" name="type" value=1<?php if (isset($_POST['type']) && $_POST["type"]==1) echo ' checked'; ?>>Rozhodca</td>
  </tr>
  <tr>
  <td>Meno:</td>
  <td><input type="text" name="uname" id="uname" value="<?php if (isset($_POST["uname"])) echo $_POST["uname"];?>" placeholder="Meno" required /></td>
  </tr>
  <tr>
  <td>Email:</td>
  <td><input type="email" name="email" value="<?php if (isset($_POST["email"])) echo $_POST["email"];?>" placeholder="Email" required /></td>
  </tr>
  <tr>
  <td>Heslo:</td>
  <td><input type="password" name="pass"  placeholder="Heslo" required /></td>
  </tr>
  <tr>
  <td>Zopakuj heslo:</td>
  <td><input type="password" name="pass2" placeholder="Zopakuj heslo" required /></td>
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
}
?>