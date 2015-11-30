<?php 
function __autoload($class_name) {
    include "classes/$class_name.php";
}
require_once("includes/functions.php");
page_head("Letná liga FLL");
page_nav();
if (!isset($_SESSION['loggedUser']))
            get_login_form();
        else
            get_logout_button();
?>
<div id="content">
<?php

$id = (integer)$_GET["id"] ;
$rok = $_GET["r"];
if($link = db_connect()){
  $sql = "SELECT * FROM CONTEXTS c INNER JOIN ASSIGNMENTS a ON (a.context_id = c.context_id) WHERE a.year = ".$rok." ORDER BY begin ASC";
  $result = mysqli_query($link, $sql);
	if($result){
  	 $por=1 ;
  	while ($row = mysqli_fetch_assoc($result)) {
  	   
  	   if($por==$id)  {  
  	        $assignmentid = $row['context_id'];
  	        $name =  $row['text_id_name'];
  	        $deadline=$row['end']; 
            $sql2 = "SELECT * FROM texts";
            $result2 = mysqli_query($link, $sql2);
            if($result2){
            	while ($row2 = mysqli_fetch_assoc($result2)) {
    	         if($row2['text_id']==$row['text_id_name'])  {  
                	?>
      	          <h2> <?php echo $row2['sk']; ?> </h2>   
                  <strong>Riešenie možno odovzdávať do:  <?php  echo $row['end']?></strong>  
                  <?php
                }
                if($row2['text_id']==$row['text_id_description'])  {  
                	?>
      	          <div> <?php echo $row2['sk']; ?> </div>  
                    
                  <?php
                  
                }
              }
            }
        }
  	    $por=$por+1;
     }
    	
  }
}
?>
<h3>Riešenia:</h3>
<ul>
<?php
   if(Date("Y-m-d H:i:s")>$deadline){
    $sql = "SELECT * FROM solutions WHERE assignment_id=$assignmentid";
    $result = mysqli_query($link, $sql);
  	if($result){
  	 while ($row = mysqli_fetch_assoc($result)) {
  	   $team = $row['context_id'];
  	   $sql2 = "SELECT * FROM contexts WHERE context_id=$team limit 1";
        $result2 = mysqli_query($link, $sql2);
  	     if($result2){
  	     while ($row2 = mysqli_fetch_assoc($result2)) {
             $user=$row2['user_id'];
             $sql3 = "SELECT * FROM teams WHERE user_id=$user limit 1 ";
             $result3 = mysqli_query($link, $sql3);
  	         if($result3){
    	         while ($row3 = mysqli_fetch_assoc($result3)) {
    	              ?>
                    <li><a href=""> <?php echo $row3['name']; ?> </a> </li>    
                    <?php
               }
             }
         }
        }  
     }
    }   
  }
?>
</ul>  
</div> 
<?php
page_footer()
?>