<?php
    $_connection = mysqli_connect(
                                    $_config['database']['hostname'], 
                                    $_config['database']['username'], 
                                    $_config['database']['password'], 
                                    $_config['database']['database']
                                  );
    if(mysqli_connect_errno()){
        echo "Failed to connect to the Database: " . mysqli_connect_error();
    }

    // set default chatacter set to UTF-8
    mysqli_set_charset($_connection,"utf8");

?>