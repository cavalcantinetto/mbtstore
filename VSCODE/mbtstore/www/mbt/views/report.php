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
        //echo $navlist;?>
        </nav>
        <main>
            <section>
                <h1>Select the date to retrieve a report</h1>
                <form action="/mbt/uniforms/index.php" method="POST">
                <input type="date" id="timestart" name="timestart"
                value="2022-07-01"
                min="2022-07-01" max="2023-12-31">
                <br>
                <input type="date" id="timeend" name="timeend"
                value="2022-12-31"
                min="2022-07-01" max="2023-12-31">
                <br>
                <input type='hidden' name='action' value='controllerofitenssold'><br>
                <input type='submit' class='submitBtn' name='submit' value='Filtrar'>
                </form>



            </section>
                
                <? if(isset($htmlreport)){echo $htmlreport;}?>;
            </section>
        </main>
        <footer>
             <?php require($_SERVER['DOCUMENT_ROOT'].'/mbt/snippets/footer.php'); ?>
        </footer>
    </div>
</body>
</html>