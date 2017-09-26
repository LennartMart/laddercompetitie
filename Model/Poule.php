<?php
class Poule
{
    public $id;
    public $naam;
    public $wedstrijden;
    public $spelers;

    public function vulOp($data){
        $this->id = $data['poule_id'];
        $this->naam = $data['naam'];
        $this->wedstrijden = [];
        $this->spelers = [];
    }

}