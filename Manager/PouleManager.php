<?php

require_once(__DIR__ . '/../Repository/PouleRepository.php');

class PouleManager {
    private $_pouleRepository;


    function __construct()
    {
        $this->_pouleRepository = new PouleRepository();

    }

    public function insert($ronde_id, $naam) {
        return $this->_pouleRepository->insert($ronde_id, $naam);
    }

    public function addSpelerToPoule($speler_id, $poule_id){
        return $this->_pouleRepository->addSpelerToPoule($speler_id, $poule_id);
    }
}