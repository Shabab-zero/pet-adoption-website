<?php
session_start();
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Adoption</title>
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

        /* Hero Section */
        .hero {
            background-image: url('picpaw-homepage.jpg');
            background-size: cover;
            background-position: center;
            height: 480px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: black;
            text-align: center;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .hero .btn {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }

        .hero .btn:hover {
            background-color: #218838;
        }

        /* Featured Pets Section */
        .featured-pets {
            text-align: center;
            margin: 50px 0;
        }

        .featured-pets h2 {
            font-size: 2rem;
            margin-bottom: 30px;
        }

        .pet-cards {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .pet-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 250px;
            text-align: center;
        }

        .pet-card h3 {
            margin-bottom: 10px;
        }

        .pet-card p {
            margin-bottom: 10px;
        }

        .pet-card .btn {
            background-color: #2d6a4f;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
        }

        .pet-card .btn:hover {
            background-color: #1b3a28;
        }

        /* Footer */
        footer {
            background-color: #2d6a4f;
            color: white;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <?php
    if (isset($_SESSION['user_id'])) {
        $userID = $_SESSION['user_id'];
        $sql = "SELECT Type FROM Users WHERE UserID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $user_type = $user['Type']; 
    }

    ?>

    <nav>
        <div class="logo">
            <h1>PicPaw</h1>
        </div>
        <ul class="nav-links">
            <li><a href="view_pets.php">Find Adoptions</a></li>
            <li><a href="add_pet.php">Post Adoptions</a></li>
            <li><a href="list_of_vets.php">Book Vets</a></li>
            <li><a href="about.php">About Us</a></li>

            <?php if(isset($_SESSION['user_id'])): ?>
                <?php
                    // Check if the user is a vet
                    if ($user_type === 'Vet') {
                        echo '<li><a href="appointment_list.php">Appointment List</a></li>';
                    }
                ?>
                <li><a href="profile.php">Profile</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Hero Section -->
    <header class="hero">
        <div class="hero-content">
            <h1>Find Your Perfect Pet Today!</h1>
            <p>Join us in helping pets find their forever homes.</p>
        </div>
    </header>

    <!-- Featured Pets Section -->
    <section class="featured-pets">
        <h2>Featured Pets for Adoption</h2>
        <div class="pet-cards">

            <?php
            $sql = "SELECT * FROM Pets WHERE AdoptionStatus = 'Available' LIMIT 3";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                echo "<div class='pet-card'>";
                echo "<h3>" . $row['Name'] . "</h3>";
                echo "<p>Category: " . $row['Category'] . "</p>";
                echo "<p>Age: " . $row['Age'] . "</p>";
                echo "<p>Gender: " . $row['Gender'] . "</p>";
                echo "<a href='pet_profile.php?PetID=" . $row['PetID'] . "' class='btn'>View Details</a>";
                echo "</div>";
            }
            ?>

        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 PicPaw. All rights reserved.</p>
    </footer>

</body>
</html>