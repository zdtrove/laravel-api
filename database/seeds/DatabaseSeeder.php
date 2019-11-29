<?php

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Profile;
use App\Models\Visibility;
use App\Models\Portfolio;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $confirm = $this->command->ask('This action will be truncate all data of admins table. Are you sure (yes/no)?');
        if ($confirm == 'yes') {
            $this->createAdmin();
            $this->createProfile();
            $this->createPortfolios();
            $this->createVisibilities();
        }
    }

    protected function createAdmin() {
        $this->command->info('Creating an admin account');
        Schema::disableForeignKeyConstraints();
        DB::table('admins')->truncate();
        Schema::enableForeignKeyConstraints();
        Admin::create([
            'id' => 1,
            'name' => 'Administrator',
            'email' => 'admin@icd-vn.com',
            'password' => bcrypt('123456')
        ]);
        $this->command->info('|-------------------------------|');
        $this->command->info('| Account created successfully. |');
        $this->command->info('| Email   : admin@icd-vn.com    |');
        $this->command->info('| Password: 123456              |');
        $this->command->info('|-------------------------------|');
        $this->command->info('*********************************');
    }

    protected function createProfile() {
        $this->command->info('Create an profile account');
        Schema::disableForeignKeyConstraints();
        DB::table('profiles')->truncate();
        Schema::enableForeignKeyConstraints();
        Profile::create([
            'id' => 1,
            'full_name' => 'Profile test',
            'email' => 'tuanlh@icd-vn.com',
            'password' => bcrypt('123456'),
            'status' => 1,
            'introduction' => 'instroduction',
            'ambition' => 'ambition'
        ]);
        $this->command->info('|-------------------------------|');
        $this->command->info('| Account created successfully. |');
        $this->command->info('| Email   : tuanlh@icd-vn.com    |');
        $this->command->info('| Password: 123456              |');
        $this->command->info('|-------------------------------|');
    }

    protected function createPortfolios() {
        $this->command->info('Create an portfolios');
        Schema::disableForeignKeyConstraints();
        DB::table('portfolios')->truncate();
        Schema::enableForeignKeyConstraints();
        for($i = 1; $i < 10; $i++) {
            Portfolio::create([
                'id' => $i,
                'title' => 'title',
                'link' => 'http://link.local',
                'profile_id' => 1
            ]);
        }
        
        $this->command->info('|-------------------------------|');
        $this->command->info('| Portfolios created successfully. |');
        $this->command->info('|-------------------------------|');
    }

    protected function createVisibilities() {
        $this->command->info('Create an visibilities');
        Schema::disableForeignKeyConstraints();
        DB::table('visibilities')->truncate();
        Schema::enableForeignKeyConstraints();
        for($i = 1; $i < 10; $i++) {
            Visibility::create([
                'id' => $i,
                'profile_id' => 1,
                'group_id' => GROUP_PERMISSION_PRIVATE,
                'object_id' => $i,
                'object_type' => OBJECT_TYPE_PORTFOLIOS
            ]);
        }
        
        $this->command->info('|-------------------------------|');
        $this->command->info('| Visibilities created successfully. |');
        $this->command->info('|-------------------------------|');
    }
}
