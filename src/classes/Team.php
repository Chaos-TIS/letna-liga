<?php
class Team extends User {
    protected $name;
    protected $description;
    protected $skLeague;

    public function __construct($id, $mail, $name, $description, $skLeague){
        parent::__construct($id, $mail);
        $this->name = $name;
        $this->description = $description;
        $this->skLeague = $skLeague;
    }

    public function getSolutionOf($assignment){
    //TODO
    }
}
?>