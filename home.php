<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $licensePlate = sanitizeInput($_POST['license_plate']);
    $vehicleInfo = getVehicleInfo($licensePlate);
}
?>

<h1>Vehicle Licensing Management</h1>

<form method="POST" action="">
    <label for="license_plate">Enter License Plate Number:</label>
    <input type="text" id="license_plate" name="license_plate" required>
    <button type="submit">Search</button>
</form>

<?php if (isset($vehicleInfo)): ?>
    <?php if ($vehicleInfo): ?>
        <table>
            <tr>
                <th>Vehicle Model</th>
                <th>Vehicle Type</th>
                <th>Chassis Number</th>
                <th>Production Year</th>
                <th>Registration Number</th>
                <th>Fuel Type</th>
                <th>Registration To</th>
            </tr>
            <tr>
                <td><?php echo $vehicleInfo['vehicle_model']; ?></td>
                <td><?php echo $vehicleInfo['vehicle_type']; ?></td>
                <td><?php echo $vehicleInfo['chassis_number']; ?></td>
                <td><?php echo $vehicleInfo['production_year']; ?></td>
                <td><?php echo $vehicleInfo['registration_number']; ?></td>
                <td><?php echo $vehicleInfo['fuel_type']; ?></td>
                <td><?php echo $vehicleInfo['registration_to']; ?></td>
            </tr>
        </table>
    <?php else: ?>
        <p>No record found for the given license plate number.</p>
    <?php endif; ?>
<?php endif; ?>