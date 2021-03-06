<?php

namespace Filament\Tests;

use Mockery;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Illuminate\Support\Facades\Facade;
use Livewire\LivewireServiceProvider;
use Livewire\Livewire;
use Watson\Active\ActiveServiceProvider;
use Watson\Watson\Facades\Active;
use BladeUI\Icons\BladeIconsServiceProvider;
use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use Thomaswelton\LaravelGravatar\LaravelGravatarServiceProvider;
use Thomaswelton\LaravelGravatar\Facades\Gravatar;
use Filament\FilamentServiceProvider;
use Filament\FilamentFacade;
use Filament\Tests\Database\Models\User;

abstract class TestCase extends OrchestraTestCase
{    
    public function setUp(): void
    {
        $this->afterApplicationCreated(function () {
            $this->makeACleanSlate();
        });

        $this->beforeApplicationDestroyed(function () {
            $this->makeACleanSlate();
        });

        parent::setUp();
        $this->loadLaravelMigrations(['--database' => 'testbench']);
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
        
        Facade::setFacadeApplication(app());
    }

    public function makeACleanSlate()
    {
        $this->artisan('view:clear');
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            ActiveServiceProvider::class,
            BladeIconsServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            LaravelGravatarServiceProvider::class,
            FilamentServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Livewire' => Livewire::class,
            'Active' => Active::class,
            'Gravatar' => Gravatar::class,
            'Filament' => FilamentFacade::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['migrator']->path(__DIR__.'/../database/migrations');

        $app['config']->set('session.driver', 'file');

        $app['config']->set('view.paths', [
            __DIR__.'/../resources/views',
            resource_path('views'),
        ]);

        $app['config']->set('app.key', 'base64:Hupj4yAgSjLrM2/edkZQNQHslgDWZfjBfCuSThJ5SK8=');

        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('auth.providers.users.model', User::class);
        $app['config']->set('filament.models.user', User::class);
    }
}
