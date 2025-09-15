-- Información básica del perfil del jugador
CREATE TABLE chesscom_player_profile (
    player_id BIGINT PRIMARY KEY,
    id_url VARCHAR(255),
    url VARCHAR(255),
    username VARCHAR(100),
    followers INT,
    country VARCHAR(255),
    last_online BIGINT,
    joined BIGINT,
    status VARCHAR(50),
    is_streamer BOOLEAN,
    verified BOOLEAN,
    league VARCHAR(50),
	fecha_alta DATE NOT NULL DEFAULT NOW(),
	fecha_modificacion DATE NOT NULL
);

-- Plataformas de streaming asociadas al perfil
CREATE TABLE chesscom_player_streaming_platform (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id BIGINT,
    platform VARCHAR(255),
	fecha_alta DATE NOT NULL DEFAULT NOW(),
	fecha_modificacion DATE NOT NULL,
    FOREIGN KEY (player_id) REFERENCES chesscom_player_profile(player_id)
);

-- Estadísticas generales del jugador
CREATE TABLE chesscom_player_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id BIGINT,
	fecha_alta DATE NOT NULL DEFAULT NOW(),
	fecha_modificacion DATE NOT NULL,
    FOREIGN KEY (player_id) REFERENCES chesscom_player_profile(player_id)
);

-- Estadísticas de cada modalidad de juego
CREATE TABLE chesscom_game_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    stats_id INT,
    type ENUM('chess_rapid','chess_bullet','chess_blitz'),
    last_rating INT,
    last_date BIGINT,
    last_rd INT,
    best_rating INT,
    best_date BIGINT,
    best_game VARCHAR(255),
    record_win INT,
    record_loss INT,
    record_draw INT,
	fecha_alta DATE NOT NULL DEFAULT NOW(),
	fecha_modificacion DATE NOT NULL,
    FOREIGN KEY (stats_id) REFERENCES chesscom_player_stats(id)
);

-- Estadísticas de táctica
CREATE TABLE chesscom_tactics_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    stats_id INT,
    highest_rating INT,
    highest_date BIGINT,
    lowest_rating INT,
    lowest_date BIGINT,
	fecha_alta DATE NOT NULL DEFAULT NOW(),
	fecha_modificacion DATE NOT NULL,
    FOREIGN KEY (stats_id) REFERENCES chesscom_player_stats(id)
);

-- Estadísticas de puzzle rush
CREATE TABLE chesscom_puzzle_rush_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    stats_id INT,
    best_total_attempts INT,
    best_score INT,
	fecha_alta DATE NOT NULL DEFAULT NOW(),
	fecha_modificacion DATE NOT NULL,
    FOREIGN KEY (stats_id) REFERENCES chesscom_player_stats(id)
);

-- Archivos mensuales de partidas
CREATE TABLE chesscom_player_games_archive (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id BIGINT,
    archive_url VARCHAR(255),
	fecha_alta DATE NOT NULL DEFAULT NOW(),
	fecha_modificacion DATE NOT NULL,
    FOREIGN KEY (player_id) REFERENCES chesscom_player_profile(player_id)
);

-- Partidas de un mes (ChesscomPlayerMonthGames)
CREATE TABLE chesscom_player_month_game (
    id INT AUTO_INCREMENT PRIMARY KEY,
    archive_id INT,
    url VARCHAR(255),
    move_by BIGINT,
    pgn TEXT,
    time_control VARCHAR(50),
    last_activity BIGINT,
    rated BOOLEAN,
    turn VARCHAR(10),
    fen VARCHAR(255),
    start_time BIGINT,
    time_class VARCHAR(50),
    rules VARCHAR(50),
    white VARCHAR(255),
    black VARCHAR(255),
	fecha_alta DATE NOT NULL DEFAULT NOW(),
	fecha_modificacion DATE NOT NULL,
    FOREIGN KEY (archive_id) REFERENCES chesscom_player_games_archive(id)
);

-- Partidas en vivo (ChesscomPlayerGames)
CREATE TABLE chesscom_player_live_game (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id BIGINT,
    url VARCHAR(255),
    pgn TEXT,
    time_control VARCHAR(50),
    end_time BIGINT,
    rated BOOLEAN,
    accuracies_white FLOAT,
    accuracies_black FLOAT,
    tcn VARCHAR(255),
    uuid VARCHAR(100),
    initial_setup VARCHAR(255),
    fen VARCHAR(255),
    time_class VARCHAR(50),
    rules VARCHAR(50),
    eco VARCHAR(255),
	fecha_alta DATE NOT NULL DEFAULT NOW(),
	fecha_modificacion DATE NOT NULL,
    FOREIGN KEY (player_id) REFERENCES chesscom_player_profile(player_id)
);

-- Jugadores de una partida en vivo (ChesscomPlayer)
CREATE TABLE chesscom_player_live_game_player (
    id INT AUTO_INCREMENT PRIMARY KEY,
    live_game_id INT,
    color ENUM('white','black'),
    rating INT,
    result VARCHAR(50),
    player_id_url VARCHAR(255),
    username VARCHAR(100),
    uuid VARCHAR(100),
	fecha_alta DATE NOT NULL DEFAULT NOW(),
	fecha_modificacion DATE NOT NULL,
    FOREIGN KEY (live_game_id) REFERENCES chesscom_player_live_game(id)
);