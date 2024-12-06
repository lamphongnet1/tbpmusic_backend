<?php
$host = "fdb1028.awardspace.net";
$username = "4555474_musictest";
$password = "lamphong123";
$database = "4555474_musictest";


$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
if(strlen($conn->real_escape_string($_POST['idquangcao']))>0)
{
      $idquangcao=$_POST['idquangcao'];
        $sql = "SELECT 
            baihat.idBaiHat, 
            baihat.idDanhMuc, 
            baihat.tenBaiHat, 
            baihat.ngayPhatHanh, 
            baihat.hinhBaiHat, 
            nghesi.idNgheSi,
            baihat.linkBaiHat, 
            nghesi.tenNgheSi
        FROM 
            baihat 
        INNER JOIN 
            banner ON baihat.idBaiHat = banner.idBaiHat
        INNER JOIN 
            nghesi ON baihat.idNgheSi = nghesi.idNgheSi -- Thêm phép nối với bảng nghesi
        WHERE 
            banner.idBanner = $idquangcao;";  
}


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