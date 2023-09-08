<?php
session_start();
 include_once(__DIR__ . "../../../db/db_connect.php");

 global $conn;

 if(isset($_POST['moderation'])) {


    
    foreach($_POST['moderation'] as $postValueId ){
        
        $bulk_options = $_POST['bulk_options'];
        
     
        switch($bulk_options) {


            case 'delete':
        
                if ($_SESSION['user_role'] === 'administrator' && $action === 'delete') {
                    foreach ($messageIds as $messageId) {
                        // Perform the deletion based on the message type (thread or reply)
                        // Be sure to validate user permissions and sanitize input.
                        // Here, we're assuming a simple SQL delete query.
        
                        if ($messageType === 'thread') {
                            // Delete a thread
                            $query = "UPDATE threads SET thread_content = NULL WHERE thread_id = ?";
                        } elseif ($messageType === 'reply') {
                            // Delete a reply
                            $query = "UPDATE replies SET reply_content = NULL WHERE reply_id = ?";
                        }
        
                        // Prepare and execute the query (using prepared statements is safer)
                        $stmt = mysqli_prepare($conn, $query);
                        mysqli_stmt_bind_param($stmt, 'i', $messageId);
                        mysqli_stmt_execute($stmt);
                    }
                    // You can add a success message here if needed
                }
                            
                
                            
                            
                         break;


        }
           
            
  
            
               


            
            
    }


            



}


?>
