<?php
require_once "database.php";

function checkSignIn($redirectLocation = "signin.php")
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: $redirectLocation");
        exit();
    }
}

function signUp($email, $username, $password, $confirm_password, $image)
{
    $conn = connect();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $userImagesDir = "Profile_Pictures/";
    if (!is_dir($userImagesDir)) {
        mkdir($userImagesDir);
    }

    $imagePath = $userImagesDir . basename($image['name']);
    if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
        die("Failed to move uploaded file");
    }

    if ($password != $confirm_password) {
        echo ("Passwords do not match");
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users (Email, Username, Password, ProfilePicture) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Failed to prepare statement: " . mysqli_error($conn));
    }

    if (!mysqli_stmt_bind_param($stmt, "ssss", $email, $username, $hashed_password, $imagePath)) {
        die("Failed to bind parameters: " . mysqli_stmt_error($stmt));
    }

    if (mysqli_stmt_execute($stmt)) {
        $user_id = mysqli_insert_id($conn);
        $_SESSION['user_id'] = $user_id;
        header("Location: profile.php");
        exit();
    } else {
        echo "Error: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
    close($conn);
}

function signIn($email, $password, $remember)
{
    $UserID = getUserID($email, $password);

    if ($UserID) {
        $_SESSION['user_id'] = $UserID;

        if ($remember) {
            setcookie('user_id', $UserID, time() + 30 * 24 * 60 * 60, '/');
        }

        header("Location: profile.php");
        exit();
    } else {
        header("Location: signin.php");
        exit();
    }
}

function addReview($fnbID, $title, $rating, $review, $image)
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: signin.php");
        exit();
    }

    $UserID = $_SESSION['user_id'];

    $conn = connect();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $reviewImagesDir = "Review_Images/";
    if (!is_dir($reviewImagesDir)) {
        mkdir($reviewImagesDir);
    }

    $imagePath = $reviewImagesDir . basename($image['name']);
    if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
        die("Failed to move uploaded file");
    }

    $query = "INSERT INTO reviews (UserID, FnbID, ReviewTitle, Rating, ReviewText, ReviewImage) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Failed to prepare statement: " . mysqli_error($conn));
    }

    if (!mysqli_stmt_bind_param($stmt, "iisdss", $UserID, $fnbID, $title, $rating, $review, $imagePath)) {
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

function updateProfile($username, $image)
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: signin.php");
        exit();
    }

    $UserID = $_SESSION['user_id'];

    $conn = connect();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $userImagesDir = "Profile_Pictures/";
    if (!is_dir($userImagesDir)) {
        mkdir($userImagesDir);
    }

    $imagePath = $userImagesDir . basename($image['name']);
    if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
        die("Failed to move uploaded file");
    }

    $query = "UPDATE users SET Username = ?, ProfilePicture = ? WHERE UserID = ?";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Failed to prepare statement: " . mysqli_error($conn));
    }

    if (!mysqli_stmt_bind_param($stmt, "ssi", $username, $imagePath, $UserID)) {
        die("Failed to bind parameters: " . mysqli_stmt_error($stmt));
    }

    if (mysqli_stmt_execute($stmt)) {
        header("Location: profile.php");
        exit();
    } else {
        echo "Error: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
    close($conn);
}

function updateReview($fnbID, $newReviewTitle, $newRating, $newReviewText, $newReviewPhoto)
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: signin.php");
        exit();
    }

    $userID = $_SESSION['user_id'];

    $conn = connect();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $reviewPhotosDir = "Review_Photos/";
    if (!is_dir($reviewPhotosDir)) {
        mkdir($reviewPhotosDir);
    }

    $newReviewPhotoPath = null;
    if (!empty($newReviewPhoto['name'])) {
        $newReviewPhotoPath = $reviewPhotosDir . basename($newReviewPhoto['name']);
        if (!move_uploaded_file($newReviewPhoto['tmp_name'], $newReviewPhotoPath)) {
            die("Failed to move uploaded file");
        }
    }

    $query = "UPDATE reviews 
              SET Rating = ?, ReviewTitle = ?, ReviewText = ?, ReviewImage = ? 
              WHERE UserID = ? AND FnBID = ?";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Failed to prepare statement: " . mysqli_error($conn));
    }

    if (!mysqli_stmt_bind_param($stmt, "isssii", $newRating, $newReviewTitle, $newReviewText, $newReviewPhotoPath, $userID, $fnbID)) {
        die("Failed to bind parameters: " . mysqli_stmt_error($stmt));
    }

    if (mysqli_stmt_execute($stmt)) {
        header("Location: profile.php");
        exit();
    } else {
        echo "Error: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
    close($conn);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['signUp'])) {
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $image = $_FILES['profile_pic'];

        signUp($email, $username, $password, $confirm_password, $image);
    } else if (isset($_POST['signIn'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $remember = isset($_POST['remember']);

        signIn($email, $password, $remember);
    } else if (isset($_POST['add_review'])) {
        $fnbID = $_POST['fnb'];
        $title = $_POST['title'];
        $rating = $_POST['rating'];
        $review = $_POST['review'];
        $image = $_FILES['review_photo'];

        addReview($fnbID, $title, $rating, $review, $image);
    } else if (isset($_POST['update_profile'])) {
        $username = $_POST['username'];
        $image = $_FILES['profile_pic'];

        updateProfile($username, $image);
    } else if (isset($_POST['update_review'])) {
        $fnbID = $_POST['fnb'];
        $newReviewTitle = $_POST['title'];
        $newRating = $_POST['rating'];
        $newReviewText = $_POST['review'];
        $newReviewPhoto = $_FILES['review_photo'];

        updateReview($fnbID, $newReviewTitle, $newRating, $newReviewText, $newReviewPhoto);
    }
}

function signOut()
{
    $_SESSION = array();

    session_destroy();

    header("Location: index.php");
    exit();
}

if (isset($_GET['sign_out'])) {
    signOut();
}

function getUserID($email, $password)
{
    $conn = connect();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $email = mysqli_real_escape_string($conn, $email);

    $query = "SELECT UserID, Password FROM users WHERE Email = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $hashed_password = $row['Password'];
            $userID = $row['UserID'];

            if (password_verify($password, $hashed_password)) {
                mysqli_stmt_close($stmt);
                close($conn);
                return $userID;
            } else {
                mysqli_stmt_close($stmt);
                close($conn);
                return null;
            }
        } else {
            mysqli_stmt_close($stmt);
            close($conn);
            return null;
        }
    } else {
        die("Failed to prepare statement: " . mysqli_error($conn));
    }
}

function getUserData()
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: signin.php");
        exit();
    }

    $conn = connect();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $user_id = $_SESSION['user_id'];

    $query = "SELECT * FROM users WHERE UserID = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            return $row;
        } else {
            echo ("User not found");
        }

        mysqli_stmt_close($stmt);
    } else {
        die("Failed to prepare statement: " . mysqli_error($conn));
    }

    close($conn);
}

function getUserReviews()
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: signIn.php");
        exit();
    }

    $conn = connect();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $user_id = $_SESSION['user_id'];

    $query = "SELECT * FROM reviews WHERE UserID = ? ORDER BY ReviewID DESC LIMIT 8";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        $reviewArray = array();
        while ($review = $result->fetch_assoc()) {
            $reviewArray[] = $review;
        }

        mysqli_stmt_close($stmt);
        close($conn);

        return $reviewArray;
    } else {
        die("Failed to prepare statement: " . mysqli_error($conn));
    }
}

function getReviewDetails()
{
    $conn = connect();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM reviews ORDER BY ReviewID DESC LIMIT 8";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $reviewArray = array();
        while ($review = $result->fetch_assoc()) {
            $reviewArray[] = $review;
        }
        $result->free();
        $conn->close();
        return $reviewArray;
    } else {
        $conn->close();
        return array();
    }
}

function getReviewedFnBs()
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: signIn.php");
        exit();
    }

    $userID = $_SESSION['user_id'];

    $conn = connect();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT fnb.FnBID, fnb.FnBName 
              FROM fnb 
              INNER JOIN reviews ON fnb.FnBID = reviews.FnBID 
              WHERE reviews.UserID = ?";

    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $userID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $fnbID, $fnbName);

        $fnbOptions = '';
        while (mysqli_stmt_fetch($stmt)) {
            $fnbOptions .= "<option value=\"$fnbID\">$fnbName</option>";
        }

        mysqli_stmt_close($stmt);
    } else {
        die("Failed to prepare the SQL statement: " . mysqli_error($conn));
    }

    mysqli_close($conn);

    return $fnbOptions;
}

function getReviewedTitles()
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: signIn.php");
        exit();
    }

    $userID = $_SESSION['user_id'];

    $conn = connect();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT reviews.ReviewID, reviews.ReviewTitle 
              FROM reviews 
              WHERE reviews.UserID = ?";

    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $userID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $reviewID, $reviewTitle);

        $reviewOptions = '';
        while (mysqli_stmt_fetch($stmt)) {
            $reviewOptions .= "<option value=\"$reviewID\">$reviewTitle</option>";
        }

        mysqli_stmt_close($stmt);
    } else {
        die("Failed to prepare the SQL statement: " . mysqli_error($conn));
    }

    mysqli_close($conn);

    return $reviewOptions;
}


function getUsername($userID)
{
    $conn = connect();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT users.Username 
              FROM users 
              INNER JOIN reviews ON users.UserID = reviews.UserID 
              WHERE reviews.UserID = ?";

    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $userID);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $username);

        if (mysqli_stmt_fetch($stmt)) {
            return $username;
        } else {
            return null;
        }
    } else {
        die("Failed to prepare the SQL statement: " . mysqli_error($conn));
    }
}
?>