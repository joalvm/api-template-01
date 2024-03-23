<?php

namespace App\Providers;

use App\Interfaces\DocumentTypesInterface;
use App\Interfaces\PersonsInterface;
use App\Interfaces\Ubigeo\DepartmentsInterface;
use App\Interfaces\Ubigeo\DistrictsInterface;
use App\Interfaces\Ubigeo\ProvincesInterface;
use App\Interfaces\Users\SessionsInterface;
use App\Interfaces\Users\UsersInterface;
use App\Repositories\DocumentTypesRepository;
use App\Repositories\PersonsRepository;
use App\Repositories\Ubigeo\DepartmentsRepository;
use App\Repositories\Ubigeo\DistrictsRepository;
use App\Repositories\Ubigeo\ProvincesRepository;
use App\Repositories\Users\SessionsRepository;
use App\Repositories\Users\UsersRepository;
use Illuminate\Support\ServiceProvider;

class BindingsServiceProvider extends ServiceProvider
{
    public $bindings = [
        DocumentTypesInterface::class => DocumentTypesRepository::class,

        // Persons
        PersonsInterface::class => PersonsRepository::class,

        // Users
        UsersInterface::class => UsersRepository::class,
        SessionsInterface::class => SessionsRepository::class,

        // Ubigeo
        DepartmentsInterface::class => DepartmentsRepository::class,
        ProvincesInterface::class => ProvincesRepository::class,
        DistrictsInterface::class => DistrictsRepository::class,
    ];
}
