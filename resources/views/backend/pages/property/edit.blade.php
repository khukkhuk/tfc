<!DOCTYPE html>
<html lang="en" class="light">
<!-- BEGIN: Head -->

<head>
    <!-- BEGIN: CSS Assets-->
    @include('backend.layout.css')
    <!-- END: CSS Assets-->
</head>
<!-- END: Head -->
<style>
    table{
            /* scrollX: true; */
            border-collapse: collapse;
            width: 50%;
            padding: 2px;
        }
        th, td,tr {
            padding: 5px;
            text-align: center;
            /* border-bottom: 1px solid grey; */
        }
        
        tr:hover {background-color: #f5f6f8}
</style>
<body class="py-5">
    <!-- BEGIN: Mobile Menu -->
    @include('backend.layout.mobile-menu')
    <!-- END: Mobile Menu -->
    <div class="flex">
        <!-- BEGIN: Side Menu -->
        @include('backend.layout.side-menu')
        <!-- END: Side Menu -->



        <div class="content">
            <!-- BEGIN: Top Bar -->
            @include('backend.layout.topbar')
            <!-- END: Top Bar -->


            <!-- BEGIN: Content -->
            <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                <h2 class="text-lg font-medium mr-auto">
                    ฟอร์มข้อมูล
                </h2>
            </div>

            <form id="menuForm" method="post" action="" onsubmit="return check_add();" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="col-span-12 lg:col-span-6 2xl:col-span-6">
                        <div class="intro-y col-span-12 lg:col-span-6">
                            <!-- BEGIN: Form Layout -->
                            <div class="intro-y box p-5 place-content-center">
                                <div class="grid grid-cols-12  gap-9 mb-12">
                                    <div class="col-span-12 lg:col-span-12">
                                        <h6 class="mb-3"><span class="text-danger">*</span>รูปภาพปก</h6>
                                        <img src="@if(@$row->image == null) {{url("noimage.jpg")}} @else {{$row->image}} @endif" class="img-thumbnail" id="preview">
                                    </div>
                                    <div class="col-span-12 lg:col-span-12">
                                        <small class="help-block">* รองรับไฟล์ <strong class="text-danger">(jpg, jpeg, png)</strong> เท่านั้น</small>
                                        <div class="col-span-6 lg:col-span-6">
                                            <input type="file" class="custom-file-input block w-80 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" name="image" id="image">
                                            <!-- <label class="custom-file-label" for="image">Choose file</label> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-12 gap-6 mt-3 mb-3">
                                    <div class="col-span-12 lg:col-span-8">
                                        <label for="crud-form-1" class="form-label">Youtube link</label>
                                        <input class="form-control" id="youtube" type="text" name="youtube" placeholder="location" autocomplete="off" value="{{$row->youtube}}">
                                    </div>
                                </div>
                                <div class="grid grid-cols-12 gap-6 mt-3 mb-3">
                                    <div class="col-span-12 lg:col-span-8">
                                        <label for="crud-form-1" class="form-label"><span class="text-danger">*</span> Name Property(TH)</label>
                                        <input class="form-control" id="name_th" type="text" name="name_th" placeholder="ชื่อภาษาไทย" autocomplete="off" value="{{$row->name_th}}">
                                    </div>
                                </div>
                                <div class="grid grid-cols-12 gap-6 mt-3 mb-3">
                                    <div class="col-span-12 lg:col-span-8">
                                        <label for="crud-form-1" class="form-label"><span class="text-danger">*</span> Name Property(EN)</label>
                                        <input class="form-control" id="name_en" type="text" name="name_en" placeholder="ชื่อภาษาอังกฤษ" autocomplete="off" value="{{$row->name_en}}">
                                    </div>
                                </div>
                                <div class="grid grid-cols-12 gap-6 mt-3 mb-3">
                                    <div class="col-span-12 lg:col-span-8">
                                        <label for="crud-form-1" class="form-label"><span class="text-danger">*</span> Name Property(CN)</label>
                                        <input class="form-control" id="name_cn" type="text" name="name_cn" placeholder="ชื่อภาษาจีน" autocomplete="off" value="{{$row->name_cn}}">
                                    </div>
                                </div>
                                <div class="grid grid-cols-12 gap-6 mt-3 mb-3">
                                    <div class="col-span-12 lg:col-span-8">
                                        <label for="crud-form-1" class="form-label"><span class="text-danger">*</span> category</label>
                                        <select class="form-control" name="type" id="type">
                                            <option value="">Select Category</option>
                                            <option value="Condominium" @if($row->type == 'Condominium') selected @endif >Condominium</option>
                                            <option value="House" @if($row->type == 'House') selected @endif >House</option>
                                            <option value="Land" @if($row->type == 'Land') selected @endif >Land</option>
                                            <option value="Commercial" @if($row->type == 'Commercial') selected @endif >Commercial</option>
                                            <option value="Project" @if($row->type == 'Project') selected @endif >Project</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-12 gap-6 mt-3 mb-3">
                                    <div class="col-span-12 lg:col-span-8">
                                        <label for="crud-form-1" class="form-label"><span class="text-danger">*</span> Location Text</label>
                                        <input class="form-control" id="location" type="text" name="location" placeholder="location" autocomplete="off" value="{{$row->location}}">
                                    </div>
                                </div>
                                <div class="grid grid-cols-12 gap-6 mt-3 mb-3">
                                    <div class="col-span-12 lg:col-span-8">
                                        <label for="crud-form-1" class="form-label">Location Google Map Link</label>
                                        <input class="form-control" id="locationmap" type="text" name="locationmap" placeholder="google map" autocomplete="off" value="{{$row->location_map}}">
                                    </div>
                                </div>
                                <div class="grid grid-cols-12 gap-6 mt-3 mb-3">
                                    <div class="col-span-12 lg:col-span-6">
                                        <label for="crud-form-1" class="form-label">For Sale</label>
                                        <input class="form-control" id="sale" type="text" name="sale" autocomplete="off" value="{{$row->sale}}">
                                    </div>
                                    <div class="col-span-12 lg:col-span-6">
                                        <label for="crud-form-1" class="form-label">For Rent</label>
                                        <input class="form-control" id="rent" type="text" name="rent" autocomplete="off" value="{{$row->rent}}">
                                    </div>
                                </div>
                            </div>
                            <!-- END: Form Layout -->
                        </div>
                    </div>

                    <div class="col-span-12 lg:col-span-6 2xl:col-span-6">
                        <div class="intro-y col-span-12 lg:col-span-6">
                            <!-- BEGIN: Form Layout -->
                            <div class="intro-y box p-5 place-content-center">
                                <h3 class="text-lg">Information</h3>
                                
                                <div class="grid grid-cols-12 gap-6 mt-3 mb-3">
                                    <div class="col-span-12 lg:col-span-6">
                                        <label for="crud-form-1" class="form-label">bedrooms</label>
                                        <input class="form-control" id="bedroom" type="text" name="bedroom" autocomplete="off" value="{{$row->bedroom}}">
                                    </div>
                                    <div class="col-span-12 lg:col-span-6">
                                        <label for="crud-form-1" class="form-label">bathroom</label>
                                        <input class="form-control" id="bathroom" type="text" name="bathroom" autocomplete="off" value="{{$row->bathroom}}">
                                    </div>
                                </div>
                                <div class="grid grid-cols-12 gap-6 mb-3">
                                    <div class="col-span-12 lg:col-span-6">
                                        <label for="crud-form-1" class="form-label">area</label>
                                        <input class="form-control" id="area" type="text" name="area" autocomplete="off" value="{{$row->area}}">
                                    </div>
                                    <div class="col-span-12 lg:col-span-6">
                                        <label for="crud-form-1" class="form-label">floor</label>
                                        <input class="form-control" id="floor" type="text" name="floor" autocomplete="off" value="{{$row->floor}}">
                                    </div>
                                </div>
                                <div class="grid grid-cols-12 gap-6 mb-3">
                                    <div class="col-span-12 lg:col-span-6">
                                        <label for="crud-form-1" class="form-label">Living area</label>
                                        <input class="form-control" id="livingarea" type="text" name="livingarea" autocomplete="off" value="{{$row->living_area}}">
                                    </div>
                                    <div class="col-span-12 lg:col-span-6">
                                        <label for="crud-form-1" class="form-label">Land area</label>
                                        <input class="form-control" id="landarea" type="text" name="landarea" autocomplete="off" value="{{$row->land_area}}">
                                    </div>
                                </div>
                            </div>
                            <div class="intro-y box p-5 mt-4 place-content-center">
                                <h3 class="text-lg">Information more</h3>
                                <input type="hidden" id="count_row" name="count_row" value="{{$count_result}}">
                                <input type="hidden" id="delete_list" name="delete_list">
                                    <table class="border-collapse border mt-3 border-slate-300 w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead class="text-xs border-slate-700 text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" class="px-6 py-3">
                                                    th
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    en
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    ch
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    <span class="sr-only">Edit</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="datatable">
                                            @if(!empty($lists))
                                            @php $ii=1; @endphp
                                            @foreach($lists as $list)
                                            @php $i = $ii++; @endphp
                                            <tr id="row{{$i}}" class="bg-white border-b border-slate-700 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                <input type="hidden" name="list_id[]" id="list_id{{$i}}" value="{{$list->id}}">
                                                <input type="hidden" name="number[]" value="{{$i}}">
                                                <td class="px-6 py-4">
                                                    <input type="text" class="form-control" id="moreth{{$i}}" name="moreth[]" value="{{$list->list_th}}">
                                                </td>
                                                <td class="px-6 py-4">
                                                    <input type="text" class="form-control" id="moreen{{$i}}" name="moreen[]" value="{{$list->list_en}}">
                                                </td>
                                                <td class="px-6 py-4">
                                                    <input type="text" class="form-control" id="morecn{{$i}}" name="morecn[]" value="{{$list->list_cn}}">
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    <a class="font-medium text-red-600 dark:text-red-500 hover:underline" onclick="deleteRowId({{$i}},{{$list->id}})" >delete</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                        <tbody>
                                            <tr class="bg-white border-b border-slate-700 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                <td class="px-6 py-4">
                                                    <input type="text" class="form-control" id="addmoreth">
                                                </td>
                                                <td class="px-6 py-4">
                                                    <input type="text" class="form-control" id="addmoreen">
                                                </td>
                                                <td class="px-6 py-4">
                                                    <input type="text" class="form-control" id="addmorecn">
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    <a class="font-medium text-blue-600 dark:text-blue-500 hover:underline" onclick="addrow()" >add</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                            </div>
                            <!-- END: Form Layout -->
                        </div>
                    </div>

                    
                </div>
                <div class="text-center mt-5">
                    <a class="btn btn-outline-secondary w-24 mr-1" href="{{ url("$segment/$folder") }}">ยกเลิก</a>
                    <button type="submit" class="btn btn-primary w-24">บันทึกข้อมูล</button>
                </div>
            </form>

        </div>

    </div>

    <!-- BEGIN: JS Assets-->
    @include('backend.layout.script')

    <script>
        $("#image").on('change', function() {
            var $this = $(this)
            const input = $this[0];
            const fileName = $this.val().split("\\").pop();
            $this.siblings(".custom-file-label").addClass("selected").html(fileName)
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview').attr('src', e.target.result).fadeIn('fast');
                }
                reader.readAsDataURL(input.files[0]);
            }
        });

        function deleteRowId(r,id){
            check_id = $("#delete_list").val();
            console.log(check_id)
            if(check_id != ''){
                $("#delete_list").val(check_id+','+id);
            }else{
                $("#delete_list").val(id);
            }
            $("#row"+r).remove();
        }

        function addrow(){
            th = $("#addmoreth").val();
            en = $("#addmoreen").val();
            cn = $("#addmorecn").val();
            num = parseInt($("#count_row").val())+1;
            $("#count_row").val(num);
            $("#datatable").append('<tr id="row'+num+'" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">'+
            '<input type="hidden" name="number[]" value="'+num+'">'+
            '<td class="px-6 py-4"><input type="text" class="form-control" id="moreth'+num+'" name="moreth[]" value="'+th+'"></td>'+
            '<td class="px-6 py-4"><input type="text" class="form-control" id="moreen'+num+'" name="moreen[]" value="'+en+'"></td>'+
            '<td class="px-6 py-4"><input type="text" class="form-control" id="morecn'+num+'" name="morecn[]" value="'+cn+'"></td>'+
            '<td class="px-6 py-4"><a class="font-medium text-red-600 dark:text-red-500 hover:underline" onclick="deleterow('+num+')" >delete</a></td>'+
            '</tr>');

            $("#addmoreth").val(null);
            $("#addmoreen").val(null);
            $("#addmorecn").val(null);
        }

        function deleterow(n){
            $("#row"+n).remove();
        }


        /*function check_add() {
            var name = $('#name').val();
            if (name == "") {
                toastr.error('กรุณากรอกข้อมูลให้ครบถ้วนก่อนบันทึกรายการ');
                return false;
            }
        }*/

        $("#menuForm").bind('keyword',function(e){
            if(e.keycode == 13){
                e.preventDefult();
                return false;
            }
        });

       
    </script>
    <!-- END: JS Assets-->
</body>

</html>
