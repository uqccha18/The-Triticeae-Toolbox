<?php
require_once 'config.php';
require_once $config['root_dir'] . 'includes/bootstrap.inc';
require_once $config['root_dir'] . 'includes/aes.inc';

if (!isset($_GET['token'])) {
  header('HTTP/1.0 404 Not Found');
  die("This script is useless without a token");
 }

connect();
$token = $_GET['token'];
$email = AESDecryptCtr($token, setting('capencryptionkey'), 128);
$sql_email = mysql_real_escape_string($email);
$user_type_participant = USER_TYPE_PARTICIPANT;
$sql = "select users_uid, name, institution from users where users_name='$sql_email' and user_types_uid<>$user_type_participant";
$r = mysql_query($sql) or die("<pre>" . mysql_error() . "\n\n\n$sql");
if (!mysql_num_rows($r)) {
  header('HTTP/1.0 404 Not Found');
  die("Couldn't find this record in the database");
 }
$row = mysql_fetch_assoc($r);
extract($row);
$html_name = htmlspecialchars($name, ENT_QUOTES);
$html_email = htmlspecialchars($email, ENT_QUOTES);
$html_institution = htmlspecialchars($institution, ENT_QUOTES);
$html_token = htmlspecialchars($token, ENT_QUOTES);

require_once $config['root_dir'].'theme/normal_header.php';
?>
<h1>CAP Participant Confirmation</h1>
<?php
if (!isset($_GET['yes']) && !isset($_GET['no'])) {
  echo <<<HTML
<p>Please confirm the following CAP participant:<br />
  <table>
    <tr><td>Name</td><td>$html_name</td></tr>
    <tr><td>Email</td><td>$html_email</td></tr>
    <tr><td>Institution</td><td>$html_institution</td></tr></table>
    <br />
    <form action="">
      <input type="hidden" name="token" value="{$html_token}"></input>
      <input type="submit" name="yes"
	     value="Yes, this user is a CAP participant"></input>
      <input type="submit" name="no"
	     value="No, this user is not a CAP participant"></input>
    </form>
</p>
HTML;
 }
 else {
   if (isset($_GET['yes'])) {
     $sql = "update users set user_types_uid=$user_type_participant
where users_uid=$users_uid";
     mysql_query($sql) or die("<pre>" . mysql_error() . "\n\n\n$sql");
     echo "<h3>User was marked as CAP participant</h3>";
   }
   else
     echo "<h3>User was NOT marked as CAP participant</h3>";
 }
$footer_div = 1;
include($config['root_dir'].'theme/footer.php');
?>
