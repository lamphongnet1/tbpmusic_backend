<?php
$host = "fdb1028.awardspace.net";
$username = "4555474_musictest";
$password = "lamphong123";
$database = "4555474_musictest";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy giá trị idTaiKhoan từ tham số GET hoặc POST
$idTaiKhoan = isset($_GET['idTaiKhoan']) ? $_GET['idTaiKhoan'] : ''; // Nếu sử dụng GET, thay thế bằng $_POST nếu cần

// Kiểm tra idTaiKhoan hợp lệ
if (empty($idTaiKhoan)) {
    die("Thiếu idTaiKhoan trong yêu cầu.");
}

// Truy vấn dữ liệu playlist theo idTaiKhoan
$sql = "SELECT 
            playlist.idPlaylist, 
            playlist.tenPlayList, 
            playlist.idBaiHat, 
            taikhoan.hoTen
        FROM 
            playlist 
        INNER JOIN 
            taikhoan ON playlist.idTaiKhoan = taikhoan.idTaiKhoan
        WHERE 
            playlist.idTaiKhoan = ?
        ORDER BY 
            playlist.idPlaylist DESC";  // Sắp xếp theo idPlaylist từ lớn đến nhỏ

// Chuẩn bị và thực thi truy vấn
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idTaiKhoan);  // "i" cho kiểu dữ liệu integer (idTaiKhoan là số)
$stmt->execute();

// Lấy kết quả
$result = $stmt->get_result();

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($data);

$stmt->close();
$conn->close();
?>
