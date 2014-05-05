<?php
namespace {{baseDirectory}}\Core\Repositories;

use Eloquent;
use {{baseDirectory}}\Core\Validators\Interfaces\ValidatorInterface;

class BasicRepositoryWriter
{
	protected $currentModel;

	protected $validator;
	protected $class;
	/**
	 * Prevent class creation from outside this class's inheritance
	 */
	protected function __construct(Eloquent $class)
	{
		$this->class = $class;
	}

	public function create(array $input, ValidatorInterface $v)
	{
		$this->validator = $v;

		if ($v->validate($input))
		{
			return $this->currentModel = $this->class->create($input);
		}

		return false;
	}

	public function update(array $input, $id, ValidatorInterface $v)
	{
		$this->currentModel = $this->class->withTrashed()->findOrFail($id);
		$this->validator = $v;

		if ($v->validate($input))
		{
			$this->currentModel->update($input);

			return $id;
		}

		return false;
	}

	public function delete($id)
	{
		$this->currentModel = $this->class->findOrFail($id);

		return $this->currentModel->delete();
	}

	public function getCurrentValidator()
	{
		return $this->validator;
	}

	public function getCurrentErrors()
	{
		if ($this->validator)
			return $this->validator->getCurrentErrors();

		return [];
	}

	public function getCurrentModel()
	{
		return $this->currentModel;
	}

	public function getClassName()
	{
		return $this->class;
	}

	public function setValidator(ValidatorInterface $validator)
	{
		$this->validator = $validator;
	}

	public function getClass()
	{
		return $this->class;
	}

	public function restoreDeleted($id)
	{
		$model = $this->class->onlyTrashed()->findOrFail($id);

		return $model->restore();
	}
}