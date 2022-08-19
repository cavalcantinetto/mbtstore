<?php 

// Accounts model to handle new users

function regClient($clientFirstname, $clientLastname, $clientEmail, $clientPassword) {
    $db = mbtConnect();
    //The SQL Statement
    $sql = 'INSERT INTO clients (clientFirstname, clientLastname, clientEmail, clientPassword) VALUES (:clientFirstname, :clientLastname, :clientEmail, :clientPassword)';
    //creat a prepared statement using connection
    $stmt = $db->prepare($sql);
    //next lines replace placeholders in statement
    $stmt->bindValue(':clientFirstname', $clientFirstname, PDO::PARAM_STR);
    $stmt->bindValue(':clientLastname', $clientLastname, PDO::PARAM_STR);
    $stmt->bindValue(':clientEmail', $clientEmail, PDO::PARAM_STR);
    $stmt->bindValue(':clientPassword', $clientPassword, PDO::PARAM_STR);
    //INSERT DATA
    $stmt->execute();
    //How many rows were inserted
    $rowsChanged = $stmt->rowCount();
    //Close database interaction
    $stmt->closeCursor();
    //return result if succsess
    return $rowsChanged;

    
}

function regKid($kidsName, $clientId) {
    $db = mbtConnect();
    //The SQL Statement
    $sql = 'INSERT INTO kids (kidsName, clientId) VALUES (:kidsName, :clientId)';
    //creat a prepared statement using connection
    $stmt = $db->prepare($sql);
    //next lines replace placeholders in statement
    $stmt->bindValue(':kidsName', $kidsName, PDO::PARAM_STR);
    $stmt->bindValue(':clientId', $clientId, PDO::PARAM_STR);
    //INSERT DATA
    $stmt->execute();
    //How many rows were inserted
    $rowsChanged = $stmt->rowCount();
    //Close database interaction
    $stmt->closeCursor();
    //return result if succsess
    return $rowsChanged;
}

function getKidName($clientId) {
    $db = mbtConnect();
    //The SQL Statement
    $sql = "SELECT kidsName FROM kids WHERE (clientId = :clientId)";
    //creat a prepared statement using connection
    $stmt = $db->prepare($sql);
    //next lines replace placeholders in statement
    $stmt->bindValue(':clientId', $clientId, PDO::PARAM_STR);
    //INSERT DATA
    $stmt->execute();
    //How many rows were inserted
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    //Close database interaction
    $stmt->closeCursor();
    //return result if succsess
    return $result;
}

//function to check if email already exists in database
function checkIfEmailExists ($clientEmail) {
    //Open connection with database
    $db = mbtConnect();
    //SQL statement
    $sql = "SELECT clientEmail FROM clients WHERE clientEmail = :email";
    //start prepare
    $stmt = $db ->prepare($sql);
    $stmt->bindValue(':email', $clientEmail, PDO::PARAM_STR);
    $stmt->execute();
    $matchEmail = $stmt->fetch(PDO::FETCH_NUM);
    $stmt->closeCursor();
    //will return 1 if match or 0 if not
    if(empty($matchEmail)) {
        return 0;
    } else {
        echo $matchEmail;
        return 1;
    }
}

// Get client data based on an email address
function getClientData($clientEmail) {
    $db = mbtConnect();
    $sql = "SELECT clientId, clientFirstname, clientLastname, clientEmail, clientLevel, clientPassword FROM clients WHERE clientEmail = :clientEmail";
    $stmt = $db ->prepare($sql);
    $stmt -> bindValue(':clientEmail', $clientEmail, PDO::PARAM_STR);
    $stmt->execute();
    $clientData = $stmt -> fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $clientData;
}

// Get client data based on clientId
function getClientId($clientId){
    $db = mbtConnect();
    $sql = 'SELECT clientId, clientFirstname, clientLastname, clientEmail, clientLevel, clientPassword FROM clients WHERE clientId = :clientId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':clientId', $clientId, PDO::PARAM_STR);
    $stmt->execute();
    $clientData = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $clientData;
}

// Update the personal information based on index id.
function updatePersonal($firstName, $lastName, $newEmail, $clientId){
    // Create a connection object using the phpmotors connection function
    $db = mbtConnect();
    // The SQL statement
    $sql = 'UPDATE clients SET clientFirstname = :clientFirstname, clientLastname = :clientLastname, clientEmail = :clientEmail WHERE clientId = :clientId';
    // Create the prepared statement using the phpmotors connection
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':clientFirstname', $firstName, PDO::PARAM_STR);
    $stmt->bindValue(':clientLastname', $lastName, PDO::PARAM_STR);
    $stmt->bindValue(':clientEmail', $newEmail, PDO::PARAM_STR);
    $stmt->bindValue(':clientId', $clientId, PDO::PARAM_INT);
    // Insert the data
    $stmt->execute();
    // Ask how many rows changed as a result of our insert
    $rowsChanged = $stmt->rowCount();
    // Close the database interaction
    $stmt->closeCursor();
    // Return the indication of success (rows changed)
    return $rowsChanged;
}

// Update the password based on index id.
function updateNewPassword($hashedPassword, $clientId){
    // Create a connection object using the phpmotors connection function
    $db = mbtConnect();
    // The SQL statement
    $sql = 'UPDATE clients SET clientPassword = :clientPassword WHERE clientId = :clientId';
    // Create the prepared statement using the phpmotors connection
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':clientPassword', $hashedPassword, PDO::PARAM_STR);
    $stmt->bindValue(':clientId', $clientId, PDO::PARAM_STR);
    // Insert the data
    $stmt->execute();
    // Ask how many rows changed as a result of our insert
    $rowsChanged = $stmt->rowCount();
    // Close the database interaction
    $stmt->closeCursor();
    // Return the indication of success (rows changed)
    return $rowsChanged;
}
?>