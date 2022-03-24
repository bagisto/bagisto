<?php
    function delete_files($target) {
        if(is_dir($target)){
            $files = glob( $target . '*', GLOB_MARK );
            foreach( $files as $file ){
                delete_files( $file );
            }
            
            rmdir( $target );
        } elseif(is_file($target)) {
            unlink( $target );
        }
    }
    
    $data['success'] = true;
    $data['message'] = 'Success!';
    echo json_encode($data);
    sleep(2);
    
    delete_files($_SERVER['DOCUMENT_ROOT'] . '/installer');
?>
