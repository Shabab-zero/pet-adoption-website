<?php

include('db.php');



$query = "SELECT UserID, FirstName, LastName, Address, PhoneNumber FROM Users WHERE Type = 'vet'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vet List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .btn {
            background-color: green;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }
        /* Navbar Styling */
        nav {
            display: flex;
            justify-content: space-between;
            background-color: #2d6a4f;
            padding: 10px 0px;
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
    <h1>Available Vets</h1>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Appointment</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['FirstName'] . ' ' . $row['LastName']; ?></td>
                    <td><?php echo $row['PhoneNumber']; ?></td>
                    <td>
                        <a href="book_appointment.php?vet_id=<?php echo $row['UserID']; ?>" class="btn">Book Appointment</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</body>
</html>

<?php
// Close the connection
mysqli_close($conn);
?>