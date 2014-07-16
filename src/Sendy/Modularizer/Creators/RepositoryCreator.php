<?php
namespace Sendy\Modularizer\Creators;

use Illuminate\Filesystem\Filesystem;

/**
 * @author Sendy Halim <sendyhalim93@gmail.com>
 */
class RepositoryCreator extends Creator
{
	protected $directories = [
		'RepositoryInterface/Read',
		'RepositoryInterface/Write',
		'Repository/Read',
		'Repository/Write',
	];

	/**
	 * template path => destination path
	 * @var array
	 */
	protected $files = [
		'template/repository-interface/ModelRepositoryReaderInterface.txt' => 'RepositoryInterface/Read/{{MODEL}}RepositoryReaderInterface.php',
		'template/repository-interface/ModelRepositoryWriterInterface.txt' => 'RepositoryInterface/Write/{{MODEL}}RepositoryWriterInterface.php',
		'template/repository/ModelRepositoryReader.txt'                    => 'Repository/Read/{{MODEL}}RepositoryReader.php',
		'template/repository/ModelRepositoryWriter.txt'                    => 'Repository/Write/{{MODEL}}RepositoryWriter.php',
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

			//get the template file inside the template dir
			$templateFile = __DIR__ . "/{$templateFile}";
			$template = $this->makeTemplate(file_get_contents($templateFile), $data);

			//create the template
			$this->create($destinationFile, $path, $template);
		}

		return true;
	}
}