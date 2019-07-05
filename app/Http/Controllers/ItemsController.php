<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Item;
use App\Models\Category;
use App\Http\Requests\Item\StoreItemRequest;
use App\Http\Requests\Item\IndexItemsRequest;
use Illuminate\Http\Response;

class ItemsController extends Controller
{
    public function index(IndexItemsRequest $request): ResourceCollection
    {
        //You can join with category table as following".
        //Item::with('category')->orderBy('id')
        //But, I think it is better to not join on the API side.
        //Because I think that API should be simple.
        return JsonResource::collection(
            Item::orderBy('id')
            ->limit(config('const.ITEM_LIMIT'))
            ->offset($request->offset)
            ->get()
        );
    }
    
    public function show(Item $item): JsonResource
    {
        return new JsonResource($item);
    }

    public function store(StoreItemRequest $request)
    {
        return new JsonResource(
            Item::create($request->validated())
        );
    }
    
    //I do not create UpdateItemRequest but use StoreItemRequest.
    //In most cases, I think store and update requests should be the same.
    //However, if there are items that can not be changed, you need to create UpdateItemRequest.
    public function update(StoreItemRequest $request, Item $item): JsonResource
    {
        $item->update($request->validated());
        return new JsonResource($item);
    }
    
    public function destroy(Request $request, Item $item): Response
    {
        $item->delete();
        return response()->noContent();
    }
}
