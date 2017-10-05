<?php
require_once(__DIR__ . '/../connect.php');
require_once (__DIR__ . '/../Model/Poule.php');

class PouleRepository
{

    function __construct()
    {
        $this->db = new ConnectionSettings();
        $this->db->connect();
    }

    function __destruct()
    {
        $this->db->close();
    }

    public function insert($ronde_id, $naam){
        $insert_query = sprintf("INSERT INTO intra_enkel_poule
            SET
                ronde_id = '%s',
                naam = '%s';",
            $this->db->mysqli->real_escape_string($ronde_id),
            $this->db->mysqli->real_escape_string($naam));
        if( $this->db->mysqli->query($insert_query) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
 
    public function addSpelerToPoule($speler_id, $poule_id){
        $insert_query = sprintf("INSERT INTO intra_enkel_poule_spelers
            SET
                speler_id = '%s',
                poule_id = '%s';",
            $this->db->mysqli->real_escape_string($speler_id),
            $this->db->mysqli->real_escape_string($poule_id));
        if( $this->db->mysqli->query($insert_query) === TRUE) {
            return true;
        } else {
            return false;
        }
    } 

    public function getByRondeId($ronde_id){
        $select_query = sprintf("SELECT p.id as poule_id, p.*
            FROM intra_enkel_poule as p
            WHERE p.ronde_id = '%s';",
            $this->db->mysqli->real_escape_string($ronde_id));
        $result = $this->db->mysqli->query($select_query);
        $poules = [];
        while($row = $result->fetch_array())
        {
            $poule = new Poule();
            $poule->vulOp($row);
            echo $poule->id;
            $poules[$poule->id] = $poule;
        }
        return $poules;
    }

    public function getPoulesByRondeId($ronde_id){

        //Eerst: basisInfo - enkel de poules zelf
        $poules = $this->getByRondeId($ronde_id);
        
        return $poules;
    }
}
