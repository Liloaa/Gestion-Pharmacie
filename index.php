<?php
require_once 'models/Database.php';
require_once 'models/Medicament.php';
require_once 'models/Entree.php';
require_once 'models/Achat.php';
require_once 'controllers/MedicamentController.php';
require_once 'controllers/EntreeController.php';
require_once 'controllers/AchatController.php';

$db = new Database();
$conn = $db->getConnection();

$controller = isset($_GET['controller']) ? $_GET['controller'] : 'medicament';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';
$numMedoc = isset($_GET['numMedoc']) ? $_GET['numMedoc'] : null;
$numEntree = isset($_GET['numEntree']) ? $_GET['numEntree'] : null;
$numAchat = isset($_GET['numAchat']) ? $_GET['numAchat'] : null;

switch ($controller) {
    case 'medicament':
        $ctrl = new MedicamentController($conn);
        switch ($action) {
            case 'create':
                $ctrl->create();
                break;
            case 'edit':
                $ctrl->edit($numMedoc);
                break;
            case 'delete':
                $ctrl->delete($numMedoc);
                break;
            case 'lowStock':
                $ctrl->lowStock();
                break;
            default:
                $ctrl->index();
        }
        break;
    case 'entree':
        $ctrl = new EntreeController($conn);
        switch ($action) {
            case 'create':
                $ctrl->create();
                break;
            case 'edit':
                $ctrl->edit($numEntree);
                break;
            case 'delete':
                $ctrl->delete($numEntree);
                break;
            default:
                $ctrl->index();
        }
        break;
    case 'achat':
        $ctrl = new AchatController($conn);
        switch ($action) {
            case 'create':
                $ctrl->create();
                break;
            case 'edit':
                $ctrl->edit($numAchat);
                break;
            case 'delete':
                $ctrl->delete($numAchat);
                break;
            default:
                $ctrl->index();
        }
        break;
}
?>