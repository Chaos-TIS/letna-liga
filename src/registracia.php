<?php
require_once(dirname(__FILE__)."/includes/functions.php");

page_head("Registration");
page_nav();

if(isset($_POST["uname"])&& 
   isset($_POST["email"])&&
   isset($_POST["pass"])&&  
   isset($_POST["pass2"])&&
   isset($_POST["os"])&&
   isset($_POST["type"])&&
   isset($_POST["liga"])){ 
     
     
	$_SESSION['uname'] = mysql_real_escape_string($_POST["uname"]);
	$_SESSION['email'] = mysql_real_escape_string($_POST["email"]);
	$_SESSION['pass'] = md5(mysql_real_escape_string($_POST["pass"]));
  $_SESSION['pass2'] = md5(mysql_real_escape_string($_POST["pass2"]));
  $_SESSION['os'] = mysql_real_escape_string($_POST["os"]);
  $_SESSION['type'] = mysql_real_escape_string($_POST["type"]);
  $_SESSION['liga'] = mysql_real_escape_string($_POST["liga"]); 
} 
    
if(isset($_SESSION['uname'])&& ($_SESSION['pass']==$_SESSION['pass2'] )) {   
    registruj();
    session_unset();
	  session_destroy();}
   else{
   if(isset($_SESSION['pass'])){
    if ($_SESSION['pass']!=$_SESSION['pass2'] ){
    echo '<p class="chyba">Nezhoda hesiel</p>'; }
    }
    if(isset($_POST["registrovat"])){
      echo '<p class="chyba">Nevyplnili ste všetky povinné údaje</p>';  
  } 
    
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
<td><input type="radio" checked name="type" value="1"<?php if (isset($_POST['type']) && $_POST["type"]=="1") echo ' checked'; ?>>Súťažný tím</td>
<td><input type="radio" name="type" value="2"<?php if (isset($_POST['type']) && $_POST["type"]=="2") echo ' checked'; ?>>Rozhodca</td>

</tr>
<tr>
<td><input type="radio" checked name="liga" value="1"<?php if (isset($_POST['liga']) && $_POST["liga"]=="1") echo ' checked'; ?>>Slovak league</td>
<td><input type="radio" name="liga" value="2"<?php if (isset($_POST['liga']) && $_POST["liga"]=="2") echo ' checked'; ?>>Open league</td>
</tr>
<tr>
<td><input type="submit" name="registrovat" value="Registrovat"></td>
</tr>
</table>
</form>
<?php
      }
page_footer()
?>
