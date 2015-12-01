<?php
session_start();
//$_SESSION["id"]=1;
require_once(dirname(__FILE__)."/includes/functions.php");
page_head("Letná liga FLL - Pridanie riešenia");
page_nav();

if (!isset($_GET['aid'])) die("Nie je vybrane zadanie!");
if (!isset($_SESSION["id"])) die("Nie si prihlásený!");

function connect() {
	$servername = "...";
	$username = "...";
	$password = "...";

	$conn = mysqli_connect($servername, $username, $password);

	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	echo "Connected successfully <br>";
	mysqli_select_db($conn,"letna_liga_test2");
	return $conn;
}

$sql_get_solution = "SELECT * FROM solutions s, contexts c WHERE s.context_id = c.context_id AND s.assignment_id = ".$_GET["aid"]." AND c.user_id = ".$_SESSION["id"];

$conn = connect();

$solution = mysqli_query($conn,$sql_get_solution);

if (mysqli_num_rows($solution) == 0) {
	mysqli_query($conn,"INSERT INTO contexts (user_id) VALUES (".$_SESSION["id"].")");
	$cid = mysqli_insert_id($conn);
	mysqli_query($conn,"INSERT INTO solutions (context_id,assignment_id) VALUES (".$cid.",".$_GET["aid"].")");
	mysqli_query($conn,"INSERT INTO programs (location_id) VALUES (".$cid.")");
	$solution = mysqli_query($conn,$sql_get_solution);
}
$zaznam = mysqli_fetch_array($solution);

if (isset($_POST['textPopis'])) {
	if (mysqli_query($conn,"UPDATE solutions SET text = \"".$_POST['textPopis']."\" WHERE context_id = ".$zaznam["context_id"])) {
		echo "[OK] Uloženie textu <br>";
	} else {
		echo "[ERROR] Uloženie textu: ". mysqli_error($conn)."<br>";
	}
}

if (isset($_FILES['uploadedImages'])) {
	$myFile = $_FILES['uploadedImages'];
	$fileCount = count($myFile["name"]);
	if ($fileCount != 0 && $myFile["name"][0] != "") {
		for ($i = 0; $i < $fileCount; $i++) {
			$subor = $myFile['name'][$i];
			$ext = substr($subor, strrpos($subor, '.') + 1);
			if (checkUploadImage($ext,$myFile["type"][$i],$myFile["size"][$i]))
			{
				if (mysqli_query($conn,"INSERT INTO images (location_id) VALUES (".$zaznam["context_id"].")")) {
					$target_file = "images/".mysqli_insert_id($conn).".".$ext;
					if (move_uploaded_file($myFile["tmp_name"][$i], $target_file)) {
						echo "[OK] Nahratie Obrázka: ".$subor."<br>";
					} else {
						mysqli_query($conn,"DELETE FROM images WHERE image_id = ".mysqli_insert_id($conn));
						echo "[ERROR] Problém s uložením na server: ".$subor."<br>";
					}
				} else {
					echo "[ERROR] Problém s uložením do databázy: ".$subor." ".mysqli_error($conn)."<br>";
				}		
			} else {
				echo "[ERROR] Nahratie Obrázka: ".$subor." Nahrať je možné len súbory s príponou jpg, png alebo gif s veľkosťou menšou ako 10MB <br>";
			}
		}
	}
}

if (isset($_POST['textVideo']) && $_POST['textVideo'] != "" ) {
	mysqli_query($conn,"DELETE FROM videos WHERE location_id = ".$zaznam["context_id"]);
	$pattern = '/[;," "\n]/';
	$pole = preg_split( $pattern, $_POST['textVideo'] );
	for ($i = 0; $i < count($pole); $i++) {
		if (strlen($pole[$i]) > 11) {
			$video = substr(trim($pole[$i]), -11);
			if (mysqli_query($conn,"INSERT INTO videos (location_id,link) VALUES (".$zaznam["context_id"].",\"".$video."\")")) {
				echo "[OK] Video ".$video." <br>";
			} else {
				echo "[ERROR] Video: chyba pri vkladaní do databázy ".$video." <br>".mysqli_error($conn);
			}
		}		
	}
}

if (isset($_FILES['uploadedFiles'])) {
	$myFile = $_FILES['uploadedFiles'];
	$fileCount = count($myFile["name"]);
	if ($fileCount != 0 && $myFile["name"][0] != "") {
		if ($fileCount == 1  && pathinfo($myFile["name"][0], PATHINFO_EXTENSION) == "zip") {
			if (checkUploadFile($myFile["size"][0]))
			{
				$target_file = "files/".$zaznam["context_id"].".zip";
				if (move_uploaded_file($myFile["tmp_name"][0], $target_file)) {
					echo "[OK] Nahratie súboru <br>";
				} else {
					echo "[ERROR] Nahranie súborov: Problém s uložením na server: <br>";
				}
			} else {
				echo "[ERROR] Nahranie súborov: Celková veľkosť súborov je viac ako 10MB <br>";
			}
		} else {
			$ok = True;
			$vel = 0;
			$zip = new ZipArchive;
			if ($zip->open("files/".$zaznam["context_id"].".zip",ZipArchive::OVERWRITE)) {		
				for ($i=0; $i<$fileCount; $i++) {
					$subor = $myFile["name"][$i];
					$vel += $myFile["size"][$i];
					if (!checkUploadFile($myFile["size"][$i]))
					{
						$ok = False;
						break;
					}
				}
				if ($ok && ($vel < 10000000)) {
					for ($i=0; $i<$fileCount; $i++) {
						$zip->addFile($myFile['tmp_name'][$i],$myFile['name'][$i]);
					}
					echo "[OK] Nahranie súborov <br>";
				} else {
					echo "[ERROR] Nahranie súborov: Celková veľkosť súborov je viac ako 10MB <br>";
				}
				$zip->close();
			}
		}

	}
}

function checkUploadImage($ext, $typ, $vel)
{
	if (( (($ext == "jpg") && ($typ == "image/jpeg")) ||
	     (($ext == "gif") && ($typ == "image/gif"))   ||
	     (($ext == "png") && ($typ == "image/png")) ) && ($vel < 10000000))
	{
		return True;
	}
	else
	{
		return False;
	}
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
$zaznam = mysqli_fetch_array(mysqli_query($conn,$sql_get_solution));
mysqli_close($conn);
?>
	<div id="content">
		
		<form name="form1" enctype="multipart/form-data" method="POST" action="">
			<h2> Popis riešenia </h2>
			<textarea name="textPopis" cols="80" rows="10" ><?php echo $zaznam["text"] ?></textarea>
			
			<h2> Obrázky k riešeniu </h2>
			<input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
			Vyber Obrázok: <input type="file" name="uploadedImages[]"  multiple />
			
			<h2> Videá k riešeniu </h2>
			<textarea name="textVideo" cols="80" rows="3" ></textarea>
			
			<h2> Program </h2>
			Vyber súbor: <input type="file" name="uploadedFiles[]" multiple />
			
			<br>
			<input type="submit" value="OK" id="upload" />
		</form>

	</div>

<?php
page_footer();
/*includes/upl.php?cid=<?php echo $zaznam["context_id"]; ?>
			
*/
?>