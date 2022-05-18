<?php

use Illuminate\Database\Seeder;
use App\Group;
use App\Role;

class GroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$role_list_group  = Role::where('name', 'List Groups')->first();
        $role_create_group  = Role::where('name', 'Create Group')->first();
        $role_details_group  = Role::where('name', 'Details Group')->first();
        $role_edit_group  = Role::where('name', 'Edit Group')->first();
        $role_delete_group  = Role::where('name', 'Delete Group')->first();
        $role_search_group  = Role::where('name', 'Search Group')->first();

        $role_list_role  = Role::where('name', 'List Roles')->first();
        $role_create_role  = Role::where('name', 'Create Role')->first();
        $role_details_role  = Role::where('name', 'Details Role')->first();
        $role_edit_role  = Role::where('name', 'Edit Role')->first();
        $role_delete_role  = Role::where('name', 'Delete Role')->first();
        $role_search_role  = Role::where('name', 'Search Role')->first();

        $role_list_user_group  = Role::where('name', 'List User Groups')->first();
        $role_create_user_group  = Role::where('name', 'Create User Group')->first();
        $role_details_user_group  = Role::where('name', 'Details User Group')->first();
        $role_edit_user_group  = Role::where('name', 'Edit User Group')->first();
        $role_delete_user_group  = Role::where('name', 'Delete User Group')->first();
        $role_search_user_group  = Role::where('name', 'Search User Group')->first();

        $role_list_group_role  = Role::where('name', 'List Group Roles')->first();
        $role_create_group_role  = Role::where('name', 'Create Group Role')->first();
        $role_details_group_role  = Role::where('name', 'Details Group Role')->first();
        $role_edit_group_role  = Role::where('name', 'Edit Group Role')->first();
        $role_delete_group_role  = Role::where('name', 'Delete Group Role')->first();
        $role_search_group_role  = Role::where('name', 'Search Group Role')->first();

        $role_list_perkara  = Role::where('name', 'List Perkara')->first();
        $role_create_perkara  = Role::where('name', 'Create Perkara')->first();
        $role_details_perkara  = Role::where('name', 'Details Perkara')->first();
        $role_edit_perkara  = Role::where('name', 'Edit Perkara')->first();
        $role_delete_perkara  = Role::where('name', 'Delete Perkara')->first();
        $role_search_perkara  = Role::where('name', 'Search Perkara')->first();

    	$group = new Group();
        $group->name = 'Admin';
        $group->save();

        $group->roles()->attach($role_list_group);
        $group->roles()->attach($role_create_group);
        $group->roles()->attach($role_details_group);
        $group->roles()->attach($role_edit_group);
        $group->roles()->attach($role_delete_group);
        $group->roles()->attach($role_search_group);

        $group->roles()->attach($role_list_role);
        $group->roles()->attach($role_create_role);
        $group->roles()->attach($role_details_role);
        $group->roles()->attach($role_edit_role);
        $group->roles()->attach($role_delete_role);
        $group->roles()->attach($role_search_role);

        $group->roles()->attach($role_list_user_group);
        $group->roles()->attach($role_create_user_group);
        $group->roles()->attach($role_details_user_group);
        $group->roles()->attach($role_edit_user_group);
        $group->roles()->attach($role_delete_user_group);
        $group->roles()->attach($role_search_user_group);
        
        $group->roles()->attach($role_list_group_role);
        $group->roles()->attach($role_create_group_role);
        $group->roles()->attach($role_details_group_role);
        $group->roles()->attach($role_edit_group_role);
        $group->roles()->attach($role_delete_group_role);
        $group->roles()->attach($role_search_group_role);

        $group->roles()->attach($role_list_perkara);
        $group->roles()->attach($role_create_perkara);
        $group->roles()->attach($role_details_perkara);
        $group->roles()->attach($role_edit_perkara);
        $group->roles()->attach($role_delete_perkara);
        $group->roles()->attach($role_search_perkara);

        $group = new Group();
        $group->name = 'Polda';
        $group->save();

        $group = new Group();
        $group->name = 'Polres';
        $group->save();

        $group = new Group();
        $group->name = 'Polsek';
        $group->save();

    }
}
