-- SQL schema for report_data table used in generate_report.php

CREATE TABLE IF NOT EXISTS report_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_estacion VARCHAR(255) NOT NULL,
    latitud VARCHAR(50) NOT NULL,
    longitud VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
