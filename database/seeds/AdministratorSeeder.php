<?php

use Illuminate\Database\Seeder;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $administrator = new \App\User;
        $administrator->username = "administrator";
        $administrator->name = "Site Administrator";
        $administrator->email = "administrator@larashop.test";
        $administrator->roles = serialize(["ADMIN"]);
        $administrator->password = \Hash::make("larashop");

        $administrator->save();

        $this->command->info("User Admin berhasil diinsert");
    }
}
