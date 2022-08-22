<?
function getUniformsByCategory($categoryId) {
    $db = mbtConnect();
    $sql = "SELECT inventoryitem.invId, inventoryitem.itemDescription, inventoryitem.invImg, inventoryitem.invPrice
    FROM inventoryitem
    WHERE inventoryitem.categoryId = :categoryId
    ORDER by inventoryitem.itemDescription ASC";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
    $stmt->execute();
    $uniformlist = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $uniformlist;
}

function getSizesByInvId($invId) {
    $db = mbtConnect();
    $sql = "SELECT inventoryitem.invImg, inventoryitem.itemDescription, inventorysizes.sizes, uniforminventory.invCost, uniforminventory.invQtd, uniforminventory.invTime, uniforminventory.invIdUniform, uniforminventory.invItem, inventoryitem.invPrice
    FROM uniforminventory
    LEFT JOIN inventoryitem ON inventoryitem.invId = uniforminventory.invItem
    LEFT JOIN inventorysizes ON inventorysizes.sizesId = uniforminventory.invSize
    WHERE uniforminventory.invItem = :invId
    ORDER by uniforminventory.invSize ASC";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':invId', $invId, PDO::PARAM_INT);
    $stmt->execute();
    $uniformlist = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $uniformlist;
}

function getOnlySizes($invItem) {
    $db = mbtConnect();
    $sql = "SELECT invSize, invIdUniform
    FROM uniforminventory
    WHERE uniforminventory.invItem = :invId
    ORDER by invSize DESC";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':invId', $invItem, PDO::PARAM_INT);
    $stmt->execute();
    $listofsizes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $listofsizes;
}

function returningItensToCart($invIdUniform, $qty) {
    $db = mbtConnect();
    $sql = "SELECT inventoryitem.invImg, inventoryitem.itemDescription, inventoryitem.invPrice, inventorysizes.sizes, uniforminventory.invIdUniform
    FROM uniforminventory LEFT JOIN inventoryitem ON uniforminventory.invItem = inventoryitem.invId
    LEFT JOIN inventorysizes ON uniforminventory.invSize = sizesId
    WHERE uniforminventory.invIdUniform = :invIdUniform";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':invIdUniform', $invIdUniform, PDO::PARAM_INT);
    $stmt->execute();
    $item = $stmt->fetch();
    $item['qty'] = $qty;
    $stmt->closeCursor();
    return $item;
    }

function checkAmountOfInventory($invIdUniform) {
    $db = mbtConnect();
    $sql = "SELECT invQtd 
    FROM uniforminventory 
    WHERE invIdUniform = :invIdUniform";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':invIdUniform', $invIdUniform, PDO::PARAM_INT);
    $stmt->execute();
    $item = $stmt->fetch();
    $stmt->closeCursor();
    return ($item);
}

function removeInventoryItem($invIdUniform, $qty) {
    $checkAmount = checkAmountOfInventory($invIdUniform);
    if(intval($checkAmount<=0)) {
        return 0;
        exit;
    } else {
    $db = mbtConnect();
    $sql = "UPDATE uniforminventory SET invQtd = invQtd - :qty WHERE invIdUniform = :invIdUniform";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':invIdUniform', $invIdUniform, PDO::PARAM_INT);
    $stmt->bindValue(':qty', $qty, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->rowCount();
    if ($result) {
        $sql = "SELECT uniforminventory.invTime, uniforminventory.invIdUniform, inventoryitem.itemDescription, uniforminventory.invQtd, inventoryitem.invPrice FROM uniforminventory LEFT JOIN inventoryitem ON uniforminventory.invItem = inventoryitem.invId WHERE invIdUniform = :invIdUniform";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':invIdUniform', $invIdUniform, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result;
    } 
}    
}

function addInventoryItem($invIdUniform, $qtyToBeInserted, $invCost) {
    $db = mbtConnect();
    $sql = "UPDATE uniforminventory SET invQtd = invQtd + :invQtd, invCost = :invCost WHERE invIdUniform = :invIdUniform";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':invIdUniform', $invIdUniform, PDO::PARAM_INT);
    $stmt->bindValue(':invQtd', $qtyToBeInserted, PDO::PARAM_INT);
    $stmt->bindValue(':invCost', $invCost, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->rowCount();
    if ($result) {
        $sql = "SELECT uniforminventory.invTime, uniforminventory.invIdUniform, inventoryitem.itemDescription, uniforminventory.invQtd FROM uniforminventory LEFT JOIN inventoryitem ON uniforminventory.invItem = inventoryitem.invId WHERE invIdUniform = :invIdUniform";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':invIdUniform', $invIdUniform, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
    } 
    return $result;
}


function getCategory($category) {
    $db = mbtConnect();
    $sql = "SELECT categoryId, category FROM uniformcategory WHERE categoryId = :categoryId";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':categoryId', $category, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    $stmt->closeCursor();
    return $result;
}

function getSizes($category) {
    $db = mbtConnect();
    $sql = "SELECT sizesId, sizes FROM inventorysizes WHERE categoryId = :categoryId ORDER BY sizes ASC";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':categoryId', $category, PDO::PARAM_INT);
    $stmt->execute();
    $category = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    $stmt->closeCursor();
    return $category;
}

function insertInventoryItem($categoryId, $itemDescription, $invImg, $invPrice) {
    //will insert a new inventoryItemDescription at database.
    $db = mbtConnect();
    $sql = "INSERT INTO inventoryitem (`categoryId`, `itemDescription`, `invImg`, `invPrice`) VALUES (:categoryId, :itemDescription, :invImg, :invPrice)";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
    $stmt->bindValue(':itemDescription', $itemDescription, PDO::PARAM_STR);
    $stmt->bindValue(':invImg', $invImg, PDO::PARAM_STR);
    $stmt->bindValue(':invPrice', $invPrice, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->rowCount();
    $stmt->closeCursor();
    return $result;
}

function getMaxValue() {
    $db = mbtConnect();
    $sql = "SELECT MAX(invID) FROM inventoryitem";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    $stmt->closeCursor();
    return $result;
}

function insertUniformInventory($invItem, $invSize, $invCost, $qtd) {
    //will insert a new uniforminventory Item at database.
    $db = mbtConnect();
    $sql = "INSERT INTO `uniforminventory`(`invItem`, `invSize`, `invCost`, `invQtd`) VALUES (:invItem, :invSize, :invCost, :invQtd)";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':invItem', $invItem, PDO::PARAM_STR);
    $stmt->bindValue(':invSize', $invSize, PDO::PARAM_STR);
    $stmt->bindValue(':invCost', $invCost, PDO::PARAM_STR);
    $stmt->bindValue(':invQtd', $qtd, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->rowCount();
    $stmt->closeCursor();
    return $result;
}

function insertSellingData($buyer, $kidsname, $total, $serialcart) {
    $db = mbtConnect();
    $sql = "INSERT INTO `uniformsolded`
    (`buyer`, `kidsName`, `total`, `cart`)
    VALUES (:buyer, :kidsname, :total, :cart)";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':buyer', $buyer, PDO::PARAM_STR);
    $stmt->bindValue(':kidsname', $kidsname, PDO::PARAM_STR);
    $stmt->bindValue(':total', $total, PDO::PARAM_STR);
    $stmt->bindValue(':cart', $serialcart, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->rowCount();
    if($result) {
        $sql = "SELECT * FROM `uniformsolded` WHERE `soldId`= LAST_INSERT_ID()";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    $stmt->closeCursor();
    return $result;
}

function insertOrderData($soldId, $description, $price, $size, $qty, $clientId) {
    $db = mbtConnect();
    $sql = "INSERT INTO `orders`(`soldId`, `description`, `price`, `size`, `qty`, `clientId`) 
    VALUES (:soldId, :descriptio, :price, :size, :qty, :clientId)";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':soldId', $soldId, PDO::PARAM_INT);
    $stmt->bindValue(':descriptio', $description, PDO::PARAM_STR);
    $stmt->bindValue(':price', $price, PDO::PARAM_STR);
    $stmt->bindValue(':size', $size, PDO::PARAM_INT);
    $stmt->bindValue(':qty', $qty, PDO::PARAM_INT);
    $stmt->bindValue(':clientId', $clientId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->rowCount();
    if($result) {
        $sql = "SELECT * FROM `orders` WHERE `soldId`= LAST_INSERT_ID()";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    $stmt->closeCursor();
    return $result;
}

function getItensSold($timestart, $timeend) {
    $db = mbtConnect();
    $sql = "SELECT * 
    FROM uniformsolded
    WHERE uniformsolded.date >=:timestart AND uniformsolded.date <=:timeend";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':timestart', $timestart, PDO::PARAM_STR);
    $stmt->bindValue(':timeend', $timeend, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchAll();
    $stmt->closeCursor();
    return $result;
}

function getorderData($soldId) {
    $db = mbtConnect();
    $sql = "SELECT orders.date, orders.soldId, orderId, clients.clientId, kids.kidsName, orders.description,size, qty,  price, orders.delivered, orders.charged, orders.paid
    FROM orders
    LEFT JOIN uniformsolded ON uniformsolded.soldID = orders.soldId
    LEFT JOIN clients ON clients.clientID = orders.clientId 
    LEFT JOIN kids ON kids.clientID = orders.ClientId
    WHERE orders.soldId = :soldId";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':soldId', $soldId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll();
    $stmt->closeCursor();
    return $result;
}

function changeOrderStatus($orderId, $field, $value) {
    $db = mbtConnect();
    $sql = "UPDATE orders SET $field = :valu WHERE orderId = :orderId";
    $stmt = $db->prepare($sql);
    //$stmt->bindValue(':field', $field, PDO::PARAM_STR);
    $stmt->bindValue(':valu', $value, PDO::PARAM_INT);
    $stmt->bindValue(':orderId', $orderId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->rowCount();
    $stmt->closeCursor();
    return $result;
}

?>