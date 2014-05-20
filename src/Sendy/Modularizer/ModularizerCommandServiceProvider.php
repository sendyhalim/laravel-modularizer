<?php
namespace Sendy\Modularizer;

use Illuminate\Support\ServiceProvider;
use Illuminate\Facades\Config;
use Sendy\Modularizer\Commands\ModuleCreatorCommand,
	Sendy\Modularizer\Creators\ModuleCreator,
	Sendy\Modularizer\Commands\PreparatorCommand,
	Sendy\Modularizer\Creators\Preparator,
	Sendy\Modularizer\Commands\RepositoryCreatorCommand,
	Sendy\Modularizer\Creators\RepositoryCreator,
	Sendy\Modularizer\Creators\EmptyCreator,
	Sendy\Modularizer\Commands\ModuleMigrateMakeCommand;

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
		$this->registerRepositoryCreatorCommand();
		$this->registerModuleMigrateMakeCommand();

		$this->commands(
				'modularizer.create-module',
				'modularizer.prepare',
				'modularizer.create-repository',
				'modularizer.make-migration'
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

	protected function registerModuleCreatorCommand()
	{
		$this->app['modularizer.create-module'] = $this->app->share(function($app)
		{
			$moduleCreator = new ModuleCreator($app['files']);
			return new ModuleCreatorCommand($moduleCreator);
		});
	}

	protected function registerPreparatorCommand()
	{
		$this->app['modularizer.prepare'] = $this->app->share(function($app)
		{
			$preparator = new Preparator($app['files']);
			return new PreparatorCommand($preparator);
		});
	}

	protected function registerRepositoryCreatorCommand()
	{
		$this->app['modularizer.create-repository'] = $this->app->share(function($app)
		{
			$repositorCreator = new RepositoryCreator($app['files']);
			return new RepositoryCreatorCommand($repositorCreator);
		});
	}

	protected function registerModuleMigrateMakeCommand()
	{
		$this->app['modularizer.make-migration'] = $this->app->share(function($app)
		{
			return \App::make('Sendy\Modularizer\Commands\ModuleMigrateMakeCommand');
		});
	}
}
