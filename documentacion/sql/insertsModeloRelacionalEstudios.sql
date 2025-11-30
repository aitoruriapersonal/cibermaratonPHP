-- Universidad
INSERT INTO universidad (id, nombre_eus, siglas_eus, nombre_esp, siglas_esp)
VALUES (1, 'Euskal Herriko Unibertsitatea', 'EHU', 'Universidad del Pais Vasco', 'EHU');

-- Tipos de estudio
INSERT INTO tipo_estudio (id, nombre_eus, nombre_esp) VALUES
(1, 'Grados', 'Grados'),
(2, 'Masterrak eta Postgraduak', 'Masters y Postgrados'),
(3, 'Doktoregoak', 'Doctorados');

-- Campus
INSERT INTO campus (id, universidad_id, nombre_eus, nombre_esp) VALUES
(1, 1, 'Araba', 'Alava'),
(2, 1, 'Bizkaia', 'Bizkaia'),
(3, 1, 'Gipuzkoa', 'Gipuzkoa');

-- Centros (ejemplo solo para Araba)
INSERT INTO centro (id, campus_id, nombre_eus, nombre_esp) VALUES
(1, 1, 'Vitoria-Gasteizko Ingeniaritza Eskola', 'Escuela de Ingeniería de Vitoria-Gasteiz'),
(2, 1, 'Ekonomia eta Enpresa Fakultatea', 'Facultad de Economía y Empresa');

-- Grados (ejemplo para el primer centro)
INSERT INTO grado (id, centro_id, nombre_eus, nombre_esp) VALUES
(1, 1, 'Gradu Bikoitza Ingeniaritza Mekanikoa + Industria Elektronikaren eta Automatikaren Ingeniaritza', 'Doble Grado en Ingeniería Mecánica + Ingeniería Electrónica Industrial y Automática'),
(2, 1, 'Gradu bikoitza Ingeniaritza Mekanikoa + Enpresen Administrazio eta Zuzendaritza', 'Doble Grado en Ingeniería Mecánica y en Administración y Dirección de Empresas');

-- Postgrados (ejemplo)
INSERT INTO postgrado (id, universidad_id, nombre_eus, nombre_esp) VALUES
(1, 1, 'IMHko Ingeniaritza Dualaren Unibertsitate Eskola', 'Escuela Universitaria de Ingeniería Dual del IMH');

-- Doctorados (ejemplo)
INSERT INTO doctorado (id, universidad_id, nombre_eus, nombre_esp) VALUES
(1, 1, 'IMHko Ingeniaritza Dualaren Unibertsitate Eskola', 'Escuela Universitaria de Ingeniería Dual del IMH');