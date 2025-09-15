-- Tabla de definición de bots/batch
CREATE TABLE bot_batch (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    script_path VARCHAR(255) NOT NULL,
    activo BOOLEAN DEFAULT TRUE
);

-- Tabla de ejecuciones de bots/batch
CREATE TABLE bot_batch_ejecucion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bot_batch_id INT NOT NULL,
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME,
    estado ENUM('pendiente','ejecutando','finalizado','error') NOT NULL DEFAULT 'pendiente',
    mensaje_error TEXT,
    parametros TEXT,
    FOREIGN KEY (bot_batch_id) REFERENCES bot_batch(id)
);

-- Tabla de logs de ejecución (opcional, para trazabilidad detallada)
CREATE TABLE bot_batch_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ejecucion_id INT NOT NULL,
    fecha DATETIME NOT NULL,
    nivel ENUM('INFO','WARN','ERROR') NOT NULL,
    mensaje TEXT NOT NULL,
    FOREIGN KEY (ejecucion_id) REFERENCES bot_batch_ejecucion(id)
);