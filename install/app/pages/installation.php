<div class="content">
    <div class="row"> 
        <div class="col-sm-12">
            <div class="col-sm-12">  
                <table class="table table-bordered"> 
                    <thead>
                        <tr>
                            <th class="white" rowspan="2" width="66%">Before installing the application please ensure the system requirements</th><th colspan="2" class="white">Permission</th>
                        </tr>
                        <tr>
                            <th width="17%" class="white">Require</th><th width="17%" class="white">Meet</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>PHP version</td><th>7.1.13</th>
                            <th>
                                <?php 
                                    echo (version_compare(phpversion(), '7.1.13', '>=')?'<i class="fa fa-check green"></i>':'<i class="fa fa-times red"></i>');
                                    
                                ?>
                            </th>
                        </tr> 
                        <tr>
                            <td><strong>storage</strong> directory</td><th>0777</th>
                            <th>
                                <?php
                                if (is_dir('../storage')) {
                                    echo (substr(sprintf('%o', fileperms('../storage/')), -4)=='0777'?'<i class="fa fa-check green"></i>':'<i class="fa fa-times red"></i>');
                                }
                                ?>
                            </th>
                        </tr> 
                        <tr>
                            <td><strong>.env</strong> file</td><th>0644</th>
                            <th>
                                <?php
                                if (is_file('../.env')) {
                                    echo (substr(sprintf('%o', fileperms('../.env')), -4)>=644?'<i class="fa fa-check green"></i>':'<i class="fa fa-times red"></i>');
                                }
                                ?>
                            </th>
                        </tr> 
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-sm-12">
            <div id="message"></div>
        </div>
        
        <div class="col-sm-12">
            <form action="./app/controller/Setup_process.php" method="post" class="form-horizontal" id="setupForm">

                <input type="hidden" name="csrf_token" value="<?= (!empty($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : null) ?>">

  
                <!-- App URL -->
                <div class="form-group">
                    <label for="app_url"  class="col-sm-4 control-label">App URL *</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="app_url" placeholder="App URL" name="app_url" value="<?= (isset($_POST['app_url']) ? $_POST['app_url'] : 'http://localhost') ?>">
                    </div>
                </div>  

                <!-- Database Connection -->
                <input type="hidden" name="db_connection" value="mysql">

                <!-- Database Hostname -->
                <div class="form-group">
                    <label for="db_host"  class="col-sm-4 control-label">Database Hostname</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="db_host" placeholder="Database Hostname" name="db_host" value="<?= (isset($_POST['db_host']) ? $_POST['db_host'] : '127.0.0.1') ?>">
                    </div>
                </div>   

                <!-- Database Port -->
                <div class="form-group">
                    <label for="db_port"  class="col-sm-4 control-label">Database Port</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="db_port" placeholder="Database Port" name="db_port" value="<?= (isset($_POST['db_port']) ? $_POST['db_port'] : '3306') ?>">
                    </div>
                </div> 

                <!-- Database Name -->
                <div class="form-group">
                    <label for="db_name"  class="col-sm-4 control-label">Database Name *</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="db_name" placeholder="Database Name" name="db_name" value="<?= (isset($_POST['db_name']) ? $_POST['db_name'] : '') ?>">
                    </div>
                </div>  
                
                <!-- Database Username -->
                <div class="form-group">
                    <label for="db_username"  class="col-sm-4 control-label">Database Username *</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="db_username" placeholder="Database Username" name="db_username" value="<?= (isset($_POST['db_username']) ? $_POST['db_username'] : 'root') ?>">
                    </div>
                </div>  
                
                <!-- Database Password -->
                <div class="form-group">
                    <label for="db_password"  class="col-sm-4 control-label">Database Password</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="db_password" placeholder="Database Password" name="db_password" value="<?= (isset($_POST['db_password']) ? $_POST['db_password'] : '') ?>">
                    </div>
                </div>   

                <div class="divider"></div>
                <div class="pull-right">
                    <button type="reset" class="cbtn">Reset</button>
                    <button type="submit" class="cbtn">Install</button>
                </div>

            </form> 
        </div>
    </div>
</div>
