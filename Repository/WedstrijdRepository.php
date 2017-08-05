<?php
require_once (__DIR__ . '/../connect.php');
require_once (__DIR__ . '/../Model/Wedstrijd.php');

class WedstrijdRepository
{

    function __construct()
    {
        $this->db = new ConnectionSettings();
        connect();
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
        $get_query = sprintf("SELECT * FROM intra_enkel_wedstrijd IW WHERE id = '%s';", $this->db->mysqli->real_escape_string($wedstrijd_id));
        $result = $this->db->mysqli->query($sql);
        if ($result) {
            // fetch the result row.
            $data = $result->fetch_assoc();
            $this->vulop($row);
            return TRUE;
        }
        return FALSE;
    }


}
