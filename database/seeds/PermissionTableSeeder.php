<?php


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $permissions = [
          'user-list',
          'user-create',
          'user-edit',
          'user-delete',
          'role-list',
          'role-create',
          'role-edit',
          'role-delete',
          'book-list',
          'book-create',
          'book-edit',
          'book-delete'
        ];


        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission]);
        }
    }
}