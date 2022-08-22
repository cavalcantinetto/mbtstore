<?
//this controller will handle computers inventory.


require_once '../library/functions.php';
require_once '../library/connection.php';
require_once '../model/main-model.php';
require_once '../model/accounts-model.php';
require_once '../model/inventory-model.php';
//require_once '../vendor/autoload.php';


//start a session to save some information
session_start();

$image_dir = '/mbt/assets/images';
$image_dir_path = $_SERVER['DOCUMENT_ROOT']. $image_dir;

//handles key-value pair and save $action as a variable.
$action = trim(filter_input(INPUT_POST, 'action', FILTER_SANITIZE_SPECIAL_CHARS));
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
}

switch ($action) {
    case 'edititem':
        $clientLevel = $_SESSION['clientData']['clientLevel'];
        $categoryId = trim(filter_input(INPUT_GET, 'category', FILTER_SANITIZE_NUMBER_INT));
        $uniformItens = getUniformsByCategory($categoryId);
        $_SESSION['category'] = $categoryId;
        
        if($clientLevel>1) {
            $uniformView = buildTableByCategory($uniformItens);
        } else {
            $message = "<h1 class='alertmessage'> Choose size and click on it to move to cart.<h1>";
            $uniformView = buildTableByCategorylowprofile($uniformItens);
        }
        include '../views/uniformsview.php';
        exit;
    

        case 'removeitem':
            $invId = trim(filter_input(INPUT_GET, 'inventoryItemId', FILTER_SANITIZE_NUMBER_INT));
            $category = $_SESSION['category'];
            $_SESSION['inventoryItemId'] = $invId;
            $uniformlist = getSizesByInvId($invId);
            $htmltable = buildTableByinvItem($uniformlist);
            include '../views/removeitempage.php';
            exit;
    
        case 'deleteitem':
            $previousqty = trim(filter_input(INPUT_POST, 'previousqtd', FILTER_SANITIZE_NUMBER_INT));
            $justification = trim(filter_input(INPUT_POST, 'justify', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
            $qtyToBeSubtracted = trim(filter_input(INPUT_POST, 'qtd', FILTER_SANITIZE_NUMBER_INT));
            $invIdUniform = trim(filter_input(INPUT_POST, 'invIdUniform', FILTER_SANITIZE_NUMBER_INT));
            $inventoryItemId = $_SESSION['inventoryItemId'];
            if ($justification==""||$qtyToBeSubtracted=="") {
                $_SESSION["message"] = "<p class='alertmessage'>You have to insert a justification and a quantity to be removed.</p>";
                header("Location: /mbt/uniforms/index.php?action=removeitem&inventoryItemId=$inventoryItemId");
                exit;
            }
            if($previousqty>=0 && $previousqty>=$qtyToBeSubtracted) {
                $result = removeInventoryItem($invIdUniform, $qtyToBeSubtracted);
                $name = getUserName($_SESSION['clientData']);
                $email = getUserEmail($_SESSION['clientData']);
                //insert name and email in the array the alread has time, invItem and description to be registered into the log file
                $result['name'] = $name;
                $result['email'] = $email;
                $result['justify'] = $justification;
                //call a function to append the log into a log file
                $data = logItemRemoved($result);
                //after all insertions at log file and removals from dataBase, return to the prevoius page.
                header("Location: /mbt/uniforms/index.php?action=removeitem&inventoryItemId=$inventoryItemId");;
                exit;
            } else {
                $_SESSION['message'] = "<p class='alertmessage'>There is only $previousqty in the inventory. You are trying to remove $qtyToBeSubtracted.";
                header("Location: /mbt/uniforms/index.php?action=removeitem&inventoryItemId=$inventoryItemId");
                break;
            }
            exit;
            

    case 'additem':
        $invId = trim(filter_input(INPUT_GET, 'inventoryItemId', FILTER_SANITIZE_NUMBER_INT));
        $category = $_SESSION['category'];
        $_SESSION['inventoryItemId'] = $invId;
        $uniformlist = getSizesByInvId($invId);
        if (empty($uniformlist)) {
            $_SESSION['message'] = "<p class='alertmessage'>Inventory is empty for this item.</p>";
            include '../views/additempage.php';
            exit;
        }
        $htmltable = buildTableByinvItemAdd($uniformlist);
        include '../views/additempage.php';
        exit;    

        case 'insertitem':

        $justification = trim(filter_input(INPUT_POST, 'justify', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $qtyToBeInserted = trim(filter_input(INPUT_POST, 'qtd', FILTER_SANITIZE_NUMBER_INT));
        $invIdUniform = trim(filter_input(INPUT_POST, 'invIdUniform', FILTER_SANITIZE_NUMBER_INT));
        $invCost = trim(filter_input(INPUT_POST, 'cost', FILTER_SANITIZE_SPECIAL_CHARS));
        $prevCost = trim(filter_input(INPUT_POST, 'prevcost', FILTER_SANITIZE_SPECIAL_CHARS));
        $prevQty = trim(filter_input(INPUT_POST, 'prevqtd', FILTER_SANITIZE_NUMBER_INT));
        $inventoryItemId = $_SESSION['inventoryItemId'];
        if ($justification==""||$qtyToBeInserted=="" ||$invCost=="") {
            $_SESSION["message"] = "<p class='alertmessage'>You have to insert receipt's number, quantity and cost to procced.</p>";
            header("Location: /mbt/uniforms/index.php?action=additem&inventoryItemId=$inventoryItemId");
            exit;
        }

        $totalCostOld = $prevCost * $prevQty;
        $totalCostNew = $invCost * $qtyToBeInserted;
        $totalQty = $prevQty + $qtyToBeInserted;
        $newUnitCost = ($totalCostNew + $totalCostOld)/$totalQty;
        $result = addInventoryItem($invIdUniform, $qtyToBeInserted, $newUnitCost);
        if ($result) {
            //get name and email to insert into the log file.
            $name = getUserName($_SESSION['clientData']);
            $email = getUserEmail($_SESSION['clientData']);
            //insert name and email in the array the alread has time, invItem and description to be registered into the log file
            $result['name'] = $name;
            $result['email'] = $email;
            //$_SESSION['category'] = $category;
            //call a function to append the log into a log file
            $data = logItemAdded($result);
            header("Location: /mbt/uniforms/index.php?action=additem&inventoryItemId=$inventoryItemId");
            break;

        } else {
            $_SESSION["message"] = "<p class='alertmessage'>Fail accessing dataBase</p>";
            include '../views/500.php';
            break;
        }
        exit;

        case 'sellingitem':
            $invId = trim(filter_input(INPUT_GET, 'inventoryItemId', FILTER_SANITIZE_NUMBER_INT));
            $category = $_SESSION['category'];
            $_SESSION['inventoryItemId'] = $invId;
            $uniformlist = getSizesByInvId($invId);
            if (empty($uniformlist)) {
                $_SESSION['message'] = "<p class='alertmessage'>Inventory is empty for this item.</p>";
                include '../views/additempage.php';
                exit;
            }
            $htmltable = buildTableByinvIdUniform($uniformlist);
            include '../views/removeitempage.php';
            exit;    
        
        case 'sendtocart':
            
            $invIdUniform = trim(filter_input(INPUT_POST, 'invIdUniform', FILTER_SANITIZE_NUMBER_INT));
            $oldQty = trim(filter_input(INPUT_POST, 'previousqtd', FILTER_SANITIZE_NUMBER_INT));
            $invCost = trim(filter_input(INPUT_POST, 'invcost', FILTER_SANITIZE_SPECIAL_CHARS));
            $invPrice = trim(filter_input(INPUT_POST, 'invPrice', FILTER_SANITIZE_SPECIAL_CHARS));
            $desiredqty = trim(filter_input(INPUT_POST, 'qty', FILTER_SANITIZE_NUMBER_INT));
            $inventoryItemId = $_SESSION['inventoryItemId'];
            if($desiredqty <= 0 || $desiredqty > $oldQty || empty($desiredqty) || $desiredqty == "") {
                if (empty($desiredqty) || $desiredqty == "") {
                    $desiredqty = 0;
                }
                $_SESSION['message'] = "<p class='alertmessage'>There is only $oldQty in the inventory. You are trying to buy $desiredqty.";
                header ("Location: /mbt/uniforms/index.php?action=sellingitem&inventoryItemId=$inventoryItemId");
                exit;
            } 
            //will check if invId alread exists in cart
            if(isset($_SESSION['cart'])) {
                $existInCart = array_search($invIdUniform, array_column($_SESSION['cart'], 'invIdUniform'));
                if ($existInCart) {
                    $amountInCart = $_SESSION['cart'][$existInCart]["invQty"];
                    $_SESSION['cart'][$existInCart]["invQty"] = $amountInCart + $desiredqty;
                    $totalAfterAdition = $amountInCart + $desiredqty;
                    $_SESSION['message'] = "<p class='alertmessage'>There were $amountInCart of this item in your cart. You added $desiredqty more. Now you have $totalAfterAdition of this item in your cart.";
                    header ("Location: /mbt/uniforms/index.php?action=edititem&category=$_SESSION[category]");
                    break;
                }
            }
            
            $cartItem = array('invIdUniform'=>$invIdUniform, 'invCost'=>$invCost, 'invPrice'=>$invPrice, 'invQty'=>$desiredqty);
            $_SESSION['cart'][] = $cartItem;
            $cartqty = count($_SESSION['cart']);
            $_SESSION['message'] = "<p class='alertmessage'>Item Added to Cart. You have $cartqty item(s) in your cart.</p>";
            header ("Location: /mbt/uniforms/index.php?action=edititem&category=$_SESSION[category]");
            exit;
            
        case 'cart':
            $itensInCart = $_SESSION['cart'];
            if(empty($itensInCart)||$itensInCart == "" ||is_null($itensInCart)) {
                $message = "<p class='alertmessage'>Your car is empty.</p>";
                include "../views/cart.php";
                exit;
            }

            $itensToBeWritten = [];
            foreach ($itensInCart as $itens) {
                
                $item = returningItensToCart($itens['invIdUniform'], $itens['invQty']);
                array_push($itensToBeWritten, $item);
                }
            
            $htmlcart = buildCartItens($itensToBeWritten);
            include "../views/cart.php";
            exit;
        
            
        case 'removefromcart':
            //will remove line from the table in cart's view.
            $item = trim(filter_input(INPUT_GET, 'item', FILTER_SANITIZE_NUMBER_INT));
            array_splice($_SESSION['cart'],$item,1);
            header("Location: /mbt/uniforms/index.php/?action=cart");
            exit;

        case 'cleancart':
            //will be triggered when user clicks "clean Cart" button. There is no confirmation
            $_SESSION['message'] = "<p class='alertmessage'>You removed all itens from the cart.</p>";
            $_SESSION['cart'] = [];
            header ("Location: /mbt/uniforms/index.php?action=edititem&category=$_SESSION[category]");
            exit;

        case 'checkout':
            //open a form to receive data from the parent and also receite a data about cart
            $grandTotal = trim(filter_input(INPUT_GET, 'grandtotal', FILTER_SANITIZE_SPECIAL_CHARS));
            $parentsname = getUserName($_SESSION['clientData']);
            $email = getUserEmail($_SESSION['clientData']);
            if (isset($_SESSION['clientData']['kidsName'])) {
                $kidsName = getUserKidsName($_SESSION['clientData']);
            }
            $personaltable = buildPersonalTable($parentsname, $email, $kidsName);
            $itensInCart = $_SESSION['cart'];
            $itensToBeWritten = [];
            foreach ($itensInCart as $itens) {
                
                $item = returningItensToCart($itens['invIdUniform'], $itens['invQty']);
                array_push($itensToBeWritten, $item);
                }

            $htmlcart = buildCartItensfinal($itensToBeWritten);
            $_SESSION['grandtotal'] = $grandTotal;
            include ("../views/slipcheckout.php");   
            //posso usar esse step para construir o slip de venda que depois vai ser reproduzido no relatório de vendas.
            //poe todos os dados de usuário e do carrinho
            exit;

        case 'finish':
            $grandTotal = $_SESSION['grandtotal'];
            $parentsname = getUserName($_SESSION['clientData']);
            $email = getUserEmail($_SESSION['clientData']);
            $serialcart = serialize($_SESSION['cart']);
            if (isset($_SESSION['clientData']['kidsName'])) {
                $kidsName = getUserKidsName($_SESSION['clientData']);
            }
            //Interrupt proccess in case there is no cart available
            if (!isset($_SESSION['cart'])) {
                $_SESSION['message'] = "<p class='alertmessage'>Error accessing database. Try again latter</p>";
                header ('Location: /mbt/uniforms/');
                exit;
            }else {
                //This else block will write data in a log file and remove the item from inventory
                $dataToBeWritten = " ";
                foreach($_SESSION['cart'] as $item) {
                    $validateamount = checkAmountOfInventory($item['invIdUniform']);
                    if(intval($validateamount[0]>0)) {
                        $result = removeInventoryItem($item['invIdUniform'], $item['invQty']);
                        if($result) {
                            $dataToBeWritten .= logItemSold($result, $item['invQty']);
                        }
                    } else {
                        $result = removeInventoryItem($item['invIdUniform'], $item['invQty']);
                        if($result) {
                            $dataToBeWritten .= logItemSold($result, $item['invQty'])."----------------------ATENTION AN ITEM BECAME NEGATIVE-------\r\n";
                        }
                    }}
                $headerLog = logheaderItemSold($name, $email, $parentsname, $kidsName);
                $textToBeWritten = $headerLog.$dataToBeWritten."\r\n -------------AMOUNT TO BE CHARGED - R$ ".$grandTotal."-------------\r\n"."\r\n -------------Slip is Ended-------------\r\n\r\n\r\n";
                writeFile('uniformsold.txt', $textToBeWritten);
                }

            //go to a register in a data base about selling
            $insertItensInDataBase = insertSellingData($parentsname, $kidsName, $grandTotal, $serialcart);
            $soldId = $insertItensInDataBase[0]["soldId"];
            //Transation is completed.
            $itensInCart = $_SESSION['cart'];
            $insertOrder = 0;
            try {
                foreach ($itensInCart as $itens) {  
                    $item = returningItensToCart($itens['invIdUniform'], $itens['invQty']);
                    $insertOrder = insertOrderData($soldId, $item['itemDescription'],$item['invPrice'],$item ["sizes"], $item['qty'], $_SESSION['clientData']['clientId']);   
                }
            } catch (Exception $e) {
                    $_SESSION['message'] = "<p class='alertmessage'>Error accessing database. Try again latter</p>";
                    header ('Location: /mbt/uniforms/');
                    exit;
            }

            try {
                $itensToBeWritten = [];
                foreach ($itensInCart as $itens) {
                    $item = returningItensToCart($itens['invIdUniform'], $itens['invQty']);
                    array_push($itensToBeWritten, $item);
                    }
                    $htmlslip = buildCartItensend($itensToBeWritten, $name, $email, $parentsname, $kidsName, $soldId);
                    unset($_SESSION['cart']);
                    $message = "<p class='alertmessage'>We are processing your order and the itens will be sent inside your kid's backpack.<br>You will be charged in your next available 'boleto'
                    <br>You don't need to print this page, but we do recomend you keep your Control Number just in case you need reviewing your order.</p>";
                    include "../views/finalslip.php";
                    exit;
                } catch (Exception $e) {
                    $_SESSION['message'] = "<p class='alertmessage'>Error accessing database. Try again latter</p>";
                    header ('Location: /mbt/uniforms/');
                    exit;
            }
            exit;
                
    case 'reportfilter':
        $timestart = trim(filter_input(INPUT_GET, 'timestart'));
        $timestartstr = strval($timestart);
        $timeend = trim(filter_input(INPUT_GET, 'timeend'));
        $timeendstr = strval($timeend);
        $timeend = strtotime($timeend);
        $timeend = $timeend + 24*60*60;
        $timestart = strtotime($timestart);
        $itenssolded = getItensSold(date('Y-m-d', $timestart), date('Y-m-d', $timeend));
        $htmlreport = buildsoldreport($itenssolded);
        $_SESSION['timestart'] = $timestartstr;
        $_SESSION['timeend'] = $timeendstr;

        include '../views/report.php';
        exit;

    case 'changestatus':
        $orderId = trim(filter_input(INPUT_GET, 'item', FILTER_SANITIZE_SPECIAL_CHARS));
        $field = trim(filter_input(INPUT_GET, 'field', FILTER_SANITIZE_SPECIAL_CHARS));
        $value = trim(filter_input(INPUT_GET, 'value', FILTER_SANITIZE_NUMBER_INT));
        $changestatus = changeOrderStatus($orderId, $field, $value);
        $timestart = $_SESSION['timestart'];
        $timeend = $_SESSION['timeend'];
        header ("Location: /mbt/uniforms/index.php?timestart=$timestart&timeend=$timeend&action=reportfilter");
        exit;

    default:
        include '../views/500.php';
        break;
    }

//Above are new cases to work on the future.

    // case 'newiteminventory':
    //     //This page will manage insertion of new itens to the inventory
    //     $categoryId = $_SESSION['category'];
    //     $category = getCategory($categoryId);
    //     $htmlCategory = selectionGenerator("category", $category);
    //     $sizes = getSizes($category);
    //     $htmlSizes = selectionGenerator('size', $sizes);
        
    //     include '../views/newiteminventory.php';
    //     exit;

    // case 'continueregisteritem':
    //     $category = trim(filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT));
    //     $description = trim(filter_input(INPUT_POST, 'itemdescription', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    //     $size = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_NUMBER_INT);
    //     $price = trim(filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT));
    //     $cost = trim(filter_input(INPUT_POST, 'cost', FILTER_SANITIZE_NUMBER_FLOAT));
    //     $qtd =  trim(filter_input(INPUT_POST, 'qtd', FILTER_SANITIZE_NUMBER_INT));
    //     if($_FILES['file1']['name']) {
    //         $imgName = $_FILES['file1']['name'];
    //         $imgPath = upLoadFile('file1');
    //     } else {
    //         $imgPath = "/mbt/assets/images/infantilredondofundobranco.png";  
    //     }
    //     $result = insertInventoryItem($category, $description, $imgPath, $price);
    //     $invItemID = getMaxValue();
    //     $invItem =  $invItemID[0];
    //     $qty = intval($qtd);
    //     $secondinsertion = insertUniformInventory($category, $invItem, $size, $cost, $qty);
    //     header ("Location: /mbt/uniforms/index.php/?action=edititem&category=$category");
    //     exit;

?>