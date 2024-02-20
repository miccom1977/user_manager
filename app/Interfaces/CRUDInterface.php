<?php
namespace app\Interfaces;

interface CRUDInterface
{
	public function create(array $data): int;

	public function read(int $id): array;

	public function update(array $data, array $conditions): array;

	public function delete(int $id): bool;
}
