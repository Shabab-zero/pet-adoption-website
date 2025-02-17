<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1); 
include 'db.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_SESSION['user_id'];
    $name = htmlspecialchars(trim($_POST['name']));
    $category = htmlspecialchars(trim($_POST['category']));
    $age = intval($_POST['age']);  
    $gender = htmlspecialchars(trim($_POST['gender']));
    $description = htmlspecialchars(trim($_POST['description']));
    $vaccinationStatus = htmlspecialchars(trim($_POST['vaccination_status']));
    $adoptionStatus = "Available";  


    $sql = "INSERT INTO Pets (UserID, Name, Category, Age, Gender, Description, VaccinationStatus, AdoptionStatus) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {

        $stmt->bind_param("isssssss", $userID, $name, $category, $age, $gender, $description, $vaccinationStatus, $adoptionStatus);

        if ($stmt->execute()) {

            $requestedPetID = $stmt->insert_id;  
            

            $sql_check_requests = "SELECT * FROM PetRequests WHERE Category = ? AND Gender = ? AND Age = ?";
            $stmt_check_requests = $conn->prepare($sql_check_requests);
            $stmt_check_requests->bind_param("sss", $category, $gender, $age);
            $stmt_check_requests->execute();
            $requests_result = $stmt_check_requests->get_result();

            if ($requests_result->num_rows > 0) {
                while ($request = $requests_result->fetch_assoc()) {
                    $requestedUserID = $request['UserID'];
                

                    $notification_message = "pet_profile.php?PetID=" . $requestedPetID; 


                    $sql_update_user = "UPDATE Users SET Notification = CONCAT(IFNULL(Notification, ''), ?) WHERE UserID = ?";
                    $stmt_update_user = $conn->prepare($sql_update_user);
                    

                    $notification_message .= "\n";
                    $stmt_update_user->bind_param("si", $notification_message, $requestedUserID);
                    $stmt_update_user->execute();
                }
            }

            echo "<p class='success'>Pet added successfully!</p>";
            header("Location: index.php");  
            exit();
        } else {
            echo "<p class='error'>Error adding pet: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p class='error'>Error preparing statement: " . $conn->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a Pet for Adoption</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Form Container */
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }

        h2 {
            text-align: center;
            color: #2d6a4f;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 5px 0 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #2d6a4f;
            color: #fff;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #1b3a28;
        }

        .success {
            color: green;
            text-align: center;
            margin-bottom: 10px;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        /* Navbar Styling */
        nav {
            display: flex;
            justify-content: space-between;
            background-color: #2d6a4f;
            padding: 10px 20px;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
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

        /* Footer */
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

        <?php else: ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Sign Up</a></li>
        <?php endif; ?>
    </ul>
</nav>

<div class="container">
    <h2>Add a Pet for Adoption</h2>
    <form method="POST" action="">
        <label for="name">Pet Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="category">Category (Dog, Cat, etc.):</label>
        <input type="text" id="category" name="category" required>

        <label for="age">Age (in years):</label>
        <input type="number" id="age" name="age" required>

        <label for="gender">Gender:</label>
        <select id="gender" name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>

        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" required></textarea>

        <label for="vaccination_status">Vaccination Status:</label>
        <select id="vaccination_status" name="vaccination_status" required>
            <option value="Vaccinated">Vaccinated</option>
            <option value="Not Vaccinated">Not Vaccinated</option>
        </select>

        <input type="submit" value="Add Pet">
    </form>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2024 PicPaw. All rights reserved.</p>
</footer>

</body>
</html>