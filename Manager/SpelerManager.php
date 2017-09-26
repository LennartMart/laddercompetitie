<?php
require_once(__DIR__ . '/../Repository/SpelerRepository.php');
require_once(__DIR__ . '/../Repository/WedstrijdRepository.php');
class SpelerManager {

    public function getSpeler($speler_id){

        //Bepalen - huidig seizoen TODO
        $seizoen_id = '1';

        $_spelerRepository = new SpelerRepository();
        $_wedstrijdRepository = new WedstrijdRepository();
        $speler = $_spelerRepository->get($speler_id);
        $speler->wedstrijden = $_wedstrijdRepository->getBySpelerAndSeizoen($speler_id, $seizoen_id);
        $this->calculateStats($speler);
        return $speler;

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