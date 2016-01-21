<?php
include 'includes/functions.php';
function set_date($contextid,$start_dat,$end_dat) {
    if ($link = db_connect()) {
        $sql = "UPDATE assignments as a SET begin = '".$start_dat."' , end='".$end_dat."'  WHERE a.context_id='" . $contextid . "'";
//      echo "sql = $sql <br>";
        $result = mysqli_query($link,$sql); // vykonaj dopyt
        if ($result) {
            // dopyt sa podarilo vykonať
            echo '<p><strong>Datum bol zmeneny</strong></p>';
        } else {
            // dopyt sa NEpodarilo vykonať!
            echo '<p class="chyba">Datum sa NEpodarilo zmenit</p>';
        }
        mysqli_close($link);
    } else {
        // NEpodarilo sa spojiť s databázovým serverom!
        echo '<p class="chyba">NEpodarilo sa spojiť s databázovým serverom</p>';
    }
}
?>