{{-- مودال ساخت سریع کاربر --}}
<div class="modal fade" id="quickCreateUserModal" tabindex="-1" role="dialog" aria-labelledby="quickCreateUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickCreateUserModalLabel">ساخت کاربر جدید (فروشنده)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="بستن">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>نام <span class="text-danger">*</span></label>
                    <input type="text" class="form-control custom-radius input-border-focus" id="quick_name" placeholder="نام کاربر">
                    <span class="text-danger mt-1 d-block" id="quick_name_error"></span>
                </div>
                <div class="form-group mt-3">
                    <label>شماره موبایل <span class="text-danger">*</span></label>
                    <input type="text" class="form-control custom-radius input-border-focus" id="quick_mobile" placeholder="شماره موبایل">
                    <span class="text-danger mt-1 d-block" id="quick_mobile_error"></span>
                </div>
                <div class="form-group mt-3">
                    <label>رمز عبور <span class="text-danger">*</span></label>
                    <div class="search-container">
                        <input type="password" class="search-input" id="quick_password" placeholder="رمز عبور">
                        <button type="button" id="togglePassword" class="search-button">
                            <i class="fa fa-eye-slash" id="eyeIcon"></i>
                        </button>
                    </div>
                    <span class="text-danger mt-1 d-block" id="quick_password_error"></span>
                </div>
                <div id="quick_general_error" class="alert alert-danger mt-3 d-none"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-beta-outline" data-dismiss="modal">انصراف</button>
                <button type="button" class="btn btn-beta-solid" id="btn-submit-quick-user">
                    <span id="quick-user-btn-text">ساخت کاربر</span>
                    <span id="quick-user-spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
            </div>
        </div>
    </div>
</div>
