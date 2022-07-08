<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Backend\ProductModel;
use App\Models\Backend\ModelModel;
use App\Models\Backend\ModellistModel;
use App\Models\Backend\GalleryModel;

class HomeController extends Controller
{
    protected $prefix = 'backend';
    protected $segment = 'webpanel';
    protected $controller = 'home';
    protected $folder = 'home';

    public function index(Request $request)
    {
        $navs = [
            '0' => ['url' => "$this->segment", 'name' => "Dashboard", "last" => 0],
        ];
        return view("$this->prefix.pages.$this->folder.index", [
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'segment' => $this->segment,
            'navs' => $navs,
            'products' => ProductModel::where('status','on')->get(),
        ]);
    }

    public function get_product(Request $request)
    {
        $id = $request->product_id;
        $row = ModelModel::where('product_id',$id)->get();
        $json_result = [];
        if($row)
        {
            foreach($row as $r)
            {
                $json_result[] = [
                    'id'=> $r->id,
                    'name'=> $r->name,
                ];
            }
            echo json_encode($json_result);
        }
    }

    public function get_model(Request $request)
    {
        $id = $request->product_id;
        $model_id = $request->model_id;
        $row = ModellistModel::where('model_id',$model_id)->get();
        $json_result = [];
        if($row)
        {
            foreach($row as $r)
            {
                $json_result[] = [
                    'id'=> $r->id,
                    'name'=> $r->code,
                ];
            }
            echo json_encode($json_result);
        }
    }

    public function search_value(Request $request)
    {
        $product_id = $request->product_id;
        $model_id = $request->model_id;
        $model_list_id = $request->model_list_id;
        return view("$this->prefix.pages.$this->folder.search", [
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'segment' => $this->segment,
            'product' => ProductModel::find($product_id),
            'model' => ModelModel::find($model_id),
            'model_list' => ModellistModel::find($model_list_id),
            'gallerys' => GalleryModel::where(['_id'=>$model_list_id, 'type'=>'model_list'])->get(),
        ]);
    }
    
    
    public static function uploadimage_text(Request $request)
    {

        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;

            $request->file('upload')->move(public_path('uploads/texteditor/'), $fileName);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('uploads/texteditor/' . $fileName);
            $msg = "อัพโหลดรูปภาพสำเร็จ";
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }
}
