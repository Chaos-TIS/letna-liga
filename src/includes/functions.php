<?php

function __autoload($class_name) {
    include "classes/$class_name.php";
}

function page_head($title, $prefix = "")
{
?>
<!DOCTYPE html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<html lang="sk-SK">
    <head>
        <title><?php echo $title ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="fll, lego, letna liga">
        <meta name="author" content="Chaos">
        <link rel="icon" href="<?php echo $prefix ?>favicon.ico" type="image/x-icon">
        <link type="text/css" href="<?php echo $prefix ?>styles.css" rel="stylesheet">

    </head>

    <body>
<?php
}

function get_login_form(){
?>
    <form id="login-form" action="http://kempelen.ii.fmph.uniba.sk/letnaliga/index.php/auth/login" method="post" accept-charset="utf-8">
        <table>
            <tr>
                <td><p style="margin-bottom: 0; margin-top: 0; font-weight: bold; color: #3399ff;">Prihlásenie</p></td>
            </tr>
            <tr>
                <td><label for="mail">E-mailová adresa:</label></td>
                <td><input id="mail" type="text" value="@"></td>
            </tr>
            <tr>
                <td><label for="passwd">Heslo:</label></td>
                <td><input id="passwd" type="password" value=""></td>
            </tr>
            <tr>
                <td><input type="submit" name="submit" value="Prihlásiť sa"></td>
                <td style="text-align: right;"><a href="http://kempelen.ii.fmph.uniba.sk/letnaliga/index.php/auth/register"> Registrácia </a></td>
                <p style="color: green;"></p>	</tr>
        </table>
    </form>

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
        <nav>
        </nav>
    <?php
}

function page_footer()
{
    ?>
    </body>
</html>
    <?php
}

function db_connect() {
    if ($link = mysqli_connect('localhost', 'letnaliga', 'nedavajteheslodosvn')) {
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

function show_table($year) {
    error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
    $upper = array('Meno tímu');
    $missions = array();

    if ($link = db_connect()) {
        $sql = "SELECT id, name FROM missions WHERE date_format(end,'%Y') = $year AND start < now() AND end < now() AND resulted = 1 ORDER BY end ASC";
        $result = mysqli_query($link, $sql);
        $num = 0;
        while ($row = mysqli_fetch_array($result)) {
            $num = $num + 1;
            array_push($upper, $num);
            array_push($missions, $row['id']);
        }
    } else {
        echo "Nepodarilo sa spojiť s databázovým serverom!";
    }

    array_push($upper, "Spolu");

    $users = array();

    if ($link = db_connect()) {
        $sql = "SELECT id FROM users WHERE type = '0' ORDER BY name ASC";
        $result = mysqli_query($link, $sql);
        $num = 0;
        while ($row = mysqli_fetch_array($result)) {
            array_push($users, $row['id']);
        }
    } else {
        echo "Nepodarilo sa spojiť s databázovým serverom!";
    }

    $solutions = array();

    foreach ($users as $user) {
        array_push($solutions, array($user));
        foreach ($missions as $mission) {
            if ($link = db_connect()) {
                $sql = "SELECT id FROM solutions WHERE $user = uid AND $mission = mid";
                $result = mysqli_query($link, $sql);
                if ($row = mysqli_fetch_array($result)) {
                    array_push($solutions[count($solutions)-1], $row['id']);
                } else {
                    array_push($solutions[count($solutions)-1], '');
                }
            } else {
                echo "Nepodarilo sa spojiť s databázovým serverom!";
            }
        }
    }

    $wins = array();

    foreach ($solutions as $solutionrow) {
        array_push($wins, array());
        foreach ($solutionrow as $solution) {
            if ($link = db_connect()) {
                $sql = "SELECT win FROM solutions WHERE $solution = id";
                $result = mysqli_query($link, $sql);
                if ($row = mysqli_fetch_array($result)) {
                    array_push($wins[count($wins)-1], $row['win']);
                } else {
                    array_push($wins[count($wins)-1], '');
                }
            } else {
                echo "Nepodarilo sa spojiť s databázovým serverom!";
            }
        }
    }

    $points = array();

    foreach ($solutions as $solutionrow) {
        array_push($points, array());
        $num = 0;
        $sumpoint = 0;
        foreach ($solutionrow as $solution) {
            if ($num == 0) {
                $num = 1;
                if ($link = db_connect()) {
                    $sql = "SELECT name FROM users WHERE $solution = ID";
                    $result = mysqli_query($link, $sql);
                    while ($row = mysqli_fetch_array($result)) {
                        array_push($points[count($points)-1], $row['name']);
                    }
                } else {
                    echo "Nepodarilo sa spojiť s databázovým serverom!";
                }
            } else {
                $point = 0;
                $count = 0;
                if ($link = db_connect()) {
                    $sql = "SELECT points FROM results WHERE $solution = SID";
                    $result = mysqli_query($link, $sql);
                    while ($row = mysqli_fetch_array($result)) {
                        $point = $point + $row['points'];
                        $count = $count + 1;
                    }
                    $point = $point / $count;
                    $sumpoint = $sumpoint + $point;
                    $point = round($point, 2);
                    array_push($points[count($points)-1], $point);
                } else {
                    echo "Nepodarilo sa spojiť s databázovým serverom!";
                }
            }
        }
        $sumpoint = round($sumpoint, 2);
        array_push($points[count($points)-1], $sumpoint);
    }

    $sort = array();

    foreach ($points as $key => $row) {
        $sort[$key] = $row[count($row)-1];
    }
    array_multisort($sort, SORT_DESC, $points, $wins);



    $result = '
		<tr style="font-weight: bold; background-color: #ff6600; border-bottom: 1px solid black;">';
    foreach ($upper as $item) {
        $result .= '<td>'.$item.'</td>';
    }
    $result .= '</tr>';
    for ($x = 0; $x < count($points); $x++) {
        if ($points[$x][count($row)-1] > 0) {
            $result .= "<tr style='border-top: 1px solid black;'>";
            for ($y = 0; $y < count($points[$x]); $y++) {
                if ($points[$x][$y] != '') {
                    if (($y > 0) && ($wins[$x][$y] == 1)) {
                        $result .= "<td style='background-color: #00ff3f;'>".$points[$x][$y]."</td>";
                    } else {
                        if ($y == 0) {
                            $result .= "<td style='border-right: 1px solid black; font-weight: bold;'>".$points[$x][$y]."</td>";
                        } elseif($y == count($points[$x]) - 1) {
                            $result .= "<td style='border-left: 1px solid black; font-weight: bold;'>".$points[$x][$y]."</td>";
                        } else {
                            $result .= "<td>".$points[$x][$y]."</td>";
                        }
                    }
                } else {
                    $result .= "<td>-</td>";
                }
            }
            $result .= "</tr>";
        }
    }

    return $result;
}

?>