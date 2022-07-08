<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <!-- BEGIN: Form Layout -->
        <div class="intro-y box p-5">

            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">
                    <b>ผลลัพธ์การค้นหาข้อมูล</b>
                </h2>
            </div>

            <div class="grid grid-cols-12 gap-6 mt-5 mb-3">
                <div class="col-span-12 lg:col-span-4">
                    <b>ชื่อสินค้า</b> <br/> <span>{{@$product->name}}</span>
                </div>
                <div class="col-span-12 lg:col-span-4">
                    <b>ชื่อรุ่นเครื่อง</b> <br/> <span>{{@$model->name}}</span>
                </div>
                <div class="col-span-12 lg:col-span-4">
                    <b>รหัสโค้ด</b> <br/> <span>{{@$model_list->code}}</span>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-6 mt-5 mb-3">
                <div class="col-span-12 lg:col-span-12">
                    <b>จุดตรวจเช็ค</b> <br/> <span>{!!@$model_list->check_point!!}</span>
                </div>
                <div class="col-span-12 lg:col-span-12">
                    <b>รายการที่เสีย</b> <br/> <span>{!!@$model_list->broken_item!!}</span>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-5 mt-5 pt-5 border-t">
                @if($gallerys)
                    @foreach($gallerys as $g)
                    <a href="javascript:;" class="intro-y block col-span-12 sm:col-span-4 2xl:col-span-3">
                        <div class="box rounded-md p-3 relative zoom-in">
                            <div class="flex-none relative block before:block before:w-full before:pt-[100%]">
                                <div class="absolute top-0 left-0 w-full h-full image-fit">
                                    <img alt="{{$model_list->code}}" data-action="zoom" class="w-full rounded-md" src="{{@$g->image}}">
                                </div>
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