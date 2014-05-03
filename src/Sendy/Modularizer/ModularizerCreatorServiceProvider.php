<?php
namespace Sendy\Modularizer;

use Illuminate\Support\ServiceProvider;
use Illuminate\Facades\Config;
use Sendy\Modularizer\Commands\ModuleCreatorCommand,
	Sendy\Modularizer\Creators\ModuleCreator;

class ModularizerServiceProvider extends ServiceProvider {

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

		$this->commands(
				'modularizer.create-module'
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
}
