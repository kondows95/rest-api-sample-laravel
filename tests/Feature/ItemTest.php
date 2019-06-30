<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Item;
use App\Models\Category;

class ItemTest extends TestCase
{
    use RefreshDatabase;
    
    //=========================================================================
    // index
    // TODL: write test for limit and offset
    //=========================================================================
    
    /** @test */
    public function on_index_items_success()
    {
        $category =  factory(Category::class)->create();
        $exps = factory(Item::class, 2)->create(['category_id' => $category->id]);
        
        $now = time();
        $res = $this->json('GET', '/api/items'); 
        $res->assertStatus(200); 
        $res->assertExactJson([
            'data' => [
                [
                    'id' => $exps[0]->id,
                    'name' => $exps[0]->name,
                    'price' => $exps[0]->price,
                    'image' => $exps[0]->image,
                    'category_id' => $category->id,
                    'deleted_at' => NULL,
                    'created_at' => $this->toMySqlDateFromJson($exps[0]->updated_at),
                    'updated_at' => $this->toMySqlDateFromJson($exps[0]->created_at),
                    'category' => [
                        'id' => $category->id,
                        'name' => $category->name,
                        'created_at' => $this->toMySqlDateFromJson($category->updated_at),
                        'updated_at' => $this->toMySqlDateFromJson($category->created_at),
                        'deleted_at' => null,
                    ]
                ],
                [
                    'id' => $exps[1]->id,
                    'name' => $exps[1]->name,
                    'price' => $exps[1]->price,
                    'image' => $exps[1]->image,
                    'category_id' => $category->id,
                    'deleted_at' => NULL,
                    'created_at' => $this->toMySqlDateFromJson($exps[1]->updated_at),
                    'updated_at' => $this->toMySqlDateFromJson($exps[1]->created_at),
                    'category' => [
                        'id' => $category->id,
                        'name' => $category->name,
                        'created_at' => $this->toMySqlDateFromJson($category->updated_at),
                        'updated_at' => $this->toMySqlDateFromJson($category->created_at),
                        'deleted_at' => null,
                    ]
                ],
            ]
        ]);
    }
    
    /** @test */
    public function items_are_order_by_id_asc()
    {
        factory(Item::class)->create(['id' => 1250]);
        factory(Item::class)->create(['id' => 8]);
        factory(Item::class)->create(['id' => 35]);
        $res = $this->json('GET', '/api/items'); 
        $res->assertStatus(200);
        $res->assertJsonCount(3, 'data');
        $res->assertJson([
            'data' => [
                ['id' => 8],
                ['id' => 35],
                ['id' => 1250],
            ]
        ]);
    }
    
    /** @test */
    public function deleted_items_are_not_shown()
    {
        $row1 = factory(Item::class)->create();
        $row2 = factory(Item::class)->create();
        $row2->delete();
        $row3 = factory(Item::class)->create();
       
        
        $res = $this->json('GET', '/api/items'); 
        $res->assertStatus(200);
        $res->assertJsonCount(2, 'data');
        $res->assertJson([
            'data' => [
                ['id' => $row1->id],
                ['id' => $row3->id],
            ]
        ]);
    }
    
    //=========================================================================
    // show
    // TODO: write test
    //=========================================================================
    
    //=========================================================================
    // store
    // TODO: write test for validation
    //=========================================================================
    
    /** @test */
    public function on_store_item_success()
    {
        $category = factory(Category::class)->create();
        
        $res = $this->json('POST', '/api/items', [
            'name' => 'item1',
            'price' => 999,
            'image' => 'item1.png',
            'category_id' => $category->id
        ]);
        $res->assertStatus(201);
        $res->assertJsonCount(7, 'data');
        $res->assertJsonStructure([
            'data' => [
                'id',
                'category_id',
                'name',
                'price',
                'image',
                'created_at',
                'updated_at'
            ]
        ]);
        $json = $res->json();//1 is id
        $this->assertEquals($category->id, $json['data']['category_id']);//2
        $this->assertEquals('item1', $json['data']['name']);//3
        $this->assertEquals(999, $json['data']['price']);//4
        $this->assertEquals('item1.png', $json['data']['image']);//5
        $this->assertLessThan(2, time() - strtotime($json['data']['created_at']));//6
        $this->assertLessThan(2, time() - strtotime($json['data']['updated_at']));//7
    }
    
    
    
    //=========================================================================
    // update
    // TODO: write test
    //=========================================================================
    
    //=========================================================================
    // destroy
    // TODO: write test
    //=========================================================================
}
