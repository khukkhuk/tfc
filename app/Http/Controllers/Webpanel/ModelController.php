<?php

namespace App\Http\Controllers\Webpanel;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Functions\MenuControl;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Webpanel\LogsController;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\DB;

use App\Models\Backend\ProductModel;
use App\Models\Backend\ModelModel;
use App\Models\Backend\ModellistModel;
use App\Models\Backend\GalleryModel;

class ModelController extends Controller
{
    protected $prefix = 'backend';
    protected $segment = 'webpanel';
    protected $controller = 'model';
    protected $folder = 'model';
    protected $folder_pro = 'product';
    protected $menu_id = '1';
    protected $name_page = "รายการประเภทสินค้า";

    public function imageSize($find = null)
    {
        $arr = [
            'cover' => [
                'md' => ['x' => 1200, 'y' => 800],
                
            ],
            'gallery' => [
                'lg' => ['x' => 1200, 'y' => 800],
            ]
        ];
        if ($find == null) {
            return $arr;
        } else {
            switch ($find) {
                case 'cover':
                    return $arr['cover'];
                    break;
                case 'gallery':
                    return $arr['gallery'];
                    break;
                default:
                    return [];
                    break;
            }
        }
    }

    public function datatable(Request $request)
    {
        $like = [
            'search_choice' => $request->search_choice,
            'search_type' => $request->search_type,
            'search_keyword' => $request->search_keyword,
        ];
        $sTable = ModellistModel::where('model_id',$request->id)->orderby('sort', 'asc')
            ->when($like, function ($query) use ($like) {
                if (@$like['search_choice'] != "") 
                {
                    if(@$like['search_type'] == "like")
                    {
                        $query->where(@$like['search_choice'], 'like', '%' . @$like['search_keyword'] . '%');
                    }
                    else
                    {
                        $query->where(@$like['search_choice'], @$like['search_type'], @$like['search_keyword']);
                    }
                }
            })
            ->get();
        return Datatables::of($sTable)
            ->addIndexColumn()
            ->addColumn('action_name', function ($row) {
                $data = $row->code;
                return $data;
            })
            ->addColumn('change_sort', function ($row) {
                $sorts = ModellistModel::orderby('sort')->where('model_id',$row->model_id)->get();
                $html = "";
                $html.='<select id="sort_'.$row->id.'" name="sort_'.$row->id.'" class="form-select w100" onchange="changesort('.$row->id.')">';
                foreach($sorts as $s)
                {
                    $select = '';
                    if($s->sort == $row->sort){ $select = 'selected'; }
                    $html.='<option value="'.$s->sort.'" '.$select.'>'.$s->sort.'</option>';
                }
                $html.='</select>';
    
                $data = $html;
                return $data;
            })
            ->editColumn('status', function ($row) {
                $status = "";
                if($row->status == "on")
                {
                    $status = "checked";
                }
                $data = "<div class='form-check form-switch '>
                            <input id='status_change_$row->id' data-id='$row->id' onclick='status($row->id);' class='show-code form-check-input mr-0 ml-3' type='checkbox' $status>
                        </div>";
                return $data;
            })
            ->addColumn('created_at', function ($row) {
                $data = date('d/m/Y', strtotime('+543 Years', strtotime($row->created_at)));
                return $data;
            })
            ->addColumn('action', function ($row) {
                return " 
                <a href='$this->segment/$this->folder/edit/$row->model_id/$row->id' class='mr-3 btn btn-sm btn-info' title='Edit'><i class='fa fa-edit w-4 h-4 mr-1'></i> Edit </a>                                                 
                <a href='javascript:' class='btn btn-sm btn-danger' onclick='deleteItem($row->id)' title='Delete'><i class='far fa-trash-alt w-4 h-4 mr-1'></i> Delete</a>
            ";
            })
            ->rawColumns(['change_sort','action_name', 'status', 'created_at', 'action'])
            ->make(true);
    }

    public function index(Request $request,$id)
    {
        $model = ModelModel::find($id);
        $product = ProductModel::find($model->product_id);

        $navs = [
            '0' => ['url' => "$this->segment", 'name' => "Dashboard", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder_pro", 'name' => "รายการประเภทสินค้า", "last" => 0],
            '2' => ['url' => "$this->segment/$this->folder_pro/view/$product->id", 'name' => "$product->name", "last" => 0],
            '3' => ['url' => "$this->segment/$this->folder/$id", 'name' => "$model->name", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder.index", [
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'segment' => $this->segment,
            'name_page' => $this->name_page,
            'navs' => $navs,
            'row' => $model,
        ]);
    }

    public function add(Request $request,$id)
    {
        $model = ModelModel::find($id);
        $product = ProductModel::find($model->product_id);

        $navs = [
            '0' => ['url' => "$this->segment", 'name' => "Dashboard", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder_pro", 'name' => "รายการประเภทสินค้า", "last" => 0],
            '2' => ['url' => "$this->segment/$this->folder_pro/view/$product->id", 'name' => "$product->name", "last" => 0],
            '3' => ['url' => "$this->segment/$this->folder/$id", 'name' => "$model->name", "last" => 0],
            '4' => ['url' => "$this->segment/$this->folder/$id/add", 'name' => "Add", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder.add", [
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'segment' => $this->segment,
            'name_page' => $this->name_page,
            'navs' => $navs,
            'row' => $model,
            'product' => $product,
        ]);
    }



    public function edit(Request $request, $id,$sub_id)
    {
        $model = ModelModel::find($id);
        $product = ProductModel::find($model->product_id);
        $data = ModellistModel::find($sub_id);
        $navs = [
            '0' => ['url' => "$this->segment", 'name' => "Dashboard", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder_pro", 'name' => "รายการประเภทสินค้า", "last" => 0],
            '2' => ['url' => "$this->segment/$this->folder_pro/view/$product->id", 'name' => "$product->name", "last" => 0],
            '3' => ['url' => "$this->segment/$this->folder/$id", 'name' => "$model->name", "last" => 0],
            '4' => ['url' => "$this->segment/$this->folder/edit/$id/$data->id", 'name' => "$data->name", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder.edit", [
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'segment' => $this->segment,
            'name_page' => $this->name_page,
            'row' => $model,
            'product' => $product,
            'data' => $data,
            'menus' => \App\Models\Backend\MenuModel::where(['status' => 'on', 'position' => 'main'])->get(),
            'navs' => $navs,
            'gallerys' => GalleryModel::where(['_id'=>$sub_id, 'type'=>'model_list'])->get(),
        ]);
    }

    public function status($id = null,$sub_id = null)
    {
        $data = ModellistModel::find($sub_id);
        $data->status = ($data->status == 'off') ? 'on' : 'off';
        if ($data->save()) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    public function destroy(Request $request)
    {
        $datas = ModellistModel::find(explode(',', $request->id));
        if (@$datas) {
            foreach ($datas as $data) 
            {
                $query = ModellistModel::destroy($data->id);
            }
        }

        if (@$query) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }
    //==== Function Insert Update Delete Status Sort & Others ====
    public function update_model(Request $request, $id = null)
    {
        try {
            DB::beginTransaction();
            $data = ModelModel::find($id);
            $data->updated_at = date('Y-m-d H:i:s');
            $data->name = $request->name;
            if ($data->save()) {
                DB::commit();
                return view("$this->prefix.alert.success", ['url' => url("$this->segment/$this->folder/$id")]);
            } else {
                return view("$this->prefix.alert.error", ['url' => url("$this->segment/$this->folder/$id")]);
            }
        } catch (\Exception $e) {
            $error_log = $e->getMessage();
            $error_line = $e->getLine();
            $type_log = 'backend';
            $error_url = url()->current();
            $log_id = LogsController::save_logbackend($type_log, $error_log, $error_line, $error_url);

            return view("$this->prefix.alert.alert", [
                'url' => $error_url,
                'title' => "เกิดข้อผิดพลาดทางโปรแกรม",
                'text' => "กรุณาแจ้งรหัส Code : $log_id ให้ทางผู้พัฒนาโปรแกรม ",
                'icon' => 'error'
            ]);
        }
    }

    public function insert(Request $request, $id = null, $sub_id = null)
    {
        return $this->store($request, $id = null, $sub_id = null);
    }
    public function update(Request $request, $id,$sub_id)
    {
        return $this->store($request, $id,$sub_id);
    }
    public function store($request, $id = null, $sub_id = null)
    {
        try {
            DB::beginTransaction();
            if ($sub_id == null) {
                $data = new ModellistModel();
                $sort = ModellistModel::count();
                $data->sort = $sort + 1;
                $data->status = "on";
                $data->model_id = $request->model_id;
                $data->product_id = $request->product_id;
                $data->created_at = date('Y-m-d H:i:s');
                $data->updated_at = date('Y-m-d H:i:s');
            } else {
                $data = ModellistModel::find($sub_id);
                $data->updated_at = date('Y-m-d H:i:s');
            }
            $data->code = $request->code;
            $data->check_point = $request->check_point;
            $data->broken_item = $request->broken_item;
            if ($data->save()) {
                DB::commit();
                return view("$this->prefix.alert.success", ['url' => url("$this->segment/$this->folder/$request->model_id")]);
            } else {
                return view("$this->prefix.alert.error", ['url' => url("$this->segment/$this->folder/$request->model_id")]);
            }
        } catch (\Exception $e) {
            $error_log = $e->getMessage();
            $error_line = $e->getLine();
            $type_log = 'backend';
            $error_url = url()->current();
            $log_id = LogsController::save_logbackend($type_log, $error_log, $error_line, $error_url);

            return view("$this->prefix.alert.alert", [
                'url' => $error_url,
                'title' => "เกิดข้อผิดพลาดทางโปรแกรม",
                'text' => "กรุณาแจ้งรหัส Code : $log_id ให้ทางผู้พัฒนาโปรแกรม ",
                'icon' => 'error'
            ]);
        }
    }

    // ===== VIEWS ======
    
    public function show_modal(Request $request)
    {
        $data = ProductModel::find($request->id);
        return view("$this->prefix.pages.$this->folder.show_modal", [
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'segment' => $this->segment,
            'name_page' => $this->name_page,
            'data' => $data,
            'link_action' => "$this->segment/$this->folder/view/$data->id/show_modal",
        ]);
    }


    // =============== Model ==================
    public function datatable_modal(Request $request)
    {
        $like = [
            'search_choice' => $request->search_choice,
            'search_type' => $request->search_type,
            'search_keyword' => $request->search_keyword,
        ];
        $sTable = ModelModel::orderby('id', 'asc')
            ->when($like, function ($query) use ($like) {
                if (@$like['search_choice'] != "") 
                {
                    if(@$like['search_type'] == "like")
                    {
                        $query->where(@$like['search_choice'], 'like', '%' . @$like['search_keyword'] . '%');
                    }
                    else
                    {
                        $query->where(@$like['search_choice'], @$like['search_type'], @$like['search_keyword']);
                    }
                }
            })
            ->get();
        return Datatables::of($sTable)
            ->addIndexColumn()
            ->addColumn('action_name', function ($row) {
                $data = $row->name;
                return $data;
            })
            ->editColumn('status', function ($row) {
                $status = "";
                if($row->status == "on")
                {
                    $status = "checked";
                }
                $data = "<div class='form-check form-switch w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0'>
                            <input id='status_change_$row->id' data-id='$row->id' onclick='status($row->id);' class='show-code form-check-input mr-0 ml-3' type='checkbox' $status>
                        </div>";
                return $data;
            })
            ->addColumn('created_at', function ($row) {
                $data = date('d/m/Y', strtotime('+543 Years', strtotime($row->created_at)));
                return $data;
            })
            ->addColumn('action', function ($row) {
                return " <a href='$this->segment/$this->folder/edit/$row->product_id/editsub/$row->id' class='mr-3 btn btn-sm btn-info' title='Edit'><i class='fa fa-edit w-4 h-4 mr-1'></i> Edit </a>                                                 
                <a href='javascript:' class='btn btn-sm btn-danger' onclick='deleteItem($row->id)' title='Delete'><i class='far fa-trash-alt w-4 h-4 mr-1'></i> Delete</a>
            ";
            })
            ->rawColumns(['action_name', 'status', 'created_at', 'action'])
        ->make(true);
    }
    public function add_modal(Request $request,$id)
    {
        $data_main = ProductModel::find($id);
        $navs = [
            '0' => ['url' => "$this->segment", 'name' => "Dashboard", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "รายการประเภทสินค้า", "last" => 0],
            '2' => ['url' => "$this->segment/$this->folder/add", 'name' => "$data_main->name", "last" => 0],
            '3' => ['url' => "$this->segment/$this->folder/add", 'name' => "Add", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder.add_modal", [
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'segment' => $this->segment,
            'name_page' => $this->name_page,
            'navs' => $navs,
            'data_main' => $data_main,
            'link_action' => "$this->segment",
        ]);
    }

    public function edit_modal(Request $request, $id, $sub_id)
    {
        $data_main = ProductModel::find($id);
        $data = ModelModel::find($sub_id);
        $navs = [
            '0' => ['url' => "$this->segment", 'name' => "Dashboard", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "รายการประเภทสินค้า", "last" => 0],
            '2' => ['url' => "$this->segment/$this->folder/edit/$id", 'name' => "$data_main->name", "last" => 0],
            '3' => ['url' => "$this->segment/$this->folder/edit/$id/editsub/$sub_id", 'name' => "$data->name", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder.edit_modal", [
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'segment' => $this->segment,
            'name_page' => $this->name_page,
            'row' => $data,
            'data_main' => $data_main,
            'menus' => \App\Models\Backend\MenuModel::where(['status' => 'on', 'position' => 'main'])->get(),
            'navs' => $navs,
        ]);
    }
    //==== Function Insert Update Delete Status Sort & Others ====
    public function insert_modal(Request $request, $id = null)
    {
        return $this->store_modal($request, $id = null);
    }
    public function update_modal(Request $request, $id)
    {
        return $this->store_modal($request, $id);
    }
    public function store_modal($request, $id = null)
    {
        try {
            DB::beginTransaction();
            if ($id == null) {
                $data = new ModelModel();
                $sort = ModelModel::count();
                $data->product_id = $request->product_id;
                $data->sort = $sort + 1;
                $data->status = "on";
                $data->created_at = date('Y-m-d H:i:s');
                $data->updated_at = date('Y-m-d H:i:s');
            } else {
                $data = ModelModel::find($id);
                $data->updated_at = date('Y-m-d H:i:s');
            }
            $data->name = $request->name_modal;
            if ($data->save()) {
                DB::commit();
                return view("$this->prefix.alert.success", ['url' => url("$this->segment/$this->folder/view/$data->product_id")]);
            } else {
                return view("$this->prefix.alert.error", ['url' => url("$this->segment/$this->folder/view/$request->product_id")]);
            }
        } catch (\Exception $e) {
            $error_log = $e->getMessage();
            $error_line = $e->getLine();
            $type_log = 'backend';
            $error_url = url()->current();
            $log_id = LogsController::save_logbackend($type_log, $error_log, $error_line, $error_url);

            return view("$this->prefix.alert.alert", [
                'url' => $error_url,
                'title' => "เกิดข้อผิดพลาดทางโปรแกรม",
                'text' => "กรุณาแจ้งรหัส Code : $log_id ให้ทางผู้พัฒนาโปรแกรม ",
                'icon' => 'error'
            ]);
        }
    }

    public function status_modal($id = null,$sub_id = null)
    {
        $data = ModelModel::find($sub_id);
        $data->status = ($data->status == 'off') ? 'on' : 'off';
        if ($data->save()) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    public function destroy_modal(Request $request)
    {
        $datas = ModelModel::find(explode(',', $request->id));
        if (@$datas) {
            foreach ($datas as $data) {
                $query = ModelModel::destroy($data->id);
            }
        }

        if (@$query) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }


    public function temp_gallery(Request $request,$id)
    {
        $randdom = rand(1,99);
        $filename = 'gallery_'.$randdom . date('dmY-His');
        $file =$request->file('file');
  
        if ($file) 
        {
            $lg = Image::make($file->getRealPath());
  
            $ext = explode("/", $lg->mime())[1];
            $size = $this->imageSize();
            // $lg->resize($size['cover']['md']['x'], $size['cover']['md']['y'])->stream();
            $height = Image::make($file)->height();
            $width = Image::make($file)->width();
            $lg->resize($width, $height)->stream();
            $newLG = 'upload/model-list/gallery/' . $filename . '.' . $ext;
            $store = Storage::disk('public')->put($newLG, $lg);
            if($store)
            {
                $data = new GalleryModel();
                $data->_id = $id;
                $data->type = "model_list";
                $data->image = $newLG;
                $data->image_real_name = $request->file('file')->getClientOriginalName();;
                $data->save();
            }
        }
    }
    public function temp_gallery_delete(Request $request, $id)
    {
        $data = GalleryModel::where(['_id'=>$id, 'type'=>'product', 'image_real_name'=>$request->name])->first();
        if($data)
        {
            Storage::disk('public')->delete($data->image);
            $query = GalleryModel::destroy($data->id);
        }
    }
    public function gallery_destroy(Request $request)
    {
        $datas = GalleryModel::find(explode(',', $request->id));
        if (@$datas) {
            foreach ($datas as $data) 
            {
            Storage::disk('public')->delete($data->image);
                //destroy
               
                $query = GalleryModel::destroy($data->id);
            }
        }

        if (@$query) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }
    public function changesort(Request $request)
    {
        $data = ModellistModel::find($request->id);
        $checksort = ModellistModel::where('id','!=',$data->id)->where('model_id',$data->model_id)->where('sort',$request->sort)->first();
        if($checksort)
        {
            $new_sort = ModellistModel::where('sort',$request->sort)->where('model_id',$data->model_id)->first();
            $new_sort->sort = $data->sort;
            $new_sort->save();
        }
        $data->sort = $request->sort;
        $data->save();
    }

}
