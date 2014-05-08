<?php
namespace {{baseDirectory}}\Core\Repositories;

use Eloquent;

class BasicRepositoryReader
{
	protected $class;

	/**
	 * Prevent class creation from outside this class's inheritance
	 */
	protected function __construct(Eloquent $class)
	{
		$this->class = $class;
	}

	public function find($id)
	{
		return $this->class->find($id);
	}

	public function findOrFail($id)
	{
		return $this->class->findOrFail($id);
	}

	public function findOrFailWithTrashed($id)
	{
		return $this->class->withTrashed()->findOrFail($id);
	}

	public function all()
	{
		return $this->class->all();
	}

	public function limit($start, $limit)
	{
		return $this->class->skip($start)->take($limit);
	}

	public function with(array $relationship)
	{
		return $this->class->with($relationship);
	}

	public function withTrashed()
	{
		return $this->class->withTrashed();
	}

	public function withTrashedRelationship(array $relationship)
	{
		$rel = [];

		foreach ($relationship as $relationshipName)
		{
			$rel[$relationshipName] = function($query)
			{
				$query->withTrashed();
			};
		}

		return $this->with($rel);
	}

	public function paginate($limit)
	{
		return $this->class->paginate($limit);
	}

	public function where($column, $operator, $value)
	{
		return $this->class->where($column, $operator, $value);
	}

	public function orderBy($column, $order)
	{
		return $this->class->orderBy($column, $order);
	}

	public function getClass()
	{
		return $this->class;
	}

	public function onlyTrashed()
	{
		return $this->onlyTrashed();
	}
}