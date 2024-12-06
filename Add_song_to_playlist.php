<?php
$host = "fdb1028.awardspace.net";
$username = "4555474_musictest";
$password = "lamphong123";
$database = "4555474_musictest";

// Kết nối tới cơ sở dữ liệu
$conn = new mysqli($host, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra nếu có dữ liệu từ POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Nhận dữ liệu từ POST
    $idBaiHat = $conn->real_escape_string($_POST['idBaiHat']);
    $idtaikhoan = $conn->real_escape_string($_POST['idTaiKhoan']);
    $idPlaylist = $conn->real_escape_string($_POST['idPlaylist']);

    // Lấy giá trị hiện tại của idBaiHat trong bảng playlist
    $sqlGet = "SELECT idBaiHat FROM playlist WHERE idTaiKhoan = $idtaikhoan AND idPlaylist = $idPlaylist";
    $result = $conn->query($sqlGet);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentIdBaiHat = $row['idBaiHat'];

        // Chuyển danh sách hiện tại thành mảng
        $idBaiHatArray = !empty($currentIdBaiHat) ? explode(",", $currentIdBaiHat) : [];

        // Kiểm tra xem bài hát mới đã tồn tại trong danh sách chưa
        if (!in_array($idBaiHat, $idBaiHatArray)) {
            // Nếu chưa tồn tại, thêm bài hát vào danh sách
            $idBaiHatArray[] = $idBaiHat;

            // Ghép lại danh sách thành chuỗi
            $updatedIdBaiHat = implode(",", $idBaiHatArray);

            // Cập nhật idBaiHat mới
            $sqlUpdate = "UPDATE playlist
                          SET idBaiHat = '$updatedIdBaiHat'
                          WHERE idTaiKhoan = $idtaikhoan
                            AND idPlaylist = $idPlaylist";

            if ($conn->query($sqlUpdate) === TRUE) {
                // Nếu thành công, trả về thông báo thành công
                echo json_encode(["status" => "success", "message" => "Playlist đã được cập nhật thành công."]);
            } else {
                // Nếu thất bại, trả về thông báo lỗi
                echo json_encode(["status" => "error", "message" => "Có lỗi xảy ra khi cập nhật: " . $conn->error]);
            }
        } else {
            // Nếu bài hát đã tồn tại, không thêm mới
            echo json_encode(["status" => "warning", "message" => "Bài hát đã tồn tại trong playlist."]);
        }
    } else {
        // Nếu không tìm thấy playlist
        echo json_encode(["status" => "error", "message" => "Không tìm thấy playlist."]);
    }
}

// Đóng kết nối
$conn->close();
?>
