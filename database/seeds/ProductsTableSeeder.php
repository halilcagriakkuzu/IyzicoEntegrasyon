<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            'name' => 'Anakart',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi nunc nibh, vehicula vitae purus sed, tincidunt suscipit tellus. Suspendisse eget accumsan ex. Morbi aliquam nibh a elit gravida, a congue libero auctor.',
            'image' => 'mainboard.jpg',
            'price' => 249.9,
            'stock' => 10,
        ]);

        DB::table('products')->insert([
            'name' => 'İşlemci',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi nunc nibh, vehicula vitae purus sed, tincidunt suscipit tellus. Suspendisse eget accumsan ex. Morbi aliquam nibh a elit gravida, a congue libero auctor.',
            'image' => 'processor.jpg',
            'price' => 755,
            'stock' => 90,
        ]);

        DB::table('products')->insert([
            'name' => 'Bellek',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi nunc nibh, vehicula vitae purus sed, tincidunt suscipit tellus. Suspendisse eget accumsan ex. Morbi aliquam nibh a elit gravida, a congue libero auctor.',
            'image' => 'ram.jpg',
            'price' => 449.99,
            'stock' => 150,
        ]);

        DB::table('products')->insert([
            'name' => 'Kasa',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi nunc nibh, vehicula vitae purus sed, tincidunt suscipit tellus. Suspendisse eget accumsan ex. Morbi aliquam nibh a elit gravida, a congue libero auctor.',
            'image' => 'case.jpg',
            'price' => 250,
            'stock' => 0,
        ]);

        DB::table('products')->insert([
            'name' => 'Güç Kaynağı',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi nunc nibh, vehicula vitae purus sed, tincidunt suscipit tellus. Suspendisse eget accumsan ex. Morbi aliquam nibh a elit gravida, a congue libero auctor.',
            'image' => 'power.jpg',
            'price' => 225,
            'stock' => 1,
        ]);

        DB::table('products')->insert([
            'name' => 'Ekran Kartı',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi nunc nibh, vehicula vitae purus sed, tincidunt suscipit tellus. Suspendisse eget accumsan ex. Morbi aliquam nibh a elit gravida, a congue libero auctor.',
            'image' => 'graphic_card.jpg',
            'price' => 1949.9,
            'stock' => 35,
        ]);
    }
}
