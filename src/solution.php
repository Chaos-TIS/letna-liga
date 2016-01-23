<?php
require_once(dirname(__FILE__)."/includes/functions.php");

page_head("Letn� liga FLL");
page_nav();
if (!isset($_SESSION['loggedUser']))
            get_login_form();
        else
            get_logout_button();
$id = (integer)$_GET["id"] ;
$teamId = (integer) $_GET["tid"];
if($link = db_connect()){
	$_SESSION['solution'] = new Solution($link,$id,Team::getFromDatabaseByID($link,$teamId),$_SESSION['asignment']);
}
if(isset($_SESSION['solution'])){
?>
  <div id="content">
<?php
  $_SESSION['solution']->getPreviewHtml();
  ?>
    <table>
    <tr>
    <?php
    if($link= db_connect()){
    
      $sql_get_images = "SELECT * FROM images i WHERE i.context_id = ".$id;
  		$images = mysqli_query($link,$sql_get_images);
  		if ($images != false) {  
  		    while ($images_row = mysqli_fetch_assoc($images)) {
  		      
  		      $subor = "attachments/solutions/".$_SESSION['solution']->getId()."/images/".$images_row['image_id'].substr($images_row['original_name'],-4); 
            ?>
              <td><a class="fancybox" rel="group" href="<?php echo $subor; ?>"><img src=<?php $subor ?>, width="100", width="100") ?> </a></td>
            <?php
          }
        }
      ?>
      </tr>
      </table>
      <?php
      $sql_get_video = "SELECT * FROM videos v WHERE v.context_id = ".$id;
  		$videos = mysqli_query($link,$sql_get_video);
  		if ($videos != false) { 
        while ($videos_row = mysqli_fetch_assoc($videos)) { 
          $linka = "http://www.youtube.com/embed/".$videos_row['link'];
          
    		?>
    		  <iframe width="500" height="375" src="<?php echo $link; ?>" frameborder="0" allowfullscreen></iframe> <br>
    		<?php
        }
      }
  }
}
?>
<!-- Add jQuery library -->
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<!-- Add fancyBox -->
<link rel="stylesheet" href="/includes/source/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="/includes/source/jquery.fancybox.pack.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$(".fancybox").fancybox();
	});
</script>
<?php
page_footer()
?>