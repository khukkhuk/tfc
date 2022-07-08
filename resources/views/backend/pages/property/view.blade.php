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
                    <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#show_modal" onclick="show_modal({{$row->id}});" class="btn btn-primary shadow-md mr-2">
                        <i class="fa fa-plus"></i>&nbsp; เพิ่มรูปภาพเพิ่มเติม
                    </a> 
                </div>
            </div>

            <div class="intro-y grid grid-cols-12 gap-5 mt-5">
                <div class="intro-y col-span-12 box p-5 place-content-center mt-3 lg:col-span-8">
                    <!-- <div class="grid grid-cols-12 gap-5 mt-5"> -->
                        <div id="datatable_01" class="mt-5 table-report table-report--tabulator"></div>
                    <!-- </div> -->
                </div>
                <div class="col-span-12 lg:col-span-4">
                    <!-- <div class="intro-y pr-1">
                        <div class="box p-2">
                            <ul class="nav nav-pills" role="tablist">
                                <li id="details-tab" class="nav-item flex-1" role="presentation">
                                    <button class="nav-link w-full py-2 active" data-tw-toggle="pill" data-tw-target="#details" type="button" role="tab" aria-controls="details" aria-selected="true" > Details </button>
                                </li>
                            </ul>
                        </div>
                    </div> -->
                    <div class="tab-content">
                        <div id="details" class="tab-pane active" role="tabpanel" aria-labelledby="details-tab">
                            <div class="box p-5 mt-5">
                                <div class="flex items-center border-b border-slate-200 dark:border-darkmode-400 py-5">
                                    <div>
                                        <div class="text-slate-500">ชื่ออสังหา</div>
                                        <div class="mt-1">{{@$row->name_th}}</div>
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
                                        <div class="text-slate-500">ประเภทที่พักอาศัย</div>
                                        <div class="mt-1">{{@$row->type}}</div>
                                    </div>
                                    <i data-lucide="users" class="w-4 h-4 text-slate-500 ml-auto"></i> 
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
    <script>
        var fullUrl = window.location.origin + window.location.pathname;
        console.log(fullUrl);

        getdatatable();
        
        function getdatatable(){
            new Tabulator("#datatable_01", {
                height:"100%",
                ajaxURL: fullUrl + "/datatable",
                ajaxConfig:"POST", //ajax HTTP request type
                ajaxFiltering:true,
                ajaxParams : {
                    _token: "{{ csrf_token() }}",
                    search_choice: $('#search_choice').val(),
                    search_type: $('#search_type').val(),
                    search_keyword: $('#search_keyword').val(),
                },
                paginationSize: 10,
                paginationSizeSelector: [10, 20, 30, 40],
                layout: "fitColumns",
                responsiveLayout: "collapse",
                progressiveLoad:"scroll",
                placeholder:"<br/><center><b>ไม่พบข้อมูล</b></center>",
                columns:[
                    {formatter:"responsiveCollapse",width:40,   minWidth:30,    hozAlign:"center",  resizable:false,    headerSort:false},
                    {field: 'DT_RowIndex',    title :'<center>#</center>',   width:'5%', hozAlign: "center", vertAlign:"middle"}, // 0
                    {field: 'image',   title :'<center>ชื่อ</center>', minWidth: 150, vertAlign:"middle" , formatter:"html",  width:'45%',responsive:1}, // 1
                    // {field: 'created_at',    title :'<center>วันที่สร้าง</center>', formatter:"html", vertAlign:"middle", hozAlign: "center", width:'20%',responsive:2}, // 2
                    {field: 'change_sort',    title :'<center>sort</center>', formatter:"html", vertAlign:"middle",  hozAlign: "center",    width:'25%'}, // 3
                    {field: 'action',    title :'<center>จัดการ</center>', formatter:"html", vertAlign:"middle",  hozAlign: "center",    width:'20%'}, // 4
                ],
            
            });
        }



        
        function search_datatable()
        {
            getdatatable();
        }
        function reset_datatable()
        {
            $('#search_choice').val(null),
            $('#search_type').val("like"),
            $('#search_keyword').val(null),
            getdatatable();
        }
        
        function status(ids) {
            const $this = $(this),
                id = ids;
            $.ajax({
                type: 'get',
                url: fullUrl + '/status/' + id,
                success: function(res) {
                    if (res == false) {
                        $(this).prop('checked', false)
                    }
                }
            });
        }
       
        function deleteItem(ids) {
            const id = [ids];
            if (id.length > 0) {
                destroy(id)
            }
        }

        function destroy(id) {
            Swal.fire({
                title: "ลบข้อมูล",
                text: "คุณต้องการลบข้อมูลใช่หรือไม่?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch(fullUrl + '/destroy/' + id)
                        .then(response => response.json())
                        .then(data => location.reload())
                        .catch(error => {
                            Swal.showValidationMessage(`Request failed: ${error}`)
                        })
                }
            });
        }

        function changesort(id)
        {
            var sort = $('#sort_'+id).val();
            $.ajax({
                type: "post",
                url: fullUrl+"/changesort",
                data:{
                    _token: "{{ csrf_token() }}",
                    sort:sort,
                    id:id
                },
                success:function(data)
                {
                    location.reload();
                }
            });
        }
       
    </script>
    <!-- END: JS Assets-->

    

</body>

</html>
