<?php

namespace App\Http\Controllers\Webpanel;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Functions\MenuControl;
use App\Http\Controllers\Functions\FunctionControl;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Webpanel\LogsController;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\DB;

use App\Models\Backend\ProductModel;
use App\Models\Backend\ModelModel;
use App\Models\Backend\PropertyModel;
use App\Models\Backend\PropertyListModel;
use App\Models\Backend\PropertyImageModel;
use App\Models\Backend\ModellistModel;

class PropertyController extends Controller
{
    protected $prefix = 'backend';
    protected $segment = 'webpanel';
    protected $controller = 'property';
    protected $folder = 'property';
    protected $menu_id = '1';
    protected $name_page = "รายการอสังหา";

    public function datatable(Request $request)
    {
        $like = [
            'search_choice' => $request->search_choice,
            'search_type' => $request->search_type,
            'search_keyword' => $request->search_keyword,
        ];
        $sTable = PropertyModel::orderby('id', 'asc')
            /*->when($like, function ($query) use ($like) {
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
            })*/
            ->get();
        return Datatables::of($sTable)
            ->addIndexColumn()
            ->addColumn('action_name', function ($row) {
                $data = $row->name_en;
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
            ->rawColumns(['action_name','status', 'created_at', 'action'])
            ->make(true);
    }

    public function index(Request $request)
    {
        $navs = [
            '0' => ['url' => "$this->segment", 'name' => "Dashboard", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "รายการอสังหา", "last" => 1],
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
        $data = PropertyModel::find($id);
        // $data2 = ModelModel::where(['product_id'=>$id])->get();
        $navs = [
            '0' => ['url' => "$this->segment", 'name' => "Dashboard", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "รายการอสังหา", "last" => 0],
            '2' => ['url' => "$this->segment/$this->folder", 'name' => "$data->name_th", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder.view", [
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'segment' => $this->segment,
            'name_page' => $this->name_page,
            'navs' => $navs,
            'row' => $data,
            // 'data' => $data2,
            // 'count_model' => $data2->count(),
            // 'count_model_list' => ModellistModel::where('product_id',$id)->count(),
        ]);
    }

    public function add(Request $request)
    {
        $navs = [
            '0' => ['url' => "$this->segment", 'name' => "Dashboard", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "รายการอสังหา", "last" => 0],
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
        $data = PropertyModel::find($id);
        $lists = PropertyListModel::where('property_id',$id)->get();
        $count = count($lists); 
        $navs = [
            '0' => ['url' => "$this->segment", 'name' => "Dashboard", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "รายการอสังหา", "last" => 0],
            '2' => ['url' => "$this->segment/$this->folder/edit/$id", 'name' => "$data->name", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder.edit", [
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'segment' => $this->segment,
            'name_page' => $this->name_page,
            'row' => $data,
            'lists' => $lists,
            'count_result' => $count,
            'menus' => \App\Models\Backend\MenuModel::where(['status' => 'on', 'position' => 'main'])->get(),
            'navs' => $navs,
        ]);
    }

    public function status($id = null)
    {
        $data = PropertyModel::find($id);
        $data->status = ($data->status == 'off') ? 'on' : 'off';
        if ($data->save()) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    public function destroy($id)
    {
        $datas = PropertyModel::find(explode(',', $id));
        if (@$datas) {
            foreach ($datas as $data) 
            {
                $model = PropertyListModel::where('property_id',$data->id)->get();
                if($model)
                {
                    foreach($model as $m)
                    {
                        PropertyListModel::where('id',$m->id)->delete();
                    }
                }
                $image = PropertyImageModel::where('property_id',$data->id)->get();
                if($image){
                    foreach($image as $im){
                        PropertyImageModel::where('id',$im->id)->delete();
                    }
                }
                $query = PropertyModel::destroy($data->id);
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
                $data = new PropertyModel();
                $data->status = 'off';
                $data->created_at = date('Y-m-d H:i:s');
                $data->updated_at = date('Y-m-d H:i:s');

                $data->save();

                if($request->count_row != 0){
                    // dd($request->count_row);
                    for($i=0;$i<$request->count_row;$i++){
                        if(!empty($request->number[$i])){
                            $list = new PropertyListModel;
                            $list->property_id = $data->id;
                            $list->list_th = $request->moreth[$i];
                            $list->list_en = $request->moreen[$i];
                            $list->list_cn = $request->morecn[$i];
                            $list->created_at = date('Y-m-d H:i:s');
                            $list->updated_at = date('Y-m-d H:i:s');
                            $list->save();
                        }
                    }
                }
            } else {
                $data = PropertyModel::find($id);
                $data->updated_at = date('Y-m-d H:i:s');
                // dd($data->id);
                if($request->count_row != 0){
                    for($i=0;$i<$request->count_row;$i++){
                        if(!empty($request->number[$i])){
                            if(!empty($request->list_id[$i])){
                                $list = PropertyListModel::find($request->list_id[$i]);

                                $list->property_id = $data->id;
                                $list->list_th = $request->moreth[$i];
                                $list->list_en = $request->moreen[$i];
                                $list->list_cn = $request->morecn[$i];
                                $list->updated_at = date('Y-m-d H:i:s');
                                
                            }else{
                                $list = new PropertyListModel;

                                $list->property_id = $data->id;
                                $list->list_th = $request->moreth[$i];
                                $list->list_en = $request->moreen[$i];
                                $list->list_cn = $request->morecn[$i];
                                $list->created_at = date('Y-m-d H:i:s');
                                $list->updated_at = date('Y-m-d H:i:s');
                            
                            } 
                            $list->save();
                        }
                    }
                }

                //delete id ที่มีอยู่แล้ว
                if(!empty($request->delete_list)){
                    $v = (explode(",",$request->delete_list));
                    for($i=0;$i<count($v);$i++){
                        if(!empty($v[$i])){
                            $datas = PropertyListModel::find($v[$i]);
                            $query = PropertyListModel::destroy($datas->id);
                        }
                    }
                }
            }

            $data->name_th = $request->name_th;
            $data->name_en = $request->name_en;
            $data->name_cn = $request->name_cn;
            $data->type = $request->type;
            $data->youtube = $request->youtube;
            $data->location = $request->location;
            $data->location_map = $request->locationmap;
            $data->sale = $request->sale;
            $data->rent = $request->rent;
            $data->bedroom = $request->bathroom;
            $data->bathroom = $request->bathroom;
            $data->area = $request->area;
            $data->floor = $request->floor;
            $data->living_area = $request->livingarea;
            $data->land_area = $request->landarea;
            
            if($request->image != ''){
                $image = FunctionControl::upload_image2($request->image,'property');
                $data->image = $image;
            }

            if ($data->save()) {
                DB::commit();
                return view("$this->prefix.alert.success", ['url' => url("$this->segment/$this->folder/view/$data->id")]);
            } else {
                return view("$this->prefix.alert.error", ['url' => url("$this->segment/$this->folder")]);
            }
        } catch (\Exception $e) {
            dd($e);
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
        $data = PropertyModel::find($request->id);
        return view("$this->prefix.pages.$this->folder.show_modal", [
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'segment' => $this->segment,
            'name_page' => $this->name_page,
            'data' => $data,
            'link_action' => "$this->segment/$this->folder/view/$data->id/show_modal",
        ]);
    }

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
                
                $folder = 'property'.$request->property_id;
                if($request->image != null){
                    // dd($request->file('image'));
                    for($i=0;$i<count($request->image);$i++){
                        $data = new PropertyImageModel();
                        $sort = PropertyImageModel::where('property_id',$request->property_id)->count();
                        $data->sort = $sort + 1;
                        $data->property_id = $request->property_id;

                        //upload file 
                        $filename = "$folder".$i.date('dmY-His');
                        $file = $request->image[$i];
                        $lg = Image::make($file->getRealPath());
                        $ext = explode("/", $lg->mime())[1];

                        $height = Image::make($file)->height();
                        $width = Image::make($file)->width();
                        $lg->resize($width, $height)->stream();
                        $newLG = 'upload/'.$folder.'/' . $filename . '.' . $ext;
                        $store = Storage::disk('public')->put($newLG, $lg);
                        if($store){
                            $data->image = $newLG;
                        }
                        $data->created_at = date('Y-m-d H:i:s');
                        $data->updated_at = date('Y-m-d H:i:s');
                        $data->save();
                    }
                }
            } else {
                $data = PropertyImageModel::find($id);
                // $image = FunctionControl::Up
                $data->updated_at = date('Y-m-d H:i:s');
            }
            $data->property_id = $request->property_id;
            if ($data->save()) {
                DB::commit();
                return view("$this->prefix.alert.success", ['url' => url("$this->segment/$this->folder/view/$data->property_id")]);
            } else {
                return view("$this->prefix.alert.error", ['url' => url("$this->segment/$this->folder/view/$request->property_id")]);
            }
        } catch (\Exception $e) {
            dd($e);
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

    // =============== Model ==================
    public function datatable_modal(Request $request,$id)
    {
        /*$like = [
            'search_choice' => $request->search_choice,
            'search_type' => $request->search_type,
            'search_keyword' => $request->search_keyword,
        ];*/
        $sTable = PropertyImageModel::where('property_id',$id)->orderby('sort', 'asc')
            /*->when($like, function ($query) use ($like) {
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
            })*/
            ->get();
        return Datatables::of($sTable)
            ->addIndexColumn()
            ->addColumn('image', function ($row) {
                $data = "<img class='rounded-lg w-52' src='$row->image' class='img-thumbnail' id='preview'>";
                return $data;
            })
            ->addColumn('change_sort', function($row){
                $sorts = PropertyImageModel::where('property_id',$row->property_id)->orderby('sort','asc')->get();
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
            ->addColumn('created_at', function ($row) {
                $data = date('d/m/Y', strtotime('+543 Years', strtotime($row->created_at)));
                return $data;
            })
            ->addColumn('action', function ($row) {
                return "<a href='javascript:' class='btn btn-sm btn-danger' onclick='deleteItem($row->id)' title='Delete'><i class='far fa-trash-alt w-4 h-4 mr-1'></i> Delete</a>
            ";
            })
            ->rawColumns(['image','change_sort', 'created_at', 'action'])
        ->make(true);
    }

    public function changesort_modal(Request $request)
    {
        $data = PropertyImageModel::find($request->id);
        $checksort = PropertyImageModel::where('id','!=',$data->id)->where('property_id',$data->property_id)->where('sort',$request->sort)->first();
        if($checksort)
        {
            $new_sort = PropertyImageModel::where('property_id',$data->property_id)->where('sort',$request->sort)->first();
            $new_sort->sort = $data->sort;
            $new_sort->save();
        }
        $data->sort = $request->sort;
        $data->save();
    }

    public function destroy_modal($main,$id)
    {
        // $datas = NewsimageModel::find($id);
        // $query = NewsimageModel::destroy($datas->id);
        $datas = PropertyImageModel::find(explode(',', $id));
        if (@$datas) {
            foreach ($datas as $data) {
                PropertyImageModel::where('property_id',$data->property_id)->where('sort', '>', $data->sort)->decrement('sort');
                $query = PropertyImageModel::destroy($data->id);
            }
        }

        if (@$query) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

}
