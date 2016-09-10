<?php namespace Boparaiamrit\FullContact;


use Illuminate\Config\Repository;
use Illuminate\Support\ServiceProvider;
use Services_FullContact;
use Services_FullContact_API;
use Services_FullContact_Company;
use Services_FullContact_Icon;
use Services_FullContact_Location;
use Services_FullContact_Name;

class FullContactProvider extends ServiceProvider
{
	
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;
	
	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->mergeConfigFrom(__DIR__ . '/../config/fullcontact.php', 'fullcontact');
		
		$this->publishes([
			__DIR__ . '/../config/fullcontact.php' => config_path('fullcontact.php'),
		]);
	}
	
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		/** @var Repository $config */
		$config = $this->app->make('config');
		$apiKey = $config->get('fullcontact.api_key');
		
		$this->app->singleton('fullcontact', function () use ($apiKey) {
			return new Services_FullContact($apiKey);
		});
		
		$this->app->singleton('fullcontact.api', function () use ($apiKey) {
			return new Services_FullContact_API($apiKey);
		});
		
		$this->app->singleton('fullcontact.person', function () use ($apiKey) {
			return new Person($apiKey);
		});
		
		$this->app->singleton('fullcontact.name', function () use ($apiKey) {
			return new Services_FullContact_Name($apiKey);
		});
		
		$this->app->singleton('fullcontact.company', function () use ($apiKey) {
			return new Services_FullContact_Company($apiKey);
		});
		
		$this->app->singleton('fullcontact.location', function () use ($apiKey) {
			return new Services_FullContact_Location($apiKey);
		});
		
		$this->app->singleton('fullcontact.icon', function () use ($apiKey) {
			return new Services_FullContact_Icon($apiKey);
		});
	}
	
	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [
			'fullcontact',
			'fullcontact.api',
			'fullcontact.person',
			'fullcontact.name',
			'fullcontact.company',
			'fullcontact.location',
			'fullcontact.icon'
		];
	}
	
}