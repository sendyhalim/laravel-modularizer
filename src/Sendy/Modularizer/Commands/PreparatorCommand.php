<?php
namespace Sendy\Modularizer\Commands;

use Symfony\Component\Console\Input\InputOption,
	Symfony\Component\Console\Input\InputArgument;
use Sendy\Modularizer\Creators\Preparator;
use Config;

class PreparatorCommand extends BaseCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'modularizer:prepare';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Preparation for repositories and validators.';

	protected $creator;
	private $path;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(Preparator $p)
	{
		parent::__construct();

		$this->creator = $p;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$path = $this->getPath();
		$data = $this->getData();

		if($this->creator->make($path, $data))
		{
			return $this->info("Preparation has been completed in {$this->path}");
		}

		$this->error("Could not make preparation in {$this->path}");
		$this->error($this->creator->getError());
	}

	protected function getPath()
	{
		return $this->path = $this->option('path') . '/' . $this->getBaseDirectory();
	}

	protected function getBaseDirectory()
	{
		return ucwords($this->option('basedirectory'));
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [];
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
				'base path to the core',
				Config::get('modularizer::module.base_path'),
			],
			[
				'basedirectory',
				null,
				InputOption::VALUE_OPTIONAL,
				'Base directory of modules',
				Config::get('modularizer::module.base_directory'),
			]
		];
	}

	protected function getData()
	{
		return ['basedirectory' => $this->getBaseDirectory()];
	}

}
