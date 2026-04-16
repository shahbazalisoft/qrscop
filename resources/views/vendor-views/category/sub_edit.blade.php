<div class="modal fade" id="edit_sub_menu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('messages.edit_sub_menu') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editSubMenuForm">
                    <div class="modal-body">
                        <input name="id" id="id" value="{{$category->id}}" class="initial-hidden">
                        <div class="form-group" id="new_category_group">
                            <label class="input-label" for="default_name">{{ translate('messages.sub_menu') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="sub_menu" id="edit_name" value="{{$category->name}}" class="form-control"
                                placeholder="{{ translate('messages.new_sub_menu') }}" >
                            <span class="text-danger errorMsg" id="edit_name_error"></span>
                        </div>

                        <div class="form-group" id="new_category_group">
                            <label class="input-label" for="default_image">{{ translate('messages.main_menu') }} <small
                                    class="text-danger">*</small></label>
                            <select name="parent_id" id="edit_parent_id" class="form-control js-select2-custom" >
                                <option value="" selected disabled>{{translate('Select Main Menu')}}</option>
                                @foreach($categoryList as $cat)
                                    <option value="{{$cat['id']}}"  {{isset($category)?($category['parent_id']==$cat['id']?'selected':''):''}} >{{$cat['name']}}</option>
                                @endforeach
                            </select>
                            <span class="text-danger errorMsg" id="edit_parent_id_error"></span>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ translate('messages.close') }}</button>
                        <div class="loaderBtn">
                            <button type="submit" class="btn btn-primary" >{{ translate('messages.update') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>