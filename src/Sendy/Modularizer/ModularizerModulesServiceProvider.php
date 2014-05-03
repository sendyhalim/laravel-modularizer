<?php
namespace Sendy\Modularizer;

use Config,
	View,
	Str;

use Illuminate\Support\ServiceProvider;

class ModularizerModulesServiceProvider extends \Illuminate\Support\ServiceProvider
{
	public function boot()
	{
		$moduleBasePath = $this->getModuleBasePath();

		if($modules = $this->getModule())
		{
			//register the package, 1st param is package name, 2nd is namespace,
			//3rd is the path to module
			foreach ($modules as $m)
			{
				$this->package("app/{$m}", $m, "{$moduleBasePath}/{$m}");
			}
		}
	}

	public function register()
	{
		$moduleBasePath = $this->getModuleBasePath();

		if($modules = $this->getModule())
		{
			foreach ($modules as $m)
			{
				//register the package's config
				$this->app['config']->package("app/{$m}", "{$moduleBasePath}/{$m}/config");

				$modulePath = "{$moduleBasePath}/{$m}";
				//routes for each module
				$routePath = "{$modulePath}/routes.php";
				$viewPath = "{$modulePath}/views";

				$this->registerRouteFile($routePath);
				$this->registerView($m, $viewPath);
			}
		}
	}

	private function getModule()
	{
		return Config::get('modularizer::module.active');
	}

	private function getModuleBasePath()
	{
		return Config::get('modularizer::module.base_path');
	}

	private function registerView($module, $viewPath)
	{
		// register view namespace
		View::addNamespace(Str::lower($module), $viewPath);
	}

	private function registerRouteFile($routePath)
	{
		if(file_exists($routePath))
			require $routePath;
	}
}