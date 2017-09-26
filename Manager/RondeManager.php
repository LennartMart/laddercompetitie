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

    public function genereer($ronde_id) {
        

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
}