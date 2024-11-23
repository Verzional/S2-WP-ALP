<?php
require_once "database.php";

function addShop($name, $category, $address, $distance, $image)
{
    $conn = connect();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $shopImagesDir = "Shop_Images/";
    if (!is_dir($shopImagesDir)) {
        mkdir($shopImagesDir);
    }

    $imagePath = $shopImagesDir . basename($image['name']);
    if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
        die("Failed to move uploaded file");
    }

    $query = "INSERT INTO shops (ShopName, Category, Address, distance, shopImage) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Failed to prepare statement: " . mysqli_error($conn));
    }

    if (!mysqli_stmt_bind_param($stmt, "sssds", $name, $category, $address, $distance, $imagePath)) {
        die("Failed to bind parameters: " . mysqli_stmt_error($stmt));
    }

    if (mysqli_stmt_execute($stmt)) {
        header("Location: message.php");
        exit();
    } else {
        echo "Error: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
    close($conn);
}

function addFnB($shop, $name, $price, $description, $image)
{
    $conn = connect();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $fnbImagesDir = "FnB_Images/";
    if (!is_dir($fnbImagesDir)) {
        mkdir($fnbImagesDir);
    }

    $imagePath = $fnbImagesDir . basename($image['name']);
    if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
        die("Failed to move uploaded file");
    }

    $query = "INSERT INTO fnb (ShopID, FnbName, Price, Description, FnbImage) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Failed to prepare statement: " . mysqli_error($conn));
    }

    if (!mysqli_stmt_bind_param($stmt, "isiss", $shop, $name, $price, $description, $imagePath)) {
        die("Failed to bind parameters: " . mysqli_stmt_error($stmt));
    }

    if (mysqli_stmt_execute($stmt)) {
        header("Location: message.php");
        exit();
    } else {
        echo "Error: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
    close($conn);
}

function updateShop($shopID, $name, $category, $address, $distance, $image)
{
    $conn = connect();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $shopImagesDir = "Shop_Images/";
    if (!is_dir($shopImagesDir)) {
        mkdir($shopImagesDir);
    }

    $imagePath = $shopImagesDir . basename($image['name']);
    if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
        die("Failed to move uploaded file");
    }

    $query = "UPDATE shops SET ShopName = ?, Category = ?, Address = ?, distance = ?, shopImage = ? WHERE ShopID = ?";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Failed to prepare statement: " . mysqli_error($conn));
    }

    if (!mysqli_stmt_bind_param($stmt, "sssdsi", $name, $category, $address, $distance, $imagePath, $shopID)) {
        die("Failed to bind parameters: " . mysqli_stmt_error($stmt));
    }

    if (mysqli_stmt_execute($stmt)) {
        header("Location: message.php");
        exit();
    } else {
        echo "Error: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
    close($conn);
}

function updateFnb($fnbID, $shop, $name, $price, $description, $image)
{
    $conn = connect();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $fnbImagesDir = "FnB_Images/";
    if (!is_dir($fnbImagesDir)) {
        mkdir($fnbImagesDir);
    }

    $imagePath = $fnbImagesDir . basename($image['name']);
    if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
        die("Failed to move uploaded file");
    }

    $query = "UPDATE fnb SET ShopID = ?, FnbName = ?, Price = ?, Description = ?, FnbImage = ? WHERE FnbID = ?";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Failed to prepare statement: " . mysqli_error($conn));
    }

    if (!mysqli_stmt_bind_param($stmt, "isissi", $shop, $name, $price, $description, $imagePath, $fnbID)) {
        die("Failed to bind parameters: " . mysqli_stmt_error($stmt));
    }

    if (mysqli_stmt_execute($stmt)) {
        header("Location: message.php");
        exit();
    } else {
        echo "Error: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
    close($conn);
}

function removeShop($shopID, $reason)
{
    $userID = $_SESSION['user_id'];

    $username = getRemoverName($userID);

    $shopName = getRemovedShop($shopID);

    $conn = connect();

    $insertQuery = "INSERT INTO removed (ShopName, Username, Reason) VALUES (?, ?, ?)";
    $insertStmt = mysqli_prepare($conn, $insertQuery);
    mysqli_stmt_bind_param($insertStmt, "sss", $shopName, $username, $reason);

    mysqli_stmt_execute($insertStmt);

    if (mysqli_stmt_error($insertStmt)) {
        die("Error executing insert statement: " . mysqli_stmt_error($insertStmt));
    }

    mysqli_stmt_close($insertStmt);

    $deleteQuery = "DELETE FROM shops WHERE ShopID = ?";
    $deleteStmt = mysqli_prepare($conn, $deleteQuery);
    mysqli_stmt_bind_param($deleteStmt, "i", $shopID);

    mysqli_stmt_execute($deleteStmt);

    if (mysqli_stmt_error($deleteStmt)) {
        die("Error executing delete statement: " . mysqli_stmt_error($deleteStmt));
    }

    mysqli_stmt_close($deleteStmt);

    close($conn);

    header("Location: message.php");
    exit();
}


function removeFnB($fnbID, $reason)
{
    $userID = $_SESSION['user_id'];

    $username = getRemoverName($userID);

    $fnbName = getRemovedFnB($fnbID);

    $conn = connect();

    $insertQuery = "INSERT INTO removed (FnBName, Username, Reason) VALUES (?, ?, ?)";
    $insertStmt = mysqli_prepare($conn, $insertQuery);
    mysqli_stmt_bind_param($insertStmt, "sss", $fnbName, $username, $reason);

    mysqli_stmt_execute($insertStmt);

    if (mysqli_stmt_error($insertStmt)) {
        die("Error executing insert statement: " . mysqli_stmt_error($insertStmt));
    }

    mysqli_stmt_close($insertStmt);

    $deleteQuery = "DELETE FROM fnb WHERE FnbID = ?";
    $deleteStmt = mysqli_prepare($conn, $deleteQuery);
    mysqli_stmt_bind_param($deleteStmt, "i", $fnbID);

    mysqli_stmt_execute($deleteStmt);

    if (mysqli_stmt_error($deleteStmt)) {
        die("Error executing delete statement: " . mysqli_stmt_error($deleteStmt));
    }

    mysqli_stmt_close($deleteStmt);

    close($conn);

    header("Location: message.php");
    exit();
}

function removeReview($reviewID, $reason)
{
    $userID = $_SESSION['user_id'];

    $username = getRemoverName($userID);

    $review = getRemovedReview($reviewID);

    $conn = connect();

    $insertQuery = "INSERT INTO removed (ReviewTitle, Username, Reason) VALUES (?, ?, ?)";
    $insertStmt = mysqli_prepare($conn, $insertQuery);
    mysqli_stmt_bind_param($insertStmt, "sss", $review, $username, $reason);

    mysqli_stmt_execute($insertStmt);

    if (mysqli_stmt_error($insertStmt)) {
        die("Error executing insert statement: " . mysqli_stmt_error($insertStmt));
    }

    mysqli_stmt_close($insertStmt);

    $deleteQuery = "DELETE FROM reviews WHERE ReviewID = ?";
    $deleteStmt = mysqli_prepare($conn, $deleteQuery);
    mysqli_stmt_bind_param($deleteStmt, "i", $reviewID);

    mysqli_stmt_execute($deleteStmt);

    if (mysqli_stmt_error($deleteStmt)) {
        die("Error executing delete statement: " . mysqli_stmt_error($deleteStmt));
    }

    mysqli_stmt_close($deleteStmt);

    close($conn);

    header("Location: message.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_shop'])) {
        $name = $_POST['name'];
        $category = $_POST['category'];
        $address = $_POST['address'];
        $distance = $_POST['distance'];
        $image = $_FILES['shop_photo'];

        addShop($name, $category, $address, $distance, $image);
    } else if (isset($_POST['add_fnb'])) {
        $shop = $_POST['shop'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $image = $_FILES['fnb_photo'];

        addFnB($shop, $name, $price, $description, $image);
    } else if (isset($_POST['update_shop'])) {
        $shopID = $_POST['shop'];
        $name = $_POST['name'];
        $category = $_POST['category'];
        $address = $_POST['address'];
        $distance = $_POST['distance'];
        $image = $_FILES['shop_photo'];

        updateShop($shopID, $name, $category, $address, $distance, $image);
    } else if (isset($_POST['update_fnb'])) {
        $fnbID = $_POST['fnb'];
        $shop = $_POST['shop'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $image = $_FILES['fnb_photo'];

        updateFnb($fnbID, $shop, $name, $price, $description, $image);
    } else if (isset($_POST['remove_fnb'])) {
        $fnbID = $_POST['fnb'];
        $reason = $_POST['reason'];

        removeFnB($fnbID, $reason);
    } else if (isset($_POST['remove_shop'])) {
        $shopID = $_POST['shop'];
        $reason = $_POST['reason'];

        removeShop($shopID, $reason);
    } else if (isset($_POST['remove_review'])) {
        $reviewID = $_POST['review'];
        $reason = $_POST['reason'];

        removeReview($reviewID, $reason);
    }   
}

function getShopName()
{
    $conn = connect();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT ShopID, ShopName FROM shops";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $options = "";
        while ($row = mysqli_fetch_assoc($result)) {
            $options .= "<option value='" . $row['ShopID'] . "'>" . $row['ShopName'] . "</option>";
        }
    } else {
        $options = "<option value='' disabled selected>No stores available</option>";
    }

    mysqli_close($conn);

    return $options;
}

function getShopDetails()
{
    $conn = connect();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM shops ORDER BY ShopID DESC LIMIT 8";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $shopArray = array();
        while ($shop = $result->fetch_assoc()) {
            $shopArray[] = $shop;
        }
        $result->free();
        $conn->close();
        return $shopArray;
    } else {
        $conn->close();
        return array();
    }
}

function getFnbName()
{
    $conn = connect();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT FnbID, FnbName FROM fnb";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $options = "";
        while ($row = mysqli_fetch_assoc($result)) {
            $options .= "<option value='" . $row['FnbID'] . "'>" . $row['FnbName'] . "</option>";
        }
    } else {
        $options = "<option value='' disabled selected>No food and beverages available</option>";
    }

    mysqli_close($conn);

    return $options;
}

function getFnbDetails()
{
    $conn = connect();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM fnb ORDER BY FnbID DESC LIMIT 8";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $fnbArray = array();
        while ($fnb = $result->fetch_assoc()) {
            $fnbArray[] = $fnb;
        }
        $result->free();
        $conn->close();
        return $fnbArray;
    } else {
        $conn->close();
        return array();
    }
}

function getRemoverName($userID)
{
    $conn = connect();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT Username FROM users WHERE UserID = ?";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Failed to prepare statement: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "i", $userID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $username);

    if (mysqli_stmt_fetch($stmt)) {
        mysqli_stmt_close($stmt);
        close($conn);
        return $username;
    } else {
        mysqli_stmt_close($stmt);
        close($conn);
        return null;
    }
}

function getRemovedShop($shopID){
    $conn = connect();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT ShopName FROM shops WHERE ShopID = ?";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Failed to prepare statement: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "i", $shopID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $shopName);

    if (mysqli_stmt_fetch($stmt)) {
        mysqli_stmt_close($stmt);
        close($conn);
        return $shopName;
    } else {
        mysqli_stmt_close($stmt);
        close($conn);
        return null;
    }
}
function getRemovedFnB($fnbID)
{
    $conn = connect();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT FnBName FROM fnb WHERE FnBID = ?";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Failed to prepare statement: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "i", $fnbID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $fnbName);

    if (mysqli_stmt_fetch($stmt)) {
        mysqli_stmt_close($stmt);
        close($conn);
        return $fnbName;
    } else {
        mysqli_stmt_close($stmt);
        close($conn);
        return null;
    }
}

function getRemovedReview($reviewID){
    $conn = connect();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT ReviewTitle FROM reviews WHERE ReviewID = ?";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Failed to prepare statement: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "i", $reviewID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $review);

    if (mysqli_stmt_fetch($stmt)) {
        mysqli_stmt_close($stmt);
        close($conn);
        return $review;
    } else {
        mysqli_stmt_close($stmt);
        close($conn);
        return null;
    }
}
?>