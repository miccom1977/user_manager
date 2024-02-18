<?php
namespace App\Controllers;

use App\Models\Group;

class GroupController
{
	protected $group;

	public function __construct(Group $group) {
		$this->group = $group;
	}

	// Metoda do wyświetlania listy użytkowników
	public function index() {
		$users = $this->group->getAllGroups();
		include '../views/group_list.php';
	}

	// Metoda do dodawania użytkownika
	public function add() {
		// Logika dodawania użytkownika
	}

	// Metoda do usuwania użytkownika
	public function delete($id) {
		// Logika usuwania użytkownika
	}

	// Metoda do edycji użytkownika
	public function edit($id) {
		// Logika edycji użytkownika
	}
}