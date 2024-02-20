<?php
namespace App\Services;

use App\Models\Group;
use PDOException;

class GroupService
{
	protected $group;

	public function __construct(Group $group)
	{
		$this->group = $group;
	}

	/** Get All groups Data method
	 * @return array
	 */
	public function getAllGroups(): array
	{
		return $this->group->getAllGroups();
	}

	/** Get group Data by Id method
	 * @param $groupId
	 * @return false
	 */
	public function getGroupById($groupId): array
	{
		// Use Model Method
		return $this->group->read($groupId);
	}

	/** Create group method
	 * @param $groupData
	 * @return false|string|null
	 */
	public function createGroup($groupData): int
	{
		try {
			// Use Model Method
			return $this->group->create($groupData);
		} catch (PDOException $e) {
			return 0;
		}
	}

	/** Delete group method
	 * @param $groupId
	 * @return bool
	 */
	public function deleteGroupById($groupId): bool
	{
		try {
			// Use Model Method
			return $this->group->delete($groupId);

		} catch (PDOException $e) {
			return false;
		}
	}

	/** Update group Data method
	 * @param array $groupData
	 * @param array $conditions
	 * @return array
	 */
	public function updateGroup(array $groupData, array $conditions): array
	{
		// Use Model Method
		return $this->group->update($groupData, $conditions);
	}
}
