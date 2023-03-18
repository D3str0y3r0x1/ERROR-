<?php
$myConnection= mysqli_connect("localhost","root","toor404") or die ("could not connect to mysql");

mysqli_select_db($myConnection, "error") or die ("no database");
?>