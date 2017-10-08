<?php
require_once(__DIR__ . '/../Repository/SpelerRepository.php');
require_once(__DIR__ . '/../Repository/WedstrijdRepository.php');
class SpelerManager {
    private $_wedstrijdRepository;
    private $_spelerRepository;
    
    function __construct()
    {
        $this->_wedstrijdRepository = new WedstrijdRepository();
        $this->_spelerRepository = new SpelerRepository();

    }
    public function getSpeler($speler_id){

        //Bepalen - huidig seizoen TODO
        $seizoen_id = '1';
        $speler = $this->_spelerRepository->get($speler_id);
        $speler->wedstrijden = $this->_wedstrijdRepository->getBySpelerAndSeizoen($speler_id, $seizoen_id);
        $this->calculateStats($speler);
        return $speler;

    }

    public function getAll(){
        $spelers =  $this->_spelerRepository->getAll();
        return $spelers;
    }

    private function calculateStats($speler){
        foreach ($speler->wedstrijden as $wedstrijd_id => $wedstrijd) {
            if($wedstrijd->ingevuld){
                $speler->matchen_gespeeld++;
                if($wedstrijd->spelerThuis_id == $speler->id){
                    if($wedstrijd->spelerThuis_punten > $wedstrijd->spelerUit_punten){
                        $speler->matchen_gewonnen++;
                    } else {
                        $speler->matchen_verloren++;
                    }
                } else {
                    if($wedstrijd->spelerThuis_punten > $wedstrijd->spelerUit_punten){
                        $speler->matchen_verloren++;
                    } else {
                        $speler->matchen_gewonnen++;
                    } 
                }
            }
        }
    }
}