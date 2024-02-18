<?php
namespace App\Models;

use App\Interfaces\CRUDInterface;
use PDO;
use PDOException;

abstract class BaseModel implements CRUDInterface
{
	protected string $tableName;
	protected PDO $pdo;

	public function __construct(PDO $pdo, $tableName)
	{
		$this->pdo = $pdo;
		$this->tableName = $tableName;
	}

	/** Base Create User method
	 * @param array $data
	 * @return false|string
	 */
	public function create(array $data)
	{
		try {
			// Prepare params to SQL Query
			$fields = implode(', ', array_keys($data));
			$placeholders = ':' . implode(', :', array_keys($data));
			$query = "INSERT INTO {$this->tableName} ($fields) VALUES ($placeholders)";

			// Prepare Full SQL Query
			$statement = $this->pdo->prepare($query);
			$statement->execute($data);

			// Return User Id
			return $this->pdo->lastInsertId();
		} catch (PDOException $e) {
			error_log("Error in add record: " . $e->getMessage());
			return false;
		}
	}

	/** Base Show UserData method
	 * @param int $id
	 * @return false|mixed
	 */
	public function read(int $id)
	{
		try {
			// Prepare SQL
			$query = "SELECT * FROM {$this->tableName} WHERE id = :id";

			// Prepare and Execute Query
			$statement = $this->pdo->prepare($query);
			$statement->execute(['id' => $id]);

			// Return User Data
			return $statement->fetch(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			error_log("Error in Get User Data method: " . $e->getMessage());
			return false;
		}
	}

	/** Base Update User Data method
	 * @param array $userData
	 * @return array
	 */
	public function update(array $userData)
	{
		try {
			// Prepare SQL
			$query = "UPDATE {$this->tableName} SET name = :name, first_name = :first_name, last_name = :last_name, birth_date = :birth_date WHERE id = :id";

			// Bind Params
			$statement = $this->pdo->prepare($query);
			$statement->execute([
				'id' => $userData['id'],
				'name' => $userData['name'],
				'first_name' => $userData['first_name'],
				'last_name' => $userData['last_name'],
				'birth_date' => $userData['birth_date']
			]);

			return ['success' => true, 'message' => 'User data saved.'];
		} catch (PDOException $e) {
			error_log('Error in update user data: ' . $e->getMessage());
			return ['success' => false, 'message' => 'Error in update user data: ' . $e->getMessage()];

		}
	}

	/** Base Delete User method
	 * @param int $id
	 * @return bool
	 */
	public function delete(int $id)
	{
		try {
			// Prepare SQL
			$query = "DELETE FROM {$this->tableName} WHERE id = :id";

			// Bind Params
			$statement = $this->pdo->prepare($query);
			$statement->execute(['id' => $id]);

			// If done, return true
			return true;
		} catch (PDOException $e) {
			error_log("Błąd podczas usuwania rekordu: " . $e->getMessage());
			return false;
		}
	}
}
