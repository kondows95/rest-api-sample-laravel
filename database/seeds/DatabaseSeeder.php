<?php

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Item;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Categories
        $categories = [
            ['id'=>1, 'name'=>'Vermeer'],
            ['id'=>2, 'name'=>'Rembrandt'],
            ['id'=>3, 'name'=>'Manet'],
            ['id'=>4, 'name'=>'Gogh'],
            ['id'=>5, 'name'=>'Gauguin'],
            ['id'=>6, 'name'=>'Modigliani'], 
        ];
        foreach ($categories as $arr) {
            Category::firstOrCreate(['id' => $arr['id']], ['name' => $arr['name']]);
        }

        //Items
        $items = [
            ['id'=>1, 'name'=>'Vermeer01', 'price'=> 1000, 'image'=>'7bb76a20-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>1],
            ['id'=>2, 'name'=>'Vermeer02', 'price'=> 1000, 'image'=>'7bb76a21-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>1],
            ['id'=>3, 'name'=>'Vermeer03', 'price'=> 1000, 'image'=>'7bb76a22-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>1],
            ['id'=>4, 'name'=>'Vermeer04', 'price'=> 1000, 'image'=>'7bb76a23-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>1],
            ['id'=>5, 'name'=>'Vermeer05', 'price'=> 1000, 'image'=>'7bb76a24-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>1],
            ['id'=>6, 'name'=>'Vermeer06', 'price'=> 1000, 'image'=>'7bb76a25-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>1],
            ['id'=>7, 'name'=>'Vermeer07', 'price'=> 1000, 'image'=>'7bb76a26-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>1],
            ['id'=>8, 'name'=>'Vermeer08', 'price'=> 1000, 'image'=>'7bb76a27-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>1],
            ['id'=>9, 'name'=>'Vermeer09', 'price'=> 1000, 'image'=>'7bb76a28-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>1],
            ['id'=>10, 'name'=>'Vermeer10', 'price'=> 1000, 'image'=>'7bb76a29-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>1],

            ['id'=>11, 'name'=>'Rembrandt01', 'price'=> 1000, 'image'=>'7bb76a2a-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>2],
            ['id'=>12, 'name'=>'Rembrandt02', 'price'=> 1000, 'image'=>'7bb76a2b-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>2],
            ['id'=>13, 'name'=>'Rembrandt03', 'price'=> 1000, 'image'=>'7bb76a2c-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>2],
            ['id'=>14, 'name'=>'Rembrandt04', 'price'=> 1000, 'image'=>'7bb76a2d-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>2],
            ['id'=>15, 'name'=>'Rembrandt05', 'price'=> 1000, 'image'=>'7bb79130-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>2],
            ['id'=>16, 'name'=>'Rembrandt06', 'price'=> 1000, 'image'=>'7bb79131-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>2],
            ['id'=>17, 'name'=>'Rembrandt07', 'price'=> 1000, 'image'=>'7bb79132-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>2],
            ['id'=>18, 'name'=>'Rembrandt08', 'price'=> 1000, 'image'=>'7bb79133-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>2],
            ['id'=>19, 'name'=>'Rembrandt09', 'price'=> 1000, 'image'=>'7bb79134-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>2],
            ['id'=>20, 'name'=>'Rembrandt10', 'price'=> 1000, 'image'=>'7bb79135-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>2],
            
            ['id'=>21, 'name'=>'Manet01', 'price'=> 1000, 'image'=>'7bb79136-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>3],
            ['id'=>22, 'name'=>'Manet02', 'price'=> 1000, 'image'=>'7bb79137-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>3],
            ['id'=>23, 'name'=>'Manet03', 'price'=> 1000, 'image'=>'7bb79138-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>3],
            ['id'=>24, 'name'=>'Manet04', 'price'=> 1000, 'image'=>'7bb79139-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>3],
            ['id'=>25, 'name'=>'Manet05', 'price'=> 1000, 'image'=>'7bb7913a-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>3],
            ['id'=>26, 'name'=>'Manet06', 'price'=> 1000, 'image'=>'7bb7913b-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>3],
            ['id'=>27, 'name'=>'Manet07', 'price'=> 1000, 'image'=>'7bb7913c-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>3],
            ['id'=>28, 'name'=>'Manet08', 'price'=> 1000, 'image'=>'7bb7913d-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>3],
            ['id'=>29, 'name'=>'Manet09', 'price'=> 1000, 'image'=>'7bb7913e-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>3],
            ['id'=>30, 'name'=>'Manet10', 'price'=> 1000, 'image'=>'7bb7913f-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>3],
            
            ['id'=>31, 'name'=>'Gogh01', 'price'=> 1000, 'image'=>'7bb79140-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>4],
            ['id'=>32, 'name'=>'Gogh02', 'price'=> 1000, 'image'=>'7bb79141-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>4],
            ['id'=>33, 'name'=>'Gogh03', 'price'=> 1000, 'image'=>'7bb79142-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>4],
            ['id'=>34, 'name'=>'Gogh04', 'price'=> 1000, 'image'=>'7bb79143-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>4],
            ['id'=>35, 'name'=>'Gogh05', 'price'=> 1000, 'image'=>'7bb79144-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>4],
            ['id'=>36, 'name'=>'Gogh06', 'price'=> 1000, 'image'=>'7bb79145-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>4],
            ['id'=>37, 'name'=>'Gogh07', 'price'=> 1000, 'image'=>'7bb79146-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>4],
            ['id'=>38, 'name'=>'Gogh08', 'price'=> 1000, 'image'=>'7bb79147-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>4],
            ['id'=>39, 'name'=>'Gogh09', 'price'=> 1000, 'image'=>'7bb79148-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>4],
            ['id'=>40, 'name'=>'Gogh10', 'price'=> 1000, 'image'=>'7bb79149-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>4],
            
            ['id'=>41, 'name'=>'Gauguin01', 'price'=> 1000, 'image'=>'7bb7914a-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>5],
            ['id'=>42, 'name'=>'Gauguin02', 'price'=> 1000, 'image'=>'7bb7914b-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>5],
            ['id'=>43, 'name'=>'Gauguin03', 'price'=> 1000, 'image'=>'7bb7914c-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>5],
            ['id'=>44, 'name'=>'Gauguin04', 'price'=> 1000, 'image'=>'7bb7914d-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>5],
            ['id'=>45, 'name'=>'Gauguin05', 'price'=> 1000, 'image'=>'7bb7914e-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>5],
            ['id'=>46, 'name'=>'Gauguin06', 'price'=> 1000, 'image'=>'7bb7914f-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>5],
            ['id'=>47, 'name'=>'Gauguin07', 'price'=> 1000, 'image'=>'7bb79150-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>5],
            ['id'=>48, 'name'=>'Gauguin08', 'price'=> 1000, 'image'=>'7bb79151-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>5],
            ['id'=>49, 'name'=>'Gauguin09', 'price'=> 1000, 'image'=>'7bb79152-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>5],
            ['id'=>50, 'name'=>'Gauguin10', 'price'=> 1000, 'image'=>'7bb79153-8216-11e9-a350-cfa7853301c2.jpg','category_id'=>5],
        
            ['id'=>51, 'name'=>'Modigliani01', 'price'=> 1000, 'image'=>'e6293c90-821f-11e9-9139-b775e1193151.jpg','category_id'=>6],
            ['id'=>52, 'name'=>'Modigliani02', 'price'=> 1000, 'image'=>'e6293c91-821f-11e9-9139-b775e1193151.jpg','category_id'=>6],
            ['id'=>53, 'name'=>'Modigliani03', 'price'=> 1000, 'image'=>'e62963a0-821f-11e9-9139-b775e1193151.jpg','category_id'=>6],
            ['id'=>54, 'name'=>'Modigliani04', 'price'=> 1000, 'image'=>'e62963a1-821f-11e9-9139-b775e1193151.jpg','category_id'=>6],
            ['id'=>55, 'name'=>'Modigliani05', 'price'=> 1000, 'image'=>'e62963a2-821f-11e9-9139-b775e1193151.jpg','category_id'=>6],
            ['id'=>56, 'name'=>'Modigliani06', 'price'=> 1000, 'image'=>'e62963a3-821f-11e9-9139-b775e1193151.jpg','category_id'=>6],
            ['id'=>57, 'name'=>'Modigliani07', 'price'=> 1000, 'image'=>'e62963a4-821f-11e9-9139-b775e1193151.jpg','category_id'=>6],
            ['id'=>58, 'name'=>'Modigliani08', 'price'=> 1000, 'image'=>'e62963a5-821f-11e9-9139-b775e1193151.jpg','category_id'=>6],
            ['id'=>59, 'name'=>'Modigliani09', 'price'=> 1000, 'image'=>'e62963a6-821f-11e9-9139-b775e1193151.jpg','category_id'=>6],
            ['id'=>60, 'name'=>'Modigliani10', 'price'=> 1000, 'image'=>'e62963a7-821f-11e9-9139-b775e1193151.jpg','category_id'=>6],           
        
        ];
        foreach ($items as $arr) {
            Item::firstOrCreate(
                ['id' => $arr['id']],
                [
                    'name' => $arr['name'],
                    'price' => $arr['price'],
                    'image' => $arr['image'],
                    'category_id' => $arr['category_id']
                ]
            );
        }
    }
}

