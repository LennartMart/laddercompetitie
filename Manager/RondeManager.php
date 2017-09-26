<?php
require_once(__DIR__ . '/../Repository/PouleRepository.php');
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
                combinatieZoeker($spelerIds, array(), 0, sizeof($spelerIds)-1, 0, $combinations);
                foreach($combinations as $combination){
                    $thuisSpeler = $poule->spelers[$combination[0]];
                    $uitSpeler = $poule->spelers[$combination[1]];
                    $thuisSpelerHandicap = 0;
                    $uitSpelerHandicap = 0;
                    $this->calculateHandicap($thuisSpeler, $uitSpeler, $thuisSpelerHandicap, $uitSpelerHandicap);
                    $this->_wedstrijdRepository->insert($poule->id, $thuisSpeler->id, $thuisSpelerHandicap, $uitSpeler->id, $uitSpelerHandicap);
                }
            }
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

    public function getRanking($ronde_id){

        $poules = $this->_pouleRepository->getByRondeId($ronde_id);
        //Nu de overeenkomstige wedstrijden...
        $poules = $this->_wedstrijdRepository->getByRondeId($ronde_id, $poules);
        //en nu de gekoppelde spelers
        $poules = $this->_spelerRepository->getByRondeId($ronde_id, $poules);

        //Bereken de statistieken
        $ranking = [];
        for ($i=0; $i < count($poules); $i++) { 
            $poule = $poules[$i];
            $this->calculateStats($poule);
            $ranking[] = $poule;
        }
        return $ranking;
    }

    private function calculateHandicap($thuisSpeler, $uitSpeler, &$thuisSpelerHandicap, &$uitSpelerHandicap){
        $thuisHandicap = $this->calculateHandicap($thuisSpeler);
        $uitHandicap = $this->calculateHandicap($uitSpeler);
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
            case "D":
                $handicap = 1;
                break;
            case "C2":
                $handicap = 2;
                break;
            case "C1":
                $handicap = 4;
                break;
            case "B2":
                $handicap = 7;
                break;
            case "B1":
                $handicap = 11;
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
       foreach ($poule->wedstrijden as $wedstrijd) {
           if($wedstrijd->ingevuld){
                $poule->spelers[$wedstrijd->spelerThuis_id]->matchen_gespeeld++;
                $poule->spelers[$wedstrijd->spelerThuis_id]->punten += $wedstrijd->spelerThuis_punten;

                $poule->spelers[$wedstrijd->spelerUit_id]->matchen_gespeeld++;
                $poule->spelers[$wedstrijd->spelerUit_id]->punten += $wedstrijd->spelerUit_punten;    

                if($poule->spelers[$wedstrijd->spelerThuis_id]->punten > $poule->spelers[$wedstrijd->spelerUit_id]->punten){
                    $poule->spelers[$wedstrijd->spelerUit_id]->matchen_verloren++;
                    $poule->spelers[$wedstrijd->spelerThuis_id]->matchen_gewonnen++;
                } else {
                    $poule->spelers[$wedstrijd->spelerThuis_id]->matchen_verloren++;
                    $poule->spelers[$wedstrijd->spelerUit_id]->matchen_gewonnen++;                
                }
           }
        }
    }    

    private function combinatieZoeker($array, $data, $start, $end, $index, &$output){
        //Indien index == 2 => Combo gevonden, dump to output
        if($index == 2){
            $output[] = $data;    
        }
        for ($i=$start; $i<=$end && $end-$i+1 >= 2-$index; $i++)
        {
            $data[$index] = $array[$i];
            combinatieZoeker($array, $data, $i+1, $end, $index+1, $output);
        }
    }
}