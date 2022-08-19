<?
//This is the controller for clients registering
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
    case 'signin':
        include '../views/signinpage.php';
        exit;
    
        //login attempt will be handled.
    case 'login':
         //Get, trim, filter and sanitize input from the user
         $clientEmail = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS));
         $clientPassword = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS));
         //call function to verify e-mail at the server-side
         $clientEmail = checkemail($clientEmail);
         //this function will be implemented
         $checkPassword = checkPassword($clientPassword);
 
         //validation at server-side. Not null, not blank.
         if (empty($clientEmail)||(empty($checkPassword))) {
             $_SESSION['message'] = "<span><p class='alertmessage'>Invalid e-mail or password!</p><span><hr>";
             include "../views/signinpage.php";
             exit;
         }

        //now we will handle valid e-mail and password to check if they exist in the database.
        //Everything was ok and the process can delivery login data
        //Get all data
        $clientData = getClientData($clientEmail);
        //Check if password is correct
        $hashCheck = password_verify($clientPassword, $clientData['clientPassword']);
        //if not ok, send a message and and return to login view
        if (!$hashCheck) {
            $_SESSION['message'] = "<span><p class='alertmessage'>Password is incorrect, please try again!</p></span><hr>";
            include "../views/signinpage.php";
            exit;
        }
        //if hash check is ok, go ahead and log  the user
        $_SESSION['loggedin'] = TRUE;
        //remove password from the data collection
        array_pop($clientData);
        $clientId = $clientData['clientId'];
        //Get kids Name to be stored in a session
        if(!empty(getKidName($clientId))) {
            $kidName = getKidName($clientId);
            $clientData["kidsName"] = $kidName["kidsName"];
        }
        //store array in the session
        $_SESSION['clientData'] = $clientData;
        //send user to client main page
        header("Location: /mbt/index.php");
        exit;
    
    case 'register':
        header('Location: /mbt/views/register.php');
        exit;

    case 'submitregister':
        $clientFirstName = trim(filter_input(INPUT_POST, 'clientFirstname', FILTER_SANITIZE_SPECIAL_CHARS));
        $clientLastName = trim(filter_input(INPUT_POST, 'clientLastname', FILTER_SANITIZE_SPECIAL_CHARS)); 
        $kidsName = trim(filter_input(INPUT_POST, 'kidsname', FILTER_SANITIZE_SPECIAL_CHARS));  
        $clientEmail = trim(filter_input(INPUT_POST, 'clientEmail', FILTER_SANITIZE_SPECIAL_CHARS));
        $clientPassword = trim(filter_input(INPUT_POST, 'clientPassword', FILTER_SANITIZE_SPECIAL_CHARS));
                 //call function to verify e-mail at the server-side
         $clientEmail = checkemail($clientEmail);
         //this function will be implemented
         $checkPassword = checkPassword($clientPassword);
 
         //Make sure responses are valid at server-side
        if (empty($clientFirstName) || empty($clientLastName)||empty($clientEmail)||empty($checkPassword)){
            $_SESSION['message'] = "<span><p class='alertmessage'>Please, provide information for all empty fields.</p></span>";
            include '../views/register.php';
            exit;
            
        }

        //Check if e-mail already exists in database
        $checkIfEmailExists = checkIfEmailExists($clientEmail);
        if ($checkIfEmailExists){
            $_SESSION['message'] = "<span><p class='alertmessage'>Email already exists.<br> Do you want to <a href='/mbt/accounts/index.php/?action=signin'>LOGIN</a> instead?</p></span>";
            include '../views/register.php';
            exit;
        }

        //hash password so it will be storaged in a hash form
        $hashedPassword = password_hash($clientPassword, PASSWORD_DEFAULT);

        //get the outcome of regClient to garantee data were inserted in the database
        $regOutcome = regClient($clientFirstName, $clientLastName, $clientEmail, $hashedPassword);
        if(!empty($regOutcome)) {
            setcookie('firstname', $clientFirstName, strtotime('+1 year'), "/");
            $clientData = getClientData($clientEmail);
            $clientId = $clientData['clientId'];
            $kidsnameevent = regKid($kidsName, $clientId);
            $_SESSION['message'] = "<span><p class='alertmessage'>Hey, $clientFirstName $clientLastName, You're registered. Please use your email and the password you provided to Sign In.</p></span>";
            include '../views/signinpage.php';
            exit;

        } else {
            //go back with a message case registration failed
            $_SESSION['message'] = "<span><p class='alertmessage'>Sorry $clientFirstName, but your registration failed. Please Try again later.</p></span>";
            include '.../views/register.php';
            exit;
        }
        exit;

    case 'logout':
        unset($_SESSION['loggedin']);
        unset($_SESSION['message']);
        unset($_SESSION['clientData']);
        unset($_SESSION['cart']);
        unset($_SESSION['inventoryItemId']);
        unset($_SESSION['category']);
        header('Location: /mbt/');

        exit;

    default:
        include '../views/500.php';
        exit;
            
}
?>

