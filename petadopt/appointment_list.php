<?php
session_start();
include('db.php');


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$current_user_id = $_SESSION['user_id'];


$sql = "SELECT Type FROM Users WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$current_user_type = $user['Type'];  

if ($current_user_type !== 'Vet') {
    header("Location: unauthorized.php");
    exit();
}


$appointment_query = "
    SELECT va.AppointmentID, va.AppointmentDate, va.Reason, u.FirstName, u.LastName 
    FROM VetAppointments va
    JOIN Users u ON va.UserID_User = u.UserID
    WHERE va.UserID_Vet = ?
    ORDER BY va.AppointmentDate DESC";
$stmt_appointments = $conn->prepare($appointment_query);
$stmt_appointments->bind_param("i", $current_user_id);
$stmt_appointments->execute();
$appointments_result = $stmt_appointments->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
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
    <h1>My Appointments</h1>

    <?php if ($appointments_result->num_rows > 0) { ?>
    <table>
        <thead>
            <tr>
                <th>Date & Time</th>
                <th>User Name</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $appointments_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo date("F j, Y, g:i a", strtotime($row['AppointmentDate'])); ?></td>
                    <td><?php echo $row['FirstName'] . ' ' . $row['LastName']; ?></td>
                    <td><?php echo $row['Reason']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
        <p>No appointments found.</p>
    <?php } ?>

</body>
</html>

<?php
// Close the connection
$conn->close();
?>