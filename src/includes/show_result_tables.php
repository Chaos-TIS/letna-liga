<?php
header('Content-type: text/plain; charset=utf-8');
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();
require_once(dirname(__FILE__)."/functions.php");
$sk_table = get_result_table(1, $_GET["year"]);
$open_table = get_result_table(0, $_GET["year"]);
if ($sk_table == "" && $open_table == ""){
    echo "<p class='center' data-trans-key='results-not-available'></p>";
}
else {
    echo $sk_table;
    echo $open_table;
}
?>
    <script>
        dict.translateElement(null, "#results");
    </script>
<?php
?>