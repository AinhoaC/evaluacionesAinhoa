<?php
	include 'funciones.php';
    session_start();
?>
    <!DOCTYPE html>
    <html>
        <body>

            <?php
            // remove all session variables
            session_unset();

            // destroy the session
            session_destroy();
            header('Location: index.html');    
            ?>

        </body>
    </html>