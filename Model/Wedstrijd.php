<?php
('_JEXEC') or die; 
class Wedstrijd
{
    public $id;
    public $spelerThuis_id;
    public $spelerThuis_voornaam;
    public $spelerThuis_naam;
    public $spelerThuis_handicap;
    public $spelerThuis_set1;
    public $spelerThuis_set2;
    public $spelerThuis_set3;
    public $spelerThuis_punten;

    public $spelerUit_id;
    public $spelerUit_voornaam;
    public $spelerUit_naam;
    public $spelerUit_handicap;
    public $spelerUit_set1;
    public $spelerUit_set2;
    public $spelerUit_set3;
    public $spelerUit_punten;

    public $ingevuld;
    public $ingevuld_door;
    public $ingevuld_op;

    //optionele velden
    public $ronde_id;
    public $ronde_naam;


    public function vulOp($data){
        $this->id = $data['wedstrijd_id'];
        $this->spelerThuis_id = $data['spelerThuis_id'];
        $this->spelerUit_id = $data['spelerUit_id'];

        $this->spelerThuis_set1 = $data['spelerThuis_set1'];
        $this->spelerThuis_set2 = $data['spelerThuis_set2'];
        $this->spelerThuis_set3 = $data['spelerThuis_set3'];
        $this->spelerUit_set1 = $data['spelerUit_set1'];
        $this->spelerUit_set2 = $data['spelerUit_set2'];
        $this->spelerUit_set3 = $data['spelerUit_set3'];

        $this->spelerThuis_punten = $data['spelerThuis_punten'];
        $this->spelerUit_punten = $data['spelerUit_punten'];
        $this->spelerThuis_voornaam = $data['spelerThuis_voornaam'];
        $this->spelerThuis_naam = $data['spelerThuis_naam'];
        $this->spelerUit_voornaam = $data['spelerUit_voornaam'];
        $this->spelerUit_naam = $data['spelerUit_naam'];
        $this->spelerThuis_handicap = $data['spelerThuis_handicap'];
        $this->spelerUit_handicap = $data['spelerUit_handicap'];
        $this->ingevuld = $data['ingevuld'];
        $this->ingevuld_door = $data['ingevuld_door'];
        $this->ingevuld_op = $data['ingevuld_op'];
    }
}