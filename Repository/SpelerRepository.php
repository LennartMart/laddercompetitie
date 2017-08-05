<?php
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
}
