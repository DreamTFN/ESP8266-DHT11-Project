<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1254" />
</head>

<body bgcolor="#FFFFFF">

<div align="center"><center>


<?php
echo "<form action='alert_write.php' methot=get>";
include "conn.php";
$database = "db_name"; 

if (isset ($_GET ['alert']))
{
	
	$alert = $_GET ['alert'];
	$mac = $_GET ['mac'];
	$insed_cnt = 0;
	
	$query = "select count (ID) cnt from $database.dbo.RoomAlert where Device_ID = '".$mac."' and Type_ = ".$alert." and Insert_Date between dateadd (second, -5, getdate()) and getdate ()";
	foreach ($conn->query ($query) as $q) {$insed_cnt = $q ['cnt'];}
	
	if ($insed_cnt == 0)
	{
		$insert_query = "insert into $database.dbo.RoomAlert (Room_ID, Device_ID, Type_) values ((select Room_ID from $database.dbo.Devices where ID = '".$mac."'), '".$mac."', ".$alert.")";
		$conn->exec ($insert_query);
	}
}

echo 
"
<table cellspacing=1 cellpadding=1 border=1>
<tr>
<th>Room_ID</th>
<th>Device_ID</th>
<th>Type_</th>
<th>Insert_Date</th>
</tr>
";

$drquery = "select top 10 Room_ID, Device_ID, Type_, Insert_Date from $database.dbo.RoomAlert order by Insert_Date desc";
foreach ($conn->query ($drquery) as $sr)
{
$Room_ID = $sr ['Room_ID'];
$Device_ID = $sr ['Device_ID'];
$Type_ = $sr ['Type_'];
$Insert_Date = $sr ['Insert_Date'];


echo "<tr>";
echo "<td>",$Room_ID,"</td>";
echo "<td>",$Device_ID,"</td>";
echo "<td>",$Type_,"</td>";
echo "<td>",$Insert_Date,"</td>";
echo "</tr>";
}

echo "</table>";
echo "</form>";
?>

</center></div>
</body>
</html>