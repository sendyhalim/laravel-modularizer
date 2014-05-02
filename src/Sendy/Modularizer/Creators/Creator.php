<?php
namespace Sendy\Modularizer\Creators;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

/**
 * @author Sendy Halim <sendyhalim93@gmail.com>
 */
abstract class Creator
{
	protected $filesystem;
	private $error;

	public function __construct(Filesystem $f)
	{
		$this->filesystem = $f;
	}

	protected function create($filename, $path, $data)
	{
		$path = $this->removeTrailingSlash($path);
		$path.= "/{$filename}";

		if (!$this->filesystem->exists($path))
		{
			return $this->filesystem->put($path, $data);
		}

		return false;
	}

	protected function prepareDirectory($path)
	{
		if (!$this->filesystem->exists($path))
		{
			$this->filesystem->makeDirectory($path, 0777, true);
			return true;
		}

		return false;
	}

	protected function removeTrailingSlash($path)
	{
		if (Str::endsWith($path, '/'))
		{
			unset($path[strlen($path)-1]);
		}

		return $path;
	}

	public function setError($error)
	{
		$this->error = $error;
	}

	public function getError()
	{
		return $this->error;
	}
}