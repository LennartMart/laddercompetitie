<?php
('_JEXEC') or die; 
class Ronde
{
    public $id;
    public $naam;
    public $einddatum;
    public $aangemaakt;
    public $startdatum;
    public $poules;

    public function vulOp($data){
        $this->id = $data['ronde_id'];
        $this->naam = $data['naam'];
        $this->einddatum = $data['einddatum'];
        $this->aangemaakt = $data['aangemaakt'];
        $this->startdatum = $data['startdatum'];
        $this->poules = [];
    }
}