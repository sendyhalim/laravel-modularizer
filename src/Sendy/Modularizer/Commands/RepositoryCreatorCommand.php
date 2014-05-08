<?php
namespace Sendy\Modularizer\Commands;

use Symfony\Component\Console\Input\InputOption,
	Symfony\Component\Console\Input\InputArgument;
use Sendy\Modularizer\Creators\RepositoryCreator;
use Config;

class RepositoryCreatorCommand extends BaseCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'modularizer:create-repository';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create repositories(Read and Write) for a given model.';

	protected $creator;
	private $path;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(RepositoryCreator $rc)
	{
		parent::__construct();

		$this->creator = $rc;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$module = $this->ucwordsArgument('module');
		$path = $this->getPath();
		$data = $this->getData();

		if($this->creator->make($path, $data))
		{
			return $this->info("Repositories(Read and Write) for has been created for module {$module}.");
		}

		$this->error("Could not create repositories for module {$module}.");
		$this->error($this->creator->getError());
	}

	protected function getPath()
	{
		return $this->path = $this->option('path').'/'.$this->ucwordsArgument('model');
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
				'model',
			 	InputArgument::REQUIRED,
			 	'Name of the model.',
			],
			[
				'module',
			 	InputArgument::REQUIRED,
			 	'Module destination.',
			],
		];
	}

	protected function getData()
	{
		return [
			'MODEL' => $this->ucwordsArgument('model'),
			'CORE'  => $this->option('basenamespace'),
			'MODULE'=> $this->ucwordsArgument('module'),
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		$path = Config::get('modularizer::module.base_path').'/'.Config::get('modularizer::module.base_directory').'/'.$this->ucwordsArgument('module');
		return [
			[
				'path',
				null,
				InputOption::VALUE_OPTIONAL,
				'base path to the module',
				$path,
			],
			[
				'basenamespace',
				null,
				InputOption::VALUE_OPTIONAL,
				'Base namespace where you put your BasicRepositoryReader/WriterInterface and your BasicRepositoryReader/Writer',
				'Core',
			],
		];
	}
}
