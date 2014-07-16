<?php
namespace Sendy\Modularizer\Creators;

use Illuminate\Filesystem\Filesystem;

/**
 * @author Sendy Halim <sendyhalim93@gmail.com>
 */
class Preparator extends Creator
{
	protected $directories = [
		'Core/RepositoryInterface/Read',
		'Core/RepositoryInterface/Write',
		'Core/Repository/Read',
		'Core/Repository/Write',
		'Core/Validator',
	];

	/**
	 * template path => destination path
	 * @var array
	 */
	protected $files = [
		'template/repository-interface/BasicRepositoryReaderInterface.txt' => 'Core/RepositoryInterface/Read/BasicRepositoryReaderInterface.php',
		'template/repository-interface/BasicRepositoryWriterInterface.txt' => 'Core/RepositoryInterface/Write/BasicRepositoryWriterInterface.php',
		'template/repository/BasicRepositoryReader.txt'                    => 'Core/Repository/Read/BasicRepositoryReader.php',
		'template/repository/BasicRepositoryWriter.txt'                    => 'Core/Repository/Write/BasicRepositoryWriter.php',
		'template/validator/ValidatorInterface.txt'                        => 'Core/Validator/ValidatorInterface.php',
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
			// get the template
			$templateFile = __DIR__ . "/{$templateFile}";
			$template = $this->makeTemplate(file_get_contents($templateFile), $data);

			//create the template
			$this->create($destinationFile, $path, $template);
		}

		return true;
	}
}