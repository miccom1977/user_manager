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
	 * @return int
	 */
	public function create(array $data): int
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
			return 0;
		}
	}

	/** Base Show UserData method
	 * @param int $id
	 * @return array
	 */
	public function read(int $id): array
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
			return [];
		}
	}

	/** Base Update User Data method
	 * @param array $data
	 * @param array $conditions
	 * @return array
	 */
	public function update(array $data, array $conditions): array
	{
		try {
			// Prepare table sql
			$query = "UPDATE {$this->tableName} SET ";
			// prepare column sql
			$setValues = $this->prepareElementsToQuery($data);
			$query .= implode(", ", $setValues);

			// prepare were sql
			$whereConditions = $this->prepareElementsToQuery($conditions);
			$whereClause = implode(" AND ", $whereConditions);

			// join all
			$query .= " WHERE " . $whereClause;

			// prepare PDO
			$statement = $this->pdo->prepare($query);
			$statement->execute(array_merge($data, $conditions));

			return ['success' => true, 'message' => 'Data updated successfully'];
		} catch (PDOException $e) {
			error_log("Error Update method: " . $e->getMessage());
			return ['success' => false, 'message' => 'Error updating data: ' . $e->getMessage()];
		}
	}

	/** Base Delete User method
	 * @param int $id
	 * @return bool
	 */
	public function delete(int $id): bool
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
			error_log("Error in delete data: " . $e->getMessage());
			return false;
		}
	}

	/** Method prepare elements to query from array
	 * @param array $conditions
	 * @return array
	 */
	private function prepareElementsToQuery(array $conditions): array
	{
		$aConditions = [];
		foreach ($conditions as $column => $value) {
			$aConditions[] = "$column = :$column";
		}
		return $aConditions;
	}
}
