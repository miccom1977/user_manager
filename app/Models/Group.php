<?php
namespace App\Models;

use PDO;

class Group extends BaseModel
{
	public function __construct(PDO $pdo) {
		parent::__construct($pdo, 'groups');
	}

	// Metoda do pobierania wszystkich użytkowników
	public function getAllGroups() {
		// Logika pobierania wszystkich użytkowników
	}

	// Metoda do dodawania użytkownika
	public function addGroup($data) {
		// Logika dodawania użytkownika
	}

	// Metoda do usuwania użytkownika
	public function deleteGroup($id) {
		// Logika usuwania użytkownika
	}

	// Metoda do edycji użytkownika
	public function editGroup($id, $data) {
		// Logika edycji użytkownika
	}
}