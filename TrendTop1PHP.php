<?php
$host = "fdb1028.awardspace.net";
$username = "4555474_musictest";
$password = "lamphong123";
$database = "4555474_musictest";


$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}


$sql = "SELECT 
    Trend.idTrend, 
    baihat.tenBaiHat,  
    baihat.hinhBaiHat,
    nghesi.tenNgheSi
FROM 
    Trend
JOIN 
    baihat ON baihat.idBaiHat = Trend.idBaiHat
JOIN 
    nghesi ON baihat.idNgheSi = nghesi.idNgheSi
LIMIT 1;
";
$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}


header('Content-Type: application/json');
echo json_encode($data);

$conn->close();
?>