<?php
class Speler
{
    public $id;
    public $voornaam;
    public $naam;
    public $geslacht;
    public $jeugd;
    public $klassement;
    public $is_lid;
    public $is_veteraan;

    public $wedstrijden;

    //Calculated Fields
    public $matchen_gespeeld;
    public $matchen_gewonnen;
    public $matchen_verloren;
    public $punten;

    public function vulOp($data){
        $this->id = $data['speler_id'];
        $this->voornaam = $data['voornaam'];
        $this->naam = $data['naam'];
        $this->geslacht = $data['geslacht'];
        $this->jeugd = $data['jeugd'];
        $this->klassement = $data['klassement'];
        $this->is_lid = $data['is_lid'];
        $this->is_veteraan = $data['is_veteraan'];
        $this->matchen_gespeeld = 0;
        $this->matchen_verloren = 0;
        $this->matchen_gewonnen = 0;
        $this->punten = 0;
        $this->wedstrijden = [];
    }
}
