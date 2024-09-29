<?php
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function sanitizeInput($input)
{
    return htmlspecialchars(strip_tags(trim($input)));
}

function getVehicleInfo($licensePlate)
{
    global $conn;
    $licensePlate = sanitizeInput($licensePlate);

    $sql = "SELECT r.*, vm.name as vehicle_model, vt.name as vehicle_type, ft.name as fuel_type
            FROM registrations r
            JOIN vehicle_models vm ON r.vehicle_model_id = vm.id
            JOIN vehicle_types vt ON r.vehicle_type_id = vt.id
            JOIN fuel_types ft ON r.fuel_type_id = ft.id
            WHERE r.registration_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $licensePlate);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

function validateRegistrationForm($data)
{
    $errors = [];
    if (empty($data['vehicle_model'])) $errors[] = "Vehicle model is required.";
    if (empty($data['vehicle_type'])) $errors[] = "Vehicle type is required.";
    if (empty($data['chassis_number'])) $errors[] = "Chassis number is required.";
    if (empty($data['production_year'])) $errors[] = "Production year is required.";
    if (empty($data['registration_number'])) $errors[] = "Registration number is required.";
    if (empty($data['fuel_type'])) $errors[] = "Fuel type is required.";
    if (empty($data['registration_to'])) $errors[] = "Registration expiration date is required.";

    if (!preg_match('/^[A-Z0-9]{17}$/', $data['chassis_number'])) {
        $errors[] = "Invalid chassis number format.";
    }

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['production_year'])) {
        $errors[] = "Invalid production year format. Use YYYY-MM-DD.";
    }

    if (!preg_match('/^[A-Z0-9]{1,10}$/', $data['registration_number'])) {
        $errors[] = "Invalid registration number format.";
    }

    return $errors;
}

function getRowClass($registrationTo)
{
    $now = new DateTime();
    $regDate = new DateTime($registrationTo);
    $interval = $now->diff($regDate);

    if ($regDate < $now) {
        return 'expired';
    } elseif ($interval->days <= 30) {
        return 'expiring';
    }
    return '';
}

function searchRegistrations($searchTerm)
{
    global $conn;
    $searchTerm = "%$searchTerm%";
    $sql = "SELECT r.*, vm.name as vehicle_model, vt.name as vehicle_type, ft.name as fuel_type
            FROM registrations r
            JOIN vehicle_models vm ON r.vehicle_model_id = vm.id
            JOIN vehicle_types vt ON r.vehicle_type_id = vt.id
            JOIN fuel_types ft ON r.fuel_type_id = ft.id
            WHERE vm.name LIKE ? OR r.registration_number LIKE ? OR r.chassis_number LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function fetchAllRegistrations()
{
    global $conn;
    $sql = "SELECT r.*, vm.name as vehicle_model, vt.name as vehicle_type, ft.name as fuel_type
            FROM registrations r
            JOIN vehicle_models vm ON r.vehicle_model_id = vm.id
            JOIN vehicle_types vt ON r.vehicle_type_id = vt.id
            JOIN fuel_types ft ON r.fuel_type_id = ft.id";
    $result = $conn->query($sql);
    if ($result === false) {
        error_log("Error fetching all registrations: " . $conn->error);
        return [];
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

function fetchVehicleTypes()
{
    global $conn;
    $sql = "SELECT * FROM vehicle_types";
    $result = $conn->query($sql);
    if ($result === false) {
        error_log("Error fetching vehicle types: " . $conn->error);
        return [];
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

function fetchFuelTypes()
{
    global $conn;
    $sql = "SELECT * FROM fuel_types";
    $result = $conn->query($sql);
    if ($result === false) {
        error_log("Error fetching fuel types: " . $conn->error);
        return [];
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

function fetchVehicleModels()
{
    global $conn;
    $sql = "SELECT * FROM vehicle_models";
    $result = $conn->query($sql);
    if ($result === false) {
        error_log("Error fetching vehicle models: " . $conn->error);
        return [];
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}
