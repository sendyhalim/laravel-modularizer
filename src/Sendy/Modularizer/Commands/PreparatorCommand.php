<?php
namespace Sendy\Modularizer\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption,
	Symfony\Component\Console\Input\InputArgument;
use Sendy\Modularizer\Creators\Preparator;
use Config;

class PreparatorCommand extends Command {

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

	private $preparator;
	private $path;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(Preparator $mc)
	{
		parent::__construct();

		$this->preparator = $mc;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$path = $this->getPath();

		if($this->preparator->make($path))
		{
			return $this->info("Preparation has been completed in {$this->path}");
		}

		$this->error("Could not make preparation in {$this->path}");
		$this->error($this->preparator->getError());
	}

	protected function getPath()
	{
		return $this->path = $this->option('path');
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
				'Path of the modules',
				Config::get('modularizer::module.base_path'),
			]
		];
	}

}
