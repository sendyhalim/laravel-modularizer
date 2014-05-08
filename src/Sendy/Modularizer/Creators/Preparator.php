<?php
namespace Sendy\Modularizer\Creators;

use Illuminate\Filesystem\Filesystem;

/**
 * @author Sendy Halim <sendyhalim93@gmail.com>
 */
class Preparator extends Creator
{
	protected $directories = [
		'Core/RepositoryInterfaces/Read',
		'Core/RepositoryInterfaces/Write',
		'Core/Repositories/Read',
		'Core/Repositories/Write',
		'Core/Validators/Interfaces',
	];

	/**
	 * template path => destination path
	 * @var array
	 */
	protected $files = [
		'templates/repository-interfaces/BasicRepositoryReaderInterface.txt' => 'Core/RepositoryInterfaces/Read/BasicRepositoryReaderInterface.php',
		'templates/repository-interfaces/BasicRepositoryWriterInterface.txt' => 'Core/RepositoryInterfaces/Write/BasicRepositoryWriterInterface.php',
		'templates/repositories/BasicRepositoryReader.txt'                   => 'Core/Repositories/Read/BasicRepositoryReader.php',
		'templates/repositories/BasicRepositoryWriter.txt'                   => 'Core/Repositories/Write/BasicRepositoryWriter.php',
		'templates/validators/ValidatorInterface.txt'                        => 'Core/Validators/Interfaces/ValidatorInterface.php',
	];

	public function __construct(Filesystem $f)
	{
		parent::__construct($f);
	}

	public function make($path, $data)
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

		foreach ($this->files as $templateFile => $destinationFile)
		{
			$templateFile = __DIR__ . "/{$templateFile}";
			$template = $this->makeTemplate(file_get_contents($templateFile), $data);
			$this->create($destinationFile, $path, $template);
		}

		return true;
	}

	protected function makeTemplate($templateFile, $data)
	{
		foreach ($data as $key => $value)
		{
			$templateFile = preg_replace("/\{\{$key\}\}/i", $value, $templateFile);
		}

		return $templateFile;
	}
}