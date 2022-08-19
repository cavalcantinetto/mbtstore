<?
//this controller will handle computers inventory.


require_once '../library/functions.php';
require_once '../library/connection.php';
require_once '../model/main-model.php';
require_once '../model/accounts-model.php';
//start a session to save some information
session_start();

//handles key-value pair and save $action as a variable.
$action = trim(filter_input(INPUT_POST, 'action', FILTER_SANITIZE_SPECIAL_CHARS));
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
}

switch ($action) {
    //first arrival at the page - try to check if he is loggedin first.
    case 'insertitem':
        include '../views/signinpage.php';
        exit;

    case 'getspecificitem':
        include '../views/signinpage.php';
        exit;
    
    case 'deleteitem':
        include '../views/signinpage.php';
        exit;


    case 'edititem':
        include '../views/signinpage.php';
        exit;

    case 'showitens':
        include '../views/signinpage.php';
        exit;

    default:
        break;
    }

?>