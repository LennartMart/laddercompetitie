<?php
('_JEXEC') or die; 
require_once(__DIR__ . '/../connect.php');
require_once (__DIR__ . '/../Model/Ronde.php');

class RondeRepository
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

    public function setGenerated($ronde_id){
        $update_query = sprintf("UPDATE intra_enkel_ronde
        SET                
            aangemaakt = '1'
        WHERE id = '%s';",            
        $this->db->mysqli->real_escape_string($ronde_id));
    if( $this->db->mysqli->query($update_query) === TRUE) {
        return true;
    } else {
        return false;
    }   
    }
    public function checkIfRondeIsAlreadyGenerated($ronde_id){
        //Haal wedstrijd op en vul de members in
        $get_query = sprintf("SELECT id as ronde_id, r.* FROM intra_enkel_ronde r WHERE id = '%s';", 
        $this->db->mysqli->real_escape_string($ronde_id));
        $result = $this->db->mysqli->query($get_query);
        if ($result) {
            // fetch the result row.
            $data = $result->fetch_assoc();
            return $data['aangemaakt'];
        }
        return true;
    }
    
    public function insert($naam, $einddatum){
        $insert_query = sprintf("INSERT INTO intra_enkel_ronde
            SET
                einddatum = '%s',
                naam = '%s',
                aangemaakt = '0',
                startdatum = NOW();",
            $this->db->mysqli->real_escape_string($einddatum),
            $this->db->mysqli->real_escape_string($naam));
        if( $this->db->mysqli->query($insert_query) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
    public function getHuidigeRonde(){    
        $get_query = "SELECT id as ronde_id, r.* FROM intra_enkel_ronde r ORDER BY id DESC LIMIT 1";
        $result = $this->db->mysqli->query($get_query);
        if ($result) {
            // fetch the result row.
            $data = $result->fetch_assoc();
            $ronde = new Ronde();
            $ronde->vulOp($data);
            return $ronde;
        }
        return new Ronde();
    }
    public function getRondes(){
        $rondes = [];
        $select_query = "SELECT r.id AS ronde_id, r.*
        FROM intra_enkel_ronde as r
        ORDER BY id DESC;";
        $result = $this->db->mysqli->query($select_query);
        while($row = $result->fetch_array())
        {
            $ronde = new Ronde();
            $ronde->vulOp($row);
            $rondes[] = $ronde;
        }
        return $rondes;
    }
}
