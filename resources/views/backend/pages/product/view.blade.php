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
                    {{@$row->name}}
                </h2>
                <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                    <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#show_modal" onclick="show_modal({{$row->id}});" class="btn btn-primary shadow-md mr-2"><i class="fa fa-plus"></i>&nbsp; เพิ่มรายการรุ่นเครื่อง</a> 
                </div>
            </div>

            <div class="intro-y grid grid-cols-12 gap-5 mt-5">
                <div class="intro-y col-span-12 lg:col-span-8">
                    <div class="lg:flex intro-y">
                        <div class="relative">
                            <input type="text" class="form-control py-3 px-4 w-full lg:w-64 box pr-10" placeholder="Search item...">
                            <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0 text-slate-500" data-lucide="search"></i> 
                        </div>
                        <select class="form-select py-3 px-4 box w-full lg:w-auto mt-3 lg:mt-0 ml-auto">
                            <option>Sort By</option>
                            <option>A to Z</option>
                            <option>Z to A</option>
                            <option>Lowest Price</option>
                            <option>Highest Price</option>
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-12 gap-5 mt-5">
                        @if(@$data)
                            @php $i=0; @endphp
                            @foreach(@$data as $d)
                            @php $i++; @endphp
                            <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in">
                                <a href="{{url("$segment/model/$d->id")}}">
                                    <div class="font-medium text-base">
                                        {{@$i.'.'.$d->name}}
                                    </div>
                                    @php
                                    $modellist = \App\Models\Backend\ModellistModel::where('model_id',$d->id)->count();
                                    @endphp
                                    <div class="text-slate-500">{{@$modellist}} Items</div>
                                </a>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="col-span-12 lg:col-span-4">
                    <div class="intro-y pr-1">
                        <div class="box p-2">
                            <ul class="nav nav-pills" role="tablist">
                                <li id="details-tab" class="nav-item flex-1" role="presentation">
                                    <button class="nav-link w-full py-2 active" data-tw-toggle="pill" data-tw-target="#details" type="button" role="tab" aria-controls="details" aria-selected="true" > Details </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div id="details" class="tab-pane active" role="tabpanel" aria-labelledby="details-tab">
                            <div class="box p-5 mt-5">
                                <div class="flex items-center border-b border-slate-200 dark:border-darkmode-400 py-5">
                                    <div>
                                        <div class="text-slate-500">ชื่อสินค้า</div>
                                        <div class="mt-1">{{@$row->name}}</div>
                                    </div>
                                    <i data-lucide="user" class="w-4 h-4 text-slate-500 ml-auto"></i> 
                                </div>
                                <div class="flex items-center border-b border-slate-200 dark:border-darkmode-400 py-5">
                                    <div>
                                        <div class="text-slate-500">วันที่สร้าง</div>
                                        <div class="mt-1">{{date('d/m/Y',strtotime($row->created_at))}}</div>
                                    </div>
                                    <i data-lucide="clock" class="w-4 h-4 text-slate-500 ml-auto"></i> 
                                </div>
                                <div class="flex items-center border-b border-slate-200 dark:border-darkmode-400 py-5">
                                    <div>
                                        <div class="text-slate-500">จำนวนรายการรุ่น</div>
                                        <div class="mt-1">{{@$count_model}}</div>
                                    </div>
                                    <i data-lucide="users" class="w-4 h-4 text-slate-500 ml-auto"></i> 
                                </div>
                                <div class="flex items-center pt-5">
                                    <div>
                                        <div class="text-slate-500">จำนวนรายการ Code</div>
                                        <div class="mt-1">{{@$count_model_list}}</div>
                                    </div>
                                    <i data-lucide="mic" class="w-4 h-4 text-slate-500 ml-auto"></i> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Start Modal -->
    <div id="show_modal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-body p-10 text-center"> </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->


    <!-- BEGIN: JS Assets-->
    @include('backend.layout.script')

    <script>
        var fullUrl = window.location.origin + window.location.pathname;
        function show_modal(id) {
            $.ajax({
                type: 'GET',
                url: fullUrl + '/show_modal',
                data: {
                    _token: "{{ csrf_token() }}",
                    id:id,
                },
                dataType: 'html',
                success: function(data) {
                    // Add response in Modal body
                    $('.modal-content').html(data);
                    // $('#show_modal').modal('show');
                }
            });
        }
    </script>
    <!-- END: JS Assets-->

    

</body>

</html>
