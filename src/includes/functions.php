<?php

function __autoload($class_name) {
    include(dirname(__FILE__)."/../classes/$class_name.php");
}

function page_head($title)
{
    ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
    session_start();
?>
<!DOCTYPE html>
<html lang="sk-SK">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="fll, lego, letna liga">
        <meta name="author" content="Chaos">
        <title><?php echo $title ?></title>
        <link rel="icon" href="favicon.ico" type="image/x-icon">
        <link type="text/css" href="styles.css" rel="stylesheet">
        <link type="text/css" href="css/dropdownmenu.css" rel="stylesheet">
        <script type="text/javascript" src="js/dropdownmenu.js" ></script>
        <script type="text/javascript" src="js/translator.js" ></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    </head>

    <body>
    <h1>Letná liga FLL</h1>
<?php
}

function get_login_form(){
?>
    <form id="login-form" onsubmit="validateLogin()" method="post" accept-charset="utf-8">
        <table>
            <tr>
                <td><p style="margin-bottom: 0; margin-top: 0; font-weight: bold; color: #3399ff;" data-trans="login-form">Prihlásenie</p></td>
            </tr>
            <tr>
                <td><label for="mail" data-trans="login-form">E-mailová adresa:</label></td>
                <td><input id="mail" type="text" value="@"></td>
            </tr>
            <tr>
                <td><label for="password" data-trans="login-form">Heslo:</label></td>
                <td><input id="password" type="password" value=""></td>
            </tr>
            <tr>
                <td><input type="submit" name="submit" value="Prihlásiť sa" data-trans="login-form"></td>
                <td style="text-align: right;"><a href="registracia.php" data-trans="login-form"> Registrácia </a></td>
            </tr>
        </table>
    </form>
    <script>
        function validateLogin() {
            event.preventDefault();
            var login = $("#mail").val();
            var password = $("#password").val();
            $.ajax({cache : false,
                    async : true,
                    type: "POST",
                    data : {mail : login, password : password},
                    url : "includes/login.php"}).done(function(data) {
                if (data) {
                    alert(data);
                }
                else{
                    location.reload();
                }
            });
        }
    </script>

<?php
}

function get_logout_button(){
    ?>
    <form id="logout-form" action="includes/logout.php">
        <input type="submit" name="submit" value="Odhlásiť">
    </form>
    <?php
}

function page_nav()
{
    ?>
		<div class="nav">
			<ul id="menu" class="menu">
				<li><span>Zadania</span>
					<ul>
					<?php
					if ($link = db_connect()) {
          $sql =  "SELECT COUNT(*) pocet FROM CONTEXTS c INNER JOIN ASSIGNMENTS a ON (a.context_id = c.context_id) WHERE a.year = ".Date("Y")." ORDER BY begin ASC";
          $result = mysqli_query($link,$sql);
            if($row = mysqli_fetch_array($result)){
              $pocet = $row['pocet']  ;
 	          }
   	      }
          for($i=1;$i<$pocet+1;$i++){
          ?> <li><a href="assignment.php?r=2015&id=<?php echo $i ?>"> <?php echo $i ?>. Zadanie</a></li>   <?php
          } ?>
						<li><a href="#">Prehľad zadaní</a></li>
					</ul>
				</li>
				<li><a href="#">Výsledky</a></li>
				<li><span>Archív</span>
					<ul>
						<li class="submenu">
              <span>2013</span> <ul>
								<li class="noborder"><a href="#">Výsledky</a></li>
								<?php
								if ($link = db_connect()) {
                $sql =  "SELECT COUNT(*) pocet FROM CONTEXTS c INNER JOIN ASSIGNMENTS a ON (a.context_id = c.context_id) WHERE a.year = 2013 ORDER BY begin ASC";
                $result = mysqli_query($link,$sql);
                  if($row = mysqli_fetch_array($result)){
                    $pocet = $row['pocet']  ;
       	          }
         	      }
								for($i=1;$i<$pocet+1;$i++){
                ?> <li><a href="assignment.php?r=2013&id=<?php echo $i ?>"> <?php echo $i ?>. Zadanie</a></li>   <?php
                } ?>
							</ul>
              
              </li>
						<li class="submenu">
              <span>2014</span> <ul>
								<li class="noborder"><a href="#">Výsledky</a></li>
								<?php
								if ($link = db_connect()) {
                $sql =  "SELECT COUNT(*) pocet FROM CONTEXTS c INNER JOIN ASSIGNMENTS a ON (a.context_id = c.context_id) WHERE a.year = 2014 ORDER BY begin ASC";
                $result = mysqli_query($link,$sql);
                  if($row = mysqli_fetch_array($result)){
                    $pocet = $row['pocet']  ;
       	          }
         	      }
								for($i=1;$i<$pocet+1;$i++){
                ?> <li><a href="assignment.php?r=2014&id=<?php echo $i ?>"> <?php echo $i ?>. Zadanie</a></li>   <?php
                } ?>
							</ul>
              
              </li>
						<li class="submenu">
							<span>2015</span><ul>
								<li class="noborder"><a href="#">Výsledky</a></li>
								<?php
								for($i=1;$i<$pocet+1;$i++){
                ?> <li><a href="assignment.php?r=2015&id=<?php echo $i ?>"> <?php echo $i ?>. Zadanie</a></li>   <?php
                } ?>
							</ul>
						</li>
					</ul>
				</li>
				<li><a href="#">Užívatelia</a></li>
				<li><a href="#">Jazyk</a>
					<ul>
						<li><a href="#" onclick="dict.translate(dict.SK)"><img src="images/sk.png" width=33 height=22></a></li>
						<li><a href="#" onclick="dict.translate(dict.ENG)"><img src="images/eng.png" width=33 height=22></a></li>
					</ul>
				</li>
			</ul>
		</div>
		<script type="text/javascript">
		var dropdown=new TINY.dropdown.init("dropdown", {id:'menu', active:'menuhover'});
		</script>
    <?php
}

function page_footer()
{
    ?>
    </body>
	</html>
    <?php
}

function checkUploadImage($ext, $typ, $vel)
{
	if (( (($ext == "jpg") && ($typ == "image/jpeg")) ||
	     (($ext == "gif") && ($typ == "image/gif"))   ||
	     (($ext == "png") && ($typ == "image/png")) ) && ($vel < 10000000))
	{
		return True;
	}
	else
	{
		return False;
	}
}

function checkUploadFile($vel)
{
	if ($vel < 10000000)
	{
		return True;
	}
	else
	{
		return False;
	}
}

function db_connect() {
    if ($link = mysqli_connect('localhost', '...', '...')) {
        if (mysqli_select_db($link, 'letnaliga')) {
            mysqli_query($link, "SET CHARACTER SET 'utf8'");
            return $link;
        } else {
            echo "Nepodarilo sa vybrať databázu!";
            return false;
        }
    } else {
        echo "Nepodarilo sa spojiť s databázovým serverom!";
        return false;
    }
}

function new_solution($conn, $uid, $aid) {
	mysqli_query($conn,"INSERT INTO contexts (user_id) VALUES (".$uid.")");
	$cid = mysqli_insert_id($conn);
	mysqli_query($conn,"INSERT INTO solutions (context_id,assignment_id) VALUES (".$cid.",".$aid.")");
	mysqli_query($conn,"INSERT INTO programs (location_id) VALUES (".$cid.")");
	return $cid;
}

function show_table($year) {
    error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
    if (!isset($year)){
        $year = "(SELECT MAX(year) FROM assignments)";
    }
    if ($link = db_connect()) {
        $sql = "SELECT q.name, a.year, q.solution_id, q.best, a.context_id assignment_id, q.points
                FROM assignments a
                LEFT OUTER JOIN (
                    SELECT t.name, s.context_id solution_id, s.best, s.assignment_id, ROUND(SUM(comm.points)/COUNT(comm.points),2) points
                    FROM solutions s
                    LEFT OUTER JOIN contexts c ON (c.context_id = s.context_id)
                    LEFT OUTER JOIN users u ON (u.user_id = c.user_id)
                    LEFT OUTER JOIN teams t ON (t.user_id = u.user_id)
                    LEFT OUTER JOIN comments comm ON (comm.solution_id = c.context_id)
                	GROUP BY t.user_id, s.context_id) q
                ON (q.assignment_id = a.context_id)
                WHERE a.year = $year
                ORDER BY a.begin ASC;
                ";



        $result = mysqli_query($link, $sql);
        $userPointsMap = array();
        $aid_array = array();
        while ($row = mysqli_fetch_array($result)) {
            if (!sizeof($aid_array) || $row['assignment_id'] != end(array_values($aid_array))){
                array_push($aid_array, $row['assignment_id']);
                foreach ($userPointsMap as $user => $array) {
                    array_push($array, null);
                }
            }

            if (!isset($userPointsMap[$row['name']]) && $row['name'] != null){
                $userPointsMap[$row['name']] = array();
                for ($i = 0; $i < sizeof($aid_array); $i++)
                {
                    array_push($userPointsMap[$row['name']], null);
                }
            }

            if ($row['name'] != null){
                $userPointsMap[$row['name']][sizeof($aid_array)-1] = array((float)$row['points'], $row['solution_id'], $row['best']);
            }
        }

        $sum_array = array();
        foreach ($userPointsMap as $user => $array){
            $sum = 0;
            for ($i = 0; $i < sizeof($aid_array); $i++){
                if (!is_null($array[$i])){
                    $sum += $array[$i][0];
                }
            }
            $sum_array[$user] = $sum;
        }

        arsort($sum_array);

        $result_table = '<table>
                         <tr style="font-weight: bold; background-color: #ff6600; border-bottom: 1px solid black;">
                         <td>Meno tímu</td>';

        for ($i = 1; $i < sizeof($aid_array)+1; $i++){
            $href = 'assignment.php?id='.$aid_array[$i];
            $result_table .= '<td ><a href="'.$href.'">'.$i.'</a></td>';
        }
        $result_table .= '<td>Spolu</td></tr>';

        foreach ($sum_array as $user => $sum){
            $result_table .= "<tr style='border-top: 1px solid black;'><td style='border-right: 1px solid black; font-weight: bold;'><strong>$user</strong></td>";
            for ($i = 0; $i < sizeof($aid_array); $i++){
                if (is_null($userPointsMap[$user][$i])){
                    $result_table .= "<td style=' font-weight: bold;'>-</td>";
                }
                else {
                    $result_table .= '<td style="font-weight: bold; '.($userPointsMap[$user][$i][2]?"background-color: #00ff3f;":"").'"><a
                    href="solution.php?id='.$userPointsMap[$user][$i][1].'">'.$userPointsMap[$user][$i][0].'</a></td>';
                };
            }
            $result_table .= '<td style="border-left: 1px solid black;"><strong>'.$sum_array[$user].'</strong></td>';
            $result_table .= "</tr>";
        }
        $result_table .= "</table>";

        return $result_table;
    }


}

function registruj() 
{
  if ($link = db_connect())
  { 
    $sql =  "INSERT INTO users(mail,password) VALUES('".$_SESSION['email']."','".$_SESSION['pass']."')";
    $result = mysqli_query($link,$sql); 
    if ($result)
    {
        if ($_POST["type"] == 0 )
        {  
            $sql =  "INSERT INTO teams(user_id,name,description,sk_league) SELECT u.user_id ,'" .$_SESSION['uname']."','" .$_SESSION['os']."','" .$_SESSION['liga']."'
            FROM users u
            WHERE LOWER(u.mail) = '".$_SESSION['email']."'";    
            $result = mysqli_query($link,$sql);
            if($result)
            {
                echo '<p>Bol ste uspesne zaregistrovany.</p>'. "\n"; 
                ?>
                <meta http-equiv="refresh" content="4;url=http://localhost:8080/NLL/index.php"> 
                <?php      
            }
        }
        else
        {
            $sql =  "INSERT INTO organisators(user_id,admin,validated) SELECT u.user_id ,0,0
            FROM users u
            WHERE LOWER(u.mail) = '".$_SESSION['email']."'";
            $result = mysqli_query($link,$sql);
            if($result)
            {
                echo '<p>Bol ste uspesne zaregistrovany.</p>'. "\n"; 
                ?>
                <meta http-equiv="refresh" content="4;url=http://localhost:8080/NLL/index.php"> 
                <?php
            }
        }
    }
    else
    {
        echo '<p class="chyba">Nastala chyba pri registracii.</p>' . "\n"; 
        ?>
        <meta http-equiv="refresh" content="4;url=http://localhost:8080/NLL/registracia.php"> 
        <?php
    }
}
else
    {
        echo '<p class="chyba">NEpodarilo sa spojiť s databázovým serverom!</p>';
    }

}


?>