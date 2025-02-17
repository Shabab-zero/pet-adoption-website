<?php
session_start();
include 'db.php'; // Include the database connection

// Check if a PetID is provided via GET
if (!isset($_GET['PetID'])) {
    echo "Pet ID not specified.";
    exit();
}

$petID = $_GET['PetID'];

// Prepare and execute the SQL to get pet details
$sql = "SELECT * FROM Pets WHERE PetID = $petID";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// Check if the pet exists
if ($result->num_rows === 0) {
    echo "No pet found with the provided ID.";
    exit();
}

// Handle the "Adopt Me" button action
if (isset($_POST['adopt'])) {
    // Update the adoption status of the pet to "Adopted"
    $update_sql = "UPDATE pets SET AdoptionStatus = 'Adopted' WHERE PetID = $petID";
    if ($conn->query($update_sql) === TRUE) {
        // Redirect to the same page to reflect the status change
        header("Location: pet_profile.php?PetID=$petID&adopted=success");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$pet = $result->fetch_assoc(); // Fetch pet details as an associative array
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pet['PetName']); ?>'s Profile</title>
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

        /* Button Styling */
        .btn {
            background-color: #2d6a4f;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px; /* Consistent border radius */
            display: inline-block;
            margin-right: 10px;
            margin-top: 20px;
            border: none; /* No border for button */
            cursor: pointer; /* Pointer cursor for buttons */
            font-size: 16px; /* Consistent font size */
        }

        .btn:hover {
            background-color: #1b3a28;
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

    <!-- Pet Profile Section -->
    <div class="container">
        <h2><?php echo htmlspecialchars($pet['Name']); ?>'s Profile</h2>

        <p><strong>Type:</strong> <?php echo htmlspecialchars($pet['Category']); ?></p>
        <p><strong>Gender:</strong> <?php echo htmlspecialchars($pet['Gender']); ?></p>
        <p><strong>Age:</strong> <?php echo htmlspecialchars($pet['Age']); ?> years</p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($pet['Description']); ?></p>
        <p><strong>Adoption Status:</strong> <?php echo htmlspecialchars($pet['AdoptionStatus']); ?></p>
        <p><strong>Vaccination Status:</strong> <?php echo htmlspecialchars($pet['VaccinationStatus']); ?></p>
        
        <?php if (isset($_SESSION['user_id'])): ?>

            <!-- Add "Adopt Me" button if the pet is not already adopted -->
            <?php if ($pet['AdoptionStatus'] == 'Available'): ?>
                <form method="POST">
                    <button type="submit" name="adopt" class="btn">Adopt Me</button>
                </form>
            <?php endif; ?>
            
            <!-- If user is logged in, show the "Appointment to Vet" button -->
            <?php if ($pet['VaccinationStatus'] != 'Vaccinated'): ?>
                <a href="list_of_vets.php" class="btn">Appointment to Vet</a>
            <?php endif; ?>
        <?php else: ?>
            <!-- If user is not logged in, show a login button instead -->
            <?php if ($pet['VaccinationStatus'] != 'Vaccinated' || $pet['AdoptionStatus'] == 'Available'): ?>
                <a href="login.php" class="btn">Log in to Adopt or Vaccinate</a>
            <?php endif; ?>
        <?php endif; ?>

        <a href="view_pets.php" class="btn">Back to Pets</a>
       
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 PicPaw. All rights reserved.</p>
    </footer>

</body>
</html>