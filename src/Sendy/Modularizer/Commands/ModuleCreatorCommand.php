<?php
namespace Sendy\Modularizer\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Sendy\Modularizer\Creators\ModuleCreator;

class ModuleCreatorCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'modularizer:create-module';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create new module.';

	private $moduleCreator;
	private $moduleName;
	private $modulePath;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(ModuleCreator $mc)
	{
		parent::__construct();

		$this->moduleCreator = $mc;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$path = $this->getPath();

		if($this->moduleCreator->make($path))
		{
			return $this->info("{$this->moduleName} has been created in {$this->modulePath}");
		}

		$this->error("Could not create {$this->moduleName} in {$this->modulePath}");
		$this->error($this->moduleCreator->getError());
	}

	protected function getPath()
	{
		$this->moduleName = $name = $this->argument('name');
		$this->modulePath = $this->argument('path') . '/' . $this->option('basedirectory');

		return "{$this->modulePath}/{$this->moduleName}";
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			[
				'path',
				InputArgument::REQUIRED,
				'Path of the module',
			],
			[
				'name',
			 	InputArgument::REQUIRED,
			 	'Name of the module to created.',
			],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			[
				'basedirectory',
				null,
				InputOption::VALUE_OPTIONAL,
				'Base directory of modules',
				'Module',
			]
		];

		array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
