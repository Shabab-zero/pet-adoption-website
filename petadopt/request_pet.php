<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request New Pet</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Container Styling */
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            margin-top: 80px; 
        }

        h2 {
            color: #2d6a4f;
            margin-bottom: 20px;
            text-align: center;
        }

        .error {
            color: red;
            text-align: center;
        }

        .success {
            color: green;
            text-align: center;
        }

        input[type="text"],
        select {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #2d6a4f;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 20px;
        }

        input[type="submit"]:hover {
            background-color: #218838;
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

        /* Footer Styling */
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
            <li><a href="profile.php">Profile</a></li>
        </ul>
    </nav>
    
    <!-- Main Content -->
    <div class="container">
        <h2>Request New Pet</h2>
        <form method="POST" action="">
            <label for="category">Category:</label>
            <input type="text" id="category" name="category" required>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>

            <label for="age">Age:</label>
            <input type="text" id="age" name="age" required>

            <input type="submit" value="Request!">
        </form>

        <?php
            include 'db.php';
            session_start();

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $userID = $_SESSION['user_id'];
                $category = $_POST['category'];
                $gender = $_POST['gender'];
                $age = $_POST['age'];

                $sql = "INSERT INTO PetRequests (UserID, Category, Gender, Age) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("isss", $userID, $category, $gender, $age);

                if ($stmt->execute()) {
                    echo "<p class='success'>Request successful!</p>";
                } else {
                    echo "<p class='error'>Error: " . $stmt->error . "</p>";
                }
            }
        ?>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 PicPaw. All rights reserved.</p>
    </footer>

</body>
</html>