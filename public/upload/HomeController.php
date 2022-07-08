<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\HomeHeaderBanner;
use App\Model\HomeFooterBanner;
use App\Model\ConditionInstallment;
use App\Model\PriceInstallment;
use App\Model\Review;
use App\Model\Promotion;
use App\Model\PromotionBanner;
use App\Model\ProductInstallment;
use App\Model\ProductPrice;
use App\Model\CustomerInstallment;
use App\Model\ReviewBanner;
use App\Model\HowtoBanner;
use App\Model\Howto;
use App\Model\HowtoLink;
use App\Model\HowtoImage;
use App\Model\HowtoImageList;
use App\Model\Customer;
use App\Model\Bank;
use DB;
use Session;

use App\Model\Product;
use App\Model\ProductType;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $header = HomeHeaderBanner::all();
        $con_instalment = ConditionInstallment::all();
        $price = PriceInstallment::all();
        $product = Product::orderBy('pro_name', 'asc')->limit(8)->get();
        $products = Product::orderBy('pro_name', 'asc')->get();
        $type = ProductType::orderBy('pro_type_name', 'asc')->get();
        $review_slide = Review::orderBy('id', 'desc')->limit(4)->get();
        $review_slide2 = Review::orderBy('id', 'desc')->limit(4)->get();
        $data = array(
            'header'            =>$header,
            'con_instalment'    =>$con_instalment,
            'price'             =>$price,
            'product'           =>$product,
            'products'           =>$products,
            'type'              =>$type,
            'review_slide'      => $review_slide,
            'review_slide2'     => $review_slide2,
        );
        return view('frontend.pages.index',$data);
    }

    public function forgotpassword()
    {
        // dd('ok');
        return view('frontend.pages.forgot-password');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function get_district($id)
    {
        $district = DB::table('tb_amphurs')
            ->where('province_id', $id)
            ->select('*')
            ->get();
        $data = array(
            'district'     => $district,
        );
        echo json_encode($data);
    }

    public function get_sub_district($id)
    {
        $sub_district = DB::table('tb_districts')
            ->where('amphure_id', $id)
            ->select('*')
            ->get();
        $data = array(
            'sub_district'     => $sub_district,
        );
        echo json_encode($data);
    }

    public function get_zip_code($id)
    {
        $zip_code = DB::table('tb_districts')
            ->where('id', $id)
            ->select('zip_code')
            ->first();
        $data = array(
            'zip_code'     => $zip_code,
        );
        echo json_encode($data);
    }

    public function search_zip_code($id)
    {
        $sub_district = DB::table('tb_districts')
            ->where('zip_code', $id)
            ->select('*')
            ->get();
        $get_dis = DB::table('tb_districts')
            ->where('zip_code', $id)
            ->select('amphure_id')
            ->first();
        if ($get_dis !== null) {
            $district = DB::table('tb_amphurs')
                ->where('id', $get_dis->amphure_id)
                ->select('*')
                ->first();
            if ($district !== null) {
                $province = DB::table('tb_provinces')
                    ->where('id', $district->province_id)
                    ->select('*')
                    ->first();
            } else {
                $province = '';
            }
        } else {
            $district = '';
            $province = '';
        }
       
        $data = array(
            'sub_district'      => $sub_district,
            'district'          => $district,
            'province'          => $province,
        );
        echo json_encode($data);
    }

    public function search_capacity($id)
    {
        $capacity = DB::table('tb_product_prices as pro_pri')
            ->leftjoin('tb_product_capacities as cap', 'pro_pri.pro_pri_capacity', 'cap.id')
            ->where('pro_pri.pro_pri_product_id', $id)
            ->where('cap.pro_cap_status', '1')
            ->select('cap.*', 'pro_pri.id as pro_pri_id')
            ->get();
        $data = array(
            'capacity'  =>$capacity,
        );
        echo json_encode($data);

    }

    public function search_color($id)
    {
        $color = DB::table('tb_product_price_colors as pri_col')
            ->leftjoin('tb_product_colors as color', 'pri_col.color_id', 'color.id')
            ->where('pri_col.product_price_id', $id)
            ->select('color.*', 'pri_col.id as pro_pri_col_id')
            ->get();
        $ins = DB::table('tb_product_prices')
            ->where('id', $id)
            ->select('pro_pri_installment')
            ->first();
        $data = array(
            'color' =>$color,
            'ins' =>$ins,
        );
        echo json_encode($data);
        
    }

}
