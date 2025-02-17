<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        input[type="password"] {
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
            <li><a href="login.php">Login</a></li>

            <?php if(isset($_SESSION['user_id'])): ?>
            <?php else: ?>
            <?php endif; ?>
        </ul>
    </nav>
    <!-- Main Content -->
    <div class="container">
        <h2>Login</h2>
        <form method="POST" action="">
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Login">
        </form>

        <?php if(!isset($_SESSION['user_id'])): ?>
        <p>Don't have an account? <a href="register.php">Sign up</a></p>
        <p>Or, <a href="vet_register.php">Sign up as a vet</a></p>
        <?php endif; ?>

        <?php
            session_start();
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                include 'db.php';

                $email = $_POST['email'];
                $password = $_POST['password'];

                $sql = "SELECT * FROM Users WHERE Email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();

                if ($user && $password === $user['Password']) {
                    $_SESSION['user_id'] = $user['UserID'];
                    $_SESSION['user_name'] = $user['FirstName'];

                    echo "<p class='success'>Login successful!</p>";
                    header("Location: index.php");
                    exit();
                } else {
                    echo "<p class='error'>Invalid credentials</p>";
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