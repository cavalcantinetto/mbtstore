<?php 

function getClassifications() {
    //creates a connection object to phpmotors connection function
    $db = mbtConnect();
    //The SQL statements to be used
    $sql = 'SELECT classificationName, classificationId FROM classifications ORDER BY classificationName ASC';
    //creates the statement for the connection using phpconnection function
    $stmt = $db->prepare($sql);
    $stmt->execute();
    //Now we are going to hold the response from DataBase
    $classifications = $stmt->fetchAll();
    //Close interaction
    $stmt->closeCursor();
    //Will retrieve the result
    return $classifications;

}

function getCategories() {
    //creates a connection object to phpmotors connection function
    $db = mbtConnect();
    //The SQL statements to be used
    $sql = 'SELECT * FROM uniformcategory ORDER BY category ASC';
    //creates the statement for the connection using phpconnection function
    $stmt = $db->prepare($sql);
    $stmt->execute();
    //Now we are going to hold the response from DataBase
    $categories = $stmt->fetchAll();
    //Close interaction
    $stmt->closeCursor();
    //Will retrieve the result
    return $categories;
}

?>