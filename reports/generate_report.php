<?php
// generate_report.php
// Example PHP script to fetch data from Bogotá open data API and insert into MySQL

// Database connection parameters - update with your own credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bogotap";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Example API endpoint from Bogotá open data (using Estaciones de TransMilenio dataset)
$apiUrl = "https://www.datos.gov.co/resource/2pnw-mmge.json?\$limit=10";

// Fetch data from API
$response = file_get_contents($apiUrl);
if ($response === FALSE) {
    die("Error al obtener datos de la API: " . error_get_last()['message']);
}

$data = json_decode($response, true);
if ($data === NULL) {
    die("Error al decodificar JSON");
}

// Example: Insert data into a table named 'report_data'
// Make sure this table exists with appropriate columns matching the data fields
foreach ($data as $item) {
    // Fields from the Estaciones de TransMilenio dataset
    $nombre_estacion = $conn->real_escape_string($item['nombre_estacion'] ?? '');
    $latitud = $conn->real_escape_string($item['latitud'] ?? '');
    $longitud = $conn->real_escape_string($item['longitud'] ?? '');

    $sql = "INSERT INTO report_data (nombre_estacion, latitud, longitud) VALUES ('$nombre_estacion', '$latitud', '$longitud')";
    if (!$conn->query($sql)) {
        echo "Error al insertar datos: " . $conn->error . "<br>";
    }
}

$conn->close();

// Prepare CSV output
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="reporte_basico.csv"');
header('Pragma: no-cache');
header('Expires: 0');

$output = fopen('php://output', 'w');

// Write CSV header
fputcsv($output, ['Nombre Estacion', 'Latitud', 'Longitud']);

// Write data rows
foreach ($data as $item) {
    $nombre_estacion = $item['nombre_estacion'] ?? '';
    $latitud = $item['latitud'] ?? '';
    $longitud = $item['longitud'] ?? '';
    fputcsv($output, [$nombre_estacion, $latitud, $longitud]);
}

fclose($output);
exit();
?>
