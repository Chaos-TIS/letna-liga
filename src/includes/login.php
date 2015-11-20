<?php
function __autoload($class_name) {
    include "../classes/$class_name.php";
}

error_reporting(0);
@ini_set('display_errors', 0);
session_start();
require_once("functions.php");
if ($link = db_connect()) {
    $mail = strtolower($_POST['mail']);
    $password = $_POST['password'];
    $sql = "SELECT u.user_id, u.mail, u.password, t.name, t.description, t.sk_league, o.admin, o.validated
            FROM users u
            LEFT OUTER JOIN teams t ON (t.user_id = u.user_id)
            LEFT OUTER JOIN organisators o ON (o.user_id = u.user_id)
            WHERE LOWER(u.mail) = '$mail'";
    $result = mysqli_query($link, $sql);
    if ($row = mysqli_fetch_array($result)) {
        if (md5($password) == $row['password']) {
            if (is_null($row['admin'])) {
                $_SESSION['loggedUser'] = new Team($row['id'], $row['mail'], $row['name'], $row['description'], $row['sk_league']);
            }
            else {
                if (!$row['admin']) {
                    if ($row['validated']) {
                        $_SESSION['loggedUser'] = new Jury($row['id'], $row['mail'], $row['validated']);
                    }
                    else {
                        echo "Tento rozhodcovský účet ešte nebol potvrdený!";
                    }
                }
                else {
                    $_SESSION['loggedUser'] = new Administrator($row['id'], $row['mail']);
                }
            }
        }
        else {
            echo "Zadali ste nesprávne heslo!";
        }
    }
    else {
        echo "Neexistuje účet zaregistrovaný na tento e-mail!";
    }
} else {
    echo "Nepodarilo sa spojiť s databázovým serverom!";
}
die;
?>