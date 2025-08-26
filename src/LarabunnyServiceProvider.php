<?php

namespace Modularavel\Larabunny;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Modularavel\Larabunny\Commands\LarabunnyCommand;
use Spatie\LaravelPackageTools\Commands\Concerns;

class LarabunnyServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('larabunny')
            ->hasConfigFile()
            //->hasViews()
            //->hasTranslations()
           // ->hasAssets()
           // ->publishesServiceProvider(LarabunnyServiceProvider::class)
            //->hasRoute('web')
            //->hasMigration('create_larabunny_table')
            ->hasCommand(LarabunnyCommand::class)
            ->hasInstallCommand(function(InstallCommand $command) {
                $command
                    ->startWith(function(InstallCommand $command) {
                        $command->info('Hello, and welcome to my great new package!');
                    })
                    ->publishConfigFile()
                    ->publishAssets()
                    ->publishMigrations()
                    ->copyAndRegisterServiceProviderInApp()
                    ->askToStarRepoOnGitHub('modularavel/larabunny')
                    ->endWith(function(InstallCommand $command) {
                        $command->info('Have a great day!');
                    });
            });
    }
}
