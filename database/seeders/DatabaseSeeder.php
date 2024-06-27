<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {




        \App\Models\Shop::factory()->create([
            'shop_name' => 'Store One',
            'shop_location' => 'Sinza kibaoni',
            'description' => 'This Paragraph is describing the shop'
        ]);
        \App\Models\Shop::factory()->create([
            'shop_name' => 'Store Two',
            'shop_location' => 'Posta Mataa',
            'description' => 'This Paragraph is describing the shop'

        ]);
        \App\Models\Category::factory()->create([
            'category_name' => 'Chassis'
        ]);
        \App\Models\Category::factory()->create([
            'category_name' => 'Engine'
        ]);
        \App\Models\Category::factory()->create([
            'category_name' => 'Braking system'
        ]);
        \App\Models\Category::factory()->create([
            'category_name' => 'Wheel and Tire'
        ]);
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Joshua Jayrous',
            'email' => 'joshuajayrous@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'System Administrator',
            'shop_id' => '1',
            'shop_name' => 'Store One',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Claudius Kalenga',
            'email' => 'claudiuskalenga@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'Manager',
            'shop_id' => '1',
            'shop_name' => 'Store One',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Alvin',
            'email' => 'alvinjay@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'Sales Person',
            'shop_id' => '1',
            'shop_name' => 'Store One',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Joyder',
            'email' => 'joyder@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'Stock Person',
            'shop_id' => '1',
            'shop_name' => 'Store One',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Eunice',
            'email' => 'eunice@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'Sales Person',
            'shop_id' => '2',
            'shop_name' => 'Store Two',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Sylivia',
            'email' => 'sylivia@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'Stock Person',
            'shop_id' => '2',
            'shop_name' => 'Store Two',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Kassim',
            'email' => 'kassim@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'Customer',
            'shop_id' => '1',
            'shop_name' => 'Store One',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Jabir',
            'email' => 'jabir@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'Customer',
            'shop_id' => '1',
            'shop_name' => 'Store One',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Lilian',
            'email' => 'lilian@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'Customer',
            'shop_id' => '1',
            'shop_name' => 'Store One',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Keshia',
            'email' => 'keshia@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'Customer',
            'shop_id' => '2',
            'shop_name' => 'Store Two',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Pendo',
            'email' => 'pendo@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'Customer',
            'shop_id' => '2',
            'shop_name' => 'Store Two',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Sansilo',
            'email' => 'sansilo@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'Customer',
            'shop_id' => '2',
            'shop_name' => 'Store Two',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Mathias',
            'email' => 'mathias@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'Customer',
            'shop_id' => '2',
            'shop_name' => 'Store Two',
        ]);
        // \App\Models\User::factory(10)->create();
        \App\Models\Permission::factory()->create(["name"=>"categoryViewAny"]);
        \App\Models\Permission::factory()->create(["name"=>"categoryView"]);
        \App\Models\Permission::factory()->create(["name"=>"categoryCreate"]);
        \App\Models\Permission::factory()->create(["name"=>"categoryEdit"]);
        \App\Models\Permission::factory()->create(["name"=>"categoryDelete"]);
        \App\Models\Permission::factory()->create(["name"=>"debtViewAny"]);
        \App\Models\Permission::factory()->create(["name"=>"debtView"]);
        \App\Models\Permission::factory()->create(["name"=>"debtUpdate"]);
        \App\Models\Permission::factory()->create(["name"=>"expenseViewAny"]);
        \App\Models\Permission::factory()->create(["name"=>"expenseView"]);
        \App\Models\Permission::factory()->create(["name"=>"expenseCreate"]);
        \App\Models\Permission::factory()->create(["name"=>"expenseDelete"]);
        \App\Models\Permission::factory()->create(["name"=>"expensetypeViewAny"]);
        \App\Models\Permission::factory()->create(["name"=>"expensetypeView"]);
        \App\Models\Permission::factory()->create(["name"=>"expensetypeCreate"]);
        \App\Models\Permission::factory()->create(["name"=>"expensetypeEdit"]);
        \App\Models\Permission::factory()->create(["name"=>"expensetypeDelete"]);
        \App\Models\Permission::factory()->create(["name"=>"productViewAny"]);
        \App\Models\Permission::factory()->create(["name"=>"productView"]);
        \App\Models\Permission::factory()->create(["name"=>"purchaseViewAny"]);
        \App\Models\Permission::factory()->create(["name"=>"purchaseView"]);
        \App\Models\Permission::factory()->create(["name"=>"purchaseCreate"]);
        \App\Models\Permission::factory()->create(["name"=>"purchaseEdit"]);
        \App\Models\Permission::factory()->create(["name"=>"purchaseDelete"]);
        \App\Models\Permission::factory()->create(["name"=>"saleViewAny"]);
        \App\Models\Permission::factory()->create(["name"=>"salesView"]);
        \App\Models\Permission::factory()->create(["name"=>"salesCreate"]);
        \App\Models\Permission::factory()->create(["name"=>"shopViewAny"]);
        \App\Models\Permission::factory()->create(["name"=>"shopView"]);
        \App\Models\Permission::factory()->create(["name"=>"shopEdit"]);
        \App\Models\Permission::factory()->create(["name"=>"storeViewAny"]);
        \App\Models\Permission::factory()->create(["name"=>"storeView"]);
        \App\Models\Permission::factory()->create(["name"=>"storeCreate"]);
        \App\Models\Permission::factory()->create(["name"=>"storeEdit"]);
        \App\Models\Permission::factory()->create(["name"=>"storeDelete"]);
        \App\Models\Permission::factory()->create(["name"=>"userViewAny"]);
        \App\Models\Permission::factory()->create(["name"=>"usersRestore"]);
        \App\Models\Permission::factory()->create(["name"=>"usersCreate"]);
        \App\Models\Permission::factory()->create(["name"=>"usersDelete"]);
        
        \App\Models\Role::factory()->create(["name"=>"System Administrator"]);
        \App\Models\Role::factory()->create(["name"=>"Sales Person"]);
        \App\Models\Role::factory()->create(["name"=>"Stock Person"]);
        \App\Models\Role::factory()->create(["name"=>"Customer"]);
        \App\Models\Role::factory()->create(["name"=>"Manager"]);
    }
}