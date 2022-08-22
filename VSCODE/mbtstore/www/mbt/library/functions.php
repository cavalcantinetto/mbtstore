<?php
//Check if email looks like an email.
function checkemail($clientEmail) {
    $val_email = filter_var($clientEmail, FILTER_VALIDATE_EMAIL);
    return $val_email;
}

//Check password to ensure it is respecting pattern
function checkPassword($clientPassword) {
    $pattern = '/^(?=.*[[:digit:]])(?=.*[[:punct:]\s])(?=.*[A-Z])(?=.*[a-z])(?:.{8,})$/';
    return preg_match($pattern, $clientPassword);
}

function navListCreator($classifications) {
    $navlist = "<ul id='cardul'>";
    $navlist .= "<li><a href='/mbt/' title='View the MBT || Management Home Page'> Home</a></li>";
    foreach ($classifications as $classification) {
        $navlist .= "<li><a href='/mbt/index.php?action=classification&classificationName=".urlencode($classification['classificationName'])."' title='View our $classification[classificationName] Management Page'>$classification[classificationName]</a></li>";
    }
    $navlist .= '</ul>';
    return $navlist;
}

function categoryCreator($categories) {
    $catlist = "<div class='cardscontainer'>";
    foreach($categories as $c) {
        $categoryId = urlencode(strtolower($c['categoryId']));
        $catlist .= "<div class='cardcategory-div'>";
        $catlist .= "<a href='/mbt/uniforms/index.php/?action=edititem&category=$categoryId'><img src='$c[categoryimage]'></a>";
        $catlist .= "<a href=''/mbt/uniforms/index.php/?action=edititem&category=$categoryId'</a>";
        $catlist .= "</div>";
    }
    $catlist .= "</ul>";
    return $catlist;

}

function buildTableByCategory($uniformItens) {
    $table = "<div class='cardscontainer'>";
    $table .= "<div class='tg-wrap'><table class='tg'>";
    $table .='<thead>';
    $table .='<tr>';
    $table .='<th class="tg-ul38">Image</th>';
    $table .='<th class="tg-ul38">Description</th>';
    $table .='<th class="tg-ul38">Add Item</th>';
    $table .='<th class="tg-ul38">Remove Item</th>';
    $table .='</tr>';
    $table .='</thead>';
    $table .= '<tbody>';
    foreach($uniformItens as $u) {
        $table .= '<tr>';
        $table .= "<td class='tg-0lax'><img src='$u[invImg]'alt='uniform image'</td>";
        $table .= "<td class='tg-0lax'>$u[itemDescription]</td>";
        $table .= "<td class='tg-0lax'><a href='?action=additem&inventoryItemId=$u[invId]'><button type='button'>+</button></a></th>";
        $table .= "<td class='tg-0lax'><a href='?action=removeitem&inventoryItemId=$u[invId]'><button type='button'>-</button></a></th>";
        $table .= '</tr>';   
    }
    $table .= '</body></table></div>';
    return $table;
}

function buildTableByCategorylowprofile($uniformItens) {
    $table = "<div class='cardscontainer'>";
    $table .= "<div class='tg-wrap'><table class='tg'>";
    $table .='<thead>';
    $table .='<tr>';
    $table .='<th class="tg-ul38">Image</th>';
    $table .='<th class="tg-ul38">Description</th>';
    $table .='<th class="tg-ul38">Price</th>';
    $table .='<th class="tg-ul38">Available sizes</th>';
    $table .='</tr>';
    $table .='</thead>';
    $table .= '<tbody>';
    foreach($uniformItens as $u) {
        $uniformData = getSizesByInvId($u['invId']);
        $table .= '<tr>';
        $table .= "<td class='tg-0lax'><img src='$u[invImg]'alt='uniform image'></td>";
        $table .= "<td class='tg-0lax'>$u[itemDescription]</td>";
        $table .= "<td class='tg-0lax'>$u[invPrice]</td>";
        $table .= "<td><table><tr>";
        if(!empty($uniformData)) {
            foreach($uniformData as $u) {
                if($u['invQtd']>0) {
                    $table .= "<td class='tg-0lax' style='border: none'>";
                    $table .= "<form action='/mbt/uniforms/index.php' method='POST'>";
                    $table .= "<input type='hidden' name='invcost' value='$u[invCost]'>";
                    $table .= "<input type='hidden' name='invIdUniform' value='$u[invIdUniform]'>";
                    $table .= "<input type='hidden' name='previousqtd' value='$u[invQtd]'>";
                    $table .= "<input type='hidden' name='qty' value='1'>";
                    $table .= "<input type='hidden' name='invPrice' value='$u[invPrice]'>";
                    $table .= "<input type='hidden' name='action' value='sendtocart'>";
                    $table .= "<input type='submit' class='cartBtn' value='$u[sizes]' title='Click to move this item to your cart'/>";
                    $table .= '</form></td>';
                    } 

                    }
                } else { 
                    $table .= "<td class='tg-0lax' style='border: none'><p>Unavailable</p></td>";  
            } 
            $table .= '</tr></table></td>';
            $table .= '</tr>'; 
    }
    $table .= '</tbody></table></div>';
    return $table;
}



function buildTableByinvItem($uniformItens) {
    $table = "<div class='cardscontainer'>";
    $table .= "<div class='tg-wrap'><table class='tg'>";
    $table .= '<tbody>';
    $table .= '<tr>';
    $img = $uniformItens['0']['invImg'];
    $itemDesc = $uniformItens['0']['itemDescription'];
    $table .= "<td class='tg-0lax'><img src='$img' alt='uniform image'</td>";
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= "<td class='tg-0lax'>$itemDesc</td>";
    $table .= '</tr>';
    $table .= '</body></table></div><br><br>';
    $table .= "<div class='cardscontainer'>";
    $table .= "<div class='tg-wrap'><table class='tg'>";
    $table .='<thead>';
    $table .='<tr>';
    $table .='<th class="tg-ul38">Time of Insertion</th>';
    $table .='<th class="tg-ul38">Size</th>';
    $table .='<th class="tg-ul38">Inventory Qty</th>';
    $table .='<th class="tg-ul38">Cost</th>';
    $table .='<th class="tg-ul38">Justify</th>';
    $table .='<th class="tg-ul38">Managing Qty</th>';
    $table .='<th class="tg-ul38">Remove Item</th>';
    $table .='</tr>';
    $table .='</thead>';
    $table .= '<tbody>';
    foreach($uniformItens as $u) {
        $table .= '<tr>';
        $table .= "<td class='tg-0lax'>$u[invTime]</td>";
        $table .= "<td class='tg-0lax'>$u[sizes]</td>";
        $table .= "<td class='tg-0lax'>$u[invQtd]</td>";
        $table .= "<td class='tg-0lax'>R$ $u[invCost]</td>";
        $table .= "<form action='/mbt/uniforms/index.php' method='POST'>";
        $table .= "<td class='tg-0lax'><textarea name='justify' placeholder='The reason for manage item.'></textarea></td>";
        $table .= "<td class='tg-0lax'><input type='number' name='qtd' min='1' max='$u[invQtd]' step='1'></td>";
        $table .= "<input type='hidden' name='action' value='deleteitem'>";
        $table .= "<input type='hidden' name='invIdUniform' value='$u[invIdUniform]'>";
        $table .= "<input type='hidden' name='previousqtd' value='$u[invQtd]'>";
        $table .= "<td class='tg-0lax'><input type='submit' value='SUBTRACT'/></td>";
        $table .= '</form></tr>';   
    }
    $table .= '</body></table></div>';
    return $table;
}

function buildTableByinvItemAdd($uniformItens) {
    $table = "<div class='cardscontainer'>";
    $table .= "<div class='tg-wrap'><table class='tg'>";
    $table .= '<tbody>';
    $table .= '<tr>';
    $img = $uniformItens['0']['invImg'];
    $itemDesc = $uniformItens['0']['itemDescription'];
    $table .= "<td class='tg-0lax'><img src='$img' alt='uniform image'</td>";
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= "<td class='tg-0lax'>$itemDesc</td>";
    $table .= '</tr>';
    $table .= '</body></table></div><br><br>';
    $table .= "<div class='tg-wrap'><table class='tg'>";
    $table .='<thead>';
    $table .='<tr>';
    $table .='<th class="tg-ul38">Time of Insertion</th>';
    $table .='<th class="tg-ul38">Size</th>';
    $table .='<th class="tg-ul38">Inventory Qty</th>';
    $table .='<th class="tg-ul38">Receipts Number</th>';
    $table .='<th class="tg-ul38">Cost</th>';
    $table .='<th class="tg-ul38">Manage Qty</th>';
    $table .='<th class="tg-ul38">Add Item</th>';
    $table .='</tr>';
    $table .='</thead>';
    $table .= '<tbody>';
    foreach($uniformItens as $u) {
        $table .= '<tr>';
        $table .= "<td class='tg-0lax'>$u[invTime]</td>";
        $table .= "<td class='tg-0lax'>$u[sizes]</td>";
        $table .= "<td class='tg-0lax'>$u[invQtd]</td>";
        $table .= "<form action='/mbt/uniforms/index.php' method='POST'>";
        $table .= "<td class='tg-0lax'><textarea name='justify' placeholder='Input receipts number.'></textarea></td>";
        $table .= "<td class='tg-0lax'><input type='number' name='cost' min='0.00' step='0.1'></td>";
        $table .= "<td class='tg-0lax'><input type='number' name='qtd' min='1' max='100' step='1'></td>";
        $table .= "<input type='hidden' name='action' value='insertitem'>";
        $table .= "<input type='hidden' name='invIdUniform' value='$u[invIdUniform]'>";
        $table .= "<input type='hidden' name='prevcost' value='$u[invCost]'>";
        $table .= "<input type='hidden' name='prevqtd' value='$u[invIdUniform]'>";
        $table .= "<td class='tg-0lax'><input type='submit' value='INSERT'/></td>";
        $table .= '</form></tr>';
    }
    $table .= '</body></table></div></div></div>';
    return $table;
}

//this function will return a table to be delivered at sellingitem
function buildTableByinvIdUniform($uniformItens) {
    $table = "<div class='cardscontainer'>";
    $table .= "<div class='tg-wrap'><table class='tg'>";
    $table .= '<tbody>';
    $table .= '<tr>';
    $img = $uniformItens['0']['invImg'];
    $itemDesc = $uniformItens['0']['itemDescription'];
    $table .= "<td class='tg-0lax'><img src='$img' alt='uniform image'</td>";
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= "<td class='tg-0lax'>$itemDesc</td>";
    $table .= '</tr>';
    $table .= '</body></table></div><br><br>';
    $table .= "<div class='cardscontainer'>";
    $table .= "<div class='tg-wrap'><table class='tg'>";
    $table .='<tr>';
    $table .='<th class="tg-ul38">Available Sizes</th>';
    $table .='<th class="tg-ul38">Units Available</th>';    
    $table .='<th class="tg-ul38">Unit Price</th>';
    $table .='<th class="tg-ul38">Quantity</th>';
    $table .='<th class="tg-ul38">Move to Cart</th>';
    $table .='</tr>';
    $table .= '<tbody>';
    $order = 1;
    foreach($uniformItens as $u) {
        $table .= '<tr>';
        $table .= "<td class='tg-0lax'>$u[sizes]</td>";
        $table .= "<td class='tg-0lax'>$u[invQtd]</td>";
        $table .= "<td class='tg-0lax'>R$ $u[invPrice]</td>";
        $table .= "<td class='tg-0lax'><form id='form$order' action='/mbt/uniforms/index.php' method='POST'>";
        $table .= "<input form='form$order' type='hidden' name='invcost' value='$u[invCost]'>";
        $table .= "<input form='form$order' type='hidden' name='invIdUniform' value='$u[invIdUniform]'>";
        $table .= "<input form='form$order' type='hidden' name='previousqtd' value='$u[invQtd]'>";
        $table .= "<input form='form$order' type='hidden' name='invPrice' value='$u[invPrice]'>";
        $table .= "<input form='form$order' type='hidden' name='action' value='sendtocart'>";
        $table .= "<input type='number' form='form$order' name='qty' min='1' max='$u[invQtd]' step='1'/></td>";
        $table .= "<td class='tg-0lax'><input form='form$order' type='submit' class='cartBtn' value='Move to Cart'/></td>";
        $table .= '</form>';
        $table .= '</tr>';
        $order = $order + 1;
    }
    $table .= '</body></table></div>';
    return $table;
}

//it will return personal data at final slip.
function buildPersonalTable($parentName, $email, $kidsName) {
    $table = "<div class='cardscontainer'>";
    $table .= "<div class='tg-wrap'><table class='tg'>";
    $table .= '<tbody>';
    $table .= "<tr><td>Name: $parentName</td></tr>";
    $table .= "<tr><td>Kid's Name: $kidsName</td></tr>";
    $table .= "<tr><td>Email: $email</td></tr>";
    $table .= '</tbody>';
    $table .= '</table></div></div>';
    return $table;
}

function logItemAdded($data) {
    $datatobewritten = $data['invTime'].' |invIdUniform: '.$data['invIdUniform'].' |invItem: '.$data['itemDescription']. ' |Qtd: '.$data['invQtd'].' |By: '.$data['name'].' |Email: '.$data['email']."\r\n";
    $file = fopen('../logs/uniforminsertions.txt', "a");
    fwrite($file, $datatobewritten);
    fclose($file);
    return "log registered";

}

function logItemRemoved($data) {
    $datatobewritten = $data['invTime'].' |invIdUniform: '.$data['invIdUniform'].' |invItem: '.$data['itemDescription'].' |By: '.$data['name'].' |Email: '.$data['email']."\r\n";
    $datatobewritten .= 'Obs: '.$data['justify']."\r\n";
    $datatobewritten .= 'Removed: '.$data['invQtd']."\r\n";
    $datatobewritten .= "--------------------------------------\r\n";
    $file = fopen('../logs/uniformremovals.txt', "a");
    fwrite($file, $datatobewritten);
    fclose($file);
    return "log registered";

}

function logItemSold($data, $invQty) {
    $datatobewritten = "--------------------------------------------------------------------------------------------------\r\n";
    $datatobewritten .= $data['invTime']."\r\n"; 
    $datatobewritten .= '|Id Uniforme: '.$data['invIdUniform'].' |Item: '.$data['itemDescription'].' - Qty: '.$invQty."units."."\r\n";
    $datatobewritten .= '|invIdUniform: '.$data['invIdUniform'].' |invItem: '.$data['itemDescription'].'  |Price: '.$data['invPrice'].' |Total Price:'.adjustCurrency(floatval(($invQty)*floatval($data['invPrice'])))."\r\n";
    $datatobewritten .= 'Obs: '.$data['justify']."\r\n";
    $datatobewritten .= 'Left in inventory: '.$data['invQtd']."units."."\r\n";
    $datatobewritten .= "--------------------------------------------------------------------------------------------------\r\n";
    return $datatobewritten;

}

function logheaderItemSold($name, $email, $parentName, $kidsName) {
    $datatobewritten = "______________SLIP START____________________\r\n";
    $datatobewritten .= '|Sold By: '.$name.' |Email: '.$email."\r\n";
    $datatobewritten .= "Parent's Name: ".$parentName."\r\n";
    $datatobewritten .= "Kid's Name: ".$kidsName."\r\n";
    return $datatobewritten;
}

function writeFile($file, $data) {
    $file = fopen("../logs/".$file, "a");
    fwrite($file, $data);
    fclose($file);

}

function getUserName($data) {
    $name = $data['clientFirstname']." ".$data['clientLastname'];
    return $name;
}

function getUserNameByClientId($clientId) {
    $name = getClientId($clientId);
    $result = $name['clientFirstname']." ".$name['clientLastname'];
    return $result;
}

function getUserEmail($data) {
    $email = $data['clientEmail'];
    return $email;
}

function getUserKidsName($data) {
    $kidsName = $data['kidsName'];
    return $kidsName;
}


function selectionGenerator($labelfor, $arrayitens) {
    $labelfortext = strtoupper($labelfor);
    $text = "<label for='$labelfor'>$labelfortext:</label>";
    $text .= "<select class='form-content' name='$labelfor' id='$labelfor'>";
    foreach($arrayitens as $k => $v) {
        $text .= "<option value='$k'>$v</option>";
    }
    $text .= "</select>";
    return $text;

}

function buildCartItens($itensInCart) {
    //built a table that have image, description, qty, unit price and total.
    $table = "<div class='cardscontainer'>";
    $table .= "<div class='tg-wrap'><table class='tg'>";
    $table .='<thead>';
    $table .='<tr>';
    //$table .='<th class="tg-ul38">Image</th>';
    $table .='<th class="tg-ul38">Item</th>';
    $table .='<th class="tg-ul38">Description</th>';
    $table .='<th class="tg-ul38">Size</th>';
    $table .='<th class="tg-ul38">Quantity</th>';
    $table .='<th class="tg-ul38">Price</th>';
    $table .='<th class="tg-ul38">Total</th>';
    $table .='<th class="tg-ul38">Action</th>';
    $table .='</tr>';
    $table .='</thead>';
    $table .= '<tbody>';
    $grandTotal = 0;
    $cartIndex = 0;
    foreach($itensInCart as $u) {
        $table .= '<tr>';
        $link = $cartIndex;
        $cartIndexadjusted = $cartIndex + 1;
        $table .= "<td class='tg-0lax'>$cartIndexadjusted</td>";
        $table .= "<td class='tg-0lax'>$u[itemDescription]</td>";
        $table .= "<td class='tg-0lax'>$u[sizes]</td>";
        $table .= "<td class='tg-0lax'>$u[qty]</td>";
        $price = adjustCurrency($u['invPrice']);
        $table .= "<td class='tg-0lax'>$price</td>"; 
        $total = intval($u['qty'])*floatval($u['invPrice']);
        $totalAdjusted = adjustCurrency($total);
        $table .= "<td class='tg-0lax'>$totalAdjusted</td>";
        $table .= "<td class='tg-0lax'><a href='/mbt/uniforms/index.php?action=removefromcart&item=$link'><button type='button' class='cartBtn'>Remove from Cart</button></a></td>";
        $table .= '</tr>';
        $grandTotal = $grandTotal + $total;
        $cartIndex =  $cartIndex + 1;

    }
    $grandTotal = adjustCurrency($grandTotal);
    $table .= "<tr><th></th><th></th><th></th><th></th><th class='tg-ul38'>Total Cart</th><th class='tg-ul38'>$grandTotal</th><th></th></tr>";
    $table .= "</body></table></div>";
    $table .= "<form action='/mbt/uniforms/index.php' method='GET'>";
    $table .= "<input type='hidden' name='action' value='checkout'></div>";
    $table .= "<input type='hidden' name='grandtotal' value='$grandTotal'></div>";
    $table .= "<div class='cardscontainer' style='justify-content: center';>";
    $table .= "<a href='/mbt/uniforms/index.php?action=cleancart'><button type='button' class='cartBtn'>Clean Cart</button></a>";
    $table .= "<input type='submit' class='cartBtn' value='Check Out'>";
    $table .= "</div>";

    return $table;
}

function buildCartItensfinal($itensInCart) {
    //built a table that have image, description, qty, unit price and total.
    $table = "<div class='cardscontainer'>";
    $table .= "<div class='tg-wrap'><table class='tg'>";
    $table .='<thead>';
    $table .='<tr>';
    //$table .='<th class="tg-ul38">Image</th>';
    $table .='<th class="tg-ul38">Item</th>';
    $table .='<th class="tg-ul38">Description</th>';
    $table .='<th class="tg-ul38">Size</th>';
    $table .='<th class="tg-ul38">Quantity</th>';
    $table .='<th class="tg-ul38">Price</th>';
    $table .='<th class="tg-ul38">Total</th>';
    $table .='</tr>';
    $table .='</thead>';
    $table .= '<tbody>';
    $grandTotal = 0;
    $cartIndex = 0;
    foreach($itensInCart as $u) {
        $table .= '<tr>';
        $link = $cartIndex;
        $cartIndexadjusted = $cartIndex + 1;
        $table .= "<td class='tg-0lax'>$cartIndexadjusted</td>";
        $table .= "<td class='tg-0lax'>$u[itemDescription]</td>";
        $table .= "<td class='tg-0lax'>$u[sizes]</td>";
        $table .= "<td class='tg-0lax'>$u[qty]</td>";
        $price = adjustCurrency($u['invPrice']);
        $table .= "<td class='tg-0lax'>$price</td>"; 
        $total = intval($u['qty'])*floatval($u['invPrice']);
        $totalAdjusted = adjustCurrency($total);
        $table .= "<td class='tg-0lax'>$totalAdjusted</td>";
        $table .= '</tr>';
        $grandTotal = $grandTotal + $total;
        $cartIndex =  $cartIndex + 1;

    }
    $grandTotal = adjustCurrency($grandTotal);
    $table .= "<tr><th></th><th></th><th></th><th></th><th class='tg-ul38'>Total Cart</th><th class='tg-ul38'>$grandTotal</th></tr>";
    $table .= "</body></table></div>";
    $table .= "<form action='/mbt/uniforms/index.php' method='GET'>";
    $table .= "<input type='hidden' name='action' value='finish'></div>";
    $table .= "<input type='hidden' name='grandtotal' value='$grandTotal'></div>";
    $table .= "<div class='cardscontainer' style='justify-content: center';>";
    $table .= "<input type='submit' class='cartBtn' value='Finish'>";
    $table .= "</div>";

    return $table;
}

function buildCartItensend($itensToBeWritten, $name, $email, $parentName, $kidsName, $soldId) {
        //built a table that have image, description, qty, unit price and total.
        $table = "<div class='cardscontainer'>";
        $table .= "<div class='tg-wrap'><table class='tg'>";
        $table .="<tbody>";
        $table .= "Control Number: $soldId<br>";
        $table .= "Name: $parentName<br>kid's name: $kidsName <br>";
        $table .= "email: $email";
        $table .= '</tbody></table>';
        $table .="<table class='tg'><thead>";
        $table .='<th class="tg-ul38">Item</th>';
        $table .='<th class="tg-ul38">Description</th>';
        $table .='<th class="tg-ul38">Size</th>';
        $table .='<th class="tg-ul38">Quantity</th>';
        $table .='<th class="tg-ul38">Price</th>';
        $table .='<th class="tg-ul38">Total</th>';
        $table .='</thead>';
        $table .= '<tbody>';
        $grandTotal = 0;
        $cartIndex = 0;
        foreach($itensToBeWritten as $u) {
            $table .= '<tr>';
            $link = $cartIndex;
            $cartIndexadjusted = $cartIndex + 1;
            $table .= "<td class='tg-0lax'>$cartIndexadjusted</td>";
            $table .= "<td class='tg-0lax'>$u[itemDescription]</td>";
            $table .= "<td class='tg-0lax'>$u[sizes]</td>";
            $table .= "<td class='tg-0lax'>$u[qty]</td>";
            $price = adjustCurrency($u['invPrice']);
            $table .= "<td class='tg-0lax'>$price</td>"; 
            $total = intval($u['qty'])*floatval($u['invPrice']);
            $totalAdjusted = adjustCurrency($total);
            $table .= "<td class='tg-0lax'>$totalAdjusted</td>";
            $table .= '</tr>';
            $grandTotal = $grandTotal + $total;
            $cartIndex =  $cartIndex + 1;
    
        }
            $grandTotal = adjustCurrency($grandTotal);
            $table .= "<tr><th></th><th></th><th></th><th></th><th class='tg-ul38'>Total Cart</th><th class='tg-ul38'>$grandTotal</th></tr>";
            $table .= "</body></table><br><button onClick='window.print()'>Print this page</button></div>";
            return $table;

}

function buildPersonalData($clientData) {

}

//This function will adjust any currency value to be outputed as Brazilian Reais
function adjustCurrency($total) {
    $totalAdjusted = number_format($total,2,',','.');
    return "R$ $totalAdjusted";
}

///working with images

//This function will hold the upload proccess and will retrieve file path to store in the data base
function uploadFile($name) {
    //get the paths that were set at index.php (vehicle controller)
    global $image_dir, $image_dir_path;
    if(isset($_FILES[$name])) {
        $filename = $_FILES[$name]['name'];
        if(empty($filename)) {
            return;
        }
        //get file from the temp folder on the server
        $src = $_FILES[$name]['tmp_name'];
        //Sets the new path - images folder
        $target = $image_dir_path . '/' . $filename;
        //Moves file to target folder
        move_uploaded_file($src, $target);
        processImage($image_dir_path, $filename);
        //sets file path to DataBase
        $filepath = $image_dir . '/' . $filename;
        return $filepath;
    }
}

function processImage($dir, $filename) {
    $dir = $dir . '/';
    $image_path = $dir . $filename;
    //$image_path_tn = $dir.makeThumbnailName($filename);
    //resizeImage($image_path, $image_path_tn, 200, 200);
    resizeImage($image_path, $image_path, 500, 500);
}

function resizeImage($old_image_path, $new_image_path, $max_width, $max_height) {
    $image_info = getimagesize($old_image_path);
    $image_type = $image_info[2];

    switch($image_type) {
        case IMAGETYPE_JPEG:
            $image_from_file = 'imagecreatefromjpeg';
            $image_to_file = 'imagejpeg';
            break;
       case IMAGETYPE_GIF:
            $image_from_file = 'imagecreatefromgif';
            $image_to_file = 'imagegif';
            break;
        case IMAGETYPE_PNG:
            $image_from_file = 'imagecreatefrompng';
            $image_to_file = 'imagepng';
            break;
        default:
            return; 
    }
    // Get the old image and its height and width
    $old_image = $image_from_file($old_image_path);
    $old_width = imagesx($old_image);
    $old_height = imagesy($old_image);

    // Calculate height and width ratios
    $width_ratio = $old_width / $max_width;
    $height_ratio = $old_height / $max_height;

    // If image is larger than specified ratio, create the new image
    if ($width_ratio > 1 || $height_ratio > 1) {

        // Calculate height and width for the new image
        $ratio = max($width_ratio, $height_ratio);
        $new_height = round($old_height / $ratio);
        $new_width = round($old_width / $ratio);

        // Create the new image
        $new_image = imagecreatetruecolor($new_width, $new_height);

        // Set transparency according to image type
        if ($image_type == IMAGETYPE_GIF) {
            $alpha = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
            imagecolortransparent($new_image, $alpha);
            }

        if ($image_type == IMAGETYPE_PNG || $image_type == IMAGETYPE_GIF) {
        imagealphablending($new_image, false);
        imagesavealpha($new_image, true);
        }

        // Copy old image to new image - this resizes the image
        $new_x = 0;
        $new_y = 0;
        $old_x = 0;
        $old_y = 0;
        imagecopyresampled($new_image, $old_image, $new_x, $new_y, $old_x, $old_y, $new_width, $new_height, $old_width, $old_height);

        // Write the new image to a new file
        $image_to_file($new_image, $new_image_path);
        // Free any memory associated with the new image
        imagedestroy($new_image);
    } else {
        // Write the old image to a new file
        $image_to_file($old_image, $new_image_path);
        }
    // Free any memory associated with the old image
    imagedestroy($old_image);
    } // ends resizeImage function


    function buildsoldreport($itens) {
        //built a table that have image, description, qty, unit price and total.
        $bigtable = "<div class='cardscontainer'>";
        $bigtable .= "<div class='tg-wrap'><table class='tg'>";

        foreach($itens as $i){
            $bigtable .= "<div class='cardscontainer'><tr>";
            $table = "<div class='tg-wrap'><table class='tg'>";
            $table .="<table class='tg'><thead>";
            $table .='<th class="tg-ul38">Date</th>';
            $table .='<th class="tg-ul38">Control Number</th>';
            $table .='<th class="tg-ul38">Name</th>';
            $table .='<th class="tg-ul38">Kid\'s Name</th>';
            $table .='<th class="tg-ul38">Item</th>';
            $table .='<th class="tg-ul38">Description</th>';
            $table .='<th class="tg-ul38">Size</th>';
            $table .='<th class="tg-ul38">Quantity</th>';
            $table .='<th class="tg-ul38">Price</th>';
            $table .='<th class="tg-ul38">Total</th>';
            $table .='<th class="tg-ul38">Sent to parent</th>';
            $table .='<th class="tg-ul38">Charged</th>';
            $table .='<th class="tg-ul38">Paid</th>';
            $table .='</thead>';
            $table .= '<tbody>';
            $grandTotal = 0;
            $cartIndex = 0;
            $itensToBeWritten =  getorderData($i["soldId"]);
            foreach($itensToBeWritten as $u) {
                $table .= '<tr>';
                $cartIndexadjusted = $cartIndex + 1;
                $table .= "<td class='tg-0lax'>$u[date]</td>";
                $table .= "<td class='tg-0lax'>$u[soldId]</td>";
                $parentName = getUserNameByClientId($u['clientId']);
                $table .= "<td class='tg-0lax'>$parentName</td>";
                $table .= "<td class='tg-0lax'>$u[kidsName]</td>";
                $table .= "<td class='tg-0lax'>$cartIndexadjusted</td>";
                $table .= "<td class='tg-0lax'>$u[description]</td>";
                $table .= "<td class='tg-0lax'>$u[size]</td>";
                $table .= "<td class='tg-0lax'>$u[qty]</td>";
                $price = adjustCurrency($u['price']);
                $table .= "<td class='tg-0lax'>$price</td>"; 
                $total = intval($u['qty'])*floatval($u['price']);
                $totalAdjusted = adjustCurrency($total);
                $table .= "<td class='tg-0lax'>$totalAdjusted</td>";
                if ($u['delivered']) {
                    $table .= "<td class='tg-0lax'><a href='/mbt/uniforms/index.php?action=changestatus&item=$u[orderId]&field=delivered&value=0'><button type='button' class='cartBtnG'>Done</button></a></td>";
                } else {
                    $table .= "<td class='tg-0lax'><a href='/mbt/uniforms/index.php?action=changestatus&item=$u[orderId]&field=delivered&value=1'><button type='button' class='cartBtn'>To Do</button></a></td>";
                }
                if ($u['charged']) {
                    $table .= "<td class='tg-0lax'><a href='/mbt/uniforms/index.php?action=changestatus&item=$u[orderId]&field=charged&value=0'><button type='button' class='cartBtnG'>Done</button></a></td>";
                } else {
                    $table .= "<td class='tg-0lax'><a href='/mbt/uniforms/index.php?action=changestatus&item=$u[orderId]&field=charged&value=1'><button type='button' class='cartBtn'>To Do</button></a></td>";
                }
                if ($u['paid']) {
                    $table .= "<td class='tg-0lax'><a href='/mbt/uniforms/index.php?action=changestatus&item=$u[orderId]&field=paid&value=0'><button type='button' class='cartBtnG'>Done</button></a></td>";
                } else {
                    $table .= "<td class='tg-0lax'><a href='/mbt/uniforms/index.php?action=changestatus&item=$u[orderId]&field=paid&value=1'><button type='button' class='cartBtn'>To Do</button></a></td>";
                }
                $table .= '</tr></div>';
                $grandTotal = $grandTotal + $total;
                $cartIndex =  $cartIndex + 1;
        
            }
                $grandTotal = adjustCurrency($grandTotal);
                $table .= "<tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th class='tg-ul38'>Total Cart</th><th class='tg-ul38'>$grandTotal</th></tr>";
                $table .= "</body></table><br></div>";
                $bigtable .= $table;
                $bigtable .= '<h></tr><hr>';

            }
            $bigtable .= '</table></div></div>';
        
        return $bigtable;
        }
?>