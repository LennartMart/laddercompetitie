<?php
    ('_JEXEC') or die;    
    require_once(__DIR__ . '/../Manager/RondeManager.php');
    require_once(__DIR__ . '/../Manager/SpelerManager.php');
    require_once(__DIR__ . '/../Manager/WedstrijdManager.php');
    require_once(__DIR__ . '/../Manager/PouleManager.php');
    if (isset($_GET['action']) && !empty($_GET['action'])) {
        $action = $_GET['action'];
        switch ($action) {
            case 'isGeautoriseerd':
                $user = JFactory::getUser();
                $authorisedViewLevels = $user->getAuthorisedViewLevels();
                echo json_encode(in_array(5,$authorisedViewLevels));
                break;
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
                    $data["error"] = "Geen ronde meegegeven!";
                    echo json_encode($data);                    
                }
                break;
            case 'viewPouleRanking' : 
                if(isset($_GET['poule_id']) && !empty($_GET['poule_id']))
                {
                    $pouleManager = new PouleManager();
                    $ranking = $pouleManager->getRanking($_GET['poule_id']);
                    $data["success"] = true;
                    $data["ranking"] = $ranking;
                    echo json_encode($data);
                } else {
                    $data["success"] = false;
                    $data["error"] = "Geen poule meegegeven!";
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
            //Tested
            case 'getPoules':
                $rondeManager = new RondeManager();
                $poules = $rondeManager->getCurrentPoules();
                $data["success"] = true;
                $data["poules"] = $poules;
                echo json_encode($data); 
                break;
            case 'getSpelers':
                $spelerManager = new SpelerManager();
                $spelers = $spelerManager->getAll();              
                $data["success"] = true;
                $data["spelers"] = $spelers;
                echo json_encode($data); 
                break;
            case 'getWedstrijd':
                if(isset($_GET['wedstrijd_id']) && !empty($_GET['wedstrijd_id']))
                {
                    $wedstrijdManager = new WedstrijdManager();
                    $wedstrijd = $wedstrijdManager->get($_GET['wedstrijd_id']);
                    $data["success"] = true;
                    $data["wedstrijd"] = $wedstrijd;
                    echo json_encode($data);
                } else {
                    $data["success"] = false;
                    $data["error"] = "Geen wedstrijd_id meegegeven!";
                    echo json_encode($data);                    
                }
                break;
        }
    }