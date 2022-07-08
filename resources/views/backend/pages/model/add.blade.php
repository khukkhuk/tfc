<!DOCTYPE html>
<html lang="en" class="light">
<!-- BEGIN: Head -->

<head>
    <!-- BEGIN: CSS Assets-->
    @include('backend.layout.css')
    <!-- END: CSS Assets-->
</head>
<!-- END: Head -->

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

            <form id="menuForm" method="post" action="" onsubmit="return check_add();">
                @csrf
                <input class="form-control" id="model_id" name="model_id" type="hidden" value="{{ @$row->id }}" placeholder="Code" autocomplete="off">
                <input class="form-control" id="product_id" name="product_id" type="hidden" value="{{ @$product->id }}" placeholder="Code" autocomplete="off">
                
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 lg:col-span-12">
                        <!-- BEGIN: Form Layout -->
                        <div class="intro-y box p-5">

                            <div class="grid grid-cols-12 gap-6 mt-5 mb-3">
                                <div class="col-span-12 lg:col-span-4">
                                    <label for="crud-form-1" class="form-label">Code</label>
                                    <input class="form-control" id="code" type="text" name="code" value="{{ @$data->code }}" placeholder="Code" autocomplete="off">
                                </div>
                            </div>

                            <div class="grid grid-cols-12 gap-6 mt-5 mb-3">
                                <div class="col-span-12 lg:col-span-6">
                                    <label for="crud-form-1" class="form-label">จุดตรวจเช็ค</label>
                                    <textarea name="check_point" id="check_point" class="form-control" cols="5" rows="5">{{ @$data->check_point }}</textarea>
                                </div>
                                <div class="col-span-12 lg:col-span-6">
                                    <label for="crud-form-1" class="form-label">รายการที่เสีย</label>
                                    <textarea name="broken_item" id="broken_item" class="form-control" cols="5" rows="5">{{ @$data->broken_item }}</textarea>
                                </div>
                            </div>

                            <div class="grid grid-cols-12 gap-6 mt-5 mb-3">
                                <div class="col-span-12 lg:col-span-6">
                                    <label for="crud-form-1" class="form-label">อัพโหลดรูปภาพ</label>
                                    <input type="file">
                                </div>
                            </div>

                            <div class="text-right mt-5">
                                <a class="btn btn-outline-secondary w-24 mr-1" href="{{ url("$segment/$folder/$row->id") }}">ยกเลิก</a>
                                <button type="submit" class="btn btn-primary w-24">บันทึกข้อมูล</button>
                            </div>
                        </div>
                        <!-- END: Form Layout -->
                    </div>
                </div>
            </form>
            <!-- END: Content -->


        </div>

    </div>

    <!-- BEGIN: JS Assets-->
    @include('backend.layout.script')

    <script>
        function check_add() {
            var code = $('#code').val();
            if (code == "") {
                toastr.error('กรุณากรอกข้อมูลให้ครบถ้วนก่อนบันทึกรายการ');
                return false;
            }
        }

        CKEDITOR.config.allowedContent = true;
        CKEDITOR.replace('check_point', {
            filebrowserUploadUrl: "{{route('upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form',
        });

        CKEDITOR.replace('broken_item', {
            filebrowserUploadUrl: "{{route('upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form',
        });
    </script>
    <!-- END: JS Assets-->
</body>

</html>
