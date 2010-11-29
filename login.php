<?php
session_start();
$root = "http://".$_SERVER['HTTP_HOST'];
$root .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
$config['base_url'] = "$root";
$config['root_dir'] = (dirname(__FILE__) . '/');
require_once $config['root_dir'] . 'includes/bootstrap.inc';
require_once $config['root_dir'] . 'includes/email.inc';
require_once $config['root_dir'] . 'includes/aes.inc';
require_once $config['root_dir'] . 'theme/normal_header.php';
require_once $config['root_dir'] . 'securimage/securimage.php';
connect();
?>
<h1>Login/Register</h1>
<div class="section">
<?php
/**
 * Return the registraion form fragment.
 */
  function HTMLRegistrationForm($msg="", $name="", $email="", $cemail="",$answer="no",$institution="")
{
  // ensure that we go back to home..
  $_SESSION['login_referer_override'] = '/';
  $c_no = "";
  $c_yes = "";
  $c_forgot="";
  if ($answer == "no")
    $c_no = 'checked="checked"';
  if ($answer == "yes")
    $c_yes = 'checked="checked"';
  $retval = "";
  if (!empty($msg))
    $retval .= "<div id='form_error'>$msg</div>\n";
  $sql = "select institutions_name, email_domain from institutions";
  $result = mysql_query($sql) or die("<pre>".mysql_error()."\n$sql");
  $domainMap = array();
  $email_domain = split('@', $email);
  $email_domain = $email_domain[1];
  while ($row = mysql_fetch_assoc($result)) {
    $edomain = $row['email_domain'];
    $iname = $row['institutions_name'];
    if ($edomain) {
      array_push($domainMap, "'$edomain': '$iname'");
      if ($edomain == $email_domain)
	$institution = $iname;
    }
  }
  $domainMap = '{' . join(", ", $domainMap) . '}';
  $retval .= <<<HTML
<br />
<h2>Registration</h2>
<script type="text/javascript">
  function validatePassword(pw) {
    if (pw.length < 6) {
      alert("Please supply a password of at least 6 characters.");
      return false;
    }
    return true;
  }
  function guessInstitution(email) {
    var dm=$domainMap;
    return dm[email.split('@')[1]] || '';
  }
</script>
<form action="{$_SERVER['SCRIPT_NAME']}" method="post"
      onsubmit="return validatePassword(document.getElementById('password').value);">
  <h3>What is your name? (BarleyCAP participants MUST give a full name to be approved)</h3>
  <label for="name">My name is:</label>&nbsp;
  <input type="text" name="name" id="name" value="$name" />
  <h3>What is your e-mail address?			</h3>
  <table border="0" cellspacing="0" cellpadding="0"
	 style="border: none; background: none">
    <tr><td style="border: none; text-align: right;">
	<label for="email">My e-mail address is:<label></td>
      <td style="border:none;">
	<input type="text" name="email" id="email" value="$email" onchange="document.getElementById('institution').value = guessInstitution(document.getElementById('email').value)" />
     </td></tr><tr><td style="border: none; text-align: right;">
	<label for="cemail">Type it again:</label></td>
      <td style="border: none;"><input type="text" name="cemail" id="cemail" value="$cemail" /></td></tr></table>
  <h3>What do you want your password to be?</h3>
  <table border="0" cellspacing="0" cellpadding="0" style="border: none; background: none">
    <tr><td style="border: none; text-align: right;">
	<label for="password">I want my password to be:</label></td>
      <td style="border: none;">
	<input type="password" name="password" id="password" /></td></tr>
    <tr><td style="border: none; text-align: right;">
	<label for="cpassword">Type it again:</label></td>
      <td style="border:none;">
	<input type="password" name="cpassword" id="cpassword" /></td></tr></table>
  <h3>Your Institution (optional for non-BarleyCAP participants.)</h3>
	<table border="0" cellspacing="0" cellpadding="0" style="border: none; background: none"><tr>
	<td style="border: none; text-align: right;">
	<label for="institution">My institution is:<label></td>
	<td style="border:none;">
	<input type="text" name="institution" id="institution"
	       value="$institution" size="30" /></td></tr></table>
  <h3>Are you a Barley CAP participant?</h3>
  <input $c_no type="radio" value="no" name="answer" id="answer_no" />
  <label for="answer_no">No</label>
  <br />
  <input $c_yes type="radio" value="yes" name="answer"
	 id="answer_yes" />
  <label for="answer_yes">Yes</label>
  <br />
  <table border="0" cellspacing=="0" cellpadding="0"
	 style="border: none; background: none">
    <tr><td><img id="captcha" src="./securimage/securimage_show.php"
		 alt="CAPTCHA image"><br>
	    <a href="#" onclick="document.getElementById('captcha').src = './securimage/securimage_show.php?' + Math.random();
				 return false;"></td>
      <td>CAPTCHA:
	<input type="text" name="captcha_code" size="10"
		 maxlength="6"></td></tr></table>
   </table>
  <br />
  <br />
  <input type="submit" name="submit_registration" value="Register" />
  </form>
HTML;
  return $retval;
}

/**
 * Return the login form fragment.
 */
function HTMLLoginForm($msg = "") {
  $email = "";
  if (isset($_GET['e']) && !empty($_GET['e']))
    $email = base64_decode($_GET['e']);
  $c_no = "checked=\"checked\"";
  $c_yes = "";
  if (isset($_GET['a']) && !empty($_GET['a'])) {
    $c_no = "";
    $c_yes = "checked=\"checked\"";
  }

  $retval = "";
  if (!empty($msg))
    $retval .= "<div id='form_error'>$msg</div>";
  $retval .= <<<HTML
<form action="{$_SERVER['SCRIPT_NAME']}" method="post">
  <h3>Why Register?</h3>
  If you&#39;re a member of the Barley Cap team, then you can get full
  access to all the phenotype and genotype data from the project as
  soon as it posted.
  <br> All non-CAP registered users can save data from the advanced
    phenotype search in their account.
    <h3>What is your e-mail address?</h3>
    My e-mail address is:
    <input type="text" name="email" value="$email" />
    <h3>Do you have a password?</h3>
    <input id="answer_no" $c_no type="radio" name="answer" value="no" />
    <label for="answer_no">No, I am a new user.</label>
    <br />
    <input id="answer_yes" $c_yes type="radio" name="answer" value="yes" />
    <label for="answer_yes">Yes, I have a password:</label>
    <input type="password" name="password" onfocus="$('answer_yes').checked = true"/>
    <br />
    <input id="answer_forgot" $c_forgot type="radio" name="answer" value="forgot" />
    <label for="answer_forgot">I forgot my password</label>
    <br />
    <input id="answer_change" $c_change type="radio" name="answer" value="change" />
    <label for="answer_change">I want to change my Password.</label>
    <br />
    <br />
    <input type="submit" name="submit_login" value="Continue" />
   </form>
HTML;
  return $retval;
}

/**
 * Return the html fragment associated with successful login.
 */
function HTMLLoginSuccess() {
  $url = (isset($_SESSION['login_referer'])) ? $_SESSION['login_referer'] : 'index.php';
  return <<< HTML
<p>You have been logged in. Please wait while you are being
redirected or click <a href="$url">here</a>.</p>
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<meta http-equiv="refresh" content="2;url=$url" />
HTML;
}

/**
 * Return the html fragment associated with successful registration.
 */
function HTMLRegistrationSuccess($name, $email) {
  $_SESSION['login_referer_override'] = '/';
  $email = base64_encode($email);
  return <<< HTML
<p>Welcome, $name. You have been registered. An email with the purpose of confirming your registration was sent.
Please wait while you are being redirected to login page
or click <a href="{$_SERVER['SCRIPT_NAME']}">here</a>.</p>
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<meta http-equiv="refresh" content="2;url={$_SERVER['SCRIPT_NAME']}"/>
HTML;
}

/**
 * Check if the given user/password pair belongs to a properly
 * registered user that can be logged in.
 */
function isUser($email, $pass) {
  $sql_email = mysql_real_escape_string($email);
  $sql_pass = mysql_real_escape_string($pass);
  $public_type_id = USER_TYPE_PUBLIC;
  $sql = "select * from users where users_name='$sql_email' and
pass = MD5('$sql_pass') and (abs(email_verified) > 0 or
user_types_uid=$public_type_id) limit 1";
  $query = mysql_query($sql) or die("<pre>".mysql_error()."\n\n\n".$sql."</pre>");
  return mysql_num_rows($query) > 0;
}

/**
 * Check if the user+password confirmed his email.
 */
function isVerified($email, $pass) {
  $sql_email = mysql_real_escape_string($email);
  $sql_pass = mysql_real_escape_string($pass);
  $sql = "select email_verified from users where
users_name='$sql_email' and pass=MD5('$sql_pass')";
  $r = mysql_query($sql);
  $row = mysql_fetch_assoc($r);
  if ($row)
    return $row['email_verified'];
  return FALSE;
}

/**
 * See if the password is right for the user.
 */
function passIsRight($email, $pass) {
  $sql_email = mysql_real_escape_string($email);
  $sql_pass = mysql_real_escape_string($pass);
  $sql = "select pass=MD5('$sql_pass') as passIsRight from users
where users_name='$sql_email'";
  $r = mysql_query($sql);
  $row = mysql_fetch_assoc($r);
  if ($row)
    return $row['passIsRight'];
  return FALSE;
}

/**
 * See if the given email belongs to a registered user at all.
 */
function isRegistered($email) {
  $sql_email = mysql_real_escape_string($email);
  $sql = "select * from users where users_name = '$sql_email'";
  $query = mysql_query($sql) or die("<pre>".mysql_error()."\n\n\n".$sql."</pre>");
  return mysql_num_rows($query) > 0;
}

/**
 * Process the login attempt and return the appropriate html
 * fragment re that
 */
function HTMLProcessLogin() {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $rv = '';
  if (isUser($email, $password)) {
    $_SESSION['username'] = $email;
    $_SESSION['password'] = md5($password);
    $sql = "update users set lastaccess = now() where
users_name = '$email'";
    mysql_query($sql) or die("<pre>" . mysql_error() .
			     "\n\n\n$sql.</pre>");
    $rv = HTMLLoginSuccess();
  }
  else
    if (!passIsRight($_POST['email'], $_POST['password']))
      $rv = HTMLLoginForm("You entered an incorrect e-mail/password combination. Please, try again.");
    else
      if (!isVerified($_POST['email'], $_POST['password']))
	$rv = HTMLLoginForm("You cannot login until you confirm your email (the link was sent to you at the time of registration)");
      else
	$rv = HTMLLoginForm("Login failed for unknown reason.");
  return $rv;
}

/**
 * Process registration attempt and return appropriate html fragment
 */
function HTMLProcessRegistration() {
  if (isRegistered($_POST['email']))
    return HTMLLoginForm("That e-mail address already has an account associated with it. Please, try again.");
  else
    return HTMLRegistrationForm("", "", $_POST['email'], "",
				$_POST['answer'],
				$_POST['institution']);
}

/**
 * Process forgotten password situation and return appropriate html
 * fragment.
 */
function HTMLProcessForgot() {
  global $root;
  // ensure that we go back to home..
  $_SESSION['login_referer_override'] = '/';
  $email = $_POST['email'];
  if (!isRegistered($email))
    return "<h3 style='color: red'>No such user, please register.</h3>";
  else {
    $key = setting('passresetkey');
    $urltoken = urlencode(AESEncryptCtr($email, $key, 128));
    send_email($email, "Hordeum Toolbox : Reset Your Password",
	       "Hi,
Per your request, please visit the following URL to reset your password:
$root/resetpass.php?token=$urltoken");
    return "<h3>An email was sent to you with a link to reset your
password</h3>";
  }
}

/**
 * Process password change situation and return appropriate html
 * fragment
 */
function HTMLProcessChange() {
  $_SESSION['login_referer_override'] = '/';
  $email = $_POST['txt_email'];
  $pass = $_POST['OldPass'];
  $rv = "";
  if (isset($email)) {
    if (isUser($email, $pass))
      if ($_POST['NewPass1'] == $_POST['NewPass2']) {
	$sql_email = mysql_real_escape_string($email);
	$sql_pass = mysql_real_escape_string($_POST['NewPass1']);
	$sql = "update users  set pass=MD5('$sql_pass')
where users_name='$sql_email'";
	if (mysql_query($sql))
	  $rv .= "<h3>Password successfully updated</h3>";
	else
	  $rv .= "<div id='form_error'>unexpected error while updating your password..</div>";
      }
      else
	$rv .= "<div id='form_error'>the two values you provided do not match..</div>";
    else
      $rv .= "<div id='form_error'>username/password pair not recognized</div>";
  }
  else
    $rv .= <<<HTML
<form action="{$_SERVER['SCRIPT_NAME']}" method="post">
<input type="hidden" name="answer" value="change">
<input type="hidden" name="submit_login" value="">
Email ID: <input name= "txt_email" type="text" value="{$email}">
<br />Old Password: <input name = "OldPass" type="password"></input>
<br /><br />
New Password: <input name="NewPass1" type="password"></input><br />
Retype New Password: <input name="NewPass2" type="password"></input>
<br />
<input name="cmd_submit" type="submit" value="Submit"></input>
</form>
HTML;
  return $rv;
}

if (isset($_POST['submit_login'])) {
  if (isset($_POST['answer'])) {
    if ($_POST['answer'] == "no")
      echo HTMLProcessRegistration();
    else if ($_POST['answer'] == "yes")
      echo HTMLProcessLogin();
    else if ($_POST['answer'] == "forgot")
      echo HTMLProcessForgot();
    else if ($_POST['answer'] == "change")
      echo HTMLProcessChange();
    else
      echo HTMLLoginForm();
  }
  else
    echo HTMLLoginForm();
 }
 else if (isset($_POST['submit_registration'])) {
   $name = $_POST['name'];
   $email = $_POST['email'];
   $cemail = $_POST['cemail'];
   $password = $_POST['password'];
   $cpassword = $_POST['cpassword'];
   $answer = $_POST['answer'];
   $institution = $_POST['institution'];

   $error = false;
   $error_msg = "";

   if (empty($name)) {
     $error = true;
     $error_msg .= "- You must provide your name.\n";
   }
   if (empty($email)) {
     $error = true;
     $error_msg .= "- You must provide your e-mail addresses.\n";
   }
   else {
     if (empty($cemail) || $email != $cemail) {
       $error = true;
       $error_msg .= "- The e-mail address you provided don't match.\n";
     }
   }	
   if (empty($password)) {
     $error = true;
     $error_msg .= "- You must provide a password.\n";
   }
   else {
     if (empty($cpassword) || $password != $cpassword) {
       $error = true;
       $error_msg .= "- The passwords you provided don't match.\n";
     }
   }
   $securimage = new Securimage();
   if (!$securimage->check($_POST['captcha_code'])) {
     $error = true;
     $error_msg .= "- Please enter the CAPTCHA code correctly.\n";
   }
   if (isRegistered($_POST['email'])) {
     $error = true;
     $error_msg .= "That e-mail address already has an account associated with it. Please, try again.";
   }

   if ($error)
     echo HTMLRegistrationForm($error_msg, $name, $email, $cemail,
			       $answer, $institution);
   else {
     $safe_email = mysql_real_escape_string($email);
     $safe_password = mysql_real_escape_string($password);
     $safe_name = mysql_real_escape_string($name);
     $safe_institution = $institution ? "'" . mysql_real_escape_string($institution) . "'" : 'NULL';
     $desired_usertype = ($answer == 'yes' ? USER_TYPE_PARTICIPANT :
			  USER_TYPE_PUBLIC);
     $safe_usertype = USER_TYPE_PUBLIC;
     $sql = "insert into users (user_types_uid, users_name, pass,
name, email, institution) values ($safe_usertype, '$safe_email',
MD5('$safe_password'), '$safe_name', '$safe_email',
$safe_institution)";
     mysql_query($sql) or die("<pre>" . mysql_error() .
			      "\n\n\n$sql</pre>");
     $key = setting('encryptionkey');
     $urltoken = urlencode(AESEncryptCtr($email, $key, 128));
     send_email($email, "Please Confirm Your Registration with Hordeum Toolbox",
		"Hi $name,
According to our records, you've created account with Hordeum Toolbox.
Please confirm that this is so by visiting the following URL:
$root/fromemail.php?token=$urltoken

--
Sincerely,
The Hordeum Toolbox Team
");
     if ($desired_usertype == USER_TYPE_PARTICIPANT) {
       $capkey = setting('capencryptionkey');
       $capurltoken = urlencode(AESEncryptCtr($email, $capkey, 128));
       send_email(setting('capmail'),
		  "[THT] Validate CAP Participant $email",
		  "Participant data:
Email: $email,
Name (as entered by the user): $name,
Institution (as entered by the user): $institution

Please use the following link to confirm or reject participant status of this user:
$root/fromcapemail.php?token=$capurltoken
");
     }
     echo HTMLRegistrationSuccess($name, $email);
   }
 }
 else {
   $referer = @(isset($_SESSION['login_referer_override'])) ?
     $_SESSION['login_referer_override'] : $_SERVER['HTTP_REFERER'];
   if (!empty($referer) &&
 	stripos($referer, $_SERVER['HTTP_HOST']) !== FALSE)
     $_SESSION['login_referer'] = $referer;
   unset($_SESSION['login_referer_override']);
   echo HTMLLoginForm();
 }

?>
	</div>
<?php
$footer_div = 1;
include($config['root_dir'].'theme/footer.php');
?>