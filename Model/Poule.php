<?php
('_JEXEC') or die; 
class Poule
{
    public $id;
    public $naam;
    public $ronde_id;
    public $wedstrijden;
    public $spelers;

    public function vulOp($data){
        $this->id = $data['poule_id'];
        $this->naam = $data['naam'];
        $this->ronde_id = $data['ronde_id'];
        $this->wedstrijden = [];
        $this->spelers = [];
    }

}