<?php

    require_once(__DIR__ . '/../Manager/RondeManager.php');
    require_once(__DIR__ . '/../Manager/SpelerManager.php');
    require_once(__DIR__ . '/../Manager/WedstrijdManager.php');
    if(isset($_POST['action']) && !empty($_POST['action']))
    {
        $action = $_POST['action'];
        switch ($action) {
            case "createRonde":
                break;
            case "createPoule":
                break;
            case "addSpelerToPoule":
                break;
            case "generateWedstrijden":
                break;
            case "vulWedstrijdIn":
                break;
        }
    }