CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS vehicle_models (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS vehicle_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS fuel_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_model_id INT,
    vehicle_type_id INT,
    chassis_number VARCHAR(17) NOT NULL UNIQUE,
    production_year DATE,
    registration_number VARCHAR(20) NOT NULL UNIQUE,
    fuel_type_id INT,
    registration_to DATE,
    FOREIGN KEY (vehicle_model_id) REFERENCES vehicle_models(id),
    FOREIGN KEY (vehicle_type_id) REFERENCES vehicle_types(id),
    FOREIGN KEY (fuel_type_id) REFERENCES fuel_types(id)
);

INSERT IGNORE INTO vehicle_types (name) VALUES 
('sedan'), ('coupe'), ('hatchback'), ('suv'), ('minivan');

INSERT IGNORE INTO fuel_types (name) VALUES 
('gasoline'), ('diesel'), ('electric');

INSERT IGNORE INTO vehicle_models (name) VALUES 
('Toyota Corolla'), ('Honda Civic'), ('Ford Focus'), ('Volkswagen Golf');

INSERT IGNORE INTO users (username, password) VALUES 
('admin', 'password');