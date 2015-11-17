<?php
session_start();
include 'includes/functions.php';
page_head("Registration");
page_nav();

if(isset($_POST['registrovat']))
{
	$uname = mysql_real_escape_string($_POST['uname']);
	$email = mysql_real_escape_string($_POST['email']);
	$upass = md5(mysql_real_escape_string($_POST['pass']));
  $upass2 = md5(mysql_real_escape_string($_POST['pass2']));
  $os = mysql_real_escape_string($_POST['os']);
  $team = mysql_real_escape_string($_POST['type']);
  $liga = mysql_real_escape_string($_POST['liga']);
  if ($upass == $upass2){
	
  	if(mysql_query("INSERT INTO users(username,email,password,osebe,team,liga) VALUES('$uname','$email','$upass','$os','$team','$liga')"))
  	{
      validate_pass($upass,$upass2)
  		?>
          <script>alert('successfully registered ');</script>
          <?php
  	}
  	else
  	{
  		?>
          <script>alert('error while registering you...');</script>
          
          <?php
  	}
  }
  else 
  { ?>
      <script>alert('Hesla sa nezhoduju');</script>
          <?php
  
  }
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
<td><input type="text" name="uname" placeholder="Meno" required /></td>
</tr>
<tr>
<td>Email:</td>
<td><input type="email" name="email" placeholder="Email" required /></td>
</tr>
<tr>
<td>Heslo:</td>
<td><input type="password" name="pass" placeholder="Heslo" required /></td>
</tr>
<tr>
<td>Zopakuj heslo:</td>
<td><input type="password" name="pass2" placeholder="Zopakuj heslo" required /></td>
</tr>
<tr>
<td>Napíš nám niečo o sebe:</td>
<td><input type="text" name="os" placeholder="Niečo o sebe" required /></td>
</tr>
<tr>
<td><input type="radio" checked name="type" value="tím">Súťažný tím</td>
<td><input type="radio" name="type" value="rozhodca">Rozhodca</td>

</tr>
<tr>
<td><input type="radio" checked name="liga" value="slovak">Slovak league</td>
<td><input type="radio" name="liga" value="open">Open league</td>
</tr>
<tr>
<td><button type="submit" name="registrovat">Registrovať</button></td>
</tr>
</table>
</form>




<?php
page_footer()
?>
