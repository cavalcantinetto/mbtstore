<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maple Bear Taubaté - Management</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Electrolize&family=Share+Tech&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/mbt/css/stylescard.css">
    <link rel="stylesheet" media="screen" href="/mbt/css/stylescard.css">
</head>

<body>
    <div class="page">
        <header>
            <?php require($_SERVER['DOCUMENT_ROOT'].'/mbt/snippets/header.php'); ?>
        </header>
        <nav>
        <?php 
        //echo $navlist;
        ?>
        </nav>
        <main>
            <section>
            <div id="card">
                <div id="card-content">
                    <? if(isset($_SESSION['message'])) {echo $_SESSION['message'];} ?>
                    <div id="card-title">
                        <h2>Manage Item from inventory</h2>
                    </div>
                        <? if(isset($message)) {echo $message;}?>
                        <? if(isset($htmltable)){echo $htmltable;}?>
                </div>
            </div>
            </section>
        </main>
        <footer>
             <?php require($_SERVER['DOCUMENT_ROOT'].'/mbt/snippets/footer.php'); ?>
        </footer>
    </div>
</body>
</html><? unset($_SESSION['message']);?>