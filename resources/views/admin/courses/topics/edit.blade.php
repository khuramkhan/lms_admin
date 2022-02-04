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
                    <h4 class="card-title" id="horz-layout-colored-controls">Edit Topic</h4>
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
                            <input type="hidden" name="courseID" value="{{ $topic->course->id }}">
                            <div class="form-body" id="topicWrapper">
                                <h4 class="form-section"><i class="la la-eye"></i> Topic Details</h4>
                                <div class="row" id="topic">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-md-2 label-control" for="userinput1">Topic</label>
                                            <div class="col-md-8">
                                                <input type="text" name="topic" value="{{ $topic->topic }}"
                                                    class="form-control border-primary"  required>
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
                                    {{-- @foreach ($topic->topicDetail as $detail)
                                        <div class="col-md-12 singleType">
                                            <div class="form-group row">
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        <label class="col-md-2 label-control" for="userinput1">Name</label>
                                                        <div class="col-md-8">
                                                            @php
                                                                $name = '';
                                                                if(!empty($detail->videoUrl)){
                                                                    $name = 'videoLinkName[]';
                                                                }elseif(!empty($detail->pdf)){
                                                                    $name = 'pdfName[]';
                                                                }elseif(!empty($detail->addressUrl)){
                                                                    $name = 'addressUrlName[]';
                                                                }elseif(!empty($detail->text)){
                                                                    $name = 'textName[]';
                                                                }
                                                            @endphp
                                                            <input type="text" name="{{ $name }}"
                                                                class="form-control border-primary name" value="{{ $detail->name }}" placeholder="Name" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-2 label-control" for="userinput1">Type</label>
                                                <div class="col-md-8">
                                                    <select name="type" class="form-control typeDropdown" required>
                                                        <option value="">-----Select Type-----</option>
                                                        <option value="1" {{ !empty($detail->videoLink) ? 'selected' : '' }} >video</option>
                                                        <option value="2" {{ !empty($detail->pdf) ? 'selected' : '' }}>pdf</option>
                                                        <option value="3" {{ !empty($detail->addressUrl) ? 'selected' : '' }}>Address Url</option>
                                                        <option value="4" {{ !empty($detail->text) ? 'selected' : '' }}>text</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                @if (!empty($detail->videoLink))
                                                    <div class="col-md-12 optionTab videoLink">
                                                        <div class="form-group row">
                                                            <label class="col-md-2 label-control" for="userinput1">Video Link</label>
                                                            <div class="col-md-8">
                                                                <input type="text" name="videoLink[]" value="{{ $detail->videoLink }}"
                                                                    class="form-control border-primary field"  >
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if (!empty($detail->text))
                                                    <div class="col-md-12 optionTab text">
                                                        <div class="form-group row">
                                                            <label class="col-md-2 label-control" for="userinput1">text</label>
                                                            <div class="col-md-8">
                                                            <textarea name="text[]" class="form-control field"  rows="5">{{ $detail->text }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if (!empty($detail->addressUrl))
                                                    <div class="col-md-12 optionTab addressUrl" >
                                                        <div class="form-group row">
                                                            <label class="col-md-2 label-control" for="userinput1">Address Url</label>
                                                            <div class="col-md-8">
                                                                <input type="text" name="addressUrl[]"
                                                                    class="form-control border-primary field"  value="{{ $detail->addressUrl }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if (!empty($detail->pdf))
                                                    <div class="col-md-12 optionTab pdf">
                                                        <div class="form-group row">
                                                            <label class="col-md-2 label-control" for="userinput1">Pdf</label>
                                                            <div class="col-md-8">
                                                                <input type="file" name="pdf[]" class="form-control field">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach --}}
                                </div>
                            </div>
                            <div class="form-actions right">
                                <button class="btn btn-primary nextBtn">
                                    <input type="hidden" name="next" id="nextStatus">
                                    <i class="la la-check-square-o"></i> Update
                                </button>
                                {{-- <a href="{{ route('course.addQuiz',['courseID' => $courseID]) }}" class="btn btn-primary">
                                    <i class="la la-check-square-o"></i> Next
                                </a> --}}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('load',function(){
            var topicWrapper = $('#topicWrapper');

            $(document).ready(function()
            {
                let topics = '{{  json_encode($topic->topicDetail) }}';
                topics = JSON.parse(topics.replace(/&quot;/g,'"'));

                for(topic of topics){
                    let name = '';
                    if(topic.videoLink != null){
                        name = 'videoLinkName[]'
                    }else if(topic.addressUrl != null){
                        name = 'addressUrlName[]'
                    }else if(topic.pdf != null){
                        name = 'pdfName[]';
                    }else if(topic.text != null){
                        name = 'textName[]';
                    }

                    let type = `<div class="col-md-12 singleType">
                                    <div class="col-12 d-flex justify-content-end my-2">
                                        <button type="button" class="btn btn-danger btn-sm deleteTopic" ><i class="fas fa-trash"></i> Delete</button>
                                    </div>
                                    <div class="form-group row">
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="col-md-2 label-control" for="userinput1">Name</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="${name}"
                                                            class="form-control border-primary name" value="${topic.name}" placeholder="Name" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-2 label-control" for="userinput1">Type</label>
                                            <div class="col-md-8">
                                                <select name="type" class="form-control typeDropdown" required>
                                                    <option value="">-----Select Type-----</option>
                                                    <option value="1" ${topic.videoLink != null ? 'selected' : ''} >video</option>
                                                    <option value="2" ${topic.pdf != null ? 'selected' : ''}>pdf</option>
                                                    <option value="3" ${topic.addressUrl != null ? 'selected' : ''}>Address Url</option>
                                                    <option value="4" ${topic.text != null ? 'selected' : ''}>text</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 optionTab text ${topic.text != null ? '' : 'd-none'}">
                                                <div class="form-group row">
                                                    <label class="col-md-2 label-control" for="userinput1">text</label>
                                                    <div class="col-md-8">
                                                       <textarea name="text[]" class="form-control field" placeholder="Enter Comma Seprated Links"  rows="5"> ${topic.text != null ? topic.text : ''} </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 optionTab videoLink ${topic.videoLink != null ? '' : 'd-none'} ">
                                                <div class="form-group row">
                                                    <label class="col-md-2 label-control" for="userinput1">Video Link</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="videoLink[]"
                                                            class="form-control border-primary field" value="${topic.videoLink != null ? topic.videoLink : ''}" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 optionTab addressUrl ${topic.addressUrl != null ? '' : 'd-none'}">
                                                <div class="form-group row">
                                                    <label class="col-md-2 label-control" for="userinput1">Address Url</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="addressUrl[]"
                                                            class="form-control border-primary field" value="${topic.addressUrl != null ? topic.addressUrl : ''}" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 optionTab pdf ${topic.pdf != null ? '' : 'd-none'}">
                                            <input type="hidden" value="${topic.pdf != null ? topic.id : ''}" name=prePdf[] class="prePdf" />
                                                <div class="form-group row">
                                                    <label class="col-md-2 label-control" for="userinput1">Pdf</label>
                                                    <div class="col-md-8">
                                                       <input type="file" name="pdf[]" value="" class="form-control pdfField field">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                topicWrapper.append(type);
                }
            });


            $('#addMore').on('click',function(event){
                event.preventDefault();
                let type = `<div class="col-md-12 singleType">
                                    <div class="col-12 d-flex justify-content-end my-2">
                                        <button type="button" class="btn btn-danger btn-sm deleteTopic" ><i class="fas fa-trash"></i> Delete</button>
                                    </div>
                                    <div class="form-group row">
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="col-md-2 label-control" for="userinput1">Name</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="name[]"
                                                            class="form-control border-primary name" placeholder="Name" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-2 label-control" for="userinput1">Type</label>
                                            <div class="col-md-8">
                                                <select name="type" class="form-control typeDropdown" required>
                                                    <option value="">-----Select Type-----</option>
                                                    <option value="1">video</option>
                                                    <option value="2">pdf</option>
                                                    <option value="3">Address Url</option>
                                                    <option value="4">text</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 optionTab text d-none">
                                                <div class="form-group row">
                                                    <label class="col-md-2 label-control" for="userinput1">text</label>
                                                    <div class="col-md-8">
                                                       <textarea name="text[]" class="form-control field" placeholder="Enter Comma Seprated Links"  rows="5"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 optionTab videoLink d-none">
                                                <div class="form-group row">
                                                    <label class="col-md-2 label-control" for="userinput1">Video Link</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="videoLink[]"
                                                            class="form-control border-primary field"  >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 optionTab addressUrl d-none">
                                                <div class="form-group row">
                                                    <label class="col-md-2 label-control" for="userinput1">Address Url</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="addressUrl[]"
                                                            class="form-control border-primary field"  >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 optionTab pdf d-none">
                                                <div class="form-group row">
                                                    <label class="col-md-2 label-control" for="userinput1">Pdf</label>
                                                    <div class="col-md-8">
                                                       <input type="file" name="pdf[]" class="form-control pdfField field">
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

            function manageOptions(current = '',parent = '') {
                let allOptions = parent.find('.optionTab');
                let currentClassName = '.'+current;
                let nameForCurrent = current+'Name[]';

                parent.find(currentClassName).removeClass('d-none');
                parent.find('.name').attr('name',nameForCurrent);
                parent.find('.field').attr('required',true);

                allOptions.each(function(){
                    if(!$(this).hasClass(current)){
                        $(this).find('.field').attr('required',false);
                        $(this).find('.field').val(null);
                        $(this).find('.prePdf').val(null);
                        $(this).addClass('d-none');
                    }
                })
            }

            $( "#topicWrapper" ).delegate( ".typeDropdown", "change", function(event) {
                let parent = $(this).parent().parent().parent();
                let selectedValue = $(this).find('option:selected').val();
                if(selectedValue == 1)
                {
                    manageOptions('videoLink',parent);
                }
                else if(selectedValue == 2)
                {
                    manageOptions('pdf',parent);
                }
                else if(selectedValue == 3)
                {
                    manageOptions('addressUrl',parent);
                }
                else if(selectedValue == 4)
                {
                    manageOptions('text',parent);
                }
            });

            $(topicWrapper).delegate(".pdfField", "change", function(){
                $(this).parent().parent().parent().find('.prePdf').val(null);
            });

            $('.nextBtn').on('click',function(){
                $('#nextStatus').val('next');
            })
        })
    </script>
@endsection

