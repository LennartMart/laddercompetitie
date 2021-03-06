<?php
('_JEXEC') or die; 
require_once(__DIR__ . '/../Repository/WedstrijdRepository.php');

class WedstrijdManager {
    private $_wedstrijdRepository;


    function __construct()
    {
        $this->_wedstrijdRepository = new WedstrijdRepository();

    }

    public function get($wedstrijd_id){
        return $this->_wedstrijdRepository->get($wedstrijd_id);
    }

    public function vulIn($wedstrijd_id, $spelerThuis_set1, $spelerThuis_set2, $spelerThuis_set3, $spelerUit_set1,
        $spelerUit_set2, $spelerUit_set3, $ingevuld_door, $datum){
        $errors = Array();
        if($spelerThuis_set1 == "" || $spelerUit_set1 == "")
        {
            $errors[] = "Onvolledige score voor set 1!";
        }
        if($spelerThuis_set2 == "" || $spelerUit_set2 == "")
        {
            $errors[] = "Onvolledige score voor set 2!";
        }
        if(($spelerThuis_set3 != "" && $spelerUit_set3 == "") || ($spelerThuis_set3 == "" && $spelerUit_set3 != ""))
        {
            $errors[] = "Onvolledige score voor set 3!";
        }
        if(($spelerThuis_set1 >= 21 && $spelerUit_set1 != $spelerThuis_set1 -2) && ($spelerUit_set1 >= 21 && $spelerThuis_set1 != $spelerUit_set1 -2))
        {
            $errors[] = "Geen duidelijke winnaar voor set 1!";
        }
        if(($spelerThuis_set2 >= 21 && $spelerUit_set2 != $spelerThuis_set2 -2) && ($spelerUit_set2 >= 21 && $spelerThuis_set2 != $spelerUit_set2 -2))
        {
            $errors[] = "Geen duidelijke winnaar voor set 2!";
        }
        if($spelerThuis_set3 != "" && $spelerUit_set3 != "")
        {
            if(($spelerThuis_set3 >= 21 && $spelerUit_set3 != $spelerThuis_set3 -2) && ($spelerUit_set3 >= 21 && $spelerThuis_set3 != $spelerUit_set3 -2))
            {
                $errors[] = "Geen duidelijke winnaar voor set 3!";
            }
        }
        if(empty($errors))
        {
            $spelerThuis_punten = 0;
            $spelerUit_punten = 0;
            //set per set
            $this->calculateSetWinnaar($spelerThuis_set1, $spelerUit_set1, $spelerThuis_punten, $spelerUit_punten);
            $this->calculateSetWinnaar($spelerThuis_set2, $spelerUit_set2, $spelerThuis_punten, $spelerUit_punten);
            if($spelerThuis_set3 != "" && $spelerUit_set3 != "")
            {
                $this->calculateSetWinnaar($spelerThuis_set3, $spelerUit_set3, $spelerThuis_punten, $spelerUit_punten);
            }
            $this->_wedstrijdRepository->vulIn($wedstrijd_id, $spelerThuis_set1, $spelerThuis_set2, $spelerThuis_set3, $spelerThuis_punten, $spelerUit_set1,
                $spelerUit_set2, $spelerUit_set3, $spelerUit_punten, $ingevuld_door, $datum);
        }
        return $errors;
    }

    private function calculateSetWinnaar($thuisSet, $uitSet, &$spelerThuis_punten, &$spelerUit_punten){
        if($thuisSet > $uitSet) {
            $spelerThuis_punten++;
        } else {
            $spelerUit_punten++;
        }     
    }

}