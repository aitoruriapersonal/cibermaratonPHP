-- Ejemplo de inserción de datos para el modelo Chesscom

-- Perfil del jugador
INSERT INTO chesscom_player_profile (
    player_id, id_url, url, username, followers, country, last_online, joined, status, is_streamer, verified, league
) VALUES (
    168897069,
    'https://api.chess.com/pub/player/aitoruriaerandio',
    'https://www.chess.com/member/AitorUriaErandio',
    'aitoruriaerandio',
    6,
    'https://api.chess.com/pub/country/XX',
    1750006704,
    1642173077,
    'premium',
    FALSE,
    FALSE,
    'Legend'
);

-- Plataformas de streaming (vacío en este ejemplo)
-- INSERT INTO chesscom_player_streaming_platform (player_id, platform) VALUES (168897069, 'Twitch');

-- Estadísticas generales del jugador
INSERT INTO chesscom_player_stats (player_id) VALUES (168897069);

-- Supongamos que el id generado para chesscom_player_stats es 1

-- Estadísticas de modalidad de juego (ejemplo para chess_rapid)
INSERT INTO chesscom_game_stats (
    stats_id, type, last_rating, last_date, last_rd, best_rating, best_date, best_game, record_win, record_loss, record_draw
) VALUES (
    1, 'chess_rapid', 1703, 1601677321, 122, 1741, 1601673976, 'https://www.chess.com/game/live/5529628946', 7, 2, 1
);

-- Estadísticas de táctica
INSERT INTO chesscom_tactics_stats (
    stats_id, highest_rating, highest_date, lowest_rating, lowest_date
) VALUES (
    1, 2433, 1584710371, 410, 1539580357
);

-- Estadísticas de puzzle rush
INSERT INTO chesscom_puzzle_rush_stats (
    stats_id, best_total_attempts, best_score
) VALUES (
    1, 19, 17
);

-- Archivo mensual de partidas
INSERT INTO chesscom_player_games_archive (player_id, archive_url) VALUES
(168897069, 'https://api.chess.com/pub/player/aitoruriaerandio/games/2025/06');

-- Supongamos que el id generado para chesscom_player_games_archive es 1

-- Partida de un mes
INSERT INTO chesscom_player_month_game (
    archive_id, url, move_by, pgn, time_control, last_activity, rated, turn, fen, start_time, time_class, rules, white, black
) VALUES (
    1,
    'https://www.chess.com/game/daily/807024754',
    0,
    '[Event "Let\'s Play!"]\n[Site "Chess.com"]\n[Date "2025.04.27"]\n...',
    '1/604800',
    1751285571,
    TRUE,
    'black',
    'r3r1k1/1ppq1pp1/p1n1b1np/4pN2/4P1P1/3P3P/PPPQN1B1/R4RK1 b - - 2 17',
    1745774671,
    'daily',
    'chess',
    'https://api.chess.com/pub/player/erik',
    'https://api.chess.com/pub/player/dalilbenchebra'
);

-- Partida en vivo
INSERT INTO chesscom_player_live_game (
    player_id, url, pgn, time_control, end_time, rated, accuracies_white, accuracies_black, tcn, uuid, initial_setup, fen, time_class, rules, eco
) VALUES (
    168897069,
    'https://www.chess.com/game/live/140106557948',
    '[Event "Live Chess"]\n[Site "Chess.com"]\n[Date "2025.06.28"]\n...',
    '180+2',
    1751125963,
    TRUE,
    63.17,
    72.89,
    'mCYIgv0SlBIBvB5QkA!Tnv7PBk9IbsInemnghgPgcugpuIZJAJSJsJTJdJ6SJt47tHp5kuSZHrXPIqQB',
    '568904aa-5437-11f0-91b1-f23cba01000f',
    'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1',
    '1q1rk2r/p2b1ppp/1p6/8/3nP3/BQ2NP2/PP2K1P1/R4B2 w k - 2 21',
    'blitz',
    'chess',
    'https://www.chess.com/openings/Sicilian-Defense-Kramnik-Variation...4.d4-cxd4-5.Nxd4-Nf6'
);

-- Supongamos que el id generado para chesscom_player_live_game es 1

-- Jugadores de la partida en vivo
INSERT INTO chesscom_player_live_game_player (
    live_game_id, color, rating, result, player_id_url, username, uuid
) VALUES
(1, 'white', 1923, 'resigned', 'https://api.chess.com/pub/player/aitoruriaerandio', 'AitorUriaErandio', '38e29a40-754c-11ec-98f9-1dbdc0fc367a'),
(1, 'black', 1940, 'win', 'https://api.chess.com/pub/player/lenci16', 'Lenci16', '310944fe-8ae0-11e8-802a-000000000000');