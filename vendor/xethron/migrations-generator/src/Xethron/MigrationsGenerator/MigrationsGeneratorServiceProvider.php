<?php namespace Xethron\MigrationsGenerator;

use Illuminate\Support\ServiceProvider;

class MigrationsGeneratorServiceProvider extends ServiceProvider {

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
		$this->app->bind(
			'migration.generate',
			$this->app->share(function($app) {
				return new MigrateGenerateCommand(
					$app->make('Way\Generators\Generator'),
					$app->make('Way\Generators\Filesystem\Filesystem'),
					$app->make('Way\Generators\Compilers\TemplateCompiler'),
					$app->make('migration.repository'),
					$app->make('config')
				);
			})
		);

		$this->commands('migration.generate');
	}

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package( 'xethron/migration-from-table' );
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

}
