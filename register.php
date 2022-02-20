<?php
require('./includes/config.inc.php');
$page_title = 'Register';
include('./includes/header.html');
require(MYSQL);
require('./includes/form_functions.inc.php');

//  p 84  / 101

$php_errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (preg_match('/^[A-Z\'.-]{2,20}$/i', $_POST['first_name'])) {
    $fn = mysqli_real_escape_string($dbc, $_POST['first_name']);
  } else {
    $reg_errors['first_name'] = 'Please enter your first name!';
  }

  if (preg_match('/^[A-Z\'.-]{2,40}$/i', $_POST['last_name'])) {
    $ln = mysqli_real_escape_string($dbc, $_POST['last_name']);
  } else {
    $reg_errors['last_name'] = 'Please enter your last name!';
  }

  if (preg_match('/^[A-Z0-9]{2,30}$/i', $_POST['username'])) {
    $fn = mysqli_real_escape_string($dbc, $_POST['username']);
  } else {
    $reg_errors['username'] = 'Please enter a desired username!';
  }

  if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $e = mysqli_real_escape_string($dbc, $_POST['email']);
  } else {
    $reg_errors['email'] = 'Please enter a valid email address!';
  }

  if (preg_match('/^(\w*(?=\w*d)(?=\w*[a-z])(?=\w*[A-Z])\w*){6,20}$/', $_POST['pass1'])) {
    if ($_POST['pass1'] == $_POST['pass2']) {
      $p = mysqli_real_escape_string($dbc, $_POST['pass1']);
    } else {
      $reg_errors['pass2'] = 'Your password did not match the confirmed password!';
    }
  } else {
    $reg_errors['pass1'] = 'Please enter a valid password!';
  }   //  End of preg_match

  if (empty($reg_errors)) {
    $q = "SELECT email, username FROM users WHERE email='$e' OR username='$u'";
    $r = mysqli_query($dbc, $q);
    $rows = mysqli_num_rows($r);
    if ($rows == 0) { //  No problems!
      $q = "INSERT INTO users (username, email, pass, first_name, last_name, date_expires)
        VALUES ('$u', '$e', '" . create_password_hash($p) . "', '$fn', '$ln', ADDATE(NOW(), INTERVAL 1 MONTH))";
      $r = mysqli_query($dbc, $q);

      if (mysqli_affected_rows($dbc) == 1) {
        echo '<h3>Thanks!</h3>
          <p>
            Thank you for registering!
            You may now log in and access the site\'s content.</p>';
        $body = "Thnk you for registering at <whatever site>. Blah. Blah. Blah.\n\n";
        mail($_POST['email'], 'Registration Confirmation', $body, 'From: cordelfenevall@gmail.com');
        include('./includes/footer.html');
        exit();
      } else {
        trigger_error('You could not be registered due to a system error.
        We apologize for ny inconvenience.');
      }
    } else {
      if ($rows == 2) { //  Both are taken.
        $reg_errors['email'] = 'This email address has already been
          registered. If you have forgotten your password, use the link at
          right to have your password sent to you.';
        $reg_errors['username'] = 'This username has already been 
          registered. Please try another.';
      } else {  //  One or both may be taken.
        $row = mysqli_fetch_array($r, MYSQLI_NUM);
        if (($row[0] == $_POST['email']) && ($row[1] == $_POST['username'])) { //  Both match.
          $reg_errors['email'] = 'This email address has already been
            registered. If you have forgotten your password, use the link at
            right to have your password sent to you.';
          $reg_errors['username'] = 'This username has already been
            registered with this email address. If you have forgotten your
            password, use the link at right to have your password sent to you.';
        } elseif ($row[0] == $_POST['email']) { //  Email match.
          $reg_errors['email'] = 'This email address has already been
            registered. If you have forgotten your password, use the link at
            right to have your password sent to you.';
        } elseif ($row[1] == $_POST['username']) {  //  Username match.
          $reg_errors['username'] = 'This username has already been
          registered. Please try another.';
        }
      } //  End of $rows == 2 ELSE.
    } //  End of $rows == 0 IF.
  } //  End of empty($reg_errors)IF.
}   //  End of the main form submission conditional.




?><h3>Register</h3>
<p>Access to the site's content is available to registered users at a cost
  of $10.00(US) per year. Use the form below to begin the registration
  process. <strong>Note: All fields are required.</strong> After
  completing this form, you'll be presented with the opportunity to
  securely pay for your yearly subscriptin via
  <a href="http://www.paypal.com">
    PayPal
  </a>.
</p>

<form action="register.php" method="post" accept-chrset="utf-8" style="padding-left:100px">
  <p><label for="first_name"><strong>First Name</strong></label>
    <br />
    <?php create_form_input('first_name', 'text', $reg_errors); ?>
  </p>
  <p><label for="last_name"><strong>Last Name</strong></label>
    <br />
    <?php create_form_input('last_name', 'text', $reg_errors); ?>
  </p>
  <p><label for="username"><strong>Desired Username</strong></label>
    <br />
    <?php create_form_input('username', 'text', $reg_errors); ?>
    <small>Only letters and numbers are allowed.</small>
  </p>
  <p><label for="email"><strong>Email Address</strong></label>
    <br />
    <?php create_form_input('email', 'text', $reg_errors); ?>
  </p>
  <p><label for="pass1"><strong>Password</strong></label>
    <br />
    <?php create_form_input('pass1', 'password', $reg_errors); ?>
    <small>Must be between 6 and 20 characters long, with at least one
      lowercase letter, one uppercase letter, and one number.</small>
  </p>
  <p><label for="pass2"><strong>Confirm Password</strong></label>
    <br />
    <?php create_form_input('pass2', 'password', $reg_errors); ?>
  </p>
  <input type="submit" name="submit_button" value="Next &rarr;" id="submit_button" class="formbutton" />
</form>


<?php
include('./includes/footer.html');



?>