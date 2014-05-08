<?php
namespace Sendy\Modularizer\Commands;

use Illuminate\Console\Command;
/**
 * @author Sendy Halim <sendyhalim93@gmail.com>
 */
abstract class BaseCommand extends Command
{
	protected $creator;

	public function __construct()
	{
		parent::__construct();
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