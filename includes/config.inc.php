<?php
$live = false;
$contact_email = 'cordelfenevall@gmail.com';

define('BASE_URI', '/path/to/Web/parent/folder/');
define('BASE_URL', 'www.example.com/');
define('MYSQL', '/path/to/mysql.inc.php');
define('PDFS_DIR', BASE_URI . 'pdfs/');   //  where is the PDFS_DIR..?

session_start();

function my_error_handler($e_number, $e_message, $e_file, $e_line, $e_vars)
{
  global $live, $contact_email;


  $message = "An error occured in script '$e_file' on line $e_line: \n$e_message\n";

  $message .= "<pre>" . print_r(debug_backtrace(), 1) . "</pre>\n";

  $message .= "<pre>" . print_r($e_vars, 1) . "</pre>\n";

  if (!$live) {
    echo '<div class="error">' . nl2br($message) . '</div>';
  } else {
    error_log($message, 1, $contact_email, 'From:admin@example.com');


    if ($e_number != E_NOTICE) {
      echo '<div class="error">A system error occurred. 
        We apologize for the inconvenience.</div>';
    }
  } //  End of $live IF-ELSE.
  return true;
}

set_error_handler('my_error_handler');
