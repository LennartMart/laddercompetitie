<?php
('_JEXEC') or die; 
require_once(__DIR__ . '/../connect.php');
require_once (__DIR__ . '/../Model/Speler.php');

class SpelerRepository
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

    function get($speler_id){
        //Haal wedstrijd op en vul de members in
        $get_query = sprintf("SELECT id as speler_id, * FROM intra_spelers WHERE id = '%s';", 
            $this->db->mysqli->real_escape_string($speler_id));
        $result = $this->db->mysqli->query($get_query);
        if ($result) {
            // fetch the result row.
            $data = $result->fetch_assoc();
            $speler = new Speler();
            $speler->vulOp($row);
            return $speler;
        }
        return new Speler();
    }
    public function getAll(){
        $select_query = "SELECT id as speler_id, s.* FROM intra_spelers s where is_lid = '1' ORDER BY voornaam, naam";
        $result = $this->db->mysqli->query($select_query);
        while($row = $result->fetch_array())
        {
            $speler = new Speler();
            $speler->vulOp($row);
            $spelers[] = $speler;
             
        }        
        return $spelers;
    }
    public function getByRondeId($ronde_id, $poules){
        $select_query = sprintf("SELECT p.id AS poule_id, s.id as speler_id, s.*
        FROM intra_spelers as s
        INNER JOIN intra_enkel_poule_spelers ps ON ps.speler_id = s.id
        INNER JOIN intra_enkel_poule p ON p.id = ps.poule_id
        WHERE p.ronde_id = '%s';",
        $this->db->mysqli->real_escape_string($ronde_id));
        $result = $this->db->mysqli->query($select_query);
        while($row = $result->fetch_array())
        {
            $speler = new Speler();
            $speler->vulOp($row);
            $poules[$row["poule_id"]]->spelers[$speler->id] = $speler;
        }
        return $poules;
    }
    public function getByPouleId($poule){
        $select_query = sprintf("SELECT p.id AS poule_id, s.id as speler_id, s.*
        FROM intra_spelers as s
        INNER JOIN intra_enkel_poule_spelers ps ON ps.speler_id = s.id
        INNER JOIN intra_enkel_poule p ON p.id = ps.poule_id
        WHERE p.id = '%s';",
        $this->db->mysqli->real_escape_string($poule->id));
        $result = $this->db->mysqli->query($select_query);
        while($row = $result->fetch_array())
        {
            $speler = new Speler();
            $speler->vulOp($row);
            $poule->spelers[$speler->id] = $speler;
        }
        return $poule;
    }
}
