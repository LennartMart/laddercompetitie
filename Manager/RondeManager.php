<?php
('_JEXEC') or die; 
require_once(__DIR__ . '/../Repository/PouleRepository.php');
require_once(__DIR__ . '/../Repository/WedstrijdRepository.php');
require_once(__DIR__ . '/../Repository/SpelerRepository.php');
require_once(__DIR__ . '/../Repository/RondeRepository.php');
class RondeManager {

    private $_pouleRepository;
    private $_wedstrijdRepository;
    private $_spelerRepository;
    private $_rondeRepository;

    function __construct()
    {
        $this->_pouleRepository = new PouleRepository();
        $this->_wedstrijdRepository = new WedstrijdRepository();
        $this->_spelerRepository = new SpelerRepository();
        $this->_rondeRepository = new RondeRepository();
    }

    public function addNieuweRonde($naam, $einddatum){
        return $this->_rondeRepository->insert($naam, $einddatum);
    }

    public function genereer($ronde_id) {
        if(!$this->_rondeRepository->checkIfRondeIsAlreadyGenerated($ronde_id)){
            $poules = $this->_pouleRepository->getByRondeId($ronde_id);
            //en nu de gekoppelde spelers
            $poules = $this->_spelerRepository->getByRondeId($ronde_id, $poules);
            foreach ($poules as $poule){
                $spelerIds = array_keys($poule->spelers);
                $combinations = array();
                $this->combinatieZoeker($spelerIds, array(), 0, sizeof($spelerIds)-1, 0, $combinations);
                foreach($combinations as $combination){
                    $thuisSpeler = $poule->spelers[$combination[0]];
                    $uitSpeler = $poule->spelers[$combination[1]];
                    $thuisSpelerHandicap = 0;
                    $uitSpelerHandicap = 0;
                    $this->calculateHandicap($thuisSpeler, $uitSpeler, $thuisSpelerHandicap, $uitSpelerHandicap);
                    $this->_wedstrijdRepository->insert($poule->id, $thuisSpeler->id, $thuisSpelerHandicap, $uitSpeler->id, $uitSpelerHandicap);
                }
            }
            $this->_rondeRepository->setGenerated($ronde_id);
            return true;
        }
        else return false;

    }

    public function getCurrentRanking(){
        $huidigeRonde = $this->_rondeRepository->getHuidigeRonde();
        if(isset($huidigeRonde->id)){
            return $this->getRanking($huidigeRonde->id);
        }
    }

    public function getCurrentPoules(){
        $huidigeRonde = $this->_rondeRepository->getHuidigeRonde();        
        if(isset($huidigeRonde->id)){
            return $this->_pouleRepository->getByRondeId($huidigeRonde->id);
        }
    }      
    public function getRondes(){
        return $this->_rondeRepository->getRondes();
    }
    public function getRanking($ronde_id){

        $poules = $this->_pouleRepository->getByRondeId($ronde_id);
        //Nu de overeenkomstige wedstrijden...
        $poules = $this->_wedstrijdRepository->getByRondeId($ronde_id, $poules);
        //en nu de gekoppelde spelers
        $poules = $this->_spelerRepository->getByRondeId($ronde_id, $poules);

        //Bereken de statistieken
        $ranking = [];
        foreach ($poules as $poule) {
            $poule = $this->calculateStats($poule);
            //verwijderen van assoc array => kendo kan hier niet mee overweg.
            //Waarom assoc array? => calculate stats!
            $poule->spelers = array_values($poule->spelers);
            $ranking[] = $poule;
        }
        return $ranking;
    }

    private function calculateHandicap($thuisSpeler, $uitSpeler, &$thuisSpelerHandicap, &$uitSpelerHandicap){
        $thuisHandicap = $this->calculateHandicapSpeler($thuisSpeler);
        $uitHandicap = $this->calculateHandicapSpeler($uitSpeler);
        $totalHandicap = $thuisHandicap - $uitHandicap;
        if($totalHandicap < 0){
            $thuisSpelerHandicap = $totalHandicap * -1;
        } else if($totalHandicap > 0){
            $uitSpelerHandicap = $totalHandicap;
        }
    }
    private function calculateHandicapSpeler($speler){
        $handicap = 0;
        switch ($speler->klassement) {
            case "Recreant":
                $handicap = 0;
                break;
            case "D":
                $handicap = 6;
                break;
            case "C2":
                $handicap = 8;
                break;
            case "C1":
                $handicap = 10;
                break;
            case "B2":
                $handicap = 12;
                break;
            case "B1":
                $handicap = 14;
                break;
            case "A":
                $handicap = 16;
                break;
        }

        if($speler->geslacht == "Vrouw"){
            $handicap = $handicap - 3;
        }
        return $handicap;
    }
    private function calculateStats($poule){
        if(is_array($poule->wedstrijden)){
            foreach ($poule->wedstrijden as $wedstrijd) {
                if($wedstrijd->ingevuld){
                        $poule->spelers[$wedstrijd->spelerThuis_id]->matchen_gespeeld++;
                        $poule->spelers[$wedstrijd->spelerThuis_id]->punten += $wedstrijd->spelerThuis_punten;

                        $poule->spelers[$wedstrijd->spelerThuis_id]->punten_gewonnen += $wedstrijd->spelerThuis_set1;
                        $poule->spelers[$wedstrijd->spelerThuis_id]->punten_gewonnen += $wedstrijd->spelerThuis_set2;
                        $poule->spelers[$wedstrijd->spelerThuis_id]->punten_gewonnen += $wedstrijd->spelerThuis_set3;
                        $poule->spelers[$wedstrijd->spelerThuis_id]->punten_verloren += $wedstrijd->spelerUit_set1;
                        $poule->spelers[$wedstrijd->spelerThuis_id]->punten_verloren += $wedstrijd->spelerUit_set2;
                        $poule->spelers[$wedstrijd->spelerThuis_id]->punten_verloren += $wedstrijd->spelerUit_set3;

                        $poule->spelers[$wedstrijd->spelerUit_id]->matchen_gespeeld++;
                        $poule->spelers[$wedstrijd->spelerUit_id]->punten += $wedstrijd->spelerUit_punten;

                        $poule->spelers[$wedstrijd->spelerUit_id]->punten_gewonnen += $wedstrijd->spelerUit_set1;
                        $poule->spelers[$wedstrijd->spelerUit_id]->punten_gewonnen += $wedstrijd->spelerUit_set2;
                        $poule->spelers[$wedstrijd->spelerUit_id]->punten_gewonnen += $wedstrijd->spelerUit_set3;
                        $poule->spelers[$wedstrijd->spelerUit_id]->punten_verloren += $wedstrijd->spelerThuis_set1;
                        $poule->spelers[$wedstrijd->spelerUit_id]->punten_verloren += $wedstrijd->spelerThuis_set2;
                        $poule->spelers[$wedstrijd->spelerUit_id]->punten_verloren += $wedstrijd->spelerThuis_set3;

                        if($wedstrijd->spelerThuis_punten > $wedstrijd->spelerUit_punten){
                            $poule->spelers[$wedstrijd->spelerUit_id]->matchen_verloren++;
                            $poule->spelers[$wedstrijd->spelerThuis_id]->matchen_gewonnen++;
                        } else {
                            $poule->spelers[$wedstrijd->spelerThuis_id]->matchen_verloren++;
                            $poule->spelers[$wedstrijd->spelerUit_id]->matchen_gewonnen++;                
                        }
                }
            }
        }
        return $poule;
    }    

    private function combinatieZoeker($array, $data, $start, $end, $index, &$output){
        //Indien index == 2 => Combo gevonden, dump to output
        if($index == 2){
            $output[] = $data;    
        }
        for ($i=$start; $i<=$end && $end-$i+1 >= 2-$index; $i++)
        {
            $data[$index] = $array[$i];
            $this->combinatieZoeker($array, $data, $i+1, $end, $index+1, $output);
        }
    }
}