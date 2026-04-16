       <div class="col-lg-6">
           <div class="card shadow--card-2 border-0">
               <div class="card-body ">
                   <div class="lang_form" id="default-form">
                       <div class="form-group">
                           <div class="justify-content-between d-flex">
                               <label class="input-label" for="default_name">{{ translate('messages.name') }} <span class="form-label-secondary text-danger"
                                       data-toggle="tooltip" data-placement="right"
                                       data-original-title="{{ translate('messages.Required.') }}"> *
                                   </span>
                               </label>

                           </div>
                           <div class="error-wrapper">
                               <div class="outline-wrapper">
                                   <input type="text" name="name" id="default_name" class="form-control" value="{{ $product?->name ?? old('name') }}"
                                       placeholder="{{ translate('messages.new_food') }}" required>
                               </div>
                           </div>
                       </div>
                       
                       <div class="form-group mb-0 des_wrapper">

                           <div class="justify-content-between d-flex">
                               <label class="input-label"
                                   for="exampleFormControlInput1">{{ translate('messages.short_description') }}</label>
                           </div>

                           <div class="error-wrapper">
                               <div class="outline-wrapper">
                                    <textarea type="text" rows="3" name="description" maxlength="1200" id="description-default" class="form-control ckeditor min-height-154px" required>{{ $product?->description ?? old('description') }}</textarea>
                               </div>
                           </div>

                       </div>
                   </div>
               </div>

           </div>
       </div>
