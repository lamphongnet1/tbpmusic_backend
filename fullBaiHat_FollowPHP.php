<?php
$host = "fdb1028.awardspace.net";
$username = "4555474_musictest";
$password = "lamphong123";
$database = "4555474_musictest";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$userID = 1; 

$sql = "
    SELECT a.idNgheSi, a.tenNgheSi, a.avartar, s.idBaiHat, s.tenBaiHat, s.hinhBaiHat
    FROM tuongtacnghesi f
    JOIN nghesi a ON f.idNgheSi = a.idNgheSi
    LEFT JOIN baihat s ON a.idNgheSi = s.idNgheSi
    WHERE f.idTaiKhoan = ?
    ORDER BY a.tenNgheSi, s.tenBaiHat
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Chuẩn bị câu lệnh thất bại: " . $conn->error);
}

$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $artistID = $row['idNgheSi']; 
    if (!isset($data[$artistID])) {
        $data[$artistID] = [
            'idNgheSi' => $artistID,
            'tenNgheSi' => $row['tenNgheSi'], 
            'avartar' => $row['avartar'],
            'baihat' => []
        ];
    }
    if (!empty($row['idBaiHat'])) { 
        $data[$artistID]['baihat'][] = [
            'idbaihat' => $row['idBaiHat'], 
            'tenBaiHat' => $row['tenBaiHat'],
            'hinhBaiHat' => $row['hinhBaiHat'],
            'tenNgheSi' => $row['tenNgheSi']  // Thêm tên nghệ sĩ vào mỗi bài hát
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($data);

$conn->close();
?>
