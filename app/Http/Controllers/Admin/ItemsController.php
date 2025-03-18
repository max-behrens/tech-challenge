<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Download;
use App\Models\Info;
use App\Models\Item;
use App\Models\Weblink;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ItemsController extends Controller
{
    /*
    * Render the item vue with table and search functionality.
    */
    public function index(): Response
    {
        $search = request('search');

        // Add search functionality to items/Index.vue.
        $items = Item::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'LIKE', "%{$search}%");
            })
            // Join with the downloads table for items with content_type 'download'
            ->leftJoin('downloads', function ($join) {
                $join->on('items.content_id', '=', 'downloads.id')
                    ->where('items.content_type', '=', 'download');
            })
            // Join with the weblinks table for items with content_type 'weblink'
            ->leftJoin('weblinks', function ($join) {
                $join->on('items.content_id', '=', 'weblinks.id')
                    ->where('items.content_type', '=', 'WEBLINK');
            })
            // Join with the infos table for items with content_type 'info'
            ->leftJoin('infos', function ($join) {
                $join->on('items.content_id', '=', 'infos.id')
                    ->where('items.content_type', '=', 'info');
            })
            ->select(
                'items.*',
                'downloads.url as download_url',
                'weblinks.url as weblink_url',
                \DB::raw("CONCAT(infos.header, '<br><br>', infos.content) as info_content") // Concatenate header and content from infos table.
            )
            ->oldest()
            ->limit(10)
            ->get();


        

        // Pass the items and search variables to the vue file.
        return Inertia::render('items/Index', [
            'items' => $items,
            'search' => $search,
        ]);
    }

    /*
    * Render the create item vue.
    */
    public function create(): Response
    {
        return Inertia::render('items/Create', []);
    }

    /*
    * Create a new item.
    */
    public function store()
    {
        $type = match(request()->input('content_type')) {
            'info' => new Info([
                'header' => request('content.header'),
                'content' => request('content.content'),
            ]),
            'download' => new Download([
                'url =' => request('content.url'),
            ]),
            'WEBLINK' => new Weblink([
                'url =' => request('content.url'),
            ]),
        };
        $type->save();

        $type->item()->save(new Item(request()->only(['name', 'description'])));

        return redirect()->route('admin.items.index')->with('message', 'Successfully Created Item');
    }

    /*
    * Render the edit item vue.
    */
    public function edit(Item $item): Response
    {
        return Inertia::render('items/Edit', [
            'item' => $item->load('content'),
        ]);
    }

    /*
    * Edit an existing item.
    */
    public function update(Item $item): RedirectResponse
    {
        $item->forceFill(request()->only(['name', 'description']));

        switch($item->content_type) {
            case 'info':
                $item->content->header = request('content.header');
                $item->content->content = request('content.content');
                break;
            case 'download':
            case 'WEBLINK':
                $item->content->url = request('content.url');
                break;
        }

        $item->push();

        return redirect()->route('admin.items.index')->with('message', 'Successfully Updated Item');
    }

    /*
    * Delete an existing item.
    */
    public function delete(Item $item): RedirectResponse
    {
        // Soft delete by setting active to 0.
        $item->active = 0;
        $item->save();

        return redirect()->route('admin.items.index')->with('message', 'Item deleted successfully.');
    }

}
