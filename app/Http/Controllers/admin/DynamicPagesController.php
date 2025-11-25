<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DynamicPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DynamicPagesController extends Controller
{
    public function index(Request $request)
    {
        $query = DynamicPage::query();


        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('slug', 'LIKE', '%' . $request->search . '%');
        }

        $pages = $query->orderBy('id', 'DESC')->paginate(10);

        return view('admin.dynamic_pages.list', compact('pages'));
    }

    public function create()
    {
        return view('admin.dynamic_pages.create');
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:dynamic_pages,name',
            'slug' => 'required|string|max:255|unique:dynamic_pages,slug',
            'content' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }


        $page = new DynamicPage();
        $page->name = $request->name;
        $page->slug = $request->slug;
        $page->content = $request->content;
        $page->status = $request->status;
        $page->save();

        // 🔹 Step 3: Redirect With Message
        return redirect()->route('dynamic-pages.index')->with('success', 'Page created successfully!');
    }

    public function destroy(Request $request, $id)
    {
        $page = DynamicPage::find($id);

        if (!$page) {
            $request->session()->flash('error', 'Page not found');
            return response()->json([
                'success' => false,
                'message' => 'Page not found'
            ], 404);
        }

        $page->delete();

        $request->session()->flash('success', 'Page deleted successfully');
        return response()->json([
            'success' => true,
            'message' => 'Page deleted successfully'
        ]);
    }


    public function edit($id)
{
    $page = DynamicPage::findOrFail($id);
    return view('admin.dynamic_pages.edit', compact('page'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'slug'  => 'required|string|max:255|unique:dynamic_pages,slug,' . $id,
        'content' => 'nullable',
    ]);

    $page = DynamicPage::findOrFail($id);

    $page->update([
        'name' => $request->name,
        'slug' => $request->slug,
        'content' => $request->content,
        'status' => $request->status,
    ]);

    return redirect()->route('dynamic-pages.index')->with('success', 'Page updated successfully!');
}

}
