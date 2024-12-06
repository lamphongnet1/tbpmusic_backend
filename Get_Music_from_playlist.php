<?php
$host = "fdb1028.awardspace.net";
$username = "4555474_musictest";
$password = "lamphong123";
$database = "4555474_musictest";

// Kết nối đến cơ sở dữ liệu
$conn = new mysqli($host, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra nếu cả idPlaylist và idTaiKhoan được gửi qua POST
if (isset($_POST['idPlaylist']) && isset($_POST['idTaiKhoan']) && strlen(trim($_POST['idPlaylist'])) > 0 && strlen(trim($_POST['idTaiKhoan'])) > 0) {
    $idPlaylist = $conn->real_escape_string($_POST['idPlaylist']);
    $idTaiKhoan = $conn->real_escape_string($_POST['idTaiKhoan']);
    
    // Tạo câu truy vấn
    $sql = "SELECT idBaiHat FROM playlist WHERE idTaiKhoan = '$idTaiKhoan' AND idPlaylist = '$idPlaylist'";
    
    // Thực hiện truy vấn
    $result = $conn->query($sql);

    $data = array();

    // Kiểm tra kết quả và xử lý
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Tách chuỗi idBaiHat thành mảng các phần tử
            $idList = explode(",", $row['idBaiHat']);
            foreach ($idList as $id) {
                $id = trim($id); // Xóa khoảng trắng thừa
                      // Truy vấn thông tin chi tiết bài hát
                    $detailsSql = "
                        SELECT 
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
                            nghesi ON baihat.idNgheSi = nghesi.idNgheSi
                        WHERE 
                            baihat.idBaiHat =  '$id'
                    ";
                      $detailsResult = $conn->query($detailsSql);
                    // Nếu có kết quả, thêm vào mảng
                    $songDetails = $detailsResult->fetch_assoc(); // Lấy kết quả bài hát
                    $data[] = $songDetails; // Thêm bài hát vào mảng
                
            }
        }
    }

    // Trả về kết quả dưới dạng JSON
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    // Trả về lỗi nếu thông tin đầu vào không hợp lệ
    header('Content-Type: application/json');
    echo json_encode(array("error" => "Thông tin đầu vào không hợp lệ."));
}

// Đóng kết nối
$conn->close();
?>
