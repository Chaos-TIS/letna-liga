<?php
include 'includes/functions.php';

function meno($m) {
    $m = addslashes(strip_tags(trim($m)));
    if ($link = db_connect())
    {
    $sql = "SELECT t.name
    FROM teams as t 
    WHERE t.name = '".$m."'";
    $result = mysqli_query($link,$sql);  
        if (mysqli_num_rows($result) == 0 )
        {
        return true;
        }
        else
        {
        echo "Zadané meno sa nachádza v databáze.";
        return false;
        }
    }
}

function email($e) {
    $e = addslashes(strip_tags(trim($e)));
    if ($link = db_connect())
    {
    $sql = "SELECT u.mail
    FROM users as u 
    WHERE u.mail = '".$e."'";
    $result = mysqli_query($link,$sql);  
        if (mysqli_num_rows($result) == 0 )
        {
        return true;
        }
        else
        {
        echo "Zadaný email sa nachádza v databáze.";
        return false;
        }
    }
}

function validate_name($n) {
	$m = addslashes(strip_tags(trim($n)));
	return (strlen($n) > 0);
}

function validate_pass($p1,$p2) {
	if ($p1 != $p2)
	{
		echo "Zadané heslá sa nezhodujú.";
		return false;
	}else{
		return true;
	}
}

function validate_mail($e){
	
	if (empty($e)) {
    echo "Zadaj email.";
    return false;
  } else {
    // check if e-mail address is well-formed
    if (!filter_var($e, FILTER_VALIDATE_EMAIL)) {
      echo "Zlý formát emailu."; 
      return false;
    }
  }
  return true;

}

function required_pass($p) {
	if (empty($p)){
		echo "Zadaj heslo.";
		return false;
	}
	return true;
}








?>