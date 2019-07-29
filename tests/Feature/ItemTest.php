<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class ItemTest extends TestCase
{
    use RefreshDatabase;
    
    const API_PATH = '/api/items';
    const STR255 = '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789ABCDE';
    const STR256 = '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789ABCDEF';
    
    
    //=========================================================================
    // index
    //=========================================================================
    
    /** @test */
    public function on_index_items_success()
    {
        $category =  factory(Category::class)->create();
        $exps = factory(Item::class, 2)->create(['category_id' => $category->id]);
        
        $now = time();
        $res = $this->withHeaders($this->getAuthHeader())->json('GET', '/api/items?offset=0'); 
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
                    'updated_at' => $this->toMySqlDateFromJson($exps[0]->created_at)
                ],
                [
                    'id' => $exps[1]->id,
                    'name' => $exps[1]->name,
                    'price' => $exps[1]->price,
                    'image' => $exps[1]->image,
                    'category_id' => $category->id,
                    'deleted_at' => NULL,
                    'created_at' => $this->toMySqlDateFromJson($exps[1]->updated_at),
                    'updated_at' => $this->toMySqlDateFromJson($exps[1]->created_at)
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
    public function deleted_items_are_not_shown()
    {
        $row1 = factory(Item::class)->create();
        $row2 = factory(Item::class)->create();
        $row2->delete();
        $row3 = factory(Item::class)->create();
       
        
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
    
    /** @test */
    public function get_11th_to_20th_items_if_limit10_offset10_totalSize30()
    {
        $category =  factory(Category::class)->create();
        $exps = factory(Item::class, 30)->create(['category_id' => $category->id]);
        
        $res = $this->withHeaders($this->getAuthHeader())->json('GET', '/api/items?offset=10'); 
        $res->assertJsonCount(10, 'data');
        $res->assertJson([
            'data' => [
                ['id' => $exps[10]->id],//11th
                ['id' => $exps[11]->id],
                ['id' => $exps[12]->id],
                ['id' => $exps[13]->id],
                ['id' => $exps[14]->id],
                ['id' => $exps[15]->id],
                ['id' => $exps[16]->id],
                ['id' => $exps[17]->id],
                ['id' => $exps[18]->id],
                ['id' => $exps[19]->id],//20th
            ]
        ]);
    }
    
    /** @test */
    public function get_11th_to_15th_items_if_limit10_offset10_totalSize15()
    {
        $category =  factory(Category::class)->create();
        $exps = factory(Item::class, 15)->create(['category_id' => $category->id]);
        
        $res = $this->withHeaders($this->getAuthHeader())->json('GET', '/api/items?offset=10'); 
        $res->assertJsonCount(5, 'data');
        $res->assertJson([
            'data' => [
                ['id' => $exps[10]->id],//11th
                ['id' => $exps[11]->id],
                ['id' => $exps[12]->id],
                ['id' => $exps[13]->id],
                ['id' => $exps[14]->id],//15th
            ]
        ]);
    }
    
    /** @test */
    public function get_no_items_if_limit10_offset10_totalSize3()
    {
        $category =  factory(Category::class)->create();
        $exps = factory(Item::class, 3)->create(['category_id' => $category->id]);
        
        $res = $this->withHeaders($this->getAuthHeader())->json('GET', '/api/items?offset=10'); 
        $res->assertJsonCount(0, 'data');
 
    }
    
    /** @test */ 
    public function noOffsetParameter_is_same_as_offset0()
    {
        $category =  factory(Category::class)->create();
        $exps = factory(Item::class, 3)->create(['category_id' => $category->id]);
        
        $res = $this->withHeaders($this->getAuthHeader())->json('GET', self::API_PATH); 
        $res->assertJsonCount(3, 'data');
    }
    
    //=========================================================================
    // show
    //=========================================================================
    /** @test */
    public function on_show_item_success()
    {
        $category =  factory(Category::class)->create();
        $exps = factory(Item::class, 3)->create(['category_id' => $category->id]);
        
        $res = $this->withHeaders($this->getAuthHeader())->json('GET', self::API_PATH.'/'.$exps[1]->id); 
        $res->assertStatus(200); 
        $res->assertExactJson([
            'data' => [
                'id' => $exps[1]->id,
                'name' => $exps[1]->name,
                'price' => $exps[1]->price,
                'image' => $exps[1]->image,
                'category_id' => $category->id,
                'deleted_at' => NULL,
                'created_at' => $this->toMySqlDateFromJson($exps[1]->updated_at),
                'updated_at' => $this->toMySqlDateFromJson($exps[1]->created_at)
            ]
        ]);
    }
    
    /** @test */
    public function show_deletedId_will_occur_error()
    {
        $row = factory(Item::class)->create();
        $row->delete();
        
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage('No query results for model [App\Models\Item] '.$row->id);
        $res = $this->withHeaders($this->getAuthHeader())->json('GET', self::API_PATH.'/'.$row->id); 
    }
    
    /** @test */
    public function show_notExistsId_will_occur_error()
    {
        $row = factory(Item::class)->create();
        $errorId = $row->id + 1;
        
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage('No query results for model [App\Models\Item] '.$errorId);
        $res = $this->withHeaders($this->getAuthHeader())->json('GET', self::API_PATH.'/'.$errorId); 
    }

    
    //=========================================================================
    // store
    // Actually, you shuould add auth for store / update method.
    //=========================================================================
    
    /** @test */
    public function on_store_item_success()
    {
        $category = factory(Category::class)->create();
        
        $res = $this->withHeaders($this->getAuthHeader())->json('POST', self::API_PATH, [
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
    
    /** @test */
    public function store_without_postData_will_occur_validation_error()
    {
        $this->expectException(ValidationException::class);
        $res = $this->withHeaders($this->getAuthHeader())->json('POST', self::API_PATH);
    }
    
    /** @test */
    public function store_noParentCategoryId_will_occur_database_error()
    {
        $this->expectException(QueryException::class);
        $res = $this->withHeaders($this->getAuthHeader())->json('POST', self::API_PATH, [
            'name' => 'item1',
            'price' => 999,
            'image' => 'item1.png',
            'category_id' => 1 //there is no categories
        ]);
    }
    
    /** @test */
    public function store_name_length_0_will_occur_validation_error()
    {
        $category =  factory(Category::class)->create();
        $this->expectException(ValidationException::class);
        $res = $this->withHeaders($this->getAuthHeader())->json('POST', self::API_PATH, [
            'name' => '',
            'price' => 999,
            'image' => 'item1.png',
            'category_id' => $category->id
        ]);
    }
    
    /** @test */
    public function store_name_length_1_will_no_validation_error()
    {
        $category =  factory(Category::class)->create();
        $res = $this->withHeaders($this->getAuthHeader())->json('POST', self::API_PATH, [
            'name' => '1',
            'price' => 999,
            'image' => 'item1.png',
            'category_id' => $category->id
            
        ]);
        $res->assertStatus(201); 
    }
    
    /** @test */
    public function store_name_length_256_will_occur_validation_error()
    {
        //first, confirm strlen is 256
        $this->assertEquals(256, strlen(self::STR256));
        
        //then, confirm exception is occured
        $category =  factory(Category::class)->create();
        $this->expectException(ValidationException::class);
        $res = $this->withHeaders($this->getAuthHeader())->json('POST', self::API_PATH, [
            'name' => self::STR256,
            'price' => 999,
            'image' => 'item1.png',
            'category_id' => $category->id
        ]);
    }
    
    /** @test */
    public function store_name_length_255_will_no_validation_error()
    {
        $category =  factory(Category::class)->create();
        $res = $this->withHeaders($this->getAuthHeader())->json('POST', self::API_PATH, [
            'name' => self::STR255,
            'price' => 999,
            'image' => 'item1.png',
            'category_id' => $category->id
        ]);
        $res->assertStatus(201); 
        
        //Confirm that the string is not truncated due to DB constraints.
        $json = $res->json();
        $this->assertEquals(255, strlen($json['data']['name']));
    }
    
    /** @test */
    public function store_image_length_0_is_nullable()
    {
        $category =  factory(Category::class)->create();
        $res = $this->withHeaders($this->getAuthHeader())->json('POST', self::API_PATH, [
            'name' => 'item1',
            'price' => 999,
            'category_id' => $category->id
        ]);
        $res->assertStatus(201); 
    }
    
    /** @test */
    public function store_image_length_256_will_occur_validation_error()
    {
        //first, confirm strlen is 256
        $this->assertEquals(256, strlen(self::STR256));
        
        //then, confirm exception is occured
        $category =  factory(Category::class)->create();
        $this->expectException(ValidationException::class);
        $res = $this->withHeaders($this->getAuthHeader())->json('POST', self::API_PATH, [
            'name' => 'item1',
            'price' => 999,
            'image' => self::STR256,
            'category_id' => $category->id
        ]);
    }
    
    /** @test */
    public function store_image_length_255_will_no_validation_error()
    {
        $category =  factory(Category::class)->create();
        $res = $this->withHeaders($this->getAuthHeader())->json('POST', self::API_PATH, [
            'name' => 'item1',
            'price' => 999,
            'image' => self::STR255,
            'category_id' => $category->id
        ]);
        $res->assertStatus(201); 
        
        //Confirm that the string is not truncated due to DB constraints.
        $json = $res->json();
        $this->assertEquals(255, strlen($json['data']['image']));
    }
    
    /** @test */
    public function store_price_minus1_will_occur_validation_error()
    {
        $category =  factory(Category::class)->create();
        $this->expectException(ValidationException::class);
        $res = $this->withHeaders($this->getAuthHeader())->json('POST', self::API_PATH, [
            'name' => 'item1',
            'price' => -1,
            'image' => 'item1.png',
            'category_id' => $category->id
        ]);
    }
    
    /** @test */
    public function store_price_0_will_no_validation_error()
    {
        $category =  factory(Category::class)->create();
        $res = $this->withHeaders($this->getAuthHeader())->json('POST', self::API_PATH, [
            'name' => 'item1',
            'price' => 0,
            'image' => 'item1.png',
            'category_id' => $category->id
        ]);
        $res->assertStatus(201); 
    }
    
    //=========================================================================
    // update
    // If update's request parameter is the same as store, the validation test will be the same as store.
    // However, you can not omit the update test.
    // The store and update tests appear to be duplicates, but because the endpoints are different, you must also test the update.
    // Also actually, you shuould add auth for store / update method.
    //=========================================================================
    
    /** @test */
    public function on_update_item_success()
    {
        $categories =  factory(Category::class, 2)->create();
        $row = factory(Item::class)->create(['category_id' => $categories[0]->id]);
        $res = $this->withHeaders($this->getAuthHeader())->json('PUT', self::API_PATH.'/'.$row->id, [
            'name' => 'editedItem',
            'price' => 1234,
            'image' => 'editedItem.png',
            'category_id' => $categories[1]->id
        ]);
        $res->assertStatus(200);
        $res->assertJsonCount(8, 'data');
        $res->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'price',
                'image',
                'category_id',
                'created_at',
                'updated_at',
                'deleted_at'
            ]
        ]);
        $json = $res->json();//1 is id
        $this->assertEquals('editedItem', $json['data']['name']);//2
        $this->assertEquals(1234, $json['data']['price']);//3
        $this->assertEquals('editedItem.png', $json['data']['image']);//4
        $this->assertEquals($categories[1]->id, $json['data']['category_id']);//5
        $this->assertLessThan(2, time() - strtotime($json['data']['created_at']));//6
        $this->assertLessThan(2, time() - strtotime($json['data']['updated_at']));//7
        $this->assertEquals(null, $json['data']['deleted_at']);//8
    }
    
    /** @test */
    public function update_without_postData_will_occur_validation_error()
    {
        $this->expectException(ValidationException::class);
        $row = factory(Item::class)->create();
        $res = $this->withHeaders($this->getAuthHeader())->json('PUT', self::API_PATH.'/'.$row->id);
    }
    
    
    /** @test */
    public function update_noParentCategoryId_will_occur_database_error()
    {
        $this->expectException(QueryException::class);
        $row = factory(Item::class)->create();
        $res = $this->withHeaders($this->getAuthHeader())->json('PUT', self::API_PATH.'/'.$row->id, [
            'name' => 'item1',
            'price' => 999,
            'image' => 'item1.png',
            'category_id' => 1 //there is no categories
        ]);
    }
    
    /** @test */
    public function update_name_length_0_will_occur_validation_error()
    {
        $category =  factory(Category::class)->create();
        $this->expectException(ValidationException::class);
        $row = factory(Item::class)->create();
        $res = $this->withHeaders($this->getAuthHeader())->json('PUT', self::API_PATH.'/'.$row->id, [
            'name' => '',
            'price' => 999,
            'image' => 'item1.png',
            'category_id' => $category->id
        ]);
    }
    
    /** @test */
    public function update_name_length_1_will_no_validation_error()
    {
        $category =  factory(Category::class)->create();
        $row = factory(Item::class)->create();
        $res = $this->withHeaders($this->getAuthHeader())->json('PUT', self::API_PATH.'/'.$row->id, [
            'name' => '1',
            'price' => 999,
            'image' => 'item1.png',
            'category_id' => $category->id
            
        ]);
        $res->assertStatus(200); 
    }
    
    /** @test */
    public function update_name_length_256_will_occur_validation_error()
    {
        //first, confirm strlen is 256
        $this->assertEquals(256, strlen(self::STR256));
        
        //then, confirm exception is occured
        $category =  factory(Category::class)->create();
        $this->expectException(ValidationException::class);
        $row = factory(Item::class)->create();
        $res = $this->withHeaders($this->getAuthHeader())->json('PUT', self::API_PATH.'/'.$row->id, [
            'name' => self::STR256,
            'price' => 999,
            'image' => 'item1.png',
            'category_id' => $category->id
        ]);
    }
    
    /** @test */
    public function update_name_length_255_will_no_validation_error()
    {
        $category =  factory(Category::class)->create();
        $row = factory(Item::class)->create();
        $res = $this->withHeaders($this->getAuthHeader())->json('PUT', self::API_PATH.'/'.$row->id, [
            'name' => self::STR255,
            'price' => 999,
            'image' => 'item1.png',
            'category_id' => $category->id
        ]);
        $res->assertStatus(200); 
        
        //Confirm that the string is not truncated due to DB constraints.
        $json = $res->json();
        $this->assertEquals(255, strlen($json['data']['name']));
    }
    
    /** @test */
    public function update_image_is_nullable()
    {
        $category =  factory(Category::class)->create();
        $row = factory(Item::class)->create();
        $res = $this->withHeaders($this->getAuthHeader())->json('PUT', self::API_PATH.'/'.$row->id, [
            'name' => 'item1',
            'price' => 999,
            'category_id' => $category->id
        ]);
        $res->assertStatus(200); 
    }
    
    /** @test */
    public function update_image_length_1_will_no_validation_error()
    {
        $category =  factory(Category::class)->create();
        $row = factory(Item::class)->create();
        $res = $this->withHeaders($this->getAuthHeader())->json('PUT', self::API_PATH.'/'.$row->id, [
            'name' => 'item1',
            'price' => 999,
            'image' => '1',
            'category_id' => $category->id
        ]);
        $res->assertStatus(200); 
    }
    
    /** @test */
    public function update_image_length_256_will_occur_validation_error()
    {
        //first, confirm strlen is 256
        $this->assertEquals(256, strlen(self::STR256));
        
        //then, confirm exception is occured
        $category =  factory(Category::class)->create();
        $this->expectException(ValidationException::class);
        $row = factory(Item::class)->create();
        $res = $this->withHeaders($this->getAuthHeader())->json('PUT', self::API_PATH.'/'.$row->id, [
            'name' => 'item1',
            'price' => 999,
            'image' => self::STR256,
            'category_id' => $category->id
        ]);
    }
    
    /** @test */
    public function update_image_length_255_will_no_validation_error()
    {
        $category =  factory(Category::class)->create();
        $row = factory(Item::class)->create();
        $res = $this->withHeaders($this->getAuthHeader())->json('PUT', self::API_PATH.'/'.$row->id, [
            'name' => 'item1',
            'price' => 999,
            'image' => self::STR255,
            'category_id' => $category->id
        ]);
        $res->assertStatus(200); 
        
        //Confirm that the string is not truncated due to DB constraints.
        $json = $res->json();
        $this->assertEquals(255, strlen($json['data']['image']));
    }
    
    /** @test */
    public function update_price_minus1_will_occur_validation_error()
    {
        $category =  factory(Category::class)->create();
        $this->expectException(ValidationException::class);
        $row = factory(Item::class)->create();
        $res = $this->withHeaders($this->getAuthHeader())->json('PUT', self::API_PATH.'/'.$row->id, [
            'name' => 'item1',
            'price' => -1,
            'image' => 'item1.png',
            'category_id' => $category->id
        ]);
    }
    
    /** @test */
    public function update_price_0_will_no_validation_error()
    {
        $category =  factory(Category::class)->create();
        $row = factory(Item::class)->create();
        $res = $this->withHeaders($this->getAuthHeader())->json('PUT', self::API_PATH.'/'.$row->id, [
            'name' => 'item1',
            'price' => 0,
            'image' => 'item1.png',
            'category_id' => $category->id
        ]);
        $res->assertStatus(200); 
    }
    
    //=========================================================================
    // destroy
    //=========================================================================
    /** @test */
    public function on_destory_category_success()
    {
        $row = factory(Item::class)->create();
        $res = $this->withHeaders($this->getAuthHeader())->json('DELETE', self::API_PATH.'/'.$row->id);
        $res->assertStatus(204);
        $this->assertSoftDeleted('items', ['id' => $row->id]);
    }
}
