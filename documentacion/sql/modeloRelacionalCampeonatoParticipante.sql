-- Tabla de campeonatos
CREATE TABLE campeonato (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
	estado VARCHAR(50) NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    descripcion TEXT,
	email_1 VARCHAR(150),
	email_2 VARCHAR(150),
	telefono_1 VARCHAR(9),
	telefono_2 VARCHAR(9),
	fecha_alta DATE NOT NULL DEFAULT NOW(),
	fecha_modificacion DATE NOT NULL
);

-- Tabla de participantes únicos por campeonato
CREATE TABLE participante (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(150) NOT NULL,
    email VARCHAR(150),
    dni VARCHAR(20),
	estado VARCHAR(50) NOT NULL,
    fecha_nacimiento DATE,
	fecha_alta DATE NOT NULL DEFAULT NOW(),
	fecha_modificacion DATE NOT NULL
);

-- Relación entre participante y campeonato (un participante por campeonato)
CREATE TABLE campeonato_participante (
    id INT AUTO_INCREMENT PRIMARY KEY,
    campeonato_id INT NOT NULL,
    participante_id INT NOT NULL,
    tipo_estudio ENUM('grado','postgrado','doctorado') NOT NULL,
    grado_id INT,
    postgrado_id INT,
    doctorado_id INT,
    chesscom_player_id BIGINT,
	fecha_alta DATE NOT NULL DEFAULT NOW(),
	fecha_modificacion DATE NOT NULL
    UNIQUE (campeonato_id, participante_id),
    FOREIGN KEY (campeonato_id) REFERENCES campeonato(id),
    FOREIGN KEY (participante_id) REFERENCES participante(id),
    FOREIGN KEY (grado_id) REFERENCES grado(id),
    FOREIGN KEY (postgrado_id) REFERENCES postgrado(id),
    FOREIGN KEY (doctorado_id) REFERENCES doctorado(id),
    FOREIGN KEY (chesscom_player_id) REFERENCES chesscom_player_profile(player_id)
);