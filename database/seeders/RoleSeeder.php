<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->name = 'ADMIN';
        $role->save();
        $role = new Role();
        $role->name = 'BIBLIOTHECAIRE';
        $role->save();
        $role = new Role();
        $role->name = 'ADHERENT';
        $role->save();
    }
}
