<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'id' => 1,
            'name' => 'Administrador',
            'permission_lvl' => 1
        ]);

        // Store Role
        DB::table('roles')->insert([
            'id' => 2,
            'name' => 'Restaurante',
            'permission_lvl' => 2
        ]);

        // User Role
        DB::table('roles')->insert([
            'id' => 3,
            'name' => 'Cliente',
            'permission_lvl' => 3
        ]);
    }
}
