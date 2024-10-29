<?php

namespace Database\Seeders;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Master\Org\OrgStruct;
use App\Models\Master\Org\Position;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::find(1);
        if (!$role) {
            $role = new Role;
            $role->name = 'Donna';
            $role->save();
        }

        $password = bcrypt('password');
        $user = User::find(1);
        if (!$user) {
            $user = new User;
            $user->fill(
                [
                    'name' => 'Donna',
                    'username' => 'donna',
                    'email' => 'donna@email.com',
                    'password' => $password,
                    'nik' => 'donna',
                ]
            );
            $user->save();
        }
        $user->assignRole($role);

        $USERS = [
            [
                "position" => 1002,
                "type"  => "internal",
                "name"  => "Dina Maryana",
                "username" => "dina",
                "password" => $password,
                "email" => "dina@email.com",
                "status" => "active",
                "role"  => "Auditor",
            ],
        ];
        // foreach ($USERS as $value) {
        //     $position               = Position::where("code", $value["position"])->first();
        //     $record                 = User::firstOrNew(['username' => $value['username']]);
        //     $record->name           = $value['name'];
        //     $record->username       = $value['username'];
        //     $record->email          = $value['email'];
        //     $record->type          = $value['type'];
        //     $record->position_id          = $position->id;
        //     $record->jabatan_provider          = $value['jabatan_provider'] ?? NULL;
        //     $record->provider_id          = $value['provider_id'] ?? NULL;
        //     $record->password       = $password;
        //     $record->status         = 'active';
        //     $record->save();

        //     $record->roles()->sync([Role::where('name', $value['role'])->first()->id]);
        // }
        // try {
        // } catch (\Throwable $th) {
        //     dd($th->getMessage(), $th->getLine(), $value);
        // }
    }
}
