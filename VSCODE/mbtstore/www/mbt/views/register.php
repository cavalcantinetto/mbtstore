<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maple Bear Taubaté - Management</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Electrolize&family=Share+Tech&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/mbt/css/styles.css">
    <link rel="stylesheet" media="screen" href="/mbt/css/stylescard.css">
</head>

<body>
    <div class="page">
        <header>
            <?php require($_SERVER['DOCUMENT_ROOT'].'/mbt/snippets/header.php'); ?>
        </header>
        <nav>
        <?php 
        //echo $navlist;?>
        </nav>
        <main>
            <section>
            
                <div id="card">
                <div id="card-content">
                    <? if(isset($_SESSION['message'])) {echo $_SESSION['message'];} ?>
                    <div id="card-title">
                    <h1>Register</h1>
                    <div class="underline-title"></div>
                    </div>
                <form action="/mbt/accounts/index.php" method="POST">
                <label for='firstName' style="padding-top:13px">First Name:</label>
                <br>
                <input class="form-content" name="clientFirstname" id="firstName" type="text" required placeholder="Pedro" <?php if(isset($clientFirstname)) { echo "value='$clientFirstname'";} ?>)>
                <br><br>
                <label for='lastName'>Last Name:</label>
                <br>
                <input class="form-content" name="clientLastname" id="lastName" type="text" required placeholder="Silva" <?php if(isset($clientLastname)) {echo "value='$clientLastname'";}?>>
                <br><br>
                <label for='kidsName'>Kid's Name:</label>
                <br>
                <input class="form-content" name="kidsname" id="kidsname" type="text" required placeholder="Joãozinho da Silva" <?php if(isset($kidsname)) {echo "value='$kidsname'";}?>>
                <br><br>
                <label>Email</label>
                <br>
                <input class="form-content" name="clientEmail" id="clientEmail" type="email" required placeholder="email@email.com" <?php if(isset($clientEmail)) {echo "value='$clientEmail'";}?>>
                <br><br>
                <label>Password</label>
                <br>
                <input class="form-content" name="clientPassword" id="clientPassword" type="password" required pattern="(?=^.{8,}$)(?=.*\d)(?=.*\W+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$">
                <br>
                <span>Passwords must be at least 8 characters and contain at least 1 number, 1 capital letter and 1 special character</span>
                <br><br>
                <div class="register-reset-btn">
                <input type="submit" name="submit" id="submit-btn" value="Sign in">
                <input type="reset" value="Reset"  id="submit-btn">
                </div>
                <input type="hidden" name="action" value="submitregister">
            </form>   
                
            </section>
        </main>
        <footer>
             <?php require($_SERVER['DOCUMENT_ROOT'].'/mbt/snippets/footer.php'); ?>
        </footer>
    </div>
</body>
</html><? unset($_SESSION['message']);?>