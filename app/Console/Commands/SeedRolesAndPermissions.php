<?php

namespace App\Console\Commands;

use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\DB;
use File;
use Spatie\Permission\Models\Permission;

class SeedRolesAndPermissions extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:seed-roles-and-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed Roles and Permissions';

    /**
     * Execute the console command.
     *
     * @return int
     */

    const ROLES_BASE = 'database/roles';

    private $adminPermissions;
    private $authorPermissions;
    private $basicUserPermissions;


    public function __construct()
    {
        parent::__construct();
        $this->adminPermissions = collect();
        $this->authorPermissions = collect();
        $this->basicUserPermissions = collect();
    }


    public function handle()
    {
        $this->info("Seeding Roles and Permissions...");


        try {
            DB::beginTransaction();

//-------------------------------------CREATE ROLES-------------------------------------------------//
            Role::firstOrCreate(['name' => Role::SUPER]);
            Role::firstOrCreate(['name' => Role::ADMIN]);
            Role::firstOrCreate(['name' => Role::AUTHOR]);
            Role::firstOrCreate(['name' => Role::BASIC_USER]);


//-----------------------------------IMPORT PERMISSION FILES-----------------------------------------//
            $adminPems = $this->getJson(self::ROLES_BASE.'/admin.json');
            $authorPems = $this->getJson(self::ROLES_BASE.'/author.json');
            $basicUserPems = $this->getJson(self::ROLES_BASE.'/basic-user.json');

//-----------------------------------------BASIC PERMISSIONS--------------------------------------//
            foreach ($basicUserPems as $permission) {
                $this->basicUserPermissions->push($permission);
                $this->authorPermissions->push($permission);
                $this->adminPermissions->push($permission);
            }

//-----------------------------------------AUTHOR PERMISSIONS--------------------------------------//
            foreach ($authorPems as $permission) {
                $this->authorPermissions->push($permission);
                $this->adminPermissions->push($permission);
            }

//-----------------------------------------ADMIN PERMISSIONS--------------------------------------//
            foreach ($adminPems as $permission) {
                $this->adminPermissions->push($permission);
            }


            //Create all permissions from admin user permissions
            foreach ($this->adminPermissions->toArray() as $permission) {
                Permission::firstOrCreate(['name' => $permission]);
            }


            //Assign Permissions to roles

            $basicRole = Role::where('name', Role::BASIC_USER)->first();
            foreach ($this->basicUserPermissions->toArray() as $permission) {
                $basicRole->givePermissionTo($permission);
            }

            $authorRole = Role::where('name', Role::AUTHOR)->first();
            foreach ($this->authorPermissions->toArray() as $permission) {
                $authorRole->givePermissionTo($permission);
            }

            $adminRole = Role::where('name', Role::ADMIN)->first();
            foreach ($this->adminPermissions->toArray() as $permission) {
                $adminRole->givePermissionTo($permission);
            }


            $this->info("Seeding roles and permissions complete!");

            $this->info("Updating user Roles ...");

            DB::commit();
        } catch (\Exception $e) {
            $this->info("Updating Failed!");
            DB::rollBack();
            $this->info("Database rolled back complete!");
            $this->info($e);
            return Command::FAILURE;
        }

        $this->info("Complete!");

        return Command::SUCCESS;
    }


    /**
     * @param  string  $path  The path to the file, relative to database.
     * @param  bool  $decode  Decode the JSON?
     * @return mixed
     * @throws FileNotFoundException
     */
    private function getJson(string $path, $decode = true)
    {
        return $decode ? json_decode(File::get(storage_path($path)), true) : File::get($path);
    }
}
