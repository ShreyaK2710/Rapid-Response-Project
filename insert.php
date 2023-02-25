<?php
$country = $_POST['country'];
$state = $_POST['state'];
$city = $_POST['city'];
$zip = $_POST['zip'];
$disaster = $_POST['disaster'];

if (!empty($country) || !empty($state) || !empty($city) || !empty($zip) || !empty($disaster)) {
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbname = "reportdb";

    //for connection
    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);

    if (mysqli_connect_error()) {
        die('Connect Error('.mysqli_connect_errno().')'. mysqli_connect_error());
    } else {
        $SELECT = "SELECT zip From report Where zip = ? Limit 1";
        $INSERT = "INSERT Into report (country, state, city, zip, disaster) values(?, ?, ?, ?, ?)";

        //prepare statement
        $stmt = $conn->prepare($SELECT);
        $stmt->bind_param("i", $zip);
        $stmt->execute();
        $stmt->bind_result($zip);
        $stmt->store_result();
        $rnum = $stmt->num_rows;

        if ($rnum == 0) {
            $stmt->close();
            $stmt = $conn->prepare($INSERT);
            $stmt->bind_param("sssis", $country, $state, $city, $zip, $disaster);
            $stmt->execute();
            echo "Report registered successfully";
        } else {
            echo "Report already registered";
        }
        $stmt->close();
        $conn->close();
    }
} else {
    echo "All fields are required";
    die();
}
?>
