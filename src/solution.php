<?php
require_once(dirname(__FILE__)."/includes/functions.php");

page_head("Letná liga FLL");
page_nav();
if (!isset($_SESSION['loggedUser']))
            get_login_form();
        else
            get_logout_button();
$id = (integer)$_GET["id"] ;

if($link = db_connect()){
	$_SESSION['solution'] = Solution::getFromDatabaseByID($link, $id);
}
if(isset($_SESSION['solution'])){
	if (isset($_GET['comment'])) {
		$comment = $_SESSION['solution']->getComments()[$_GET['comment']];
		if ($_SESSION['loggedUser']->getId() == $comment->getAuthor()->getId()) {
			if (isset($_POST['commentText']) && $_POST['commentText'] != $comment->getTxt()) {
				$comment->setTxt($link, $_POST['commentText']);	
			}
			if (isset($_POST['commentPoints']) && $_POST['commentPoints'] != $comment->getPoints()) {
				$comment->setPoints($link, $_POST['commentPoints']);	
			}
		}
	}	
	
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
              <td><a class="fancybox" rel="group" href="<?php echo $subor; ?>"><img src="<?php echo $subor ?>" width="100", width="100") ?> </a></td>
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
    		  <iframe width="500" height="375" src="<?php echo $linka; ?>" frameborder="0" allowfullscreen></iframe> <br>
    		<?php
        }
      }
  }
}
?>
<!-- Add jQuery library -->
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<!-- Add fancyBox -->
<link rel="stylesheet" href="js/source/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/source/jquery.fancybox.pack.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$(".fancybox").fancybox();
	});
</script>
<?php
page_footer()
?>