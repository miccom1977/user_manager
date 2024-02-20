<?php
namespace App\Controllers;

use App\Services\GroupService;

class GroupController
{
	protected GroupService $groupService;

	public function __construct(GroupService $groupService)
	{
		$this->groupService = $groupService;
	}

	/** Show group list method
	 * @return void
	 */
	public function index(): void
	{
		// Get group list
		$groups = $this->groupService->getAllGroups();

		// Put data to endpoint
		header('Content-Type: application/json');
		echo json_encode($groups);
	}

	/** Add new group method
	 * @return void
	 */
	public function create(): void
	{

		$groupData = $this->validateData($_POST);

		// Add new group in service
		$groupId = $this->groupService->createGroup($groupData);

		if ($groupId) {
			// If group added, send response
			echo json_encode(['success' => true, 'message' => 'group added.', 'groupId' => $groupId]);
		} else {
			// Send error
			echo json_encode(['success' => false, 'message' => 'Error in addGroup process.']);
		}
	}

	/** Show group Data
	 * @param $groupId
	 * @return void
	 */
	public function read($groupId): void
	{
		// Get groupData
		$groupData = $this->groupService->getGroupById($groupId);

		// Put data to endpoint
		header('Content-Type: application/json');
		echo json_encode($groupData);
	}


	/** Delete group method
	 * @param $groupId
	 * @return void
	 */
	public function delete($groupId): void
	{
		$groupData = $this->groupService->deleteGroupById($groupId);
		// Put data to endpoint
		header('Content-Type: application/json');
		echo json_encode($groupData);
	}

	/** Update groupData method
	 * @param $groupId
	 * @return void
	 */
	public function update($groupId): void
	{
		// Send POST Data to validate
		$groupData = $this->validateData($_POST);
		$conditions = ['id' => $groupId];
		// Update groupData to service method
		$result = $this->groupService->updateGroup($groupData, $conditions);

		// Put data to endpoint
		header('Content-Type: application/json');
		echo json_encode($result);
	}

	/** Validate Data method
	 * @param $post
	 * @return array
	 */
	private function validateData($post): array
	{
		$errors = [];

		$name = filter_var($post['name'], FILTER_SANITIZE_STRING);
		if (empty($name)) {
			$errors[] = ['field' => 'name', 'message' => 'Field "Name" is required'];
			echo json_encode(['success' => false, 'errors' => $errors]);
			exit;
		}

		return [
			'name' => $name
		];
	}
}
