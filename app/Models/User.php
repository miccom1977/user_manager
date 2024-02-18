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
	public function create(array $data)
	{
		try {
			return parent::create($data);
		} catch (PDOException $e) {
			error_log("Error Add User method: " . $e->getMessage());
			return false;
		}
	}

	/** Show User Method
	 * @param int $id
	 * @return false|mixed
	 */
	public function read(int $id)
	{
		try {
			return parent::read($id);
		} catch (PDOException $e) {
			error_log("Error Get User Data method: " . $e->getMessage());
			return false;
		}
	}

	/** Update User method
	 * @param array $userData
	 * @return array|false
	 */
	public function update(array $userData)
	{
		try {
			return parent::update($userData);
		} catch (PDOException $e) {
			error_log("Error Update User method: " . $e->getMessage());
			return false;
		}
	}

	/** Delete User method
	 * @param int $id
	 * @return bool
	 */
	public function delete(int $id)
	{
		try {
			return parent::delete($id);
		} catch (PDOException $e) {
			error_log("Error Delete User method: " . $e->getMessage());
			return false;
		}
	}

	/** Show All Users method
	 * @return array|false
	 */
	public function getAllUsers()
	{
		$query = "SELECT * FROM users";
		$stmt = $this->db->query($query);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}
