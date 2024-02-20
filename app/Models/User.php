<?php
namespace App\Models;

use PDO;
use PDOException;

class User extends BaseModel
{
	private $db;

	public function __construct(PDO $pdo) {
		parent::__construct($pdo, 'users');
		$this->db = $pdo;
	}

	/** Create method
	 * @param array $data
	 * @return false|string
	 */
	public function create(array $data): int
	{
		try {
			return parent::create($data);
		} catch (PDOException $e) {
			error_log("Error Add User method: " . $e->getMessage());
			return 0;
		}
	}

	/** Show User Method
	 * @param int $id
	 * @return false|mixed
	 */
	public function read(int $id): array
	{
		try {
			return parent::read($id);
		} catch (PDOException $e) {
			error_log("Error Get User Data method: " . $e->getMessage());
			return [];
		}
	}

	/** Update User method
	 * @param array $data
	 * @param array $conditions
	 * @return array
	 */
	public function update(array $data, array $conditions): array
	{
		try {
			return parent::update($data, $conditions);
		} catch (PDOException $e) {
			error_log("Error Update User method: " . $e->getMessage());
			return ['success' => false, 'message' => 'Error updating data: ' . $e->getMessage()];
		}
	}

	/** Delete User method
	 * @param int $id
	 * @return bool
	 */
	public function delete(int $id): bool
	{
		try {
			return parent::delete($id);
		} catch (PDOException $e) {
			error_log("Error Delete User method: " . $e->getMessage());
			return false;
		}
	}

	/** Show All Users method
	 * @return array
	 */
	public function getAllUsers(): array
	{
		$query = "SELECT users.*, groups.name AS group_name
			FROM users
			LEFT JOIN groups ON users.group_id = groups.id
		";
		$stmt = $this->db->query($query);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}
