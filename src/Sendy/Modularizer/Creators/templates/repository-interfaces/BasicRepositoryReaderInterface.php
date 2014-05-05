<?php
namespace {{baseDirectory}}\Core\RepositoryInterfaces;

interface BasicRepositoryReaderInterface
{
	public function find($id);
	public function findOrFail($id);
	public function findOrFailWithTrashed($id);
	public function all();
	public function limit($start, $limit);
	public function paginate($limit);
	public function where($column, $operator, $value);
	public function with(array $relationship);
	public function withTrashed();
	public function withTrashedRelationship(array $relationship);
	public function onlyTrashed();
	public function orderBy($column, $order);
	public function getClass();
}