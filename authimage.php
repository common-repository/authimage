<?php
/*
Plugin Name: AuthImage
Plugin URI: http://www.gudlyf.com/index.php?p=376
Description: Creates an authentication image to help combat spam in comments.
Version: 3.0
Author: Keith McDuffee
Author URI: http://www.gudlyf.com/
*/

$sess_key_name = "veriword"; // Don't change this

$authimage = get_settings('siteurl') . '/wp-content/plugins/authimage-inc/image.veriword.php';

if ($_GET['type'] == "text") {
  createAICode("text");
  exit;
} elseif ($_GET['type'] == "image") {
  createAICode("image");
  exit;
}

function testValues()
{
?>
<script language="JavaScript" type="text/JavaScript">
function testValues (form) {
  var author = form.author.value;
  var email = form.email.value;
  var code = form.code.value;
  var comment = form.comment.value;
  
  if (author.length < 1) { // Test name entry for any text
    window.alert ("Please enter your name!");
	return false;
  }
  // Uncomment the following lines if you require an email address on comments
  //if (email.indexOf("@") < 0) {  // Test email address entry for somewhat valid email
  //  window.alert ("Please enter an email address!");
  //  return false;
  //}
  if (code.length < 1) {  // Test code length
    window.alert ("Please enter a valid length code!");
	return false;
  }
  if (comment.length < 1) {  // Test for a comment
    window.alert ("Please enter a comment!");
	return false;
  }
  
  return true;
}
</script>
<?php
}

function checkAICode($code)
{
  global $sess_key_name;

  require_once("authimage-inc/class.verificator.php");
  $veri = new VeriFicator($code);
  $verified = $veri->verified();

  $return = ($verified == 1) ? 1 : 0;
  if(!isset($_SESSION[$sess_key_name])) {
    $return = 0;
  } else {
    session_unset();
    session_destroy();
    session_start();
  }

  return $return;
}

add_filter('wp_head','testValues');

?>
