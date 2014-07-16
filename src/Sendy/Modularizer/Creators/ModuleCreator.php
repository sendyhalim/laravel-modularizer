<?php
namespace Sendy\Modularizer\Creators;

use Illuminate\Filesystem\Filesystem;

/**
 * @author Sendy Halim <sendyhalim93@gmail.com>
 */
class ModuleCreator extends Creator
{
	protected $directories = [
		'Controller',
		'Repository/Read',
		'Repository/Write',
		'RepositoryInterface/Read',
		'RepositoryInterface/Write',
		'database/migrations',
		'views'
	];

	protected $files = [
		'template/controller/ModuleBaseController.txt' => 'Controller/{{MODULE}}ModuleBaseController.php',
		'template/routes.txt'                           => 'routes.php'
	];

	public function __construct(Filesystem $f)
	{
		parent::__construct($f);
	}

	public function make($path, $data)
	{
		$path = $this->removeTrailingSlash($path);

		if ($this->filesystem->exists($path))
		{
			$module = basename($path);
			$this->setError("Module {$module} exists");

			return false;
		}

		foreach ($this->directories as $dir)
		{
			$dirPath = "{$path}/{$dir}";
			$this->prepareDirectory($dirPath);
		}

		foreach ($this->files as $templateFile => $destinationFile)
		{
			//replace placeholder in destination file
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