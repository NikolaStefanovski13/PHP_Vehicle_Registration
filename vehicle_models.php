<?php
class VehicleModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($name)
    {
        $sql = "INSERT INTO vehicle_models (name) VALUES (?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $name);
        return $stmt->execute();
    }

    public function read()
    {
        $sql = "SELECT * FROM vehicle_models";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function update($id, $name)
    {
        $sql = "UPDATE vehicle_models SET name = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $name, $id);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM vehicle_models WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
