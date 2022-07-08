<!DOCTYPE html>
<html lang="en" class="light">
    <!-- BEGIN: Head -->
    <head>

        <!-- BEGIN: CSS Assets-->
        @include("backend.layout.css")
        <!-- END: CSS Assets-->
    </head>
    <!-- END: Head -->
    <body class="py-5">
        <!-- BEGIN: Mobile Menu -->
        @include("backend.layout.mobile-menu")
        <!-- END: Mobile Menu -->
        <div class="flex">
            <!-- BEGIN: Side Menu -->
            @include("backend.layout.side-menu")
            <!-- END: Side Menu -->


            <!-- BEGIN: Content -->
            <div class="content">
                <!-- BEGIN: Top Bar -->
                @include("backend.layout.topbar")
                <!-- END: Top Bar -->


                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-12 mt-8">
                        <div class="intro-y flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">
                                Dashboard
                            </h2>
                        </div>
                    </div>
                </div>


                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 lg:col-span-6">
                        <!-- BEGIN: Form Layout -->
                        <div class="intro-y box p-5">

                            <div class="grid grid-cols-12 gap-6 mt-5 mb-3">

                                <div class="col-span-12 lg:col-span-6">
                                    <label for="crud-form-1" class="form-label">เลือกข้อมูลสินค้า</label>
                                    <select name="product_id" id="product_id" class="form-control" onchange="select_product();">
                                        <option value="">กรุณาเลือกข้อมูลสินค้า</option>
                                        @if($products)
                                            @foreach($products as $pro)
                                            <option value="{{$pro->id}}">{{$pro->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small id="show_product_id" name="show_product_id" style="color:red;" hidden><i class="uil uil-exclamation-octagon font-size-16 text-danger me-2"></i> กรุณาเลือกข้อมูล</small>
                                </div>
                                <div class="col-span-12 lg:col-span-6">
                                    <label for="crud-form-1" class="form-label">เลือกรายการรุ่นเครื่อง</label>
                                    <select name="model_id" id="model_id" class="form-control" onchange="select_model();">
                                        <option value="">กรุณาเลือกข้อมูลสินค้า</option>
                                    </select>
                                    <small id="show_model_id" name="show_model_id" style="color:red;" hidden><i class="uil uil-exclamation-octagon font-size-16 text-danger me-2"></i> กรุณาเลือกข้อมูล</small>
                                </div>
                                <div class="col-span-12 lg:col-span-6">
                                    <label for="crud-form-1" class="form-label">เลือกรหัสโค้ด</label>
                                    <select name="model_list_id" id="model_list_id" class="form-control" >
                                        <option value="">กรุณาเลือกข้อมูลสินค้า</option>
                                    </select>
                                    <small id="show_model_list_id" name="show_model_list_id" style="color:red;" hidden><i class="uil uil-exclamation-octagon font-size-16 text-danger me-2"></i> กรุณาเลือกข้อมูล</small>
                                </div>
                            </div>

                            <div class="text-right mt-5">
                                <a class="btn btn-outline-secondary w-24 mr-1" href="{{ url("$segment") }}">ยกเลิก</a>
                                <button type="button" onclick="show_info();" class="btn btn-primary w-24">ค้นหาข้อมูล</button>
                            </div>
                        </div>
                        <!-- END: Form Layout -->
                    </div>
                </div>

                <div class="show_value"></div>
            </div>
            <!-- END: Content -->
        </div>
       
        <!-- BEGIN: JS Assets-->
        @include("backend.layout.script")
        <script>
            var fullUrl = window.location.origin + window.location.pathname;
            function select_product()
            {
                var product_id = $('#product_id').val();
                if(product_id != null)
                {
                    $.ajax({
                        url: fullUrl+'/ajax/getproduct',
                        type: 'post',
                        data: {
                            _token: "{{ csrf_token() }}",
                            product_id: product_id,
                        },
                        dataType: 'json',
                        success: function(data) {
                            // == Reset
                            $("#model_id").text("");
                            $("#model_id").append("<option value=''>กรุณาเลือกรายการรุ่นเครื่อง</option>");

                            $("#model_list_id").text("");
                            $("#model_list_id").append("<option value=''  selected>เลือกรายการรุ่นเครื่อง</option>");

                            // ===

                            $.each(data, function(index, value) {
                                $("#model_id").append("<option value='" + value.id + "'>" + value.name +"</option>");
                            });
                        }
                    });
                }
                else
                {
                    $("#model_id").text("");
                    $("#model_id").append("<option value=''  selected>กรุณาเลือกสินค้า</option>");

                    $("#model_list_id").text("");
                    $("#model_list_id").append("<option value=''  selected>เลือกรายการรุ่นเครื่อง</option>");

                }
                
            }

            function select_model()
            {
                var product_id = $('#product_id').val();
                var model_id = $('#model_id').val();
                if(product_id != null)
                {
                    $.ajax({
                        url: fullUrl+'/ajax/getmodel',
                        type: 'post',
                        data: {
                            _token: "{{ csrf_token() }}",
                            product_id: product_id,
                            model_id: model_id,
                        },
                        dataType: 'json',
                        success: function(data) {
                            // == Reset
                            $("#model_list_id").text("");
                            $("#model_list_id").append("<option value='' selected>เลือกรายการรุ่นเครื่อง</option>");

                            // ===

                            $.each(data, function(index, value) {
                                $("#model_list_id").append("<option value='" + value.id + "'>" + value.name +"</option>");
                            });
                        }
                    });
                }
                else
                {
                    $("#model_list_id").text("");
                    $("#model_list_id").append("<option value='' selected>เลือกรายการรุ่นเครื่อง</option>");

                }
                
            }

            function show_info()
            {
                var product_id = $('#product_id').val();
                var model_id = $('#model_id').val();
                var model_list_id = $('#model_list_id').val();

                if(product_id == ""){ $('#show_product_id').attr('hidden', false); }else { $('#show_product_id').attr('hidden', true); }
                if(model_id == ""){ $('#show_model_id').attr('hidden', false); }else { $('#show_model_id').attr('hidden', true); }
                if(model_list_id == ""){ $('#show_model_list_id').attr('hidden', false); }else { $('#show_model_list_id').attr('hidden', true); }
                if(product_id == "" || model_id == "" || model_list_id == "")
                {
                    toastr.error("กรุณาเลือกข้อมูลให้ครบถ้วน");
                    return false;
                }
                else
                {
                    $.ajax({
                        url: fullUrl+'/ajax/search_value',
                        type: 'post',
                        data: {
                            _token: "{{ csrf_token() }}",
                            product_id: product_id,
                            model_id: model_id,
                            model_list_id: model_list_id,
                        },
                        dataType: 'html',
                        success: function(data) {
                            $('.show_value').html(data);
                        }
                    });
                   
                }
            }
            
        </script>
        <!-- END: JS Assets-->
    </body>
</html>