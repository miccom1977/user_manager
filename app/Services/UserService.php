<?php
namespace App\Services;

use App\Models\User;
use PDOException;

class UserService
{
	protected $user;

	public function __construct(User $user)
	{
		$this->user = $user;
	}

	/** Get All users Data method
	 * @return array|false
	 */
	public function getAllUsers()
	{
		return $this->user->getAllUsers();
	}

	/** Get User Data by Id method
	 * @param $userId
	 * @return array
	 */
	public function getUserById($userId): array
	{
		// Use Model Method
		return $this->user->read($userId);
	}

	/** Create User method
	 * @param $userData
	 * @return int
	 */
	public function createUser($userData): int
	{
		try {
			// Use Model Method
			return $this->user->create($userData);
		} catch (PDOException $e) {
			return 0;
		}
	}

	/** Delete User method
	 * @param $userId
	 * @return bool
	 */
	public function deleteUserById($userId): bool
	{
		try {
			// Use Model Method
			return $this->user->delete($userId);

		} catch (PDOException $e) {
			return false;
		}
	}

	/** Update User Data method
	 * @param array $userData
	 * @param array $conditions
	 * @return array
	 */
	public function updateUser(array $userData, array $conditions): array
	{
		// Use Model Method
		return $this->user->update($userData, $conditions);
	}
}
