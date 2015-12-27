<?php
include 'includes/functions.php';

class Validate {
    var $error_message;
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
            $this->HandleError("Zadané meno sa nachádza v databáze.");
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
            $this->HandleError("Zadaný email sa nachádza v databáze.");
            return false;
            }
        }
    }

    function validate_name($n) {
    	if (empty($n)) {
            $this->HandleError("Zadaj meno.");
            return false;
        }
        return true;
    }

    function validate_pass($p1,$p2) {
    	if ($p1 != $p2)
    	{
    		$this->HandleError("Zadané heslá sa nezhodujú.");
    		return false;
    	}else{
    		return true;
    	}
    }

    function validate_mail($e){
    	
    	if (empty($e)) {
        $this->HandleError("Zadaj email.");
        return false;
      } else {
        // check if e-mail address is well-formed
        if (!filter_var($e, FILTER_VALIDATE_EMAIL)) {
          $this->HandleError("Zlý formát emailu."); 
          return false;
        }
      }
      return true;

    }

    function required_pass($p) {
    	if (empty($p)){
    		$this->HandleError("Zadaj heslo.");
    		return false;
    	}
    	return true;
    }

    function GetErrorMessage()
    {
        if(empty($this->error_message))
        {
            return '';
        }
        $errormsg = nl2br(htmlentities($this->error_message,ENT_COMPAT,"UTF-8"));
        return $errormsg;
    }    
    //-------Private Helper functions-----------
    
    function HandleError($err)
    {
        $this->error_message .= $err."\r\n";
    }
}








?>