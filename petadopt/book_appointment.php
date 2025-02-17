<?php

session_start();
include('db.php');


if (isset($_GET['vet_id'])) {
    $vet_id = $_GET['vet_id'];
} else {
    die('Vet ID not provided.');
}


$query = "SELECT FirstName, LastName FROM Users WHERE UserID = '$vet_id' AND Type = 'vet'";
$result = mysqli_query($conn, $query);
$vet = mysqli_fetch_assoc($result);


if (!$vet) {
    die('Vet not found.');
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user_id = $_SESSION['user_id'];  
    $appointment_date = $_POST['appointment_date'];
    $reason = $_POST['reason'];

    $query = "INSERT INTO VetAppointments (UserID_User, UserID_Vet, AppointmentDate, Reason) 
              VALUES ('$user_id', '$vet_id', '$appointment_date', '$reason')";

    if (mysqli_query($conn, $query)) {
        $message = "Appointment scheduled successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment with <?php echo $vet['FirstName'] . ' ' . $vet['LastName']; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        form {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="datetime-local"], input[type="submit"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
        }
        textarea {
            resize: vertical;
        }
        .btn {
            background-color: green;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }
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
    </style>
</head>
<body>

    <h1>Book Appointment with <?php echo $vet['FirstName'] . ' ' . $vet['LastName']; ?></h1>

    <!-- Show success or error message -->
    <?php if (isset($message)) { ?>
        <p><?php echo $message; ?></p>
    <?php } ?>
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
            <li><a href="login.php">Login</a></li>

            <?php if(isset($_SESSION['user_id'])): ?>
            <?php else: ?>
            <?php endif; ?>
        </ul>
    </nav>
    <!-- Appointment Form -->
    <form action="" method="POST">
        <input type="hidden" name="vet_id" value="<?php echo $vet_id; ?>">

        <label for="appointment_date">Choose Appointment Date & Time:</label>
        <input type="datetime-local" id="appointment_date" name="appointment_date" required>

        <label for="reason">Reason for Appointment:</label>
        <textarea id="reason" name="reason" rows="4" required></textarea>

        <input type="submit" value="Book Appointment" class="btn">
    </form>

</body>
</html>

<?php
// Close the connection
mysqli_close($conn);
?>