<div class='headerlogo'><a href='/mbt/index.php?action=loggedpage'><img src="/mbt/assets/images/thebestcanadianeducationb.png" alt="TheBestCanadianEducation_Logo"></a></div>
<?php if($_SESSION['loggedin']) {
    $clientFirstname  = $_SESSION['clientData']['clientFirstname']; 
    $clientLastname = $_SESSION['clientData']['clientLastname'];
    echo "<div class='loggedinlink'><a href='/mbt/accounts/index.php/?action=clientloggedin'>$clientFirstname $clientLastname</a> |  <a href='/mbt/accounts/index.php/?action=logout'>Log Out</a></div>";
    } else {
        echo "<div class='myaccountlink'><a href='/mbt/accounts/index.php?action=signin'>My Account</a></div>";
    }
    $cart = "<div class='cart'><a href='/mbt/uniforms/index.php/?action=cart'><img src='/mbt/assets/images/cart.jpeg' alt='cartImage'></a></div>";
    if($_SESSION['clientData']['clientLevel'] == 1) {echo $cart;}
?>
