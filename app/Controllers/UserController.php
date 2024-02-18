<?php
namespace App\Controllers;

use App\Services\UserService;

class UserController
{
	protected $userService;

	public function __construct(UserService $userService) {
		$this->userService = $userService;
	}

	/** Show User list method
	 * @return void
	 */
	public function index() {
		// Get user list
		$users = $this->userService->getAllUsers();

		// Put data to endpoint
		header('Content-Type: application/json');
		echo json_encode($users);
	}

	/** Add new user method
	 * @return void
	 */
	public function create()
	{

		$userData = $this->validateData($_POST);

		// Add new user in service
		$userId = $this->userService->createUser($userData);

		if ($userId) {
			// If user added, send response
			echo json_encode(['success' => true, 'message' => 'User added.', 'userId' => $userId]);
		} else {
			// Send error
			echo json_encode(['success' => false, 'message' => 'Error in addUser process.']);
		}
	}

	/** Show User Data
	 * @param $userId
	 * @return void
	 */
	public function read($userId)
	{
		// Get UserData
		$userData = $this->userService->getUserById($userId);

		// Put data to endpoint
		header('Content-Type: application/json');
		echo json_encode($userData);
	}


	/** Delete User method
	 * @param $userId
	 * @return void
	 */
	public function delete($userId)
	{
		$userData = $this->userService->deleteUserById($userId);
		// Put data to endpoint
		header('Content-Type: application/json');
		echo json_encode($userData);
	}

	/** Update userData method
	 * @param $userId
	 * @return void
	 */
	public function update($userId)
	{
		// Send POST Data to validate
		$userData = $this->validateData($_POST, $userId);

		// Update UserData to service method
		$result = $this->userService->updateUser($userData);

		// Put data to endpoint
		header('Content-Type: application/json');
		echo json_encode($result);
	}

	/** Validate Data method
	 * @param $post
	 * @param null $userId
	 * @return array|void
	 */
	private function validateData($post, $userId = null)
	{
		$errors = [];

		$name = filter_var($post['name'], FILTER_SANITIZE_STRING);
		if (empty($name)) {
			$errors[] = ['field' => 'name', 'message' => 'Field "Name" is required'];
		}

		$first_name = filter_var($post['first_name'], FILTER_SANITIZE_STRING);
		if (empty($first_name)) {
			$errors[] = ['field' => 'first_name', 'message' => 'Field "First name" is required'];
		}

		$last_name = filter_var($post['last_name'], FILTER_SANITIZE_STRING);
		if (empty($last_name)) {
			$errors[] = ['field' => 'last_name', 'message' => 'Field "Last name" is required'];
		}

		$date_of_birth = $post['birth_date'] ?? null;
		$current_date = strtotime(date('Y-m-d'));
		$selected_date = strtotime($date_of_birth);

		if (empty($post['birth_date'])) {
			$errors[] = ['field' => 'birth_date', 'message' => 'Date of birth is required'];
		} else {
			if ($selected_date > $current_date) {
				$errors[] = ['field' => 'birth_date', 'message' => 'Date of birth is incorrect'];
			}
		}

		// Show errors, if exists
		if (!empty($errors)) {
			echo json_encode(['success' => false, 'errors' => $errors]);
			exit;
		}
		return [
			'id' => $userId,
			'name' => $name,
			'first_name' => $first_name,
			'last_name' => $last_name,
			'birth_date' => $date_of_birth
		];
	}
}
