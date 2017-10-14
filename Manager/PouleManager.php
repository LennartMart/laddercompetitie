<?php
('_JEXEC') or die;    
require_once(__DIR__ . '/../Repository/PouleRepository.php');
require_once(__DIR__ . '/../Repository/WedstrijdRepository.php');
require_once(__DIR__ . '/../Repository/SpelerRepository.php');
class PouleManager {
    private $_pouleRepository;


    function __construct()
    {
        $this->_pouleRepository = new PouleRepository();
        $this->_wedstrijdRepository = new WedstrijdRepository();
        $this->_spelerRepository = new SpelerRepository();
    }

    public function insert($ronde_id, $naam) {
        return $this->_pouleRepository->insert($ronde_id, $naam);
    }

    public function addSpelerToPoule($speler_id, $poule_id){
        return $this->_pouleRepository->addSpelerToPoule($speler_id, $poule_id);
    }
    public function getByRondeId($ronde_id){
        return $this->_pouleRepository->getByRondeId($ronde_id);
    }
    public function getAll(){
        return $this->_pouleRepository->getAll();
    }
    
    public function getRanking($poule_id){
        $poule = $this->_pouleRepository->getById($poule_id);
        if(isset($poule->id)){
            $poule = $this->_wedstrijdRepository->getByPouleId($poule);
            $poule = $this->_spelerRepository->getByPouleId($poule);
        }
        return $poule;
    }
}