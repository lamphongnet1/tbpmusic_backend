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
    // Nhận dữ liệu từ POST (ví dụ: tên playlist và mô tả)
    $tenplaylist = $conn->real_escape_string($_POST['tenPlayList']);
    $idtaikhoan = $conn->real_escape_string($_POST['idTaiKhoan']);
    $idbaihat = $conn->real_escape_string($_POST['idBaiHat']);

    // Câu lệnh INSERT để thêm playlist mới vào bảng
    $sql = "INSERT INTO playlist (tenPlayList,idTaiKhoan, idBaiHat) 
            VALUES ('$tenplaylist','$idtaikhoan', '$idbaihat')";

    // Thực thi câu lệnh INSERT
    if ($conn->query($sql) === TRUE) {
        // Nếu thành công, trả về thông báo thành công
        echo json_encode(["status" => "success", "message" => "Playlist đã được thêm thành công."]);
    } else {
        // Nếu thất bại, trả về thông báo lỗi
        echo json_encode(["status" => "error", "message" => "Có lỗi xảy ra: " . $conn->error]);
    }
}

// Đóng kết nối
$conn->close();
?>
