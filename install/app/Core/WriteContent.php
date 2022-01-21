<?php
namespace App\Core;

class WriteContent
{  

    // create database with post data
    public function createEnvironmentFile($data = array())
    { 
        //check template file is exists
        if (file_exists($data['templatePath'])) {

            //get template data
            $templateFile =file_get_contents($data['templatePath']);

            $appAndDataSearch = array(
                "|APP_URL|",
                "|DB_CONNECTION|",
                "|DB_HOST|",
                "|DB_PORT|",
                "|DB_DATABASE|",
                "|DB_USERNAME|",
                "|DB_PASSWORD|", 
            );
            $appAndDataReplace = array(
                $data['app_url'],
                $data['db_connection'],
                $data['db_host'],
                $data['db_port'],
                $data['db_name'],
                $data['db_username'],
                $data['db_password'] 
            );
            
            $content = str_replace($appAndDataSearch, $appAndDataReplace, $templateFile); 
            //file with string replace 

            //create a new file with string replace
            $this->createFileWithStringReplace([
                'outputPath' => $data['outputPath'],
                'content'    => $content,
            ]);

        } else {
            //template file is not exists
            return false;
        }
    }

    //create a file with string replace
    public function createFileWithStringReplace($file = array())
    { 
        //check output file is exists
        if (file_exists($file['outputPath'])) {

            // chmod the file, in case the user forgot
            @chmod($file['outputPath'],0777);

            // Verify file permissions
            if (is_writable($file['outputPath'])) {
                // Write the file
                if (file_put_contents($file['outputPath'].'.env',$file['content'])) {
                    return true;
                } else {
                    //file not write
                    return false;
                }
            } else {
                //file is not writeable
                return false;
            }
        } else {
            //output file is not exists
            return false;
        }
    }

    //create a file with directory and data
    public function createFileWithDirectory($data = array())
    { 
        //get file info 
        $fileInfo   = pathinfo($data['outputPath']);
        //get the file directory path
        $directoryPath  = $fileInfo['dirname'];
        //get the file name with ext.
        $newFileName = $fileInfo['basename'];

        if (!is_dir($directoryPath)) {
            @mkdir($directoryPath, 0777, true);
        }

        if (file_put_contents($directoryPath.'/'.$newFileName, $data['content'])) {
            return true;
        } else {
            return false;
        }
    }

    // check a file
    public function fileExists($filePath = null)
    {
        //check file exits
        if (!file_exists($filePath)) {
            return false;
        } else {
            return true;
        }
    }


    // delete a file
    public function deleteFile($filePath = null)
    {
        //check file exits
        if (!file_exists($filePath)) {
            return false;
        } else {
            // delete a file
            @unlink($filePath);
        }
    }
}
