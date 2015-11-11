<?php
abstract class Organisator extends User {
    public function __construct($id, $mail){
        parent::__construct($id, $mail);
    }
}
?>