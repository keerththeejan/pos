<?php

namespace App\Http\Controllers;

use App\Brands;
use App\Utils\ModuleUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\Facades\DataTables;

class BrandController extends Controller
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
        if (! auth()->user()->can('brand.view') && ! auth()->user()->can('brand.create')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax() || request()->wantsJson() || request()->has('draw')) {
            $business_id = request()->session()->get('user.business_id');

            $select = ['id', 'name', 'description'];
            if (Schema::hasColumn('brands', 'image_path')) {
                $select[] = 'image_path';
            }
            if (Schema::hasColumn('brands', 'image')) {
                $select[] = 'image';
            }

            $brands = Brands::where('business_id', $business_id)
                        ->select($select);

            return DataTables::of($brands)
                ->addColumn('image', function ($row) {
                    $src = null;
                    // Preferred: storage path
                    if (!empty($row->image_path)) {
                        $src = Str::startsWith($row->image_path, ['http://', 'https://'])
                            ? $row->image_path
                            : Storage::url($row->image_path);
                    }
                    // Legacy: image filename under public/uploads/brand_images
                    if (empty($src) && !empty($row->image)) {
                        $src = asset('uploads/brand_images/' . ltrim($row->image, '/'));
                    }
                    if (empty($src)) {
                        $src = asset('img/default.png');
                    }
                    return '<img src="'.e($src).'" alt="'.e($row->name).'" style="width:40px;height:40px;object-fit:cover;border-radius:4px;" onerror="this.src=\'' . asset('img/default.png') . '\'">';
                })
                ->addColumn('action', function ($row) {
                    $buttons = '';
                    if (auth()->user()->can('brand.update')) {
                        $buttons .= '<button data-href="'.e(action('App\\Http\\Controllers\\BrandController@edit', [$row->id])).'" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-primary edit_brand_button"><i class="glyphicon glyphicon-edit"></i> '.e(__('messages.edit')).'</button> ';
                    }
                    if (auth()->user()->can('brand.delete')) {
                        $buttons .= '<button data-href="'.e(action('App\\Http\\Controllers\\BrandController@destroy', [$row->id])).'" class="tw-dw-btn tw-dw-btn-outline tw-dw-btn-xs tw-dw-btn-error delete_brand_button"><i class="glyphicon glyphicon-trash"></i> '.e(__('messages.delete')).'</button>';
                    }
                    return $buttons;
                })
                ->removeColumn('id')
                ->rawColumns(['image', 'action'])
                ->make(true);
        }

        return view('brand.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! auth()->user()->can('brand.create')) {
            abort(403, 'Unauthorized action.');
        }

        $quick_add = false;
        if (! empty(request()->input('quick_add'))) {
            $quick_add = true;
        }

        $is_repair_installed = $this->moduleUtil->isModuleInstalled('Repair');

        return view('brand.create')
                ->with(compact('quick_add', 'is_repair_installed'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! auth()->user()->can('brand.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $input = $request->only(['name', 'description']);
            $business_id = $request->session()->get('user.business_id');
            $input['business_id'] = $business_id;
            $input['created_by'] = $request->session()->get('user.id');

            if ($this->moduleUtil->isModuleInstalled('Repair')) {
                $input['use_for_repair'] = ! empty($request->input('use_for_repair')) ? 1 : 0;
            }

            // Handle image upload if provided
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                // Store under public disk so Storage::url() works
                $path = $request->file('image')->store('brands', 'public');
                $input['image_path'] = $path;
            }

            $brand = Brands::create($input);
            $output = ['success' => true,
                'data' => $brand,
                'msg' => __('brand.added_success'),
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
        if (! auth()->user()->can('brand.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $brand = Brands::where('business_id', $business_id)->find($id);

            $is_repair_installed = $this->moduleUtil->isModuleInstalled('Repair');

            return view('brand.edit')
                ->with(compact('brand', 'is_repair_installed'));
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
        if (! auth()->user()->can('brand.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $input = $request->only(['name', 'description']);
                $business_id = $request->session()->get('user.business_id');

                $brand = Brands::where('business_id', $business_id)->findOrFail($id);
                $brand->name = $input['name'];
                $brand->description = $input['description'];

                if ($this->moduleUtil->isModuleInstalled('Repair')) {
                    $brand->use_for_repair = ! empty($request->input('use_for_repair')) ? 1 : 0;
                }
                // If new image uploaded, replace and remove old if exists
                if ($request->hasFile('image') && $request->file('image')->isValid()) {
                    if (!empty($brand->image_path) && Storage::disk('public')->exists($brand->image_path)) {
                        Storage::disk('public')->delete($brand->image_path);
                    }
                    $brand->image_path = $request->file('image')->store('brands', 'public');
                }

                $brand->save();

                $output = ['success' => true,
                    'msg' => __('brand.updated_success'),
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
        if (! auth()->user()->can('brand.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $business_id = request()->user()->business_id;

                $brand = Brands::where('business_id', $business_id)->findOrFail($id);
                // Remove image file if exists
                if (!empty($brand->image_path) && Storage::disk('public')->exists($brand->image_path)) {
                    Storage::disk('public')->delete($brand->image_path);
                }
                $brand->delete();

                $output = ['success' => true,
                    'msg' => __('brand.deleted_success'),
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

    public function getBrandsApi()
    {
        try {
            $api_token = request()->header('API-TOKEN');

            $api_settings = $this->moduleUtil->getApiSettings($api_token);

            $brands = Brands::where('business_id', $api_settings->business_id)
                                ->get();
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            return $this->respondWentWrong($e);
        }

        return $this->respond($brands);
    }
}
