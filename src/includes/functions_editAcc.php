<?php
include 'includes/functions_reg.php';

function zmaz_acc($nazov) {
    if ($link = db_connect()) {
        $sql = "DELETE FROM users WHERE user_id='" . $nazov . "'";
//      echo "sql = $sql <br>";
        $result = mysqli_query($link,$sql); // vykonaj dopyt
        if ($result) {
            // dopyt sa podarilo vykonať
            echo '<p><strong>Účet bol zmazaný</strong></p>';
        } else {
            // dopyt sa NEpodarilo vykonať!
            echo '<p class="chyba">Účet sa NEpodarilo zmazať z databázy</p>';
        }
        mysqli_close($link);
    } else {
        // NEpodarilo sa spojiť s databázovým serverom!
        echo '<p class="chyba">NEpodarilo sa spojiť s databázovým serverom</p>';
    }
}

function set_jury($nazov) {
    if ($link = db_connect()) {
        $sql = "UPDATE organisators as o SET validated = '1'  WHERE o.user_id='" . $nazov . "'";
//      echo "sql = $sql <br>";
        $result = mysqli_query($link,$sql); // vykonaj dopyt
        if ($result) {
            // dopyt sa podarilo vykonať
            echo '<p><strong>Účet bol potvrdený</strong></p>';
        } else {
            // dopyt sa NEpodarilo vykonať!
            echo '<p class="chyba">Účet sa NEpodarilo potvrdiť</p>';
        }
        mysqli_close($link);
    } else {
        // NEpodarilo sa spojiť s databázovým serverom!
        echo '<p class="chyba">NEpodarilo sa spojiť s databázovým serverom</p>';
    }
}

function daj_udaje_uctu($id) {
    if ($link = db_connect()) {
        $sql = "SELECT * FROM users u INNER JOIN teams t ON u.user_id = t.user_id WHERE t.user_id='$id'"; // definuj dopyt
//      echo "sql = $sql <br>";
        $result = mysqli_query($link,$sql); // vykonaj dopyt
        if ($result) {
            // dopyt sa podarilo vykonať
            return mysqli_fetch_assoc($result);
        } else {
            // dopyt sa NEpodarilo vykonať!
            return false;
        }
    } else {
        // NEpodarilo sa spojiť s databázovým serverom!
        return false;
    }
}


class Edit{
    var $error_message;
    var $message;
    function edituj($email,$pas,$name="",$os="",$liga="") 
    {
        $pass = md5($pas);
      if ($link = db_connect())
      { 
        $sql =  "UPDATE users AS u,
teams AS t SET u.mail =  '".$email."',
u.password =  '".$pass."',
t.name =  '".$name."',
t.sk_league ='".$liga."',
t.description='".$os."'
 WHERE u.user_id ='".$_GET['id']."' AND t.user_id ='".$_GET['id']."'";
        $result = mysqli_query($link,$sql); 
        if ($result)
        {
            $this->Handle("Zmeny boli ulozené do DB."); 
            ?>
            <meta http-equiv="refresh" content="4;url=spravaUctov.php?id=0"> 
            <?php      
        }else
        {
            $this->HandleError("Nastala chyba pri editácii.");
            ?>
            <meta http-equiv="refresh" content="4;url=editAcc.php"> 
            <?php
        }
    

        }else{
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


function daj_udaje_rozhodcu($id) {
    if ($link = db_connect()) {
        $sql = "SELECT * FROM users u INNER JOIN organisators o ON u.user_id = o.user_id WHERE o.user_id='$id'"; // definuj dopyt
        $result = mysqli_query($link,$sql); // vykonaj dopyt
        if ($result) {
            // dopyt sa podarilo vykonať
            return mysqli_fetch_assoc($result);
        } else {
            // dopyt sa NEpodarilo vykonať!
            return false;
        }
    } else {
        // NEpodarilo sa spojiť s databázovým serverom!
        return false;
    }
}


class EditJury{
    var $error_message;
    var $message;
    function editujJury($email,$pas) 
    {
        $pass = md5($pas);
      if ($link = db_connect())
      { 
        $sql =  "UPDATE users AS u SET u.mail =  '".$email."',
u.password =  '".$pass."'
 WHERE u.user_id ='".$_GET['id']."'";
        $result = mysqli_query($link,$sql); 
        if ($result)
        {
            $this->Handle("Zmeny boli ulozené do DB."); 
            ?>
            <meta http-equiv="refresh" content="4;url=spravaUctov.php?id=1"> 
            <?php      
        }else
        {
            $this->HandleError("Nastala chyba pri editácii.");
            ?>
            <meta http-equiv="refresh" content="4;url=editAcc.php"> 
            <?php
        }
    

        }else{
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