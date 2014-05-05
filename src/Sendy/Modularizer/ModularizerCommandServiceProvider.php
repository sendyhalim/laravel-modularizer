<?php
namespace Sendy\Modularizer;

use Illuminate\Support\ServiceProvider;
use Illuminate\Facades\Config;
use Sendy\Modularizer\Commands\ModuleCreatorCommand,
	Sendy\Modularizer\Creators\ModuleCreator,
	Sendy\Modularizer\Commands\PreparatorCommand,
	Sendy\Modularizer\Creators\Preparator;

class ModularizerCommandServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// register the package so we can access config etc etc
		$this->package('sendy/modularizer');

		$this->registerModuleCreatorCommand();
		$this->registerPreparatorCommand();

		$this->commands(
				'modularizer.create-module',
				'modularizer.prepare'
			);
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

	public function registerModuleCreatorCommand()
	{
		$this->app['modularizer.create-module'] = $this->app->share(function($app)
		{
			$moduleCreator = new ModuleCreator($app['files']);
			return new ModuleCreatorCommand($moduleCreator);
		});
	}

	public function registerPreparatorCommand()
	{
		$this->app['modularizer.prepare'] = $this->app->share(function($app)
		{
			$preparator = new Preparator($app['files']);
			return new PreparatorCommand($preparator);
		});
	}
}
