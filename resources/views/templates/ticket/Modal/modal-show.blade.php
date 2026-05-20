

<!-- مودال تغییر وضعیت -->
<div id="statusChangeModal" class="mobile-status-modal">
    <div class="mobile-status-overlay"></div>
    <div class="mobile-status-sheet">
        <div class="mobile-status-header">
            <h3 class="mobile-status-title">تغییر وضعیت</h3>
            <button class="mobile-status-close" id="closeModalBtn">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <div class="mobile-status-content">
            <div class="status-option" data-status="0">
                <span>در حال بررسی توسط ایندکس</span>
                <i class="fa fa-check check-icon"></i>
            </div>
            <div class="status-option" data-status="1">
                <span>منتظر پاسخ فروشگاه</span>
                <i class="fa fa-check check-icon"></i>
            </div>
            @if(auth()->user()->hasanyRole('admin'))
            <div class="status-option" data-status="2">
                <span>بسته شده</span>
                <i class="fa fa-check check-icon"></i>
            </div>
            <div class="status-option" data-status="3">
                <span>ارجاع به واحد فنی</span>
                <i class="fa fa-check check-icon"></i>
            </div>
            @endif
        </div>

        <div class="mobile-status-footer">
            <button class="btn btn-beta-solid" id="applyBtn">اعمال</button>
            <button class="btn btn-beta-outline" id="cancelBtn">انصراف</button>
        </div>
    </div>
</div>




<!-- مدال -->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="myModalLabel"> تغییر وضعیت <span id="store_name"></span></h4>
            </div>

            <div class="modal-body">
                <h4>آیا از تغییر وضعیت تیکت شماره مطمئن هستید</h4>
                <input type="hidden" id="ticket_id">
                <input type="hidden" id="new_status">
                <input type="hidden" id="status_text">
                <div class="mt-8 d-flex justify-content-center">
                    <button type="button" class="btn btn-beta-solid" onclick="confirmStatusChange()">تایید</button>
                    <button type="button" class="btn btn-beta-outline" data-dismiss="modal">انصراف</button>
                </div>

            </div>

        </div>
    </div>
</div>
