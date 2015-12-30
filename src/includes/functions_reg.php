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


class Reg{
    var $error_message;
    var $message;
    function registruj($email,$pass,$type,$name="",$os="",$liga="") 
    {
      if ($link = db_connect())
      { 
        $sql =  "INSERT INTO users(mail,password) VALUES('".$email."','".$pass."')";
        $result = mysqli_query($link,$sql); 
        if ($result)
        {
            if ($type == 0 )
            {  
                $sql =  "INSERT INTO teams(user_id,name,description,sk_league) SELECT u.user_id ,'" .$name."','" .$os."','" .$liga."'
                FROM users u
                WHERE LOWER(u.mail) = '".$email."'";    
                $result = mysqli_query($link,$sql);
                if($result)
                {
                    $this->Handle("Bol ste uspesne zaregistrovany."); 
                    ?>
                    <meta http-equiv="refresh" content="4;url=index.php"> 
                    <?php      
                }
            }
            else
            {
                $sql =  "INSERT INTO organisators(user_id,admin,validated) SELECT u.user_id ,0,0
                FROM users u
                WHERE LOWER(u.mail) = '".$email."'";
                $result = mysqli_query($link,$sql);
                if($result)
                { 
                    $this->Handle("Bol ste uspesne zaregistrovany."); 
                    ?>
                    <meta http-equiv="refresh" content="4;url=index.php"> 
                    <?php
                }
            }
        }
        else
        {
            $this->HandleError("Nastala chyba pri registracii."); 
            ?>
            <meta http-equiv="refresh" content="4;url=registracia.php"> 
            <?php
        }
    }
    else
        {
            $this->HandleError("NEpodarilo sa spojiť s databázovým serverom!");
        }

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

    function GetMessage()
    {
        if(empty($this->message))
        {
            return '';
        }
        $msg = nl2br(htmlentities($this->message,ENT_COMPAT,"UTF-8"));
        return $msg;
    }       
    
    function HandleError($err)
    {
        $this->error_message .= $err."\r\n";
    }

    function Handle($msg)
    {
        $this->message .= $msg."\r\n";
    }
}









?>