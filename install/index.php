<?php include './app/Config.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Application Installer | <?= (!empty($title) ? $title : null) ?></title>
        <!-- Favicon -->
        <link rel="icon" href="public/img/favicon.png" type="image/png">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="public/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="public/css/font-awesome.min.css">
        <!-- Custom Style -->
        <link rel="stylesheet" href="public/css/style.css">
    </head>
    <body> 

        <!-- MAIN WRAPPER -->
        <div class="main_wrapper col-sm-6 col-sm-offset-3">

            <!-- BEGIN HEADER -->
            <div class="page-header"> 
                <h1 class="text-center"><img src="public/img/favicon.png" width="50"> Application Installer</h1>
            </div>
            <!-- ENDS HEADER -->

            <div class="row"> 
                <!-- BEGIN CONTENT -->
                <div class="col-sm-12">
                    <?php include($content); ?>
                </div>
                <!-- ENDS CONTENT -->

                <!-- BEGIN FOOTER -->
                <div class="col-sm-12">
                    <div class="divider"></div> 
                    <p class="col-sm-12 text-right">Version 4.1 | Developed by <a href="https://marquisvirgo.com">MVL</a></p>
                </div>
                <!-- ENDS FOOTER -->

            </div>
        </div>
        <!-- END MAIN WRAPPER -->

        <!-- ALL SCRIPTS/JS -->
        <script src="public/js/jquery.min.js"></script>
        <script src="public/js/script.js"></script>
    </body>
</html>