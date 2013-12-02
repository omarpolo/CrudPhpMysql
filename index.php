<?php


include "dbfuncs.php";
include "funkcije.php";


  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  header("Cache-Control: no-store, no-cache, must-revalidate");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");

  // authenticate

  session_start();



/*  if (!isset($_SERVER['PHP_AUTH_USER']) || ($_POST['SeenBefore'] == 1 && $_POST['OldAuth'] == 
$_SERVER['PHP_AUTH_USER']))
  {
    header('WWW-Authenticate: Basic realm="Enter your username and password"');
    header('HTTP/1.0 401 Unauthorized');
    echo "You must enter a valid username and password to access the system.\n";
    exit;
  } 


 if (find_manager($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) == false )
 {
   echo "<form action='{$_SERVER['PHP_SELF']}' METHOD='post' />\n";
   echo "<input type='hidden' name='SeenBefore' value='1' />\n";
   echo "<input type='hidden' name='OldAuth' value='{$_SERVER['PHP_AUTH_USER']}'/>\n";
   echo "<input type='submit' value='Reuuu Authenticate' />\n";
   echo "</form></p>\n";
   exit;
 }
*/

   $_SERVER['PHP_AUTH_USER']=admin_user;
   $_SERVER['PHP_AUTH_PW']=rootexec_psw;
  
  find_manager($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
  
  // read the main template

  $filename = adminmain_tpl;
  if(file_exists($filename))
    $page = implode("", file($filename));
  else
    die("Can't find $filename");

  if ($_REQUEST['cont'] == "")
    $_REQUEST['cont'] = "list_kontakti";

  switch ($_REQUEST['cont'])
  {

/*#####################################################################################################################################*/	  
	case "ask_del_kontakti":
      $subpage = ask_del_kontakti($_REQUEST['username']);
      break;
  	case "del_kontakti":
      $subpage = del_kontakti($_REQUEST['username']);
      break;

	case "update_kontakti":
      $subpage = update_kontakti($_REQUEST['username'],$_REQUEST['fullname'],  $_REQUEST['email'], $_REQUEST['password']);
      break;
	case "edit_kontakti":
      $subpage = edit_kontakti($_REQUEST['username'],$_REQUEST['fullname'],  $_REQUEST['email'], $_REQUEST['password'], $_REQUEST['error']);
      break;
    case "store_kontakti":
      $subpage = store_kontakti($_REQUEST['username'],$_REQUEST['fullname'],  $_REQUEST['email'], $_REQUEST['password']);
      break;
	case "list_kontakti":
      $subpage = list_kontakti($_REQUEST['fullname'], $_REQUEST['username'], $_REQUEST['email'], $_REQUEST['password'], $_REQUEST['from']);
      break;
	  
	case "new_kontakti":
      $subpage = new_kontakti($_REQUEST['username'],$_REQUEST['fullname'],  $_REQUEST['email'], $_REQUEST['password'], $_REQUEST['error']);
	  break;

    default:
      if ($_REQUEST['cont'] != "")
      {
        $filename = $_REQUEST['cont'] . ".htm";
        if(file_exists($filename))
          $subpage = implode("", file($filename));
        else
          die("Can't find $filename");
      }
  }

  // replace template data
   
  if (!$_SESSION['usr_name'])
    $current_user = no_user;
  else
    $current_user = $_SESSION['usr_name'];

  $page = str_replace('{CONTENT}', $subpage, $page);
  $page = str_replace('{LASTUSERS}', lastusers, $page);
  $page = str_replace('{DATE}', date("Y-m-d") . "%", $page);
  
  print($page);
  
  
  


?>
