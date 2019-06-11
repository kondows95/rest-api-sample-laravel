<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function everyone_can_get_rows()
    {
        $exps = factory(Category::class, 2)->create();
 
        $res = $this->get('/api/categories'); 
        $res->assertStatus(200); 
        $res->assertExactJson([
            'data' => [
                [
                    'id' => $exps[0]->id,
                    'name' => $exps[0]->name,
                    'created_at' => $this->toMySqlDateFromJson($exps[0]->updated_at),
                    'updated_at' => $this->toMySqlDateFromJson($exps[0]->created_at),
                    'deleted_at' => null,
                ],
                [
                    'id' => $exps[1]->id,
                    'name' => $exps[1]->name,
                    'created_at' => $this->toMySqlDateFromJson($exps[1]->updated_at),
                    'updated_at' => $this->toMySqlDateFromJson($exps[1]->created_at),
                    'deleted_at' => null,
                ]
            ]
        ]);
    }
    
    /** @test */
    public function rows_are_order_by_id_asc()
    {
        factory(Category::class)->create(['id' => 1250]);
        factory(Category::class)->create(['id' => 8]);
        factory(Category::class)->create(['id' => 35]);
        $res = $this->get('/api/categories'); 
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
    public function deleted_rows_are_not_shown()
    {
        $row1 = factory(Category::class)->create();
        $row2 = factory(Category::class)->create();
        $row2->delete();
        $row3 = factory(Category::class)->create();
       
        
        $res = $this->get('/api/categories'); 
        $res->assertStatus(200);
        $res->assertJsonCount(2, 'data');
        $res->assertJson([
            'data' => [
                ['id' => $row1->id],
                ['id' => $row3->id],
            ]
        ]);
    }
    
    /** @test */
    public function everyone_can_add_row()
    {
        $res = $this->post('/api/categories', [
            'name' => 'category1'
        ]);
        $res->assertStatus(201);
        $res->assertJsonCount(4, 'data');
        $res->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'created_at',
                'updated_at'
            ]
        ]);
        $json = $res->json();//1 is id
        $this->assertEquals('category1', $json['data']['name']);//2
        $this->assertLessThan(2, time() - strtotime($json['data']['created_at']));//3
        $this->assertLessThan(2, time() - strtotime($json['data']['updated_at']));//4
    }
}
