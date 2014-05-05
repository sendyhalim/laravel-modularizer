<?php
namespace {{baseDirectory}}\Core\RepositoryInterfaces;

use {{baseDirectory}}\Core\Validators\Interfaces\ValidatorInterface;

interface BasicRepositoryWriterInterface
{
	public function create(array $inputs, ValidatorInterface $v);
	public function update(array $inputs, $id, ValidatorInterface $v);
	public function delete($id);
	public function getCurrentValidator();
	public function getCurrentErrors();
	public function getCurrentModel();
}