<?php
namespace app\Interfaces;

interface CRUDInterface
{
	public function create(array $data);

	public function read(int $id);

	public function update(array $userData);

	public function delete(int $id);
}
