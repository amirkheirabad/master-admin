<style>
    #myModal .checklist-modal-dialog { width: min(760px, calc(100% - 30px)); }
    #myModal .modal-content { border: 0; border-radius: 14px; box-shadow: 0 18px 55px rgba(14, 45, 85, .18); overflow: hidden; }
    #myModal .modal-header { padding: 20px 24px; border-bottom: 1px solid #edf0f4; }
    #myModal .modal-title { color: #0e2d55; font-size: 20px; font-weight: 700; line-height: 1.5; }
    #myModal #store_name { color: #5a6d82; font-size: 15px; font-weight: 500; }
    #myModal .close { margin-top: 2px; color: #5a6d82; opacity: .75; }
    #myModal .modal-body { max-height: calc(100vh - 110px); padding: 24px; overflow-y: auto; }
    #myModal .checklist-help { margin-bottom: 14px; color: #73879c; font-size: 13px; }
    #myModal .checklist-items { display: grid; gap: 10px; }
    #myModal .checklist-row { border: 1px solid #dfe5ec; border-radius: 10px; background: #fff; cursor: pointer; transition: border-color .16s ease, background-color .16s ease, box-shadow .16s ease; }
    #myModal .checklist-row:hover { border-color: #9fb2c9; box-shadow: 0 3px 12px rgba(19, 60, 109, .07); }
    #myModal .checklist-row:focus { outline: 2px solid rgba(19, 60, 109, .28); outline-offset: 2px; }
    #myModal .checklist-row.is-selected { border-color: #133c6d; background: #f3f7fc; box-shadow: 0 0 0 1px rgba(19, 60, 109, .08); }
    #myModal .checklist-row-summary { display: flex; align-items: center; min-height: 54px; padding: 10px 14px; gap: 12px; }
    #myModal .checklist-checkbox { width: 18px; height: 18px; margin: 0; accent-color: #133c6d; cursor: pointer; flex: 0 0 auto; }
    #myModal .checklist-title { margin: 0; color: #30465f; font-size: 15px; font-weight: 600; line-height: 1.6; cursor: pointer; flex: 1 1 auto; }
    #myModal .checklist-comment-toggle { min-width: 38px; min-height: 34px; padding: 6px 10px; border: 1px solid #d9e1ea; border-radius: 8px; background: #fff; color: #5a6d82; cursor: pointer; transition: color .16s ease, border-color .16s ease, background-color .16s ease; }
    #myModal .checklist-comment-toggle:hover,
    #myModal .checklist-comment-toggle:focus,
    #myModal .checklist-comment-toggle.is-open { border-color: #133c6d; background: #edf4fb; color: #133c6d; outline: none; }
    #myModal .checklist-comment-toggle.has-comment::after { content: ''; display: inline-block; width: 6px; height: 6px; margin-right: 5px; border-radius: 50%; background: #133c6d; vertical-align: middle; }
    #myModal .checklist-comment-panel { max-height: 0; padding: 0 14px; opacity: 0; overflow: hidden; visibility: hidden; cursor: default; transition: max-height .2s ease, padding .2s ease, opacity .15s ease, visibility 0s linear .2s; }
    #myModal .checklist-comment-panel.is-open { max-height: 160px; padding: 0 14px 14px; opacity: 1; visibility: visible; transition-delay: 0s; }
    #myModal .checklist-field-label { display: block; margin-bottom: 7px; color: #30465f; font-size: 13px; font-weight: 600; }
    #myModal .checklist-textarea { width: 100%; min-height: 70px; height: auto; padding: 10px 12px; border: 1px solid #d9e1ea; border-radius: 8px; resize: vertical; line-height: 1.7; }
    #myModal .checklist-textarea:focus { border-color: #133c6d; box-shadow: 0 0 0 3px rgba(19, 60, 109, .08); }
    #myModal .checklist-status { margin-bottom: 12px; padding: 9px 12px; border-radius: 8px; background: #f3f7fc; color: #133c6d; font-size: 13px; }
    #myModal .checklist-status.is-error { background: #fff1f0; color: #a94442; }
    #myModal .checklist-status[hidden] { display: none; }
    #myModal .checklist-actions { display: flex; justify-content: flex-end; margin-top: 22px; padding-top: 18px; border-top: 1px solid #edf0f4; gap: 10px; }
    #myModal #checkListForm.is-loading .checklist-items { opacity: .55; pointer-events: none; }

    @media (max-width: 576px) {
        #myModal .checklist-modal-dialog { width: auto; margin: 10px; }
        #myModal .modal-header, #myModal .modal-body { padding: 18px; }
        #myModal .checklist-row-summary { padding: 10px; gap: 9px; }
        #myModal .checklist-title { font-size: 14px; }
        #myModal .checklist-actions .btn { flex: 1 1 0; }
    }
</style>

<div class="container">
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog checklist-modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="بستن"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title" id="myModalLabel">چک‌لیست فروشگاه <span id="store_name"></span></h3>
                </div>

                <div class="modal-body">
                    <form method="post" action="{{ route('update_check_list_store') }}" id="checkListForm"
                          data-has-errors="{{ $errors->hasAny(['store_id', 'check_lists', 'check_lists.*', 'comments', 'comments.*']) ? 'true' : 'false' }}">
                        @csrf
                        <input type="hidden" name="store_id" id="store_id" value="{{ old('store_id') }}">

                        <p class="checklist-help">برای انتخاب هر مورد روی ردیف آن کلیک کنید. یادداشت هر مورد از دکمه کنار آن در دسترس است.</p>
                        <div id="checklistStatus" class="checklist-status" role="status" aria-live="polite" hidden></div>

                        @error('store_id')<div class="alert alert-danger">{{ $message }}</div>@enderror
                        @error('check_lists')<div class="alert alert-danger">{{ $message }}</div>@enderror

                        <div class="checklist-items">
                            @foreach($checkLists as $checkList)
                                @php($commentError = $errors->first('comments.' . $checkList->id))
                                <div class="checklist-row @if(in_array($checkList->id, old('check_lists', []))) is-selected @endif"
                                     data-checklist-row tabindex="0">
                                    <div class="checklist-row-summary">
                                        <input class="form-check-input checklist-checkbox" type="checkbox"
                                               name="check_lists[]" value="{{ $checkList->id }}"
                                               id="checklist_{{ $checkList->id }}"
                                               @checked(in_array($checkList->id, old('check_lists', [])))>
                                        <label class="checklist-title" for="checklist_{{ $checkList->id }}">{{ $checkList->title }}</label>
                                        <button type="button" class="checklist-comment-toggle @if(old('comments.' . $checkList->id)) has-comment @endif"
                                                aria-expanded="{{ $commentError ? 'true' : 'false' }}"
                                                aria-controls="checklist_comment_{{ $checkList->id }}" title="افزودن یادداشت">
                                            <i class="fa fa-comment-o" aria-hidden="true"></i>
                                            <span class="sr-only">یادداشت برای {{ $checkList->title }}</span>
                                        </button>
                                    </div>

                                    <div class="checklist-comment-panel @if($commentError) is-open @endif" id="checklist_comment_{{ $checkList->id }}">
                                        <label class="checklist-field-label" for="comment_{{ $checkList->id }}">یادداشت این مورد</label>
                                        <textarea class="form-control checklist-textarea" id="comment_{{ $checkList->id }}"
                                                  name="comments[{{ $checkList->id }}]" rows="2" maxlength="2000"
                                                  data-comment-id="{{ $checkList->id }}"
                                                  placeholder="جزئیات یا اقدام مرتبط با این مورد را بنویسید…">{{ old('comments.' . $checkList->id) }}</textarea>
                                        @if($commentError)<span class="text-danger">{{ $commentError }}</span>@endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="checklist-actions">
                            <button type="button" class="btn btn-beta-outline" data-dismiss="modal">بستن</button>
                            <button type="submit" class="btn btn-beta-solid" id="checklistSaveButton">
                                <i class="fa fa-check" aria-hidden="true"></i><span>ذخیره</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
