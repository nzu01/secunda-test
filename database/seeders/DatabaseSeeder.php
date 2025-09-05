<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Organization;
use App\Models\OrganizationContact;
use App\Models\OrganizationOrganizationType;
use App\Models\OrganizationType;
use App\Models\PublicUser;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = new PublicUser([
            PublicUser::NAME => 'Test User',
            PublicUser::EMAIL => 'test@secunda.com',
            PublicUser::EMAIL_VERIFIED_AT => Carbon::now(),
            PublicUser::PASSWORD => Hash::make('a15bf60c9afce1d46'),
            PublicUser::API_TOKEN => '41c86ea3-ea36-4855-af72-47e4225df069'
        ]);
        $user->save();
        $this->createTypes();
        $this->createBuildings();
        $this->createOrganizations();
    }

    public function createTypes()
    {
        $type = new OrganizationType([
            OrganizationType::NAME => 'government',
            OrganizationType::TITLE => 'Государственные',
        ]);
        $type->save();

        $type = new OrganizationType([
            OrganizationType::NAME => 'health',
            OrganizationType::TITLE => 'Здравоохранение',
        ]);
        $type->save();

        //food
        $food = OrganizationType::query()->firstWhere(OrganizationType::NAME, '=', 'food');
        if (!$food instanceof OrganizationType) {
            $food = new OrganizationType([
                OrganizationType::NAME => 'food',
                OrganizationType::TITLE => 'Еда',
            ]);
            $food->save();
        }

        $foodList = [
            [
                OrganizationType::NAME => 'meat',
                OrganizationType::TITLE => 'Мясная продукция',
            ],
            [
                OrganizationType::NAME => 'milk',
                OrganizationType::TITLE => 'Молочная продукция',
            ]
        ];
        foreach ($foodList as $foodItem) {
            $foodChild = new OrganizationType($foodItem);
            $foodChild->parent_uuid = $food->uuid;
            $foodChild->save();
        }

        //cars

        $car = OrganizationType::query()->firstWhere(OrganizationType::NAME, '=', 'car');
        if (!$car instanceof OrganizationType) {
            $car = new OrganizationType([
                OrganizationType::NAME => 'cars',
                OrganizationType::TITLE => 'Автомобили',
            ]);
            $car->save();
        }
        $carsList = [
            [
                OrganizationType::NAME => 'light',
                OrganizationType::TITLE => 'Легковые',
            ],
            [
                OrganizationType::NAME => 'trucks',
                OrganizationType::TITLE => 'Грузовые',
            ]
        ];
        $carsListL2 = [
            [
                OrganizationType::NAME => 'parts',
                OrganizationType::TITLE => 'Запчасти',
            ],
            [
                OrganizationType::NAME => 'accessories',
                OrganizationType::TITLE => 'Аксессуары',
            ]
        ];
        foreach ($carsList as $carItem) {
            $carChild = new OrganizationType($carItem);
            $carChild->parent_uuid = $car->uuid;
            $carChild->save();
            foreach ($carsListL2 as $carItem2) {
                $carChild2 = new OrganizationType($carItem2);
                $carChild2->name =  $carChild->name . '_' . $carItem2['name'];
                $carChild2->parent_uuid = $carChild->uuid;
                $carChild2->save();
            }
        }
    }

    public function createBuildings()
    {
        $buildings = [
            [
                Building::ADDRESS => 'Красная площадь, Москва, 109012',
                Building::LATITUDE => 55.753930,
                Building::LONGITUDE => 37.620795,
            ],
            [
                Building::ADDRESS => 'Театральная пл., 1, Москва, 125009',
                Building::LATITUDE => 55.760096,
                Building::LONGITUDE => 37.618713,
            ],
            [
                Building::ADDRESS => 'Ленинские горы, 1, Москва, 119991',
                Building::LATITUDE => 55.703297,
                Building::LONGITUDE => 37.530090,
            ],
            [
                Building::ADDRESS => 'ул. Академика Королёва, 15, Москва, 127427',
                Building::LATITUDE => 55.819739,
                Building::LONGITUDE => 37.611457,
            ],
            [
                Building::ADDRESS => 'ул. Лужники, 24, Москва, 119048',
                Building::LATITUDE => 55.715762,
                Building::LONGITUDE => 37.553651,
            ],
            [
                Building::ADDRESS => 'ул. Волхонка, 15, Москва, 119019',
                Building::LATITUDE => 55.744724,
                Building::LONGITUDE => 37.605006,
            ],
            [
                Building::ADDRESS => 'ул. Крымский Вал, 9, Москва, 119049',
                Building::LATITUDE => 55.729997,
                Building::LONGITUDE => 37.601107,
            ],
            [
                Building::ADDRESS => 'Лаврушинский пер., 10, Москва, 119017',
                Building::LATITUDE => 55.741420,
                Building::LONGITUDE => 37.620040,
            ],
            [
                Building::ADDRESS => 'проспект Мира, 119, Москва, 129223',
                Building::LATITUDE => 55.829953,
                Building::LONGITUDE => 37.633364,
            ],
            [
                Building::ADDRESS => 'ул. Варварка, 6, Москва, 109240',
                Building::LATITUDE => 55.752537,
                Building::LONGITUDE => 37.626044,
            ],
            [
                Building::ADDRESS => 'Пресненская наб., 12, Москва, 123100',
                Building::LATITUDE => 55.749792,
                Building::LONGITUDE => 37.539202,
            ],
            [
                Building::ADDRESS => 'Краснопресненская наб., 2, Москва, 103274',
                Building::LATITUDE => 55.757996,
                Building::LONGITUDE => 37.586571,
            ],
            [
                Building::ADDRESS => 'Красная пл., 3, Москва, 109012',
                Building::LATITUDE => 55.754930,
                Building::LONGITUDE => 37.621620,
            ],
            [
                Building::ADDRESS => 'Красная площадь, 2, Москва, 103073',
                Building::LATITUDE => 55.752023,
                Building::LONGITUDE => 37.617499,
            ],
            [
                Building::ADDRESS => 'Новодевичий пр-д, 1, Москва, 119435',
                Building::LATITUDE => 55.726619,
                Building::LONGITUDE => 37.556134,
            ],
            [
                Building::ADDRESS => 'Б. Грузинская ул., 1, Москва, 123242',
                Building::LATITUDE => 55.761237,
                Building::LONGITUDE => 37.582041,
            ],
            [
                Building::ADDRESS => 'пл. Киевского вокзала, 2, Москва, 121059',
                Building::LATITUDE => 55.744585,
                Building::LONGITUDE => 37.565023,
            ],
            [
                Building::ADDRESS => 'Космодамианская наб., 52, стр. 8, Москва, 115054',
                Building::LATITUDE => 55.732972,
                Building::LONGITUDE => 37.644781,
            ],
            [
                Building::ADDRESS => 'Ходынский бул., 4, Москва, 125167',
                Building::LATITUDE => 55.789180,
                Building::LONGITUDE => 37.530459,
            ],
            [
                Building::ADDRESS => 'ул. Нобеля, 7, Москва, 143026',
                Building::LATITUDE => 55.698697,
                Building::LONGITUDE => 37.368379,
            ],
        ];
        
        foreach ($buildings as $buildingA) {
            $building = new Building($buildingA);
            $building->save();
        }

    }

    public function createOrganizations()
    {
        $factory = Factory::create();
        for ($i = 1; $i <= 1000; $i++) {
            $org = new Organization();
            $org->title = $factory->sentence(2);
            $org->building_uuid = Building::query()->inRandomOrder()->first()->uuid;
            $org->save();

            $contact = new OrganizationContact();
            $contact->contact_type = 'phone';
            $contact->organization_uuid = $org->uuid;
            $contact->value = $factory->phoneNumber();
            $contact->save();
            if (random_int(0, 1) === 1) {
                $contact = new OrganizationContact();
                $contact->contact_type = 'phone';
                $contact->value = $factory->phoneNumber();
                $contact->organization_uuid = $org->uuid;
                $contact->save();
            }

            $typeUuids = OrganizationType::query()
                ->inRandomOrder()
                ->limit(random_int(1, 2))
                ->pluck('uuid');

            foreach ($typeUuids as $typeUuid) {
                $pivot = new OrganizationOrganizationType();
                $pivot->organization_uuid = $org->uuid;
                $pivot->organization_type_uuid = $typeUuid;
                $pivot->save();
            }
        }
    }
}
