<?php

    require_once(__DIR__ . '/../Manager/RondeManager.php');
    require_once(__DIR__ . '/../Manager/PouleManager.php');
    require_once(__DIR__ . '/../Manager/SpelerManager.php');
    require_once(__DIR__ . '/../Manager/WedstrijdManager.php');
    date_default_timezone_set("Europe/Brussels");
    if(isset($_POST['action']) && !empty($_POST['action']))
    {
        $action = $_POST['action'];
        switch ($action) {
            //Tested
            case "createRonde":
                if(isset($_POST['einddatum']) && !empty($_POST['einddatum'])
                && isset($_POST['naam']) && !empty($_POST['naam']))
                {
                    if(validateDate($_POST['einddatum'])){
                        $rondeManager = new RondeManager();
                        $geslaagd = $rondeManager->addNieuweRonde($_POST['naam'], $_POST['einddatum']);
                        if($geslaagd){
                            $data["success"] = true;
                        } else {
                            $data["success"] = false;
                            $data["error"] = "Er liep iets mis bij het inserteren!";
                        }
                                                
                    } else {
                        $data["success"] = false;
                        $data["error"] = "einddatum in ongeldig formaat";
                    }
                } else {
                    $data["success"] = false;
                    $data["error"] = "Niet alle elementen werden meegegeven";
                }
                echo json_encode($data);
                break;
            //Tested
            case "createPoule":
                if(isset($_POST['ronde_id']) && !empty($_POST['ronde_id'])
                && isset($_POST['naam']) && !empty($_POST['naam']))
                {
                    $pouleManager = new PouleManager();
                    $geslaagd = $pouleManager->insert($_POST['ronde_id'], $_POST['naam']);
                    if($geslaagd){
                        $data["success"] = true;
                    } else {
                        $data["success"] = false;
                        $data["error"] = "Er liep iets mis bij het inserteren!";
                    }

                } else {
                    $data["success"] = false;
                    $data["error"] = "Niet alle elementen werden meegegeven";
                }
                echo json_encode($data);
                break;
            //Tested
            case "addSpelerToPoule":
                if(isset($_POST['speler_id']) && !empty($_POST['speler_id'])
                && isset($_POST['poule_id']) && !empty($_POST['poule_id']))
                {
                    $pouleManager = new PouleManager();
                    $geslaagd = $pouleManager->addSpelerToPoule($_POST['speler_id'], $_POST['poule_id']);
                    if($geslaagd){
                        $data["success"] = true;
                    } else {
                        $data["success"] = false;
                        $data["error"] = "Er liep iets mis bij het inserteren!";
                    }

                } else {
                    $data["success"] = false;
                    $data["error"] = "Niet alle elementen werden meegegeven";
                }
                echo json_encode($data);
                break;
            case "generateWedstrijden":
                if(isset($_POST['ronde_id']) && !empty($_POST['ronde_id']))
                {
                    $rondeManager = new rondeManager();
                    $geslaagd = $rondeManager->genereer($_POST['ronde_id']);
                    if($geslaagd){
                        $data["success"] = true;
                    } else {
                        $data["success"] = false;
                        $data["error"] = "Ronde werd reeds gegenereerd!";
                    }

                } else {
                    $data["success"] = false;
                    $data["error"] = "Niet alle elementen werden meegegeven";
                }
                echo json_encode($data);

                break;
            case "vulWedstrijdIn":
                if(isset($_POST['wedstrijd_id']) && !empty($_POST['wedstrijd_id'])
                && isset($_POST['spelerThuis_set1']) && !empty($_POST['spelerThuis_set1'])
                && isset($_POST['spelerThuis_set2']) && !empty($_POST['spelerThuis_set2'])
                && isset($_POST['spelerUit_set1']) && !empty($_POST['spelerUit_set1'])
                && isset($_POST['spelerUit_set2']) && !empty($_POST['spelerUit_set2'])        )
                {      
                    $wedstrijdManager = new WedstrijdManager();
                    $errors = $wedstrijdManager->vulIn($_POST['wedstrijd_id'], $_POST['spelerThuis_set1'],  $_POST['spelerThuis_set2'],  $_POST['spelerThuis_set3'],
                    $_POST['spelerUit_set1'], $_POST['spelerUit_set2'], $_POST['spelerUit_set3'], "test");
                    $data["success"] = empty($errors);
                    $data["error"] = $errors;
                }
                else {
                    $data["success"] = false;
                    $data["error"] = "Oeps... Heb je een correcte uitslag ingegeven?";
                }
                echo json_encode($data);
                break;
        }
    }


    function validateDate($date)
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }