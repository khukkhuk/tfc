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

            <div class="grid grid-cols-12 gap-6 mt-5">
                <div class="intro-y col-span-12 lg:col-span-12">
                    <!-- BEGIN: Form Layout -->
                    <div class="intro-y box p-5">

                        <div class="grid grid-cols-12 gap-6 mt-5 mb-3">
                            <div class="col-span-12 lg:col-span-12">
                                <b>อัพโหลดอัลบั้มรูปภาพ</b>
                                <p class="card-title-desc">เลือกรูปภาพที่ต้องการลง นำมาลากวางใส่ไว้หรือเลือกจากโฟเดอร์<br>
                                    <small style="color:red;">* ขนาดรูปภาพแนะนำ 1200x800 pixel</small></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-12 gap-6 mt-5 mb-3">
                            <div class="col-span-12 lg:col-span-12">
                                <form action="{{url("$segment/$folder/temp_gallery/$data->id")}}" class="dropzone">
                                @csrf
                                    <div class="fallback"> <input name="file" type="file" multiple /> </div>
                                    <div class="dz-message" data-dz-message>
                                        <div class="text-lg font-medium">วางไฟล์ที่นี่หรือคลิกเพื่ออัปโหลด</div>
                                        <div class="text-slate-500"> โปรดกรุณาอัพไฟล์ที่มีนามสกุลเป็นรูปภาพเท่านั้น !</div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                       
                    </div>
                    <!-- END: Form Layout -->
                </div>
            </div>

            <div class="grid grid-cols-12 gap-6 mt-5">
                <div class="intro-y col-span-12 lg:col-span-12">
                    <!-- BEGIN: Form Layout -->
                    <div class="intro-y box p-5">

                        <div class="grid grid-cols-12 gap-6 mt-5 mb-3">
                            <div class="col-span-12 lg:col-span-4">
                                <b>อัลบั้มรูปภาพ</b>
                            </div>
                        </div>
                   
                        <div class="grid grid-cols-12 gap-5 mt-5 pt-5 border-t">
                            @if($gallerys)
                                @foreach($gallerys as $g)
                                <a href="javascript:;" id="gallery{{$g->id}}" class="intro-y block col-span-12 sm:col-span-4 2xl:col-span-3">
                                    <div class="box rounded-md p-3 relative zoom-in">
                                        <div class="flex-none relative block before:block before:w-full before:pt-[100%]">
                                            <div class="absolute top-0 left-0 w-full h-full image-fit">
                                                <img alt="{{$data->name}}" data-action="zoom" class="w-full rounded-md" src="{{$g->image}}">
                                            </div>
                                        </div>
                                        <div class="block font-medium text-center truncate mt-3">
                                            <button type="button" class="btn btn-sm btn-danger deleteGallery" data-id="{{$g->id}}"><i data-lucide="trash"></i></button>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            @endif

                        </div>
                       
                    </div>
                    <!-- END: Form Layout -->
                </div>
            </div>


        </div>

    </div>

    <!-- BEGIN: JS Assets-->
    @include('backend.layout.script')

    <script>

        $('.deleteGallery').click(function() {
            const id = [$(this).data('id')],
                row = $(this).data('row');
            deleteGallery(id, row);

        });

        function deleteGallery(id, row)
        {
            Swal.fire({

                title: "ยืนยันลบ",
                text: "คุณแน่ใจใช่หรือไม่?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch('webpanel/model/gallery/destroy?id=' + id)
                        .then(response => response.json())
                        .then(data => {
                            $.each(id, function(i, v) {
                                $('#gallery' + v).remove()
                            })
                        })
                        .catch(error => {
                            Swal.showValidationMessage(`Request failed: ${error}`)
                        })
                }
            });
        }

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
