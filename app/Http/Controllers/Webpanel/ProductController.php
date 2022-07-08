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

class ProductController extends Controller
{
    protected $prefix = 'backend';
    protected $segment = 'webpanel';
    protected $controller = 'product';
    protected $folder = 'product';
    protected $menu_id = '1';
    protected $name_page = "รายการประเภทสินค้า";

    public function datatable(Request $request)
    {
        $like = [
            'search_choice' => $request->search_choice,
            'search_type' => $request->search_type,
            'search_keyword' => $request->search_keyword,
        ];
        $sTable = ProductModel::orderby('id', 'asc')
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
                $data = "<div class='form-check form-switch'>
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
                <a href='$this->segment/$this->folder/view/$row->id' class='mr-3 mb-2 btn btn-sm btn-instagram ' title='Edit'><i class='fa fa-search w-4 h-4 mr-1'></i> View </a>                                                 
                <a href='$this->segment/$this->folder/edit/$row->id' class='mr-3 mb-2 btn btn-sm btn-info' title='Edit'><i class='fa fa-edit w-4 h-4 mr-1'></i> Edit </a>                                                 
                <a href='javascript:' class='mr-3 mb-2 btn btn-sm btn-danger' onclick='deleteItem($row->id)' title='Delete'><i class='far fa-trash-alt w-4 h-4 mr-1'></i> Delete</a>
            ";
            })
            ->rawColumns(['action_name', 'status', 'created_at', 'action'])
            ->make(true);
    }

    public function index(Request $request)
    {
        $navs = [
            '0' => ['url' => "$this->segment", 'name' => "Dashboard", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "รายการประเภทสินค้า", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder.index", [
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'segment' => $this->segment,
            'name_page' => $this->name_page,
            'navs' => $navs,
        ]);
    }

    public function view(Request $request,$id)
    {
        $data = ProductModel::find($id);
        $data2 = ModelModel::where(['product_id'=>$id])->get();
        $navs = [
            '0' => ['url' => "$this->segment", 'name' => "Dashboard", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "รายการประเภทสินค้า", "last" => 0],
            '2' => ['url' => "$this->segment/$this->folder", 'name' => "$data->name", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder.view", [
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'segment' => $this->segment,
            'name_page' => $this->name_page,
            'navs' => $navs,
            'row' => $data,
            'data' => $data2,
            'count_model' => $data2->count(),
            'count_model_list' => ModellistModel::where('product_id',$id)->count(),
        ]);
    }

    public function add(Request $request)
    {
        $navs = [
            '0' => ['url' => "$this->segment", 'name' => "Dashboard", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "รายการประเภทสินค้า", "last" => 0],
            '2' => ['url' => "$this->segment/$this->folder/add", 'name' => "Add", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder.add", [
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'segment' => $this->segment,
            'name_page' => $this->name_page,
            'navs' => $navs,
        ]);
    }



    public function edit(Request $request, $id)
    {
        $data = ProductModel::find($id);
        $navs = [
            '0' => ['url' => "$this->segment", 'name' => "Dashboard", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "รายการประเภทสินค้า", "last" => 0],
            '2' => ['url' => "$this->segment/$this->folder/edit/$id", 'name' => "$data->name", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder.edit", [
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'segment' => $this->segment,
            'name_page' => $this->name_page,
            'row' => $data,
            'menus' => \App\Models\Backend\MenuModel::where(['status' => 'on', 'position' => 'main'])->get(),
            'navs' => $navs,
        ]);
    }

    public function status($id = null)
    {
        $data = ProductModel::find($id);
        $data->status = ($data->status == 'off') ? 'on' : 'off';
        if ($data->save()) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    public function destroy(Request $request)
    {
        $datas = ProductModel::find(explode(',', $request->id));
        if (@$datas) {
            foreach ($datas as $data) 
            {
                $model = ModelModel::where('product_id',$data->id)->get();
                if($model)
                {
                    foreach($model as $m)
                    {
                        ModellistModel::where('model_id',$m->id)->delete();
                        ModelModel::where('id',$m->id)->delete();
                    }
                }
                $query = ProductModel::destroy($data->id);
            }
        }

        if (@$query) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }
    //==== Function Insert Update Delete Status Sort & Others ====
    public function insert(Request $request, $id = null)
    {
        return $this->store($request, $id = null);
    }
    public function update(Request $request, $id)
    {
        return $this->store($request, $id);
    }
    public function store($request, $id = null)
    {
        try {
            DB::beginTransaction();
            if ($id == null) {
                $data = new ProductModel();
                $sort = ProductModel::count();
                $data->sort = $sort + 1;
                $data->status = "on";
                $data->created_at = date('Y-m-d H:i:s');
                $data->updated_at = date('Y-m-d H:i:s');
            } else {
                $data = ProductModel::find($id);
                $data->updated_at = date('Y-m-d H:i:s');
            }
            $data->name = $request->name;
            if ($data->save()) {
                DB::commit();
                return view("$this->prefix.alert.success", ['url' => url("$this->segment/$this->folder/view/$data->id")]);
            } else {
                return view("$this->prefix.alert.error", ['url' => url("$this->segment/$this->folder")]);
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

}
