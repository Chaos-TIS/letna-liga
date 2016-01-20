<?php

define("SK", 0);
define("ENG", 1);

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

    <script>
        $(document).ready(function(){
            dict.translateElement();
        });
    </script>

    <body>
    <a href="."><h1 data-trans-key="main-header"></h1></a>
<?php
Image::setIcon("images/image.png");
Video::setIcon("images/video.png");
Program::setIcon("images/file.png");
}

function get_login_form(){
?>
    <form id="login-form" onsubmit="validateLogin()" method="post" accept-charset="utf-8">
        <table>
            <tr>
                <td><p style="margin-bottom: 0; margin-top: 0; font-weight: bold; color: #3399ff;" data-trans-key="login-form"></p></td>
            </tr>
            <tr>
                <td><label for="mail" data-trans-key="login-form"></label></td>
                <td><input id="mail" type="text" placeholder="email@email.com"></td>
            </tr>
            <tr>
                <td><label for="password" data-trans-key="login-form"></label></td>
                <td><input id="password" type="password"  data-trans-key="login-form"></td>
            </tr>
            <tr>
                <td><input type="submit" name="submit" data-trans-key="login-form"></td>
                <td style="text-align: right;"><a href="registracia.php" data-trans-key="login-form"></a></td>
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
                    url : "includes/login.php"}).done(function(error) {
                if (error) {
                    dict.echoError(error, '');
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
        <span><span data-trans-key="logged-in"></span> <?php echo $_SESSION['loggedUser']->mail;?></span>
        <input type="submit" name="submit" data-trans-key="logout">
    </form>
    <?php
}

function page_nav()
{
    ?>
		<div class="nav">
			<ul id="menu" class="menu">
				<li><span data-trans-key="assignments"></span>
					<ul>
					<?php
					if ($link = db_connect()) {
            $sql =  "SELECT * FROM contexts c INNER JOIN assignments a ON (a.context_id = c.context_id) WHERE a.year = ".Date("Y")." ORDER BY begin ASC";
            $result = mysqli_query($link,$sql);
            $i=1;
            while ($row = mysqli_fetch_assoc($result)) {
              ?> <li><a href="assignment.php?id=<?php echo $row["context_id"] ?>"> <?php echo $i ?>. <span data-trans-key="assignment"></span></a></li>  <?php
              $i++;
            }     
          }
          ?>
						<li><a href="#" data-trans-key="assignments-overview"></a></li>
					</ul>
				</li>
				<li><a href="results.php" data-trans-key="results"></a></li>
				<li><span data-trans-key="archive"></span>
					<ul>
						<li class="submenu">
              <span>2013</span> <ul>
								<li class="noborder"><a href="results.php?year=2013" data-trans-key="results"></a></li>
								<?php
								if ($link = db_connect()) {
                  $sql =  "SELECT * FROM CONTEXTS c INNER JOIN ASSIGNMENTS a ON (a.context_id = c.context_id) WHERE a.year = 2013 ORDER BY begin ASC";
                  $result = mysqli_query($link,$sql);
                  $i=1;
                  while ($row = mysqli_fetch_assoc($result)) {
                    ?> <li><a href="assignment.php?id=<?php echo $row["context_id"] ?>"> <?php echo $i ?>. <span data-trans-key="assignment"></span></a></li>  <?php
                    $i++;
                  }     
                }
                ?>
							</ul>
              
              </li>
						<li class="submenu">
              <span>2014</span> <ul>
								<li class="noborder"><a href="results.php?year=2014" data-trans-key="results"></a></li>
								<?php
								if ($link = db_connect()) {
                  $sql =  "SELECT * FROM CONTEXTS c INNER JOIN ASSIGNMENTS a ON (a.context_id = c.context_id) WHERE a.year = 2014 ORDER BY begin ASC";
                  $result = mysqli_query($link,$sql);
                  $i=1;
                  while ($row = mysqli_fetch_assoc($result)) {
                    ?> <li><a href="assignment.php?id=<?php echo $row["context_id"] ?>"> <?php echo $i ?>. <span data-trans-key="assignment"></span></a></li>  <?php
                    $i++;
                  }     
                }
                ?>
							</ul>
              
              </li>
						<li class="submenu">
							<span>2015</span><ul>
								<li class="noborder"><a href="results.php?year=2015" data-trans-key="results"></a></li>
								<?php
      					if ($link = db_connect()) {
                  $sql =  "SELECT * FROM contexts c INNER JOIN assignments a ON (a.context_id = c.context_id) WHERE a.year = ".Date("Y")." ORDER BY begin ASC";
                  $result = mysqli_query($link,$sql);
                  $i=1;
                  while ($row = mysqli_fetch_assoc($result)) {
                    ?> <li><a href="assignment.php?id=<?php echo $row["context_id"] ?>"> <?php echo $i ?>. <span data-trans-key="assignment"></span></a></li>  <?php
                    $i++;
                  }     
                }
                ?>
							</ul>
						</li>
					</ul>
				</li>
                <?php if ($_SESSION['loggedUser'] instanceof Administrator) { ?>
				<li><a href="#" data-trans-key="users"></a>
                    <ul>
                        <li><a href="spravaUctov.php?id=0" data-trans-key="teams"></a></li>
                        <li><a href="spravaUctov.php?id=1" data-trans-key="jury-pl"></a></li>
                    </ul>
                </li>
                <?php }?>
				<li><a href="#" data-trans-key="language"></a>
					<ul>
						<li><a href="#" onclick="dict.translateElement(dict.SK)"><img src="images/sk.png" width=33 height=22></a></li>
						<li><a href="#" onclick="dict.translateElement(dict.ENG)"><img src="images/eng.png" width=33 height=22></a></li>
					</ul>
				</li>
			</ul>
		</div>
		<script type="text/javascript">
		var dropdown=new TINY.dropdown.init("dropdown", {id:'menu', active:'menuhover'});
		</script>
        <p id="success-message"></p>
        <p id="error-message"></p>
    <?php
}

function page_footer()
{
    ?>
    </body>
	</html>
    <?php
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

function echoError($key, $info = null){
    ?><script>dict.echoError('<?php echo $key;?>', '<?php echo $info;?>');</script>
    <?php
}

function echoMessage($key, $info = null){
    ?><script>dict.echoSuccess('<?php echo $key;?>', '<?php echo $info;?>');</script>
    <?php
}

function db_connect() {
    if ($link = mysqli_connect('localhost', 'letnaliga', '12345')) {
        if (mysqli_select_db($link, 'letnaliga')) {
            mysqli_query($link, "SET CHARACTER SET 'utf8'");
            return $link;
        } else {
            echoError('db-choice-fail');
            return false;
        }
    } else {
        echoError('db-connection-fail');
        return false;
    }
}

function new_solution($conn, $uid, $aid) {
	mysqli_query($conn,"INSERT INTO contexts (user_id) VALUES (".$uid.")");
	$cid = mysqli_insert_id($conn);
	mysqli_query($conn,"INSERT INTO solutions (context_id,assignment_id) VALUES (".$cid.",".$aid.")");
	return $cid;
}

function get_result_table($sk_league, $year) {
    error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
    if (!isset($year)){
        $year = "(SELECT MAX(year) FROM assignments)";
    }
    if ($year <= 2015 && !$sk_league) {
        return;
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
                   	WHERE t.sk_league IN (1, $sk_league)
                	GROUP BY t.user_id, s.context_id) q
                ON (q.assignment_id = a.context_id)
                WHERE a.year = $year
                ORDER BY a.begin ASC;
                ";



        $result = mysqli_query($link, $sql);
        $userPointsMap = array();
        $aid_array = array();

        if (!$result)
            return;
        while ($row = mysqli_fetch_array($result)) {
            $end_array = array_values($aid_array);
            if (!sizeof($aid_array) || $row['assignment_id'] != end($end_array)){
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

        $league = $sk_league ? "sk-league" : "open-league";

        $result_table = '<p class="center" data-trans-key="'.$league.'"></p>';
        $result_table .= '<table class="result-table">
                         <tr style="font-weight: bold; background-color: #ff6600; border-bottom: 1px solid black;">
                         <td data-trans-key="team-name"></td>';

        for ($i = 1; $i < sizeof($aid_array)+1; $i++){
            $href = 'assignment.php?id='.$aid_array[$i];
            $result_table .= '<td><a style="color: black;" href="'.$href.'">'.$i.'</a></td>';
        }
        $result_table .= '<td data-trans-key="sum-points"></td></tr>';

        foreach ($sum_array as $user => $sum){
            $result_table .= "<tr style='border-top: 1px solid black;'><td style='border-right: 1px solid black; font-weight: bold;'><strong>$user</strong></td>";
            for ($i = 0; $i < sizeof($aid_array); $i++){
                if (is_null($userPointsMap[$user][$i])){
                    $result_table .= "<td style=' font-weight: bold;'>-</td>";
                }
                else {
                    $result_table .= '<td style="font-weight: bold; '.($userPointsMap[$user][$i][2]?"background-color: #6CF952;":"").'"><a
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



function sprava_uctov() {

    if ($link = db_connect()) {
        $sql="SELECT * FROM teams WHERE user_id>0 ORDER BY name ASC"; // definuj dopyt
    $result = mysqli_query($link, $sql); // vykonaj dopyt
    if ($result) {
            // dopyt sa podarilo vykonať
            echo '<p>';
        ?>
            <form method="post">
            <?php
            echo "<table text-align = 'center' border = '0'>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td><a href='editAcc.php?id={$row['user_id']}'>{$row['name']}</a></td>";
                echo "<td><button type='submit' name='zrus' value='{$row['user_id']}'><span data-trans-key='delete'></span></button><br></td>\n";
                echo "</tr>";
            }
            echo "</table>";
            ?>
</form>
<?php
            echo '</p>';
            mysqli_free_result($result);
    } else {
            // NEpodarilo sa vykonať dopyt!
        echoError('db-query-fail');
    }
    mysqli_close($link);
    } else {
        // NEpodarilo sa spojiť s databázovým serverom alebo vybrať databázu!
        echoError('db-connection-fail');
    }
}

function sprava_uctov_jury() {

    if ($link = db_connect()) {
        $sql="SELECT * FROM organisators o INNER JOIN users u ON o.user_id = u.user_id WHERE o.user_id>0 ORDER BY u.mail ASC"; // definuj dopyt
    $result = mysqli_query($link, $sql); // vykonaj dopyt
    if ($result) {
            // dopyt sa podarilo vykonať
            echo '<p>';
        ?>
            <form method="post">
            <?php
            echo "<table text-align = 'center' border = '0'>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td><a href='editAccJury.php?id={$row['user_id']}'>{$row['mail']}</a></td>";
                if ($row['validated']==0) {
                    echo "<td><button type='submit' name='active' value='{$row['user_id']}'><span data-trans-key='validate'></span></button><br></td>";
                }else{
                    echo "<td><br></td>";
                }
                echo "<td><button type='submit' name='zrus' value='{$row['user_id']}'><span data-trans-key='delete'></span></button><br></td>\n";
                echo "</tr>";
            }
            echo "</table>";
            ?>
</form>
<?php
            echo '</p>';
            mysqli_free_result($result);
    } else {
            // NEpodarilo sa vykonať dopyt!
        echoError('db-query-fail');
    }
    mysqli_close($link);
    } else {
        // NEpodarilo sa spojiť s databázovým serverom alebo vybrať databázu!
        echoError('db-connection-fail');
    }
}




?>