<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;


class CategoryTest extends TestCase
{
    use RefreshDatabase;
    
    //=========================================================================
    // index
    //=========================================================================
    
    /** @test */
    public function everyone_can_index_categories()
    {
        $exps = factory(Category::class, 2)->create();
 
        $res = $this->json('GET', '/api/categories'); 
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
        $res = $this->json('GET', '/api/categories'); 
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
       
        
        $res = $this->json('GET', '/api/categories'); 
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
    //=========================================================================
    
    /** @test */
    public function everyone_can_show_category()
    {
        $exps = factory(Category::class, 3)->create();
        
        $res = $this->json('GET', '/api/categories/'.$exps[1]->id); 
        $res->assertStatus(200); 
        $res->assertExactJson([
            'data' => [
                'id' => $exps[1]->id,
                'name' => $exps[1]->name,
                'created_at' => $this->toMySqlDateFromJson($exps[1]->updated_at),
                'updated_at' => $this->toMySqlDateFromJson($exps[1]->created_at),
                'deleted_at' => null,
            ]
        ]);
    }
    
    /** @test */
    public function show_with_id_that_does_not_exist_will_occur_error()
    {
        $row = factory(Category::class)->create();
        $row->delete();
        
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->expectExceptionMessage('No query results for model [App\Models\Category] '.$row->id);
        $res = $this->json('GET', '/api/categories/'.$row->id); 
    }
    
    /** @test */
    public function show_with_deleted_id_will_occur_error()
    {
        $row = factory(Category::class)->create();
        $errorId = $row->id + 1;
        
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->expectExceptionMessage('No query results for model [App\Models\Category] '.$errorId);
        $res = $this->json('GET', '/api/categories/'.$errorId); 
    }
    
    //=========================================================================
    // store
    //=========================================================================
    
    /**
     * @test 
     * Actually, you shuould add auth for store method.
     */
    public function everyone_can_store_category()
    {
        $res = $this->json('POST', '/api/categories', [
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
    
    /** @test */
    public function name_length_0_will_occur_validation_error()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');
        $res = $this->json('POST', '/api/categories', [
            'name' => ''
        ]);
    }
    
    /** @test */
    public function name_length_1_will_no_validation_error()
    {
        $res = $this->json('POST', '/api/categories', [
            'name' => '1'
        ]);
        $res->assertStatus(201); 
    }
    
    /** @test */
    public function name_length_256_will_occur_validation_error()
    {
        //first, confirm strlen is 256
        $str = '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789ABCDEF';
        $this->assertEquals(256, strlen($str));
        
        //then, confirm exception is occured
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->expectExceptionMessage('The given data was invalid.');
        $res = $this->json('POST', '/api/categories', [
            'name' => $str
        ]);
    }
    
    /** @test */
    public function name_length_255_will_no_validation_error()
    {
        $res = $this->json('POST', '/api/categories', [
            'name' => '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789ABCDE'
        ]);
        $res->assertStatus(201); 
        
        //Confirm that the string is not truncated due to DB constraints.
        $json = $res->json();
        $this->assertEquals(255, strlen($json['data']['name']));
    }
    
    //=========================================================================
    // destroy
    //=========================================================================
    
    /**
     * @test 
     * Actually, you shuould add auth for destroy method.
     */
    public function everyone_can_destroy_category()
    {
        $row = factory(Category::class)->create();
        $res = $this->json('DELETE', "/api/categories/{$row->id}");
        $res->assertStatus(204);
        $this->assertSoftDeleted('categories', ['id' => $row->id]);
    }
}
