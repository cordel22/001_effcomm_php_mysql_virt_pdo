<?php
$live = false;
$contact_email = 'cordelfenevall@gmail.com';

define('BASE_URI', 'C:\Users\Sisi\Desktop\akcia_14_07_21\php-14-07-21\effcomm_php_mysql\001_effcomm_php_mysql_virt'/* '/path/to/Web/parent/folder/' */);
define('BASE_URL', 'http://localhost:3000/'/* 'www.example.com/' */);
define('MYSQL', 'C:\Users\Sisi\Desktop\akcia_14_07_21\php-14-07-21\effcomm_php_mysql\001_effcomm_php_mysql_virt\mysql.inc.php'/* '/path/to/mysql.inc.php' */);
define('PDFS_DIR', BASE_URI . '/includes/pdfs/');   //  where is the PDFS_DIR..?

session_start();

function my_error_handler($e_number = null, $e_message = null, $e_file = null, $e_line = null, $e_vars = null)
{
  global $live, $contact_email;


  $message = "An error occured in script '$e_file' on line $e_line: \n$e_message\n";

  $message .= "<pre>" . print_r(debug_backtrace(), 1) . "</pre>\n";

  $message .= "<pre>" . print_r($e_vars, 1) . "</pre>\n";

  if (!$live) {
    echo '<div class="error">' . nl2br($message) . '</div>';
  } else {
    error_log($message, 1, $contact_email, 'From:cordelfenevall@gmail.com');


    if ($e_number != E_NOTICE) {
      echo '<div class="error">A system error occurred. 
        We apologize for the inconvenience.</div>';
    }
  } //  End of $live IF-ELSE.
  return true;
} //  End of my_error_handler() definition.

set_error_handler('my_error_handler');
