<?php
$host = "fdb1028.awardspace.net";
$username = "4555474_musictest";
$password = "lamphong123";
$database = "4555474_musictest";



$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}


$sql = "SELECT banner.idBanner, banner.hinhAnhBanner, banner.noiDung, banner.idBaiHat, baihat.tenBaiHat, baihat.hinhBaiHat 
              FROM banner 
              INNER JOIN baihat 
              ON baihat.idBaiHat = banner.idBaiHat;";
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