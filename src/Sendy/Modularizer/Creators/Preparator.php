<?php
namespace Sendy\Modularizer\Creators;

use Illuminate\Filesystem\Filesystem;

/**
 * @author Sendy Halim <sendyhalim93@gmail.com>
 */
class Preparator extends Creator
{
	private $directories = [
		'Core/RepositoryInterfaces/Read',
		'Core/RepositoryInterfaces/Write',
		'Core/Repositories/Read',
		'Core/Repositories/Write',
		'Core/Validators/Interfaces',
	];

	private $files = [
		'Core/RepositoryInterfaces/Read/BasicRepositoryReaderInterface.php',
		'Core/RepositoryInterfaces/Write/BasicRepositoryWriterInterface.php',
		'Core/Repositories/Read/BasicRepositoryReader.php',
		'Core/Repositories/Write/BasicRepositoryWriter.php',
		'Core/Validators/Interfaces/ValidatorInterface.php',
	];

	public function __construct(Filesystem $f)
	{
		parent::__construct($f);
	}

	public function make($path)
	{
		$path = $this->removeTrailingSlash($path);

		// check in the current path if dir Core exists or not
		if ($this->filesystem->exists("{$path}/Core"))
		{
			$module = basename($path);
			$this->setError("Core exists");

			return false;
		}

		foreach ($this->directories as $dir)
		{
			$dirPath = "{$path}/{$dir}";
			$this->prepareDirectory($dirPath);
		}

		foreach ($this->files as $file)
		{
			$this->create($file, $path, '');
		}

		return true;
	}
}