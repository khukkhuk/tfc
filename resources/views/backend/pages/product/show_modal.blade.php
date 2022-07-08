<div class="modal-header">
    <h2 class="font-medium text-base mr-auto">
        <b>{{ @$data->name }}</b>
    </h2>
</div>

<div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
    <div class="col-span-12">
        <form id="menuForm" method="post" action="{{@$link_action}}" onsubmit="return check_add_modal();">
        @csrf
            <input type="hidden" id="product_id" name="product_id" value="{{@$data->id}}">
            <div class="col-span-12">
                <label for="pos-form-5" class="form-label">รายการรุ่นเครื่อง</label>
                <input class="form-control" type="text" id="name_modal" name="name_modal" value="{{ @$row->name }}" placeholder="ชื่อ" autocomplete="off">
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
        var name_modal = $('#name_modal').val();
        if (name_modal == "") {
            toastr.error('กรุณากรอกข้อมูลให้ครบถ้วนก่อนบันทึกรายการ');
            return false;
        }
    }
</script>