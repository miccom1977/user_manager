<?php
namespace App\Models;

use PDO;
use PDOException;

class Group extends BaseModel
{
	private PDO $db;

	public function __construct(PDO $pdo) {
		parent::__construct($pdo, 'groups');
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
			error_log("Error Add Group method: " . $e->getMessage());
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
			error_log("Error Get Group Data method: " . $e->getMessage());
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
			error_log("Error Update Group method: " . $e->getMessage());
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
			error_log("Error Delete Group method: " . $e->getMessage());
			return false;
		}
	}

	/** Show All Groups method
	 * @return array
	 */
	public function getAllGroups(): array
	{
		$query = "SELECT groups.id, groups.name, GROUP_CONCAT(users.first_name SEPARATOR ', ') AS users_names
			FROM groups
			LEFT JOIN users ON groups.id = users.group_id
			GROUP BY groups.id";
		$stmt = $this->db->query($query);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}