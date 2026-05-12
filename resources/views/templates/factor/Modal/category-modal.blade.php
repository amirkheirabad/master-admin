<div class="container">
    <!-- مدال -->
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title" id="myModalLabel"> ایجاد دسته بندی </h3>
                </div>

                <div class="modal-body">
                    <form method="post" action="{{ route('insert-category') }}" id="categoryForm">
                        @csrf
                        <label>نام دسته بندی</label>
                        <input
                            type="text"
                            name="name"
                            id="category_name"
                            class="form-control custom-radius">

                        <small class="text-danger" id="error_name"></small>
                    </form>
                </div>

                <div class="d-flex justify-content-center mt-8 mb-3 gap">
                    <button type="button" class="btn btn-beta-outline" data-dismiss="modal">بستن</button>
                    <button type="button" class="btn btn-beta-solid" onclick="submitCategory()">ذخیره</button>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- مدال -->
    <div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title" id="myModalLabel"> ویرایش دسته بندی </h3>
                </div>

                <div class="modal-body">
                    <form id="categoryForm2">
                        @csrf
                        <input type="hidden" id="edit_category_id">

                        <div class="form-group">
                            <label>نام دسته بندی</label>
                            <input type="text" name="name" id="edit_category_name" class="form-control custom-radius">
                            <small class="text-danger" id="error_name2"></small>
                        </div>

                        <div class="form-group">
                            <label>وضعیت نمایش:</label>
                            <div class="mt-2">
                                <div class="radio radio-lg">
                                    <label>
                                        <input type="radio" class="flat" name="active" value="1"> فعال
                                    </label>
                                    <label class="mr-1">
                                        <input type="radio" class="flat" name="active" value="0"> غیرفعال
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="d-flex justify-content-center mb-3" style="gap: 10px;">
                    <button type="button" class="btn btn-beta-outline" data-dismiss="modal">بستن</button>
                    <button type="button" class="btn btn-beta-solid" onclick="submitCategory2()">ویرایش</button>
                </div>

            </div>
        </div>
    </div>
</div>
