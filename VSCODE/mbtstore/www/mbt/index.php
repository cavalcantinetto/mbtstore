<?
//This is the main index page that will trigger the first arrival at the page.

require_once 'library/connection.php';

require_once 'model/main-model.php';

require_once 'model/accounts-model.php';
require_once 'library/functions.php';

session_start();

$classifications = getClassifications();

//handles key-value pair and save $action as a variable.
$action = trim(filter_input(INPUT_POST, 'action', FILTER_SANITIZE_SPECIAL_CHARS));
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
}

switch ($action) {

    case 'classification':
        $classification = trim(filter_input(INPUT_GET, 'classificationName', FILTER_SANITIZE_SPECIAL_CHARS));
        if($classification == 'Uniforms Management') {
            header('Location: /mbt/uniforms/index.php?action=reportfilter');
        }


        exit;


    
    default:
        
        if(isset($_SESSION['loggedin'])) {
            $clientLevel = $_SESSION['clientData']['clientLevel'];
            if($clientLevel>1) {
            $navlist = navListCreator($classifications);
            }
            $clientFirstName = $_SESSION['clientData']['clientFirstname'];
            $clientLastName = $_SESSION['clientData']['clientLastname'];
            $categories = getCategories();
            $categorieshtml = categoryCreator($categories);
            include 'views/mainpage.php';
            break;
        } else {
            header('Location: /mbt/accounts/index.php/?action=signin');
        }
        
        exit;
            
}

?>