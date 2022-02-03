@extends('admin.layout.interface')
@include('admin.layout.breadcrumbs',
['breadcrumbs' =>
    ['title' => 'Dashbaord',
     'items' => [
         ['name' => 'Home', 'url' => url("/"), 'active' => false],
         ['name' => 'Add Topic', 'url' => null, 'active' => true],
     ]
    ]
])
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title" id="horz-layout-colored-controls">Add Topic</h4>
                </div>
                <div class="card-content collpase show">

                    <div class="card-body">
                        @if (session()->has('success') || session()->has('error'))
                            <div class="alert alert-{{session()->has('success') ? 'success' : 'danger'}}">
                                {{session()->has('success') ? session()->get('success') : session()->get('error')}}
                            </div>
                        @endif
                        <form class="form form-horizontal" action="" method="POST" enctype="multipart/form-data" id="topicForm">
                            @csrf
                            <input type="hidden" name="courseID" value="{{ $courseID }}">
                            <div class="form-body" id="topicWrapper">
                                <h4 class="form-section"><i class="la la-eye"></i> Topic Details</h4>
                                <div class="row" id="topic">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-md-2 label-control" for="userinput1">Topic</label>
                                            <div class="col-md-8">
                                                <input type="text" name="topic"
                                                    class="form-control border-primary" placeholder="Topic" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-end my-2">
                                                <button class="btn btn-success btn-sm" id="addMore"><i class="fas fa-add"></i> Add More</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 singleType">
                                        <div class="form-group row">
                                            <label class="col-md-2 label-control" for="userinput1">Type</label>
                                            <div class="col-md-8">
                                                <select name="type" class="form-control typeDropdown" >
                                                    <option value="">-----Select Type-----</option>
                                                    <option value="1">video</option>
                                                    <option value="2">pdf</option>
                                                    <option value="3">Address Url</option>
                                                    <option value="4">text</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 optionTab videoLink" style="display: none">
                                                <div class="form-group row">
                                                    <label class="col-md-2 label-control" for="userinput1">Video Link</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="videoLink[]"
                                                            class="form-control border-primary field" placeholder="Topic" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 optionTab text" style="display: none">
                                                <div class="form-group row">
                                                    <label class="col-md-2 label-control" for="userinput1">text</label>
                                                    <div class="col-md-8">
                                                       <textarea name="text[]" class="form-control field" placeholder="Enter Comma Seprated Links"  rows="5"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 optionTab addressUrl"  style="display: none">
                                                <div class="form-group row">
                                                    <label class="col-md-2 label-control" for="userinput1">Address Url</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="addressUrl[]"
                                                            class="form-control border-primary field" placeholder="Topic" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 optionTab pdf" style="display: none">
                                                <div class="form-group row">
                                                    <label class="col-md-2 label-control" for="userinput1">Pdf</label>
                                                    <div class="col-md-8">
                                                       <input type="file" name="pdf[]" class="form-control field">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions right">
                                <button class="btn btn-primary nextBtn">
                                    <input type="hidden" name="next" id="nextStatus">
                                    <i class="la la-check-square-o"></i> Save & And More
                                </button>
                                <a href="{{ route('course.addQuiz',['courseID' => $courseID]) }}" class="btn btn-primary">
                                    <i class="la la-check-square-o"></i> Next
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('load',function(){
            $('#addMore').on('click',function(event){

                event.preventDefault();
                let topicWrapper = $('#topicWrapper');
                let type = `<div class="col-md-12 singleType">
                                    <div class="col-12 d-flex justify-content-end my-2">
                                        <button type="button" class="btn btn-danger btn-sm deleteTopic" ><i class="fas fa-trash"></i> Delete</button>
                                    </div>
                                        <div class="form-group row">
                                            <label class="col-md-2 label-control" for="userinput1">Type</label>
                                            <div class="col-md-8">
                                                <select name="type" class="form-control typeDropdown" >
                                                    <option value="">-----Select Type-----</option>
                                                    <option value="1">video</option>
                                                    <option value="2">pdf</option>
                                                    <option value="3">Address Url</option>
                                                    <option value="4">text</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 optionTab text" style="display: none">
                                                <div class="form-group row">
                                                    <label class="col-md-2 label-control" for="userinput1">text</label>
                                                    <div class="col-md-8">
                                                       <textarea name="text[]" class="form-control field" placeholder="Enter Comma Seprated Links"  rows="5"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 optionTab videoLink" style="display: none">
                                                <div class="form-group row">
                                                    <label class="col-md-2 label-control" for="userinput1">Video Link</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="videoLink[]"
                                                            class="form-control border-primary field" placeholder="Topic" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 optionTab addressUrl"  style="display: none">
                                                <div class="form-group row">
                                                    <label class="col-md-2 label-control" for="userinput1">Address Url</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="addressUrl[]"
                                                            class="form-control border-primary field" placeholder="Topic" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 optionTab pdf" style="display: none">
                                                <div class="form-group row">
                                                    <label class="col-md-2 label-control" for="userinput1">Pdf</label>
                                                    <div class="col-md-8">
                                                       <input type="file" name="pdf[]" class="form-control field">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                topicWrapper.append(type);
            });

            $( "#topicWrapper" ).delegate( ".deleteTopic", "click", function(event) {
                event.preventDefault();
                let r = confirm('Are You Sure You Want to Delete?');
                if(r){
                    $(this).parent().parent().remove();
                }
            });

            function hideOtherTab(current = '',parent = '') {
                let allOptions = parent.find('.optionTab');
                allOptions.each(function(){
                    if(!$(this).hasClass(current)){
                        $(this).find('.field').attr('required',false);
                        $(this).find('.field').val(null);
                        $(this).hide();
                    }
                })
            }

            $( "#topicWrapper" ).delegate( ".typeDropdown", "change", function(event) {
                let parent = $(this).parent().parent().parent();
                let selectedValue = $(this).find('option:selected').val();
                if(selectedValue == 1){
                    parent.find('.videoLink').show();
                    $(parent).find('.field').attr('required',true);
                    hideOtherTab('videoLink',parent);
                }else if(selectedValue == 2){
                    parent.find('.pdf').show()
                    $(parent).find('.field').attr('required',true);
                    hideOtherTab('pdf',parent);
                }else if(selectedValue == 3){
                    $(parent).find('.field').attr('required',true);
                    parent.find('.addressUrl').show()
                    hideOtherTab('addressUrl',parent);
                }
                else if(selectedValue == 4){
                    $(parent).find('.field').attr('required',true);
                    parent.find('.text').show()
                    hideOtherTab('text',parent);
                }
            });

            $('.nextBtn').on('click',function(){
                $('#nextStatus').val('next');
            })
        })
    </script>
@endsection

