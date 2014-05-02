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

	private $moduleCretor;

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

		$this->moduleCreator->make($path);
	}

	protected function getPath()
	{
		$basePath = $this->argument('path');
		$name = $this->argument('name');
		$baseDir = $this->option('basedirectory');

		return "{$basePath}/{$baseDir}/{$name}";
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
