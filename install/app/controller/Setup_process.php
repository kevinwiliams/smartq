<?php
require '../Config.php'; 

if($_SERVER['REQUEST_METHOD'] == "POST") {

	$validate = $Validation->validate(array(
	    'csrf_token'    => $_SESSION['csrf_token'],
	    'app_url'       => $_POST['app_url'],  
	    'db_connection' => $_POST['db_connection'],  
	    'db_host'       => $_POST['db_host'],  
	    'db_port'       => $_POST['db_port'],  
	    'db_name'       => $_POST['db_name'],  
	    'db_username'   => $_POST['db_username'],  
	    'db_password'   => $_POST['db_password'],   
	)); 

	if (($validate) === true 
		&& $Write->fileExists(SQL_FILE_PATH)) {
 
		// it is use to create .env file
		$data = array(
			'templatePath' => ENV_TEMPLATE,
			'outputPath'   => ENV_OUTPUT,
		    'app_url'       => $_POST['app_url'],  
		    'db_connection' => $_POST['db_connection'],  
		    'db_host'       => $_POST['db_host'],  
		    'db_port'       => $_POST['db_port'],  
		    'db_name'       => $_POST['db_name'],  
		    'db_username'   => $_POST['db_username'],  
		    'db_password'   => $_POST['db_password'],    
		);
		$Write->createEnvironmentFile($data);

		//create database & tables
		$data = array( 
			'hostname'  => $_POST['db_host'],
			'username'  => $_POST['db_username'],
			'password'  => $_POST['db_password'],
			'database'  => $_POST['db_name']   
		);
		$DB->createDatabase($data);
		$DB->createTables($data); 

        $data['status']  = true;
        $data['success'] = "Success!";
 
	} else { 

		$errors  = "";
		$errors .= "<ul>";
		if(!empty($validate) && is_array($validate))
		foreach ($validate as $error) {
		    $errors .= "<li>$error</li>";
		}
		$errors .= "</ul>";

		$data['status'] = false;
		$data['exception']  = $errors;	
	}

    echo json_encode($data);
}
