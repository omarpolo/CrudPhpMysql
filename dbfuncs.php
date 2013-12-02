<?php
/*****************************************************************************/
/**        Name: dbconnect				                    **/
/** Description: Connect to database  					    **/
/**       Input: 						            **/
/**      Output: 						            **/
/*****************************************************************************/
  
function dbconnect()
{
  $dblink = mysql_connect(db_host, db_user, db_psw)
    or die("Could not connect to");
  
  mysql_select_db(db_base)
    or die("Could not select database");
    
  return $dblink;
}

/*****************************************************************************/
/**        Name: dbdisconnect				                    **/
/** Description: Disconnect database  					    **/
/**       Input: 						            **/
/**      Output: 						            **/
/*****************************************************************************/

function dbdisconnect($dblink)
{
  mysql_close($dblink);
}

?>
