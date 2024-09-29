<?php
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validateRegistrationForm($_POST);
    if (empty($errors)) {
        $vehicle_model_id = sanitizeInput($_POST['vehicle_model']);
        $vehicle_type_id = sanitizeInput($_POST['vehicle_type']);
        $chassis_number = sanitizeInput($_POST['chassis_number']);
        $production_year = sanitizeInput($_POST['production_year']);
        $registration_number = sanitizeInput($_POST['registration_number']);
        $fuel_type_id = sanitizeInput($_POST['fuel_type']);
        $registration_to = sanitizeInput($_POST['registration_to']);

        $check_sql = "SELECT id FROM registrations WHERE chassis_number = ? OR registration_number = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ss", $chassis_number, $registration_number);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            echo "<p class='error'>A vehicle with this chassis number or registration number already exists.</p>";
        } else {
            $sql = "INSERT INTO registrations (vehicle_model_id, vehicle_type_id, chassis_number, production_year, registration_number, fuel_type_id, registration_to) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iisssss", $vehicle_model_id, $vehicle_type_id, $chassis_number, $production_year, $registration_number, $fuel_type_id, $registration_to);

            if ($stmt->execute()) {
                echo "<p class='success'>Vehicle registered successfully!</p>";
            } else {
                echo "<p class='error'>Error registering vehicle: " . $conn->error . "</p>";
            }
        }
    } else {
        foreach ($errors as $error) {
            echo "<p class='error'>$error</p>";
        }
    }
}

$vehicleModels = fetchVehicleModels();
$vehicleTypes = fetchVehicleTypes();
$fuelTypes = fetchFuelTypes();

if (isset($_GET['search'])) {
    $registrations = searchRegistrations($_GET['search']);
} else {
    $registrations = fetchAllRegistrations();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Vehicle Licensing Management</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .expired {
            background-color: #ffcccc;
        }

        .expiring {
            background-color: #ffffcc;
        }

        .search-container {
            text-align: right;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <h1>Admin Dashboard</h1>

    <h2>Vehicle Registration Form</h2>
    <form method="POST" action="">
        <label for="vehicle_model">Vehicle Model:</label>
        <select name="vehicle_model" required>
            <?php foreach ($vehicleModels as $model): ?>
                <option value="<?php echo $model['id']; ?>"><?php echo $model['name']; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="vehicle_type">Vehicle Type:</label>
        <select name="vehicle_type" required>
            <?php foreach ($vehicleTypes as $type): ?>
                <option value="<?php echo $type['id']; ?>"><?php echo $type['name']; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="chassis_number">Chassis Number:</label>
        <input type="text" name="chassis_number" required pattern="[A-Z0-9]{17}" placeholder="e.g., 1HGCM82633A004352" title="17 characters, uppercase letters and numbers only">

        <label for="production_year">Production Year:</label>
        <input type="date" name="production_year" required>

        <label for="registration_number">Registration Number:</label>
        <input type="text" name="registration_number" required pattern="[A-Z0-9]{1,10}" placeholder="e.g., ABC123" title="1-10 characters, uppercase letters and numbers only">

        <label for="fuel_type">Fuel Type:</label>
        <select name="fuel_type" required>
            <?php foreach ($fuelTypes as $type): ?>
                <option value="<?php echo $type['id']; ?>"><?php echo $type['name']; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="registration_to">Registration To:</label>
        <input type="date" name="registration_to" required>

        <button type="submit">Register Vehicle</button>
    </form>

    <h2>Licensed Vehicles</h2>
    <div class="search-container">
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search by model, license plate, or chassis number">
            <button type="submit">Search</button>
        </form>
    </div>
    <table id="vehicles-table">
        <tr>
            <th>Model</th>
            <th>Type</th>
            <th>Chassis Number</th>
            <th>Production Year</th>
            <th>Registration Number</th>
            <th>Fuel Type</th>
            <th>Registration To</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($registrations as $registration): ?>
            <tr class="<?php echo getRowClass($registration['registration_to']); ?>" data-id="<?php echo $registration['id']; ?>">
                <td><?php echo $registration['vehicle_model']; ?></td>
                <td><?php echo $registration['vehicle_type']; ?></td>
                <td><?php echo $registration['chassis_number']; ?></td>
                <td><?php echo $registration['production_year']; ?></td>
                <td><?php echo $registration['registration_number']; ?></td>
                <td><?php echo $registration['fuel_type']; ?></td>
                <td><?php echo $registration['registration_to']; ?></td>
                <td>
                    <button onclick="editRegistration(<?php echo $registration['id']; ?>)">Edit</button>
                    <button onclick="deleteRegistration(<?php echo $registration['id']; ?>)">Delete</button>
                    <?php if (getRowClass($registration['registration_to']) !== ''): ?>
                        <button onclick="extendRegistration(<?php echo $registration['id']; ?>)">Extend</button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <script src="script.js"></script>
</body>

</html>