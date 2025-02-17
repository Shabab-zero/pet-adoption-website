<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['user_id'];
$sql = "SELECT * FROM Users WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


if (isset($_POST['delete_notifications'])) {

    $sql_update = "UPDATE Users SET Notification = NULL WHERE UserID = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("i", $userID);
    
    if ($stmt_update->execute()) {

        header("Location: profile.php");
        exit();
    } else {
        echo "Error clearing notifications: " . $stmt_update->error;
    }
}

// Get notifications
$notifications = isset($user['Notification']) ? explode("\n", $user['Notification']) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user['FirstName']); ?>'s Profile</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #eaf0e7;
            color: #333;
        }

        /* Navbar Styling */
        nav {
            display: flex;
            justify-content: space-between;
            background-color: #2d6a4f;
            padding: 10px 20px;
        }

        nav .logo h1 {
            color: white;
        }

        nav .nav-links {
            display: flex;
            list-style: none;
        }

        nav .nav-links li {
            margin-left: 20px;
        }

        nav .nav-links a {
            color: white;
            text-decoration: none;
        }

        nav .nav-links a:hover {
            color: #a0d6a0;
        }

        /* Profile Container */
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            max-width: 700px;
            margin: 50px auto;
            text-align: center;
        }

        .container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .container p {
            margin: 5px 0;
            color: #555;
        }

        .btn {
            background-color: #2d6a4f;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin-right: 10px;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #1b3a28;
        }

        /* Notification Section */


        .notifications {
            margin-top: 30px;
            text-align: left;
        }
        .notification {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 10px;
            margin: 10px 0;
            display: flex;
            align-items: center; 
            justify-content: space-between; 
        }

        .notification p {
            margin: 0;
            line-height: 1.5; 
        }

        .notification .btn {
            margin: 0;
            padding: 10px 15px;
            font-size: 16px;
            line-height: 1.5; 
            display: inline-block;
            vertical-align: middle;
        }

        footer {
            background-color: #2d6a4f;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav>
        <div class="logo">
            <h1>PicPaw</h1>
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="view_pets.php">Find Adoptions</a></li>
            <li><a href="add_pet.php">Post Adoptions</a></li>
            <li><a href="about.php">About Us</a></li>
            <?php if(isset($_SESSION['user_id'])): ?>
                <li><a href="profile.php">Profile</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Profile Section -->
    <div class="container">
        <h2><?php echo htmlspecialchars($user['FirstName'] . " " . $user['LastName']); ?>'s Profile</h2>

        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['Email']); ?></p>
        <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($user['PhoneNumber']); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($user['Address']); ?></p>

        <a href="request_pet.php" class="btn">Request New Pet</a>
        <a href="logout.php" class="btn">Logout</a>

        <!-- Notifications Section -->
        <div class="notifications">
            <h3>Notifications 
                <form method="POST" style="display: inline;">
                    <button type="submit" name="delete_notifications" style="background: none; border: none; color: red; cursor: pointer; margin-left: 10px;">✖</button>
                </form>
            </h3>
            <?php if (empty($notifications)): ?>
                <p>No notifications.</p>
            <?php else: ?>
                <?php foreach ($notifications as $notification): ?>
                    <?php
                    // Check if the notification contains the pet profile link
                    if (preg_match('/pet_profile\.php\?PetID=(\d+)/', $notification, $matches)) {
                        $petID = $matches[1]; ?>
                        <div class="notification">
                            <p>A new pet matching your request is available for adoption!</p>
                            <a href="pet_profile.php?PetID=<?php echo $petID; ?>" class="btn">View</a>
                        </div>
                    <?php } ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 PicPaw. All rights reserved.</p>
    </footer>

</body>
</html>