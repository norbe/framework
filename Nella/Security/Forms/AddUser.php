<?php
/**
 * This file is part of the Nella Framework.
 *
 * Copyright (c) 2006, 2011 Patrik Votoček (http://patrik.votocek.cz)
 *
 * This source file is subject to the GNU Lesser General Public License. For more information please see http://nellacms.com
 */

namespace Nella\Security\Forms;

/**
 * Add user form
 *
 * @author	Patrik Votoček
 */
class AddUser extends \Nella\Forms\EntityForm
{
	public $successLink = ":Security:Backend:";

	protected function setup()
	{
		parent::setup();

		$roles = $this->getDoctrineContainer()->getService('Nella\Security\RoleEntity')->repository->fetchPairs('id', "name");

		$this->addText('username', "Username")->setRequired();
		$this->addEmail('email', "E-mail")->setRequired();
		$this->addPassword('password', "Password");
		$this->addPassword('password2', "Re-Password")->addCondition(static::FILLED)
			->addRule(static::EQUAL, NULL, $this['password']);
		$this->addSelect('role', "Role", $roles);
		$this->addSelect('lang', "Lang", array('en' => "English"))->setRequired(); // @todo

		$this->addSubmit('sub', "Add");

		$this->onSuccess[] = callback($this, 'process');
	}

	public function process()
	{
		$values = $this->getValues();
		$presenter = $this->getPresenter();
		$service = $this->getDoctrineContainer()->getService('Nella\Security\CredentialsEntity');

		try {
			$entity = $service->create($values);
			$presenter->logAction("Security", \Nella\Utils\IActionLogger::CREATE, "Created user '{$entity->username}'");
			$presenter->flashMessage(__("User '%s' successfuly added", $entity->username), 'success');
			$presenter->redirect($this->successLink);
		} catch (\Nella\Models\InvalidEntityException $e) {
			$this->processErrors($e->getErrors());
		} catch (\Nella\Models\DuplicateEntryException $e) {
			$this['username']->addError("Username %value already exist");
		}
	}
}
