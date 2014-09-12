<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_conn_time_table = "localhost";
$database_conn_time_table = "feedback_mis";
$username_conn_time_table = "root";
$password_conn_time_table = "p";
$conn_time_table = mysql_pconnect($hostname_conn_time_table, $username_conn_time_table, $password_conn_time_table) or trigger_error(mysql_error(),E_USER_ERROR); 
?>