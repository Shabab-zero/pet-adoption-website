<?php
include 'db.php';
session_start();

//SORT
//Default sort values
$sort_column = isset($_GET['column']) ? $_GET['column'] : 'PetID'; // default column
$sort_order = isset($_GET['order']) ? $_GET['order'] : 'ASC';    // default order

// Toggle sort order between ASC and DESC
$next_order = ($sort_order === 'ASC') ? 'DESC' : 'ASC';

// Filter
// Initialize filter variables
$search_filter = isset($_GET['search']) ? $_GET['search'] : '';
$gender_filter = isset($_GET['Gender']) ? $_GET['Gender'] : ''; // default
$adoption_status_filter = isset($_GET['AdoptionStatus']) ? $_GET['AdoptionStatus'] : '';
$vaccination_status_filter = isset($_GET['VaccinationStatus']) ? $_GET['VaccinationStatus'] : '';

// Build the SQL query with filters and sorting
$sql = "SELECT * FROM pets WHERE TRUE"; 

// Filtering Query
if (!empty($gender_filter)) {
    $sql .= " && Gender = '$gender_filter'";
}
if (!empty($adoption_status_filter)) {
    $sql .= " && AdoptionStatus = '$adoption_status_filter'";
}
if (!empty($vaccination_status_filter)) {
    $sql .= " && VaccinationStatus = '$vaccination_status_filter'";
}

// Apply search filter if set
if (!empty($search_filter)) {
    // Escape the search term to prevent SQL injection
    $string_escaped = $conn->real_escape_string($search_filter);
    
    // Add the search condition to the SQL query
    $sql .= " AND (Name LIKE '%$string_escaped%' 
               OR Category LIKE '%$string_escaped%' 
               OR Age LIKE '%$string_escaped%')";
}

// Sorting query
$sql .= " ORDER BY $sort_column $sort_order";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Pets</title>
    <link rel="stylesheet" type="text/css" href="view_pets_style.css">
</head>

<body>
    
    <!-- Navigation Bar html -->
    <nav>
    <div class="logo">
        <h1><a href="index.php">PicPaw</a></h1>
    </div>
    <ul class="nav-links">
        <li><a href="add_pet.php">Post Adoptions</a></li>
        <li><a href="about.php">About Us</a></li>
        <?php if(isset($_SESSION['user_id'])): ?>
            <li><a href="profile.php">Profile</a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
        <?php endif; ?>
    </ul>
    </nav>

    <div class="container">
        <!-- Filter Form -->
        <div>
            <form method="GET" action="">
                <label for="Gender">Gender:</label>
                <select name="Gender" id="Gender">
                    <option value="">All</option>
                    <option value="Male" <?php if($gender_filter == 'Male') echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if($gender_filter == 'Female') echo 'selected'; ?>>Female</option>
                </select>
                
                <label for="AdoptionStatus">Adoption Status:</label>
                <select name="AdoptionStatus" id="AdoptionStatus">
                    <option value="">All</option>
                    <option value="Available" <?php if($adoption_status_filter == 'Available') echo 'selected'; ?>>Available</option>
                    <option value="Adopted" <?php if($adoption_status_filter == 'Adopted') echo 'selected'; ?>>Adopted</option>
                </select>
                
                <label for="VaccinationStatus">Vaccination Status:</label>
                <select name="VaccinationStatus" id="VaccinationStatus">
                    <option value="">All</option>
                    <option value="Vaccinated" <?php if($vaccination_status_filter == 'Vaccinated') echo 'selected'; ?>>Vaccinated</option>
                    <option value="Not Vaccinated" <?php if($vaccination_status_filter == 'Not Vaccinated') echo 'selected'; ?>>Not Vaccinated</option>
                </select>
                
                <!-- Search Input Field -->
                <label for="search">Search:</label>
                <input type="text" name="search" id="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" placeholder="Search by keyword">

                <button type="submit" class="button">Filter</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th><a href="?<?php echo http_build_query(array_merge($_GET, ['column' => 'Name', 'order' => $next_order])); ?>">Name</a></th>
                    <th><a href="?<?php echo http_build_query(array_merge($_GET, ['column' => 'Category', 'order' => $next_order])) ?>">Category</a></th>
                    <th><a href="?<?php echo http_build_query(array_merge($_GET, ['column' => 'Age', 'order' => $next_order])) ?>">Age</a></th>
                    <th><a href="?<?php echo http_build_query(array_merge($_GET, ['column' => 'Gender', 'order' => $next_order])) ?>">Gender</a></th>
                    <th><a href="?<?php echo http_build_query(array_merge($_GET, ['column' => 'AdoptionStatus', 'order' => $next_order])) ?>">Adoption Status</a></th>
                    <th><a href="?<?php echo http_build_query(array_merge($_GET, ['column' => 'VaccinationStatus', 'order' => $next_order])) ?>">Vaccination Status</a></th>
                    <th>Profile</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($result === false) {
                echo "Error: " . $conn->error;
            } 
            else {
                if ($result->num_rows > 0) {
                // Output data for each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['Name'] . "</td>";
                    echo "<td>" . $row['Category'] . "</td>";
                    echo "<td>" . $row['Age'] . "</td>";
                    echo "<td>" . $row['Gender'] . "</td>";
                    echo "<td>" . $row['AdoptionStatus'] . "</td>";
                    echo "<td>" . $row['VaccinationStatus'] . "</td>";
                    echo "<td><a href='pet_profile.php?PetID=" . $row['PetID'] . "'><button class='button'>View Profile</button></a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No pets found</td></tr>";
            }
            }
            ?>
        </tbody>
    </table>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 PicPaw. All rights reserved.</p>
    </footer>

</body>
</html>