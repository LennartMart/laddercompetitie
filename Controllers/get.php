<?php

    require_once(__DIR__ . '/../Manager/RondeManager.php');
    require_once(__DIR__ . '/../Manager/SpelerManager.php');
    require_once(__DIR__ . '/../Manager/WedstrijdManager.php');
    if (isset($_GET['action']) && !empty($_GET['action'])) {
        $action = $_GET['action'];
        switch ($action) {
            case 'viewCurrentRanking' :
                $rondeManager = new RondeManager();
                $ranking = $rondeManager->getCurrentRanking();
                $data["success"] = true;
                $data["ranking"] = $ranking;
                echo json_encode($data);
                break;
            case 'viewRanking' : 
                if(isset($_GET['ronde_id']) && !empty($_GET['ronde_id']))
                {
                    $rondeManager = new RondeManager();
                    $ranking = $rondeManager->getRanking($_GET['ronde_id']);
                    $data["success"] = true;
                    $data["ranking"] = $ranking;
                    echo json_encode($data);
                } else {
                    $data["success"] = false;
                    $data["errors"] = "Geen ronde meegegeven!";
                    echo json_encode($data);                    
                }
                break;
            //Tested
            case 'getRondes':
                $rondeManager = new RondeManager();
                $rondes = $rondeManager->getRondes();
                $data["success"] = true;
                $data["rondes"] = $rondes;
                echo json_encode($data); 
                break;
        }
    }