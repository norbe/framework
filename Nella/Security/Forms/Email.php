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
 * Credentials login form
 *
 * @author	Patrik Votoček
 */
class Email extends \Nella\Forms\EntityForm
{
	public $successLink = ":Security:Backend:profile";

	protected function setup()
	{
		parent::setup();

		$this->addPassword('current', "Current password")->setRequired();
		$this->addEmail('email', "E-mail")->setRequired();

		$this->addSubmit('sub', "Save");

		$this->onSuccess[] = callback($this, 'process');
	}

	public function process()
	{
		$values = $this->getValues();
		$presenter = $this->getPresenter();

		$service = $this->getDoctrineContainer()->getService('Nella\Security\CredentialsEntity');
		$credentials = $service->repository->findOneByIdentity($presenter->getUser()->identity->id);

		if (!$credentials->verifyPassword($values['current'])) {
			$this['current']->addError("Current password is not valid");
		} else {
			$service->update($credentials, $values);
			$presenter->logAction("Security", \Nella\Utils\IActionLogger::UPDATE, "Changed email");
			$presenter->flashMessage(__("Email changed successfuly"), 'success');
			$presenter->redirect($this->successLink);
		}
	}
}
