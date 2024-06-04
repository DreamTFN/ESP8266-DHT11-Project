<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1254" />
</head>

<body bgcolor="#FFFFFF">

<div align="center"><center>


<?php
echo "<form action='room_sensor.php' methot=get>";
include "conn.php";
$database = "db_name"; 


if (isset ($_GET ['temp']))
{

	$Max_Temp = 28;
	$Max_Humidity = 60;	
	
	$temp = (is_numeric ($_GET ['temp'])) ? str_replace (',','.',$_GET ['temp']) : "null";
	$humidity = (is_numeric ($_GET ['humidity'])) ? str_replace (',','.',$_GET ['humidity']) : "null";

	$mac = $_GET ['mac'];
	
	$insert_query = "insert into $database.dbo.RoomVal (Device_ID, Temp, Humidity) values ('".$mac."', ".$temp.", ".$humidity.")";
	$conn->exec ($insert_query);
	
	$rm_name = null;
	$ask_rm = "select Name, ISNULL(Max_Temp, 28) Max_Temp, ISNULL(Max_Humidity,60) Max_Humidity from $database.dbo.Devices where ID = '".$mac."'";
	foreach ($conn->query ($ask_rm) as $arm)
	{
		$rm_name = $arm ['Name'];
		$Max_Temp = $arm ['Max_Temp'];
		$Max_Humidity = $arm ['Max_Humidity'];
	}
	
}

echo 
"
<table cellspacing=1 cellpadding=1 border=1>
<tr>
<th>Device_ID</th>
<th>Temp</th>
<th>Humidity</th>
<th>Insert_Date</th>
</tr>
";

$drquery = "select top 10 Device_ID, Temp, Humidity, Insert_Date from $database.dbo.RoomVal order by Insert_Date desc";
foreach ($conn->query ($drquery) as $sr)
{

$Device_ID = $sr ['Device_ID'];
$Temp = $sr ['Temp'];
$Humidity = $sr ['Humidity'];
$Insert_Date = $sr ['Insert_Date'];


echo "<tr>";
echo "<td>",$Device_ID,"</td>";
echo "<td>",$Temp,"</td>";
echo "<td>",$Humidity,"</td>";
echo "<td>",$Insert_Date,"</td>";
echo "</tr>";
}

echo "</table>";
echo "</form>";
?>

</center></div>
</body>
</html>