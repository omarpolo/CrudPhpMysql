<?php
/*****************************************************************************/
/**        Name: authenticate	                                            **/
/** Description: 			                                    **/
/**       Input:                                                            **/
/**      Output: 							    **/
/*****************************************************************************/
function authenticate()
{
  header( "WWW-Authenticate: Basic realm=\"Enter your username and password\"");
  header( "HTTP/1.0 401 Unauthorized");
  echo "You must enter a valid username and password to access the system.\n";
  exit;
}

/*****************************************************************************/
/**        Name: sysmsg  				                    **/
/** Description: 							    **/
/**       Input: 						            **/
/**      Output:							    **/
/*****************************************************************************/

function sysmsg($msg_text)
{
  if(file_exists(sysmsg_tpl))
    $page = implode("", file(sysmsg_tpl));
  else
    die("Can't find " . sysmsg_tpl);

  $page = str_replace('{MSG}', $msg_text, $page);
  return $page;
}

/*****************************************************************************/
/**        Name: getvar			                                    **/
/** Description: For Apache lookback techniques				    **/
/**       Input: 					                    **/
/**      Output: 			                                    **/
/*****************************************************************************/
function getvar($idx)
{
  $array = explode("/", str_replace(path, "", $_SERVER["REQUEST_URI"]));
  return $array[$idx];
}

/*****************************************************************************/
/**        Name: strip			                                    **/
/** Description: 							    **/
/**       Input: 					                    **/
/**      Output: 			                                    **/
/*****************************************************************************/
function strip($string, $length)
{
  if (strlen($string) > $length)
    return substr($string, 0, $length) . "...";
  else
    return substr($string, 0, $length);
}

/*****************************************************************************/
/**        Name: secstohms		                                    **/
/** Description: 							    **/
/**       Input: seconds				                    **/
/**      Output: hhhh:mm:ss		                                    **/
/*****************************************************************************/
function secstohms($secs)
{
  $res = mysql_query("SELECT SEC_TO_TIME($secs)")
    or die("Invalid query");
    
  $row = mysql_fetch_array($res);
  return $row[0];
}

/*****************************************************************************/
/**        Name: hmstosecs		                                    **/
/** Description: 							    **/
/**       Input: hhhh:mm:ss				                    **/
/**      Output: seconds		                                    **/
/*****************************************************************************/
function hmstosecs($hms)
{
  $res = mysql_query("SELECT TIME_TO_SEC('$hms')")
    or die("Invalid query");
    
  $row = mysql_fetch_array($res);
  return $row[0];
}

/*****************************************************************************/
/**        Name: redirect	                                            **/
/** Description: 			                                    **/
/**       Input:                                                            **/
/**      Output: 							    **/
/*****************************************************************************/
function redirect($url)
{
  print "<HTML><HEAD><TITLE>Redirecting...</TITLE>";
  print '<META http-equiv="Content-Type" cont="text/html">';
  print '<SCRIPT language="JavaScript">';
  print "location.replace('$url');";
  print "</SCRIPT></HEAD><BODY></BODY></HTML>";
}
?>
