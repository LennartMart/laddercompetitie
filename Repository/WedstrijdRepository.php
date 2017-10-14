<?php
('_JEXEC') or die; 
require_once (__DIR__ . '/../connect.php');
require_once (__DIR__ . '/../Model/Wedstrijd.php');

class WedstrijdRepository
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
    /**
     * @param int $wedstrijd_id
     */
    public function get($wedstrijd_id)
    {
        //Haal wedstrijd op en vul de members in
        $select_query = sprintf("SELECT p.id AS poule_id, p.naam AS naam, w.id as wedstrijd_id, w.*, 
        s1.naam AS spelerThuis_naam, s1.voornaam AS spelerThuis_voornaam, s2.naam AS spelerUit_naam, s2.voornaam AS spelerUit_voornaam
        FROM intra_enkel_poule as p
        INNER JOIN intra_enkel_wedstrijd w ON w.poule_id = p.id
        INNER JOIN intra_spelers s1 ON s1.id = w.spelerThuis_id
        INNER JOIN intra_spelers s2 ON s2.id = w.spelerUit_id
        WHERE w.id = '%s';",
        $this->db->mysqli->real_escape_string($wedstrijd_id));
        $result = $this->db->mysqli->query($select_query);
        if ($result) {
            // fetch the result row.
            $data = $result->fetch_assoc();
            $wedstrijd = new Wedstrijd();
            $wedstrijd->vulOp($data);
            return $wedstrijd;
        }
        return new Wedstrijd();
    }
    public function insert($poule_id, $spelerThuis_id, $spelerThuis_handicap, $spelerUit_id, $spelerUit_handicap){
        $insert_query = sprintf("INSERT INTO intra_enkel_wedstrijd
            SET
                poule_id = '%s',
                spelerThuis_id = '%s',
                spelerThuis_handicap = '%s',
                spelerUit_id = '%s',
                spelerUit_handicap = '%s';",
            $this->db->mysqli->real_escape_string($poule_id),
            $this->db->mysqli->real_escape_string($spelerThuis_id),
            $this->db->mysqli->real_escape_string($spelerThuis_handicap),
            $this->db->mysqli->real_escape_string($spelerUit_id),
            $this->db->mysqli->real_escape_string($spelerUit_handicap));
        if( $this->db->mysqli->query($insert_query) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function vulIn($wedstrijd_id, $spelerThuis_set1, $spelerThuis_set2, $spelerThuis_set3, $spelerThuis_punten, $spelerUit_set1,
        $spelerUit_set2, $spelerUit_set3, $spelerUit_punten, $ingevuld_door, $datum){
        
        $update_query = sprintf("UPDATE intra_enkel_wedstrijd
            SET                
                spelerThuis_set1 = '%s',
                spelerThuis_set2 = '%s',
                spelerThuis_set3 = '%s',
                spelerThuis_punten = '%s',
                spelerUit_set1 = '%s',
                spelerUit_set2 = '%s',
                spelerUit_set3 = '%s',
                spelerUit_punten = '%s',
                ingevuld_door = '%s',
                ingevuld_op = '%s',
                ingevuld = '1'
            WHERE id = '%s';",            
            $this->db->mysqli->real_escape_string($spelerThuis_set1),
            $this->db->mysqli->real_escape_string($spelerThuis_set2),
            $this->db->mysqli->real_escape_string($spelerThuis_set3),
            $this->db->mysqli->real_escape_string($spelerThuis_punten),
            $this->db->mysqli->real_escape_string($spelerUit_set1),
            $this->db->mysqli->real_escape_string($spelerUit_set2),
            $this->db->mysqli->real_escape_string($spelerUit_set3),
            $this->db->mysqli->real_escape_string($spelerUit_punten),
            $this->db->mysqli->real_escape_string($ingevuld_door),
            $this->db->mysqli->real_escape_string($datum),
            $this->db->mysqli->real_escape_string($wedstrijd_id));
        if( $this->db->mysqli->query($update_query) === TRUE) {
            return true;
        } else {
            return false;
        }   


    }

    public function getBySpelerAndSeizoen($speler_id, $seizoen_id){
        $select_query = sprintf("SELECT r.id AS ronde_id, r.naam AS ronde_naam, w.id as wedstrijd_id, w.*, 
            s1.naam AS spelerThuis_naam, s1.voornaam AS spelerThuis_voornaam, s2.naam AS spelerUit_naam, s2.voornaam AS spelerUit_voornaam
            FROM intra_enkel_wedstrijd w
            INNER JOIN intra_enkel_poule p on p.id = w.poule_id
            INNER JOIN intra_enkel_ronde r on r.id = p.ronde_id
            INNER JOIN intra_spelers s1 ON s1.id = w.spelerThuis
            INNER JOIN intra_spelers s2 ON s2.id = w.spelerUit
            WHERE (s1.id = '%s' OR s2.id = '%s') AND r.seizoen_id = '%s' ;",
            $this->db->mysqli->real_escape_string($speler_id),
            $this->db->mysqli->real_escape_string($speler_id),
            $this->db->mysqli->real_escape_string($seizoen_id));
        $result = $this->db->mysqli->query($query);
        $wedstrijden = [];
        while($row = $result->fetch_array())
        {            
            $wedstrijd = new Wedstrijd();
            $wedstrijd->vulOp($row);
            //Optionele velden invullen
            $wedstrijd->ronde_id = $row->ronde_id;
            $wedstrijd->ronde_naam = $row->ronde_naam;
            
            $wedstrijden[$wedstrijd->id] = $wedstrijd;
        }
    }

    //Haal Ronde op + vul in bij poule object
    public function getByRondeId($ronde_id, $poules){
        $select_query = sprintf("SELECT p.id AS poule_id, p.naam AS naam, w.id as wedstrijd_id, w.*, 
        s1.naam AS spelerThuis_naam, s1.voornaam AS spelerThuis_voornaam, s2.naam AS spelerUit_naam, s2.voornaam AS spelerUit_voornaam
        FROM intra_enkel_poule as p
        INNER JOIN intra_enkel_wedstrijd w ON w.poule_id = p.id
        INNER JOIN intra_spelers s1 ON s1.id = w.spelerThuis_id
        INNER JOIN intra_spelers s2 ON s2.id = w.spelerUit_id
        WHERE p.ronde_id = '%s' ORDER BY CASE WHEN w.ingevuld_op IS NULL THEN 1 ELSE 0 END, w.ingevuld_op;",
        $this->db->mysqli->real_escape_string($ronde_id));
        $result = $this->db->mysqli->query($select_query);
        if($result){
            while($row = $result->fetch_array())
            {            
                $wedstrijd = new Wedstrijd();
                $wedstrijd->vulOp($row);
                $poules[$row["poule_id"]]->wedstrijden[] = $wedstrijd;
            }
        }
        return $poules;
    }

    //Haal Ronde op + vul in bij poule object
    public function getByPouleId($poule){
        $select_query = sprintf("SELECT p.id AS poule_id, p.naam AS naam, w.id as wedstrijd_id, w.*, 
        s1.naam AS spelerThuis_naam, s1.voornaam AS spelerThuis_voornaam, s2.naam AS spelerUit_naam, s2.voornaam AS spelerUit_voornaam
        FROM intra_enkel_poule as p
        INNER JOIN intra_enkel_wedstrijd w ON w.poule_id = p.id
        INNER JOIN intra_spelers s1 ON s1.id = w.spelerThuis_id
        INNER JOIN intra_spelers s2 ON s2.id = w.spelerUit_id
        WHERE p.id = '%s';",
        $this->db->mysqli->real_escape_string($poule->id));
        $result = $this->db->mysqli->query($select_query);
        while($row = $result->fetch_array())
        {            
            $wedstrijd = new Wedstrijd();
            $wedstrijd->vulOp($row);
            $poule->wedstrijden[$wedstrijd->id] = $wedstrijd;
        }
        return $poule;
    }

}
