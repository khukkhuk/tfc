<div class="modal-header">
    <h2 class="font-medium text-base mr-auto">
        <b>{{ @$data->name }}</b>
    </h2>
</div>

<div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
    <div class="col-span-12">
        <form id="menuForm" method="post" action="{{@$link_action}}" onsubmit="return check_add_modal();" enctype="multipart/form-data">
        @csrf
            <input type="hidden" id="property_id" name="property_id" value="{{@$data->id}}">
            <div class="col-span-12">
                <label for="pos-form-5" class="form-label">รายการรุ่นเครื่อง</label>
                
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300" for="file_input">Upload file</label>
                <input class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="file_input" name="image[]" type="file" multiple>
            </div>
            <div class="col-span-12 text-right mt-3">
                <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">ยกเลิก</button>
                <button type="submit" class="btn btn-primary w-24">บันทึกข้อมูล</button>
            </div>
        </form>
    </div>
</div>

<script>
    function check_add_modal() {
        var name_modal = $('#file_input').val();
        if (name_modal == "") {
            toastr.error('กรุณากรอกข้อมูลให้ครบถ้วนก่อนบันทึกรายการ');
            return false;
        }
    }
</script>