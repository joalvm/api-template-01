<?php

namespace Database\Seeders;

use App\DataObjects\Repositories\CreatePersonData;
use App\DataObjects\Repositories\Users\CreateUserData;
use App\Enums\Gender;
use App\Enums\UserRole;
use App\Facades\User;
use App\Interfaces\PersonsInterface;
use App\Interfaces\Users\UsersInterface;
use App\Models\Person;
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
        $this->usersRepository->loadUser(User::getFacadeRoot());
        $this->personsRepository->loadUser(User::getFacadeRoot());

        $this->makeUser($this->makePerson());
    }

    private function makePerson(): Person
    {
        $faker = fake('es_PE');

        $data = CreatePersonData::from([
            'names' => $faker->firstName,
            'last_names' => $faker->lastName,
            'gender' => Gender::MALE,
            'document_type_id' => DocumentTypesSeeder::DEFAULT_DOCUMENT_TYPE,
            'id_document' => '99999999',
            'email' => self::DEFAULT_EMAIL,
        ]);

        return $this->personsRepository->save($data);
    }

    private function makeUser(Person $person): void
    {
        $faker = fake('es_PE');

        $data = CreateUserData::from([
            'person_id' => $person->id,
            'email' => self::DEFAULT_EMAIL,
            'role' => UserRole::ADMIN,
            'avatar_url' => $faker->imageUrl(75, 75),
            'password' => self::DEFAULT_PASSWORD,
            'confirm_password' => self::DEFAULT_PASSWORD,
            'super_admin' => true,
        ]);

        $this->usersRepository->save($data);
    }
}
