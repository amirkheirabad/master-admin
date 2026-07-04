<div class="container">
    <!-- مدال -->
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title" id="myModalLabel"> چک لیست ها </h3>
                </div>

                <div class="modal-body">
                    <form method="post" action="{{ route('update_check_list_store') }}" id="checkListForm">
                        @csrf
                        <input type="hidden" name="store_id" id="store_id">
                        @foreach($checkLists as $checkList)
                            <div class="border rounded p-3 d-flex justify-content-between align-items-center">

                                <h4 class="mb-0 fw-bold">
                                    {{ $checkList->title }}
                                </h4>

                                <input
                                    class="form-check-input m-0"
                                    type="checkbox"
                                    name="check_lists[]"
                                    value="{{ $checkList->id }}"
                                    id="checklist_{{ $checkList->id }}"
                                >
                            </div>
                        @endforeach

                        <div class="d-flex justify-content-center mt-8 mb-3 gap">
                            <button type="button" class="btn btn-beta-outline" data-dismiss="modal">بستن</button>
                            <button type="submit" class="btn btn-beta-solid">ذخیره</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
