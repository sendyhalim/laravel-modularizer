<?php
namespace Sendy\Modularizer;

use Illuminate\Support\ServiceProvider;
use Sendy\Modularizer\Commands\ModuleCreatorCommand;

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
			return new ModuleCreatorCommand();
		});
	}
}
