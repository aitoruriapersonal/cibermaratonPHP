<?php
require_once __DIR__ . '/../dao/ValoresGenericosDAO.php';

class ValoresGenericosService
{
    private ValoresGenericosDAO $dao;

    public function __construct(PDO $pdo)
    {
        $this->dao = new ValoresGenericosDAO($pdo);
    }

    public function getAll(): array
    {
        return $this->dao->getAll();
    }

    public function getByTipo(string $tipo): array
    {
        return $this->dao->getByTipo($tipo);
    }

    public function getById(int $id): ?ValorGenerico
    {
        return $this->dao->getById($id);
    }

    public function crearValor(ValorGenerico $valor): int
    {
        return $this->dao->create($valor);
    }

    public function actualizarValor(ValorGenerico $valor): bool
    {
        return $this->dao->update($valor);
    }

    public function eliminarValor(int $id): bool
    {
        return $this->dao->delete($id);
    }
}