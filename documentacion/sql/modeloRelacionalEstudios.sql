-- Universidad principal
CREATE TABLE universidad (
    id INT PRIMARY KEY,
    nombre_eus VARCHAR(255) NOT NULL,
    siglas_eus VARCHAR(20),
    nombre_esp VARCHAR(255) NOT NULL,
    siglas_esp VARCHAR(20),
	fecha_alta DATE NOT NULL DEFAULT NOW(),
	fecha_modificacion DATE NOT NULL
);

-- Tipos de estudio: Grados, Másteres/Postgrados, Doctorados
CREATE TABLE tipo_estudio (
    id INT PRIMARY KEY,
    nombre_eus VARCHAR(100) NOT NULL,
    nombre_esp VARCHAR(100) NOT NULL,
	fecha_alta DATE NOT NULL DEFAULT NOW(),
	fecha_modificacion DATE NOT NULL
);

-- Campus (solo para estudios tipo "Grados")
CREATE TABLE campus (
    id INT PRIMARY KEY,
    universidad_id INT NOT NULL,
    nombre_eus VARCHAR(100) NOT NULL,
    nombre_esp VARCHAR(100) NOT NULL,
	fecha_alta DATE NOT NULL DEFAULT NOW(),
	fecha_modificacion DATE NOT NULL,
    FOREIGN KEY (universidad_id) REFERENCES universidad(id)
);

-- Centros (solo para estudios tipo "Grados")
CREATE TABLE centro (
    id INT PRIMARY KEY,
    campus_id INT NOT NULL,
    nombre_eus VARCHAR(255) NOT NULL,
    nombre_esp VARCHAR(255) NOT NULL,
	fecha_alta DATE NOT NULL DEFAULT NOW(),
	fecha_modificacion DATE NOT NULL,
    FOREIGN KEY (campus_id) REFERENCES campus(id)
);

-- Grados (vinculados a centro)
CREATE TABLE grado (
    id INT PRIMARY KEY,
    centro_id INT NOT NULL,
    nombre_eus VARCHAR(255) NOT NULL,
    nombre_esp VARCHAR(255) NOT NULL,
	fecha_alta DATE NOT NULL DEFAULT NOW(),
	fecha_modificacion DATE NOT NULL,
    FOREIGN KEY (centro_id) REFERENCES centro(id)
);

-- Postgrados (vinculados a universidad, pueden tener centro si lo necesitas)
CREATE TABLE postgrado (
    id INT PRIMARY KEY,
    universidad_id INT NOT NULL,
    nombre_eus VARCHAR(255) NOT NULL,
    nombre_esp VARCHAR(255) NOT NULL,
	fecha_alta DATE NOT NULL DEFAULT NOW(),
	fecha_modificacion DATE NOT NULL,
    FOREIGN KEY (universidad_id) REFERENCES universidad(id)
);

-- Doctorados (vinculados a universidad, pueden tener centro si lo necesitas)
CREATE TABLE doctorado (
    id INT PRIMARY KEY,
    universidad_id INT NOT NULL,
    nombre_eus VARCHAR(255) NOT NULL,
    nombre_esp VARCHAR(255) NOT NULL,
	fecha_alta DATE NOT NULL DEFAULT NOW(),
	fecha_modificacion DATE NOT NULL,
    FOREIGN KEY (universidad_id) REFERENCES universidad(id)
);