<?php
//handling error reporting  
error_reporting(0);
// session start
session_start();

//including vendor/autoload.php
require_once __DIR__.'/../vendor/autoload.php';
 
use App\Core\DatabaseMigration as DB;
use App\Core\WriteContent      as Write;
use App\Core\FormValidation    as Validation;
$DB          = new DB();
$Write       = new Write();
$Validation  = new Validation();

// ------------------DEFAULT VARIABLES----------------
define('ENV_OUTPUT',      '../../../');
define('ENV_TEMPLATE',    '../templates/env.txt'); 
define('INSTALL_FLAG',    '../system.config');  
define('SQL_FILE_PATH',   '../../public/files/Load.sql');   

// -------------CHECK FLAG IS EXISTS------------------
$Validation->checkFlag(INSTALL_FLAG);

// -----------------MENU & VARIABLE SET---------------
if (!empty($_GET['step'])) {
    switch ($_GET['step']) { 
        case 'installation':
            $content = './app/pages/installation.php';
            $title   = 'Installation';
            //generate token 
            unset($_SESSION['csrf_token']);
            $_SESSION['csrf_token'] = $Validation::csrfToken();
            break;
        case 'complete':
            $content = './app/pages/complete.php';
            $title   = 'Complete';
            //install flag file
            $Write->createFileWithDirectory([
             'outputPath' => INSTALL_FLAG, 
             'content'    => date('d-m-Y h:i:s')
            ]); 
            // delete a file
            $Write->deleteFile(SQL_FILE_PATH); 
            break; 
        default:
        case 'installation':
            $content = './app/pages/installation.php';
            $title   = 'Installation';
            //generate token 
            unset($_SESSION['csrf_token']);
            $_SESSION['csrf_token'] = $Validation::csrfToken();
            break;
    }
} else {
    $content = './app/pages/installation.php';
    $title   = 'Installation';
    //generate token 
    unset($_SESSION['csrf_token']);
    $_SESSION['csrf_token'] = $Validation::csrfToken();
}  



 