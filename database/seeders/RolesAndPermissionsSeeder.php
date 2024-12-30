<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Create roles
    $adminRole = Role::create(['name' => 'admin']);
    $clientRole = Role::create(['name' => 'client']);
    $memberRole = Role::create(['name' => 'member']);

    // Create permissions
    Permission::create(['name' => 'create appointment']);
    Permission::create(['name' => 'edit appointment']);
    Permission::create(['name' => 'delete appointment']);
    Permission::create(['name' => 'view appointment']);

    Permission::create(['name' => 'add pharmacy']);
    Permission::create(['name' => 'edit pharmacy']);
    Permission::create(['name' => 'delete pharmacy']);
    Permission::create(['name' => 'view pharmacy']);
    Permission::create(['name' => 'rate pharmacy']);

    // Assign permissions to roles
    $adminRole->givePermissionTo(Permission::all()->except(['rate pharmacy']));

    $clientRole->givePermissionTo([
      'create appointment',
      'edit appointment',
      'delete appointment',
      'view appointment',
      'add pharmacy',
      'edit pharmacy',
      'delete pharmacy',
      'view pharmacy'
    ]);

    $memberRole->givePermissionTo([
      'create appointment',
      'view appointment',
      'view pharmacy',
      'rate pharmacy'
    ]);
  }
}
