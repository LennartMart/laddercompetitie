<?php
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
    
    public function getHuidigeRonde(){        
        $get_query = "SELECT TOP 1 id as ronde_id, * FROM intra_enkel_ronde ORDER BY id DESC;";
        $result = $this->db->mysqli->query($get_query);
        if ($result) {
            // fetch the result row.
            $data = $result->fetch_assoc();
            $ronde = new Ronde();
            $ronde->vulOp($row);
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
