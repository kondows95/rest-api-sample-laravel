<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;


class CategoryTest extends TestCase
{
    use RefreshDatabase;
    
    const API_PATH = '/api/categories';
    const STR255 = '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789ABCDE';
    const STR256 = '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789ABCDEF';
    
    
    //=========================================================================
    // index
    //=========================================================================
    
    /** @test */
    public function on_index_categories_success()
    {
        $exps = factory(Category::class, 2)->create();

        $res = $this->withHeaders($this->getAuthHeader())->json('GET', self::API_PATH);
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
    public function categories_are_order_by_id_asc()
    {
        factory(Category::class)->create(['id' => 1250]);
        factory(Category::class)->create(['id' => 8]);
        factory(Category::class)->create(['id' => 35]);
        $res = $this->withHeaders($this->getAuthHeader())->json('GET', self::API_PATH); 
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
    public function deleted_categories_are_not_shown()
    {
        $row1 = factory(Category::class)->create();
        $row2 = factory(Category::class)->create();
        $row2->delete();
        $row3 = factory(Category::class)->create();
       
        
        $res = $this->withHeaders($this->getAuthHeader())->json('GET', self::API_PATH); 
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
    public function on_show_category_success()
    {
        $exps = factory(Category::class, 3)->create();
        
        $res = $this->withHeaders($this->getAuthHeader())->json('GET', self::API_PATH.'/'.$exps[1]->id); 
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
    public function show_deletedId_will_occur_error()
    {
        $row = factory(Category::class)->create();
        $row->delete();
        
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage('No query results for model [App\Models\Category] '.$row->id);
        $res = $this->withHeaders($this->getAuthHeader())->json('GET', self::API_PATH.'/'.$row->id); 
    }
    
    /** @test */
    public function show_notExistsId_will_occur_error()
    {
        $row = factory(Category::class)->create();
        $errorId = $row->id + 1;
        
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage('No query results for model [App\Models\Category] '.$errorId);
        $res = $this->withHeaders($this->getAuthHeader())->json('GET', self::API_PATH.'/'.$errorId); 
    }
    
    //=========================================================================
    // store
    // Actually, you shuould add auth for store method.
    //=========================================================================
    
    /** @test */
    public function on_store_category_success()
    {
        $res = $this->withHeaders($this->getAuthHeader())->json('POST', self::API_PATH, [
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
    public function store_without_postData_will_occur_validation_error()
    {
        $this->expectException(ValidationException::class);
        $res = $this->withHeaders($this->getAuthHeader())->json('POST', self::API_PATH);
    }
    
    /** @test */
    public function store_name_length_0_will_occur_validation_error()
    {
        $this->expectException(ValidationException::class);
        $res = $this->withHeaders($this->getAuthHeader())->json('POST', self::API_PATH, [
            'name' => ''
        ]);
    }
    
    /** @test */
    public function store_name_length_1_will_no_validation_error()
    {
        $res = $this->withHeaders($this->getAuthHeader())->json('POST', self::API_PATH, [
            'name' => '1'
        ]);
        $res->assertStatus(201); 
    }
    
    /** @test */
    public function store_name_length_256_will_occur_validation_error()
    {
        //first, confirm strlen is 256
        $this->assertEquals(256, strlen(self::STR256));
        
        //then, confirm exception is occured
        $this->expectException(ValidationException::class);
        $res = $this->withHeaders($this->getAuthHeader())->json('POST', self::API_PATH, [
            'name' => self::STR256
        ]);
    }
    
    /** @test */
    public function store_name_length_255_will_no_validation_error()
    {
        $res = $this->withHeaders($this->getAuthHeader())->json('POST', self::API_PATH, [
            'name' => self::STR255
        ]);
        $res->assertStatus(201); 
        
        //Confirm that the string is not truncated due to DB constraints.
        $json = $res->json();
        $this->assertEquals(255, strlen($json['data']['name']));
    }
    
    //=========================================================================
    // update
    // Actually, you shuould add auth for store method.
    //=========================================================================
    
    /** @test */
    public function on_update_category_success()
    {
        $row = factory(Category::class)->create();
        $res = $this->withHeaders($this->getAuthHeader())->json('PUT', self::API_PATH.'/'.$row->id, [
            'name' => 'editedCategory'
        ]);
        $res->assertStatus(200);
        $res->assertJsonCount(5, 'data');
        $res->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'created_at',
                'updated_at',
                'deleted_at'
            ]
        ]);
        $json = $res->json();//1 is id
        $this->assertEquals('editedCategory', $json['data']['name']);//2
        $this->assertLessThan(2, time() - strtotime($json['data']['created_at']));//3
        $this->assertLessThan(2, time() - strtotime($json['data']['updated_at']));//4
        $this->assertEquals(null, $json['data']['deleted_at']);//5
    }
    
    /** @test */
    public function update_name_length_1_will_no_validation_error()
    {
        $row = factory(Category::class)->create();
        $res = $this->withHeaders($this->getAuthHeader())->json('PUT', self::API_PATH.'/'.$row->id, [
            'name' => '1'
        ]);
        $res->assertStatus(200); 
    }
    
    /** @test */
    public function update_name_length_256_will_occur_validation_error()
    {
        //first, confirm strlen is 256
        $this->assertEquals(256, strlen(self::STR256));
        
        //then, confirm exception is occured
        $this->expectException(ValidationException::class);
        $row = factory(Category::class)->create();
        $res = $this->withHeaders($this->getAuthHeader())->json('PUT', self::API_PATH.'/'.$row->id, [
            'name' => self::STR256
        ]);
    }
    
    /** @test */
    public function update_name_length_255_will_no_validation_error()
    {
        $row = factory(Category::class)->create();
        $res = $this->withHeaders($this->getAuthHeader())->json('PUT', self::API_PATH.'/'.$row->id, [
            'name' => self::STR255
        ]);
        $res->assertStatus(200); 
        
        //Confirm that the string is not truncated due to DB constraints.
        $json = $res->json();
        $this->assertEquals(255, strlen($json['data']['name']));
    }
    
    
    //=========================================================================
    // destroy
    //=========================================================================
    
    /** @test */
    public function on_destory_category_success()
    {
        $row = factory(Category::class)->create();
        $res = $this->withHeaders($this->getAuthHeader())->json('DELETE', self::API_PATH.'/'.$row->id);
        $res->assertStatus(204);
        $this->assertSoftDeleted('categories', ['id' => $row->id]);
    }
}
