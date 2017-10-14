<?php
('_JEXEC') or die; 
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
                    $speler->punten_gewonnen += $wedstrijd->spelerThuis_set1;
                    $speler->punten_gewonnen += $wedstrijd->spelerThuis_set2;
                    $speler->punten_gewonnen += $wedstrijd->spelerThuis_set3;
                    $speler->punten_verloren += $wedstrijd->spelerUit_set1;
                    $speler->punten_verloren += $wedstrijd->spelerUit_set2;
                    $speler->punten_verloren += $wedstrijd->spelerUit_set3;

                    if($wedstrijd->spelerThuis_punten > $wedstrijd->spelerUit_punten){
                        $speler->matchen_gewonnen++;
                    } else {
                        $speler->matchen_verloren++;
                    }
                } else {
                    $speler->punten_gewonnen += $wedstrijd->spelerUit_set1;
                    $speler->punten_gewonnen += $wedstrijd->spelerUit_set2;
                    $speler->punten_gewonnen += $wedstrijd->spelerUit_set3;
                    $speler->punten_verloren += $wedstrijd->spelerThuis_set1;
                    $speler->punten_verloren += $wedstrijd->spelerThuis_set2;
                    $speler->punten_verloren += $wedstrijd->spelerThuis_set3;

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