<?php
namespace Sendy\Modularizer\Commands;
use Symfony\Component\Console\Input\InputOption,
	Symfony\Component\Console\Input\InputArgument;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand,
	Illuminate\Database\Migrations\MigrationCreator;
use Config;

class ModuleMigrateMakeCommand extends MigrateMakeCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'modularizer:make-migration';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create new migration for a module.';

	protected $creator;
	private $moduleName;
	private $modulePath;

	public function __construct(MigrationCreator $creator)
	{
		parent::__construct($creator, '');
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		// set path explicitly, see Illuminate\Database\Console\Migrations\BaseCommand line 19
		// if path option is set, then the other migration options are not needed

		$this->input->setOption('path', $this->getPath());

		parent::fire();

		return $this->info("Migration has been created for module {$this->moduleName} in {$this->modulePath}.");
	}

	protected function getPath()
	{
		$this->moduleName = $this->ucwordsArgument('module');

		// use basename because module.base_path will be appended with app_path()
		// again in the laravel migration command
		$defaultPath = basename(Config::get('modularizer::module.base_path'));

		// if no custom path then set to default migration start path, "app/"
		if (!$path = $this->option('path'))
		{
			$path = "app/{$defaultPath}";
		}

		$this->modulePath = $path . '/' . $this->ucwordsOption('basedirectory');

		return "{$this->modulePath}/{$this->moduleName}/database/migrations";
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
				'name',
			 	InputArgument::REQUIRED,
			 	'The name of the migration.',
			],
			[
				'module',
			 	InputArgument::REQUIRED,
			 	'Name of the module the migration will be created for.',
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
				'path',
				null,
				InputOption::VALUE_OPTIONAL,
				'Path of the modules',
				null
			],
			[
				'basedirectory',
				null,
				InputOption::VALUE_OPTIONAL,
				'Base directory of modules',
				Config::get('modularizer::module.base_directory'),
			],
			[
				'create',
				null,
				InputOption::VALUE_OPTIONAL,
				'The table to be created.'
			],
			[
				'table',
				null,
				InputOption::VALUE_OPTIONAL,
				'The table to migrate.'
			],
		];
	}

	public function getCreator()
	{
		return $this->creator;
	}

	protected function ucwordsArgument($arg)
	{
		return ucwords($this->argument($arg));
	}

	protected function ucwordsOption($opt)
	{
		return ucwords($this->option($opt));
	}
}