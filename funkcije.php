<?php
include "misc.php";
include "dbconf.php";
 error_reporting (E_ALL ^ E_NOTICE); // iskljuci notice
/*#####################################################################################################################################*/


  // system definitions
	
	define("currency", "BAM");			// currency

	
	define("adminmain_tpl", "adminmain_tpl.htm");
	define("adminmenu_tpl", "adminmenu_tpl.htm");
	
	define("kontakti_tpl", "kontakti_tpl.htm");//**************************
	define("kontakti_item_tpl", "kontakti_item_tpl.htm");//**************************
	define("searchkontakti_tpl", "kontakti_tpl.htm");//**************************
	define("newkontakti_tpl", "newkontakti_tpl.htm");//**************************
	define("editkontakti_tpl", "editkontakti_tpl.htm");//**************************
	define("askdelkontakti_tpl", "askdelkontakti_tpl.htm");//**************************
	define("syserror_tpl", "syserror_tpl.htm");
	define("sysmsg_tpl", "sysmsg_tpl.htm");
	
	
	define("kontakti_per_page", 5);

  // strings

	define("str_next_page", "slijedeca stranica");
	define("str_prev_page", "prethodna stranica");
	define("str_no_limit", "bez limita");
	define("str_never_expires", "nikada");
	define("str_default_realname", "Nepoznat korisnik");
	define("str_acc_disabled", "Racun je iskljucen");
	define("str_acc_expired", "Racun je istekao");
	define("str_acc_active", "Racun je aktivan");
	define("str_acc_limit", "<-limit");
	define("str_traffic_reset", "Traffic limits resetted");
	define("str_yes", "da");
	define("str_no", "ne");
  
    // colors

	define("bgcolor0", "#ffffff");
	define("bgcolor1", "#cde78b");
	define("bgcolorover", "#93bf23");
	define("col_sess_active", "#FFDE6B");
	define("col_user_dis", "#f54e25");
	define("col_user_en", "");
	define("col_user_exp", "#FFE400");
	define("col_limiton", "#FFE400");
	define("col_limitoff", "#dee5f2");
  
  //errors
	define("err_kontakti_not_found", "Korisnik ne postoji!");
	define("err_kontakt_postoji", "Kontakt postoji!");
	define("err_not_kontakti_email", "Mail mota biti popunjen!");
	define("err_not_kontakti_fullname", "Full name  is required!");
	define("err_not_kontakti_password", "Password is required!");
	define("err_new_kontakt_prazan_username", "Userame is required!");
	define("err_new_kontakt_prazan_fullname", "Fullname is required!");
	define("err_new_kontakt_prazan_email", "Email is required!");
	define("err_new_kontakt_prazan_password", "Password is required!");
	define("err_new_kontakt_postoji_username", "Username exist!");


  
  //messages
  define("msg_kontakti_deleted", "Kontakt je obrisan!");
  define("msg_kontakti_updated", "Kontakt je uspjesno prepravljen!");
  define("msg_kontakti_created", "Kontakt je upisan!");
/*#####################################################################################################################################*/



/*****************************************************************************/
/**     write_error  				                    **/
/*****************************************************************************/

function write_error($error_text)
{
  if(file_exists(syserror_tpl))
    $page = implode("", file(syserror_tpl));
  else
    die("Can't find " . syserror_tpl);

  $page = str_replace('{ERROR}', $error_text, $page);
  return $page;
}


/*****************************************************************************/
/**       find_manager	                                            **/
/*****************************************************************************/
function find_manager($username, $password)
{
  // check for username/password

  $dblink = dbconnect();
  $res = mysql_query("SELECT username, password FROM dbusers WHERE username = '$username' AND password = '$password'")
    or die("Invalid query");
    
  $row = mysql_fetch_array($res);
  dbdisconnect($dblink);

  if (mysql_num_rows($res) == 1)
    return true;
  else
    return false;
} 




/*#####################################################################################################################################*/
/*#####################################################################################################################################*/
/*#####################################################################################################################################*/

/*****************************************************************************/
/**       list_kontakti				                    **/
/*****************************************************************************/

function list_kontakti($fullname,$username,$email,$password,$from)
{
  // read main template
  
  $filename = kontakti_tpl;
  if(file_exists($filename))
    $searchkontakti_tpl = implode("", file($filename));
  else
    die("Can't find  $filename");

  // read item template
  
  $filename = kontakti_item_tpl;
  if(file_exists($filename))
    $searchkontakti_item_tpl = implode("", file($filename));
  else
    die("Can't find $filename");

  if ($datarate != 0 )
    $datarate *= 1024;
  
 
  $fullname1   = $fullname;
  $username1   = $username;
  $email1       = $email;
  $password1    = $password;
   
  $fullname   = $fullname . "%";
  $username   = $username . "%";
  $email       = $email . "%";
  $password     = $password . "%";

  $dblink = dbconnect();

 
 
 
  // determine the maximum number of users
  $res = mysql_query("SELECT COUNT(*) FROM kontakti WHERE
    		      fullname LIKE '$fullname' AND username LIKE '$username' AND email LIKE '$email'")
    or die("Invalid query kontakti");

  $row = mysql_fetch_row($res);
  $total_kontakti = $row[0];
  
  // set the starting user number and check for errors
  if ( ($from == "") or ($from > $total_kontakti) or ($from < 0) )
   $from = 0;

  $limit = kontakti_per_page;

  $res =
  mysql_query("SELECT  username, fullname, email, password
                      FROM kontakti WHERE
    		      fullname LIKE '$fullname' AND username LIKE '$username' AND email LIKE '$email' 
                      ORDER BY fullname LIMIT $from, $limit")


    or die("Invalid query kontakti greška na mysqlu -- " . mysql_error($dblink));
  $bgcolormis=bgcolorover;
  $bgcolor = bgcolor0;
  $num = $from + 1;
  
  while ( $row = mysql_fetch_array($res) )
  {
    $tmpuserinfo = $searchkontakti_item_tpl;
    $tmpuserinfo = str_replace('{N}',  	      $num, $tmpuserinfo);
    $tmpuserinfo = str_replace('{USERNAME}',  $row[0], $tmpuserinfo);
    $tmpuserinfo = str_replace('{FULLNAME}',  $row[1], $tmpuserinfo);
    $tmpuserinfo = str_replace('{EMAIL}',     $row[2], $tmpuserinfo);
    $tmpuserinfo = str_replace('{PASSWORD}',     $row[3], $tmpuserinfo);
    $tmpuserinfo = str_replace('{BGCOLOR}',   $bgcolor, $tmpuserinfo);
    $tmpuserinfo = str_replace('{BGCOLOROVER}',   "this.style.backgroundColor='" . $bgcolormis . "'", $tmpuserinfo);
	$tmpuserinfo = str_replace('{BGCOLOROUT}',   "this.style.backgroundColor='" . $bgcolor . "'", $tmpuserinfo);

    $subpage.= $tmpuserinfo;
    $num++;
	
    // swap bgcolors
      
    if ($bgcolor == bgcolor0)
      $bgcolor = bgcolor1;
    else
      if ($bgcolor == bgcolor1)
        $bgcolor = bgcolor0;
  }

  $page = str_replace('{KONTAKTI}', $subpage, $searchkontakti_tpl);
  
  // set the link for the "Next" button

  $str_next_page = str_next_page;

  if ($from + users_per_page >= $total_kontakti)
    $page = str_replace('{NEXT}', "", $page);
  else
  {
    $next = $from + kontakti_per_page;

    $page = str_replace('{NEXT}', "<a href=index.php?cont=list_kontakti&from=" . "$next" .
    			 "&username=" . rawurlencode($fullname1) . "&fullname=" . rawurlencode($username1) . "&email=" . rawurlencode($email1) .
      			 ">$str_next_page</a>", $page);
  }

  // set the link for the "Previous" button

  $str_prev_page = str_prev_page;

  if ($from <= 0)
    $page = str_replace('{PREV}', "", $page);
  else
  {
    $prev = $from - kontakti_per_page;
    if ( $prev < 0 )
      $prev = 0;


   $page = str_replace('{PREV}', "<a href=index.php?cont=list_kontakti&from=" . "$prev" .
    			 "&username=" . rawurlencode($fullname1) . "&fullname=" . rawurlencode($username1) . "&email=" . rawurlencode($email1) .
      			 ">$str_prev_page</a>", $page);
  }

  dbdisconnect($dblink);
  return $page;
  
}

/*****************************************************************************/
/**        new_kontakt	                                            **/
/*****************************************************************************/
function new_kontakti ($username,$fullname, $email, $password, $error)
{
  if ($_SERVER['PHP_AUTH_USER'] != admin_user)
    return write_error(err_only_superuser);

 

  // read template

  $filename = newkontakti_tpl;
  if(file_exists($filename))
    $subpage = implode("", file($filename));
  else
    die("Can't find  $filename");


 //--------------------------------------------------------------------------- 


  $subpage = str_replace('{ERROR}',      $error, $subpage);
  $subpage = str_replace('{USERNAME}',   $username, $subpage);
  $subpage = str_replace('{FULLNAME}',   $fullname, $subpage);
  $subpage = str_replace('{EMAIL}',       $email, $subpage);
  $subpage = str_replace('{PASSWORD}',       $password, $subpage);

  

 
  return $subpage;
}

/*****************************************************************************/
/**         store_kontakt	                                            **/
/*****************************************************************************/


function store_kontakti ($username,$fullname,$email,$password)
{
 
  if ($_SERVER['PHP_AUTH_USER'] != admin_user)
    return write_error(err_only_superuser);

  

//---------------------------------- prazne kontrole
  
  //validacija ugovora

  if ($username == "")
    return new_kontakti($username,$fullname,$email,$password, err_new_kontakt_prazan_username);
	
	
	  if ($email == "")
    return new_kontakti($username,$fullname,$email,$password, err_new_kontakt_prazan_email);
	
	
  if ($fullname == "")
    return new_kontakti($username,$fullname,$email,$password, err_new_kontakt_prazan_fullname);
	
  if ($password == "")
    return new_kontakti($username,$fullname,$email,$password, err_new_kontakt_prazan_password);	

//--------------------------------- provjera dal postoji

  $dblink = dbconnect();

  //user postoji
  $res = mysql_query("SELECT username FROM kontakti WHERE username= '$username'")
    or die("Invalid query");

  if (mysql_num_rows($res) > 0)
  {
    dbdisconnect($dblink);
    return new_modem($username,$fullname,$email,$password, err_new_kontakt_postoji_username);
  }

  
//----------------------------------- upisuj
  
  $res = mysql_query("INSERT INTO kontakti (username,fullname,email,password)
                      VALUES ('$username','$fullname','$email','$password') ")
    or die("Invalid query");

  dbdisconnect($dblink);
  
  
   return edit_kontakti ($username,$fullname,$email,$password, msg_kontakti_created);

}
/*****************************************************************************/
/**         edit_kontakti                                                **/
/*****************************************************************************/
function edit_kontakti ($username,$fullname,$email,$password, $error)
{
  $filename = editkontakti_tpl;
  if(file_exists($filename))
    $page = implode("", file($filename));
  else
    die("Can't find $filename");

 


  // get userdatas
 $dblink = dbconnect();
  $res = mysql_query("SELECT username,fullname,email,password FROM kontakti WHERE username='$username'")
    or die("Invalid query");

  if (mysql_num_rows($res) == 0)
  {
    dbdisconnect($dblink);
    return write_error(err_kontakti_not_found);
  }
  $row = mysql_fetch_array($res);

  if ($error == "")
  {
    
    $username1 = $row[0];
    $fullname1 = $row[1];
	$email1 = $row[2];
	$password1 = $row[3];

  }
  else
  {

    $fullname1 =$fullname;
    $username1 = $username;
    $email1 = $email;
	$password1 = $password;	
  }




  $page = str_replace('{USERNAME}',     $username1, $page); 
  $page = str_replace('{FULLNAME}',     $fullname1, $page);
  $page = str_replace('{EMAIL}',         $email1, $page);
  $page = str_replace('{PASSWORD}',      $password1, $page);  
  $page = str_replace('{ERROR}',    $error, $page);

 

  dbdisconnect($dblink);

  return $page;
}
/*****************************************************************************/
/**         update_kontakti                                             **/
/*****************************************************************************/
function update_kontakti ($username,$fullname,$email,$password)
{
  if ($_SERVER['PHP_AUTH_USER'] != admin_user)
    return write_error(err_only_superuser);

 
  // check for profile
  
  if ($email == "")
    return edit_kontakti($username,$fullname,$email,$password, err_not_kontakti_email);
	
	
  if ($fullname == "")
    return edit_kontakti($username,$fullname,$email,$password, err_not_kontakti_fullname);
	
  if ($password == "")
    return edit_kontakti($username,$fullname,$email,$password, err_not_kontakti_password);	

 $dblink = dbconnect();


  $res = mysql_query("UPDATE kontakti
                      SET fullname='$fullname', email = '$email', password='$password' WHERE username= '$username'")
    or die("Invalid query");



  dbdisconnect($dblink);

 
  
 
  return edit_kontakti ($username,$fullname,$email,$password, msg_kontakti_updated);
}
/*****************************************************************************/
/**         ask_del_kontakti                                               **/
/*****************************************************************************/
function ask_del_kontakti($username)
{

  if ($_SERVER['PHP_AUTH_USER'] != admin_user)
    return write_error(err_only_superuser);

  // read template
  
  $filename = askdelkontakti_tpl;
  if(file_exists($filename))
    $page = implode("", file($filename));
  else
    die("Can't find $filename");
	

  $page = str_replace('{KONTAKTI}', $username, $page);
  return $page;
}

/*****************************************************************************/
/**         del_modem                                                  **/
/*****************************************************************************/
function del_kontakti($username)
{
  if ($_SERVER['PHP_AUTH_USER'] != admin_user)
    return write_error(err_only_superuser);

  

  $dblink = dbconnect();
  
  $res = mysql_query("SELECT username FROM kontakti WHERE username= '$username'")
    or die("Invalid query");

  if (mysql_num_rows($res) == 0)
  {
    dbdisconnect($dblink);
    return write_error(err_kontakti_not_found);
  }
  
  
  $res = mysql_query("DELETE FROM kontakti WHERE username= '$username'")
    or die("Invalid query");



  dbdisconnect($dblink);
  


  return sysmsg(msg_kontakti_deleted);
}


?>