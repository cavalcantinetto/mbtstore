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
                    <h1>Register New Item</h1>
                    <div class="underline-title"></div>
                    </div>
                <form action="/mbt/uniforms/index.php" method="POST" enctype="multipart/form-data">
                <? if(isset($htmlCategory)) {echo $htmlCategory;}?>
                <br><br>
                <label for='itemdescription'>ITEM DESCRIPTION:</label>
                <br>
                <textarea class="form-content" name="itemdescription" id="itemdescription" type="text" required placeholder="Describe the Item here (Ex. Camisa Fundamental Manga Curta Masculina)"><?php if(isset($itemdescription)) {echo "value='$itemdescription'";}?></textarea>
                <br><br>
                <? if(isset($htmlSizes)) {echo $htmlSizes;}?>
                <br><br>
                <label>UPLOAD IMAGE:</label><br>
                    <input type="file" name="file1">
                <br><br>
                <label for='price'>UNIT PRICE (format: 99.99)</label>
                <input type="number" name="price" id="price" step="0.01" min="30.00" required>
                <br><br>
                <label for='cost'>UNIT COST (format: 99.99)</label>
                <input type="number" name="cost" id="cost" step="0.01" min="0.01" required>
                <br><br>
                <label for='qtd'>QUANTITY</label>
                <input type="number" name="qtd" id="qtd" step="1" min="1" required>
                <br><br>
                <div class="register-reset-btn">
                <input type="submit" name="submit" id="submit-btn" value="Continue">
                </div>
                <input type="hidden" name="action" value="continueregisteritem">
            </form>   
                
            </section>
        </main>
        <footer>
             <?php require($_SERVER['DOCUMENT_ROOT'].'/mbt/snippets/footer.php'); ?>
        </footer>
    </div>
</body>
</html><? unset($_SESSION['message']);?>