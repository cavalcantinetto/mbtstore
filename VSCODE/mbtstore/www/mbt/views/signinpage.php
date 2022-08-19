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
                        <h2>LOGIN</h2>
                        <div class="underline-title"></div>
                        </div>
                        <form method="POST" class="form">
                        <label for="user-email" style="padding-top:13px">
                        &nbsp;Email
                        </label>
                        <input id="user-email" class="form-content" type="email" name="email" autocomplete="on" required />
                        <div class="form-border"></div>
                        <label for="user-password" style="padding-top:22px">&nbsp;Password</label>
                        <input id="user-password" class="form-content" type="password" name="password" required />
                        <div class="form-border"></div>
                        <span>Passwords must be at least 8 characters and contain at least 1 number, 1 capital letter and 1 special character</span>
                        <!-- build a function to send an email with password - undone yet -->
                        <a href="#"><legend id="forgot-pass">Forgot password?</legend></a>
                        <input id="submit-btn" type="submit" name="submit" value="LOGIN" />

                        <a href="/mbt/accounts/index.php/?action=register" id="signup">Don't have account yet?</a>
                        <input type="hidden" name="action" value='login'>
                        </form>
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