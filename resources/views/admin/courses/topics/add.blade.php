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
                        <form class="form form-horizontal" action="" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="courseID" value="{{ $courseID }}">
                            <div class="form-body" id="topicWrapper">
                                <h4 class="form-section"><i class="la la-eye"></i> Topic Details</h4>
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end my-2">
                                        <button class="btn btn-success btn-sm" id="addMore"><i class="fas fa-add"></i> Add More</button>
                                    </div>
                                </div>
                                <div class="row" id="topic">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-md-2 label-control" for="userinput1">Topic</label>
                                            <div class="col-md-8">
                                                <input type="text" name="topic[]"
                                                    class="form-control border-primary" placeholder="Topic" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-md-2 label-control" for="userinput1">Video Link</label>
                                            <div class="col-md-8">
                                               <textarea name="videoLink[]" class="form-control" placeholder="Enter Comma Seprated Links"  rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-md-2 label-control" for="userinput1">Pdf</label>
                                            <div class="col-md-8">
                                               <input type="file" name="pdf[]" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="la la-check-square-o"></i> Save & Next
                                </button>
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
                let topic = `<div class="row" id="topic">
                                    <div class="col-12 d-flex justify-content-end my-2">
                                        <button type="button" class="btn btn-danger btn-sm deleteTopic" ><i class="fas fa-trash"></i> Delete</button>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-md-2 label-control" for="userinput1">Topic</label>
                                            <div class="col-md-8">
                                                <input type="text" name="topic[]"
                                                    class="form-control border-primary" placeholder="Topic" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-md-2 label-control" for="userinput1">Video Link</label>
                                            <div class="col-md-8">
                                               <textarea name="videoLink[]" class="form-control" placeholder="Enter Comma Seprated Links"  rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-md-2 label-control" for="userinput1">Pdf</label>
                                            <div class="col-md-8">
                                               <input type="file" name="pdf[]" class="form-control" >
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                topicWrapper.append(topic);
            });

            $( "#topicWrapper" ).delegate( ".deleteTopic", "click", function(event) {
                event.preventDefault();
                let r = confirm('Are You Sure You Want to Delete?');
                if(r){
                    $(this).parent().parent().remove();
                }
            });
        })
    </script>
@endsection

