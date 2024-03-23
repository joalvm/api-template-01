<?php

namespace Database\Seeders;

use App\Enums\Gender;
use App\Enums\UserRole;
use App\Facades\User;
use App\Interfaces\PersonsInterface;
use App\Interfaces\Users\UsersInterface;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    public const DEFAULT_EMAIL = 'developer@correo.com';
    public const DEFAULT_PASSWORD = '123456789';

    public function __construct(
        protected PersonsInterface $personsRepository,
        protected UsersInterface $usersRepository
    ) {
        $this->personsRepository = $personsRepository;
    }

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = fake('es_PE');

        $this->usersRepository->loadUser(User::getFacadeRoot());
        $this->personsRepository->loadUser(User::getFacadeRoot());

        $personModel = $this->personsRepository->save([
            'names' => $faker->firstName,
            'last_names' => $faker->lastName,
            'gender' => Gender::MALE,
            'document_type_id' => DocumentTypesSeeder::DEFAULT_DOCUMENT_TYPE,
            'id_document' => '99999999',
            'email' => self::DEFAULT_EMAIL,
        ]);

        $this->usersRepository->save([
            'person_id' => $personModel->id,
            'email' => self::DEFAULT_EMAIL,
            'role' => UserRole::ADMIN,
            'avatar_url' => fake()->imageUrl(75, 75),
            'password' => self::DEFAULT_PASSWORD,
            'confirm_password' => self::DEFAULT_PASSWORD,
            'super_admin' => true,
        ]);
    }
}
