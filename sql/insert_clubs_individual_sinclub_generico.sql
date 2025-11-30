INSERT INTO `clubs` (`provincia_id`, `nombre`, `direccion`, `telefono`, `email`, `web`, `estado`, `fecha_alta`)
SELECT p.id, 'Individual', '', '', '', '', 1, NOW()
FROM `provincias` p
WHERE p.id != 48 -- Excluir Bizkaia
  AND p.estado = 1
  AND NOT EXISTS (
    SELECT 1 FROM `clubs` c 
    WHERE c.provincia_id = p.id 
    AND c.nombre = 'Individual'
  );

INSERT INTO `clubs` (`provincia_id`, `nombre`, `direccion`, `telefono`, `email`, `web`, `estado`, `fecha_alta`)
SELECT p.id, 'Sin club', '', '', '', '', 1, NOW()
FROM `provincias` p
WHERE p.id != 48 -- Excluir Bizkaia
  AND p.estado = 1
  AND NOT EXISTS (
    SELECT 1 FROM `clubs` c 
    WHERE c.provincia_id = p.id 
    AND c.nombre = 'Sin club'
  );