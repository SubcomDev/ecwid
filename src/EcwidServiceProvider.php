<?php

namespace subcom\Ecwid;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use subcom\Ecwid\Commands\EcwidCommand;

class EcwidServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('ecwid')
            ->hasConfigFile();
//            ->hasViews()
//            ->hasMigration('create_ecwid_table')
//            ->hasCommand(EcwidCommand::class);
    }
}
