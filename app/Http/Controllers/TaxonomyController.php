<?php

namespace App\Http\Controllers;

use App\Category;
use App\Utils\ModuleUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class TaxonomyController extends Controller
{
    /**
     * All Utils instance.
     */
    protected $moduleUtil;

    /**
     * Constructor
     *
     * @param  ProductUtils  $product
     * @return void
     */
    public function __construct(ModuleUtil $moduleUtil)
    {
        $this->moduleUtil = $moduleUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category_type = request()->get('type');
        if ($category_type == 'product' && ! auth()->user()->can('category.view') && ! auth()->user()->can('category.create')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $can_edit = true;
            if ($category_type == 'product' && ! auth()->user()->can('category.update')) {
                $can_edit = false;
            }

            $can_delete = true;
            if ($category_type == 'product' && ! auth()->user()->can('category.delete')) {
                $can_delete = false;
            }

            $business_id = request()->session()->get('user.business_id');

            $all_categories = Category::where('business_id', $business_id)
                ->where('category_type', $category_type)
                ->get()
                ->keyBy('id');

            $grouped = $all_categories->groupBy('parent_id');

            $category = collect();

            // Get parents (those with parent_id = null or 0)
            $parents = $grouped[null] ?? $grouped[0] ?? [];

            foreach ($parents as $parent) {
                // Push parent
                $category->push($parent);

                // Get and push children with prefixed parent name
                foreach ($grouped[$parent->id] ?? [] as $child) {
                    // Attach parent name for use in editColumn
                    $child->parent_name = $parent->name;
                    $category->push($child);
                }
            }

            return Datatables::of($category)
                ->addColumn('action', function ($row) use ($can_edit, $can_delete, $category_type) {
                    $html = '';
                    if ($can_edit) {
                        $html .= '<button data-href="' . action([\App\Http\Controllers\TaxonomyController::class, 'edit'], [$row->id]) . '?type=' . $category_type . '" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-primary edit_category_button"><i class="glyphicon glyphicon-edit"></i> ' . __('messages.edit') . '</button>';
                    }

                    if ($can_delete) {
                        $html .= '&nbsp;<button data-href="' . action([\App\Http\Controllers\TaxonomyController::class, 'destroy'], [$row->id]) . '" class="tw-dw-btn tw-dw-btn-outline tw-dw-btn-xs tw-dw-btn-error delete_category_button"><i class="glyphicon glyphicon-trash"></i> ' . __('messages.delete') . '</button>';
                    }

                    return $html;
                })
                ->addColumn('image', function ($row) {
                    $defaultUrl = '/' . ltrim('img/default.png', '/');
                    $raw = $row->image_path ?: '';
                    $raw = str_replace('\\', '/', $raw); // normalize slashes

                    $final = '';
                    if ($raw === '') {
                        $final = $defaultUrl;
                    } elseif (preg_match('/^https?:\/\//i', $raw)) {
                        // Absolute URL already
                        $final = $raw;
                    } elseif (strpos($raw, '/storage/') === 0) {
                        // Already a storage URL, verify file exists; if missing, try legacy path
                        $candidate = ltrim($raw, '/'); // storage/category_images/...
                        if (file_exists(public_path($candidate))) {
                            $final = $raw;
                        } else {
                            $basename = basename($raw);
                            $legacyRel = 'uploads/public/category_images/' . $basename;
                            if (file_exists(public_path($legacyRel))) {
                                $final = '/' . $legacyRel;
                            } else {
                                $final = $raw; // leave as-is; onerror will swap to default
                            }
                        }
                    } elseif (strpos($raw, 'storage/') === 0) {
                        $candidate = ltrim('/' . $raw, '/');
                        if (file_exists(public_path($candidate))) {
                            $final = '/' . $raw;
                        } else {
                            $basename = basename($raw);
                            $legacyRel = 'uploads/public/category_images/' . $basename;
                            if (file_exists(public_path($legacyRel))) {
                                $final = '/' . $legacyRel;
                            } else {
                                $final = '/' . $raw;
                            }
                        }
                    } elseif (strpos($raw, 'public/') === 0) {
                        // Convert Laravel disk path public/... -> /storage/...
                        $final = '/' . preg_replace('/^public\//', 'storage/', $raw);
                    } else {
                        // Try common storage locations
                        // 1) category_images/foo.jpg -> /storage/category_images/foo.jpg
                        if (\Illuminate\Support\Facades\Storage::exists('public/' . ltrim($raw, '/'))) {
                            $final = \Illuminate\Support\Facades\Storage::url(ltrim($raw, '/'));
                        } elseif (\Illuminate\Support\Facades\Storage::exists(ltrim($raw, '/'))) {
                            $final = \Illuminate\Support\Facades\Storage::url(ltrim($raw, '/'));
                        } else {
                            // Legacy fallback: files saved under public/uploads/public/category_images
                            $basename = basename($raw);
                            $legacyRel = 'uploads/public/category_images/' . $basename;
                            if (file_exists(public_path($legacyRel))) {
                                $final = '/' . $legacyRel;
                            } else {
                                // Fallback to serving as public path
                                $final = '/' . ltrim($raw, '/');
                            }
                        }
                    }

                    return '<img src="' . e($final) . '" alt="' . e($row->name) . '" onerror="this.onerror=null;this.src=\'' . e($defaultUrl) . '\';" style="height:32px;width:32px;object-fit:cover;border-radius:6px;" loading="lazy" />';
                })
                ->editColumn('name', function ($row) {
                    $label = !empty($row->parent_name) ? ($row->parent_name . ' -> ' . $row->name) : $row->name;
                    return e($label);
                })
                ->removeColumn('id')
                ->removeColumn('parent_id')
                ->rawColumns(['action','image'])
                ->make(true);

            }

        $module_category_data = $this->moduleUtil->getTaxonomyData($category_type);

        return view('taxonomy.index')->with(compact('module_category_data', 'module_category_data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category_type = request()->get('type');
        if ($category_type == 'product' && ! auth()->user()->can('category.create')) {
            abort(403, 'Unauthorized action.');
        }
        $business_id = request()->session()->get('user.business_id');

        $module_category_data = $this->moduleUtil->getTaxonomyData($category_type);

        $categories = Category::where('business_id', $business_id)
                        ->where('parent_id', 0)
                        ->where('category_type', $category_type)
                        ->select(['name', 'short_code', 'id'])
                        ->get();

        $parent_categories = [];
        if (! empty($categories)) {
            foreach ($categories as $category) {
                $parent_categories[$category->id] = $category->name;
            }
        }

        return view('taxonomy.create')
                    ->with(compact('parent_categories', 'module_category_data', 'category_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category_type = request()->input('category_type');
        if ($category_type == 'product' && ! auth()->user()->can('category.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $input = $request->only(['name', 'short_code', 'category_type', 'description']);
            if (! empty($request->input('add_as_sub_cat')) && $request->input('add_as_sub_cat') == 1 && ! empty($request->input('parent_id'))) {
                $input['parent_id'] = $request->input('parent_id');
            } else {
                $input['parent_id'] = 0;
            }
            $input['business_id'] = $request->session()->get('user.business_id');
            $input['created_by'] = $request->session()->get('user.id');

            // handle image upload (optional)
            if ($request->hasFile('image')) {
                $request->validate([
                    'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                ]);
                $path = $request->file('image')->store('public/category_images');
                $input['image_path'] = Storage::url($path); // e.g. storage/category_images/...
            }

            $category = Category::create($input);
            $output = ['success' => true,
                'data' => $category,
                'msg' => __('category.added_success'),
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => false,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return $output;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category_type = request()->get('type');
        if ($category_type == 'product' && ! auth()->user()->can('category.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $category = Category::where('business_id', $business_id)->find($id);

            $module_category_data = $this->moduleUtil->getTaxonomyData($category_type);

            $parent_categories = Category::where('business_id', $business_id)
                                        ->where('parent_id', 0)
                                        ->where('category_type', $category_type)
                                        ->where('id', '!=', $id)
                                        ->pluck('name', 'id');
            $is_parent = false;

            if ($category->parent_id == 0) {
                $is_parent = true;
                $selected_parent = null;
            } else {
                $selected_parent = $category->parent_id;
            }

            return view('taxonomy.edit')
                ->with(compact('category', 'parent_categories', 'is_parent', 'selected_parent', 'module_category_data'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (request()->ajax()) {
            try {
                $input = $request->only(['name', 'description']);
                $business_id = $request->session()->get('user.business_id');

                $category = Category::where('business_id', $business_id)->findOrFail($id);

                if ($category->category_type == 'product' && ! auth()->user()->can('category.update')) {
                    abort(403, 'Unauthorized action.');
                }

                $category->name = $input['name'];
                $category->description = $input['description'];
                $category->short_code = $request->input('short_code');

                if (! empty($request->input('add_as_sub_cat')) && $request->input('add_as_sub_cat') == 1 && ! empty($request->input('parent_id'))) {
                    $category->parent_id = $request->input('parent_id');
                } else {
                    $category->parent_id = 0;
                }
                // handle image upload (optional)
                if ($request->hasFile('image')) {
                    $request->validate([
                        'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                    ]);
                    $path = $request->file('image')->store('public/category_images');
                    $category->image_path = Storage::url($path);
                }
                $category->save();

                $output = ['success' => true,
                    'msg' => __('category.updated_success'),
                ];
            } catch (\Exception $e) {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                $output = ['success' => false,
                    'msg' => __('messages.something_went_wrong'),
                ];
            }

            return $output;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (request()->ajax()) {
            try {
                $business_id = request()->session()->get('user.business_id');

                $category = Category::where('business_id', $business_id)->findOrFail($id);

                if ($category->category_type == 'product' && ! auth()->user()->can('category.delete')) {
                    abort(403, 'Unauthorized action.');
                }

                $category->delete();

                $output = ['success' => true,
                    'msg' => __('category.deleted_success'),
                ];
            } catch (\Exception $e) {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                $output = ['success' => false,
                    'msg' => __('messages.something_went_wrong'),
                ];
            }

            return $output;
        }
    }

    public function getCategoriesApi()
    {
        try {
            $api_token = request()->header('API-TOKEN');

            $api_settings = $this->moduleUtil->getApiSettings($api_token);

            $categories = Category::catAndSubCategories($api_settings->business_id);
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            return $this->respondWentWrong($e);
        }

        return $this->respond($categories);
    }

    /**
     * get taxonomy index page
     * through ajax
     *
     * @return \Illuminate\Http\Response
     */
    public function getTaxonomyIndexPage(Request $request)
    {
        if (request()->ajax()) {
            $category_type = $request->get('category_type');
            $module_category_data = $this->moduleUtil->getTaxonomyData($category_type);

            return view('taxonomy.ajax_index')
                ->with(compact('module_category_data', 'category_type'));
        }
    }
}
