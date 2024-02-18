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
	 * @return false|mixed
	 */
	public function getUserById($userId)
	{
		// Use Model Method
		return $this->user->read($userId);
	}

	/** Create User method
	 * @param $userData
	 * @return false|string|null
	 */
	public function createUser($userData)
	{
		try {
			// Use Model Method
			return $this->user->create($userData);
		} catch (PDOException $e) {
			return null;
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
	 * @return array|false
	 */
	public function updateUser(array $userData)
	{
		// Use Model Method
		return $this->user->update($userData);
	}
}