<?php
namespace Sendy\Modularizer\Creators;

use Illuminate\Filesystem\Filesystem;

/**
 * @author Sendy Halim <sendyhalim93@gmail.com>
 */
class RepositoryCreator extends Creator
{
	protected $directories = [
		'RepositoryInterfaces/Read',
		'RepositoryInterfaces/Write',
		'Repositories/Read',
		'Repositories/Write',
	];

	/**
	 * template path => destination path
	 * @var array
	 */
	protected $files = [
		'templates/repository-interfaces/ModelRepositoryReaderInterface.txt' => 'RepositoryInterfaces/Read/{{MODEL}}RepositoryReaderInterface.php',
		'templates/repository-interfaces/ModelRepositoryWriterInterface.txt' => 'RepositoryInterfaces/Write/{{MODEL}}RepositoryWriterInterface.php',
		'templates/repositories/ModelRepositoryReader.txt'                   => 'Repositories/Read/{{MODEL}}RepositoryReader.php',
		'templates/repositories/ModelRepositoryWriter.txt'                   => 'Repositories/Write/{{MODEL}}RepositoryWriter.php',
	];

	public function __construct(Filesystem $f)
	{
		parent::__construct($f);
	}

	public function make($path, $data)
	{
		$path = $this->removeTrailingSlash($path);

		foreach ($this->directories as $dir)
		{
			$dirPath = "{$path}/{$dir}";
			$this->prepareDirectory($dirPath);
		}

		foreach ($this->files as $templateFile => $destinationFile)
		{
			//put model name in destination file
			$destinationFile = $this->makeTemplate($destinationFile, $data);

			//get the template file inside the templates dir
			$templateFile = __DIR__ . "/{$templateFile}";
			$template = $this->makeTemplate(file_get_contents($templateFile), $data);

			//create the template
			$this->create($destinationFile, $path, $template);
		}

		return true;
	}
}