<x-app-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Companies') }}
        </h2>
        <div class="pull-right mb-2">
            <a class="btn btn-success" onClick="add()" href="javascript:void(0)"> Create Company</a>
        </div>
    </x-slot>
    <!-- boostrap company model -->
    <div class="modal fade" id="company-modal1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="CompanyModal"></h4>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="CompanyForm" name="CompanyForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="name" class="col-sm-6 control-label">Company Name</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Company Name" maxlength="50" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-6 control-label">Company Email</label>
                            <div class="col-sm-12">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter Company Email" maxlength="50">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Company Address</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="address" name="address" placeholder="Enter Company Address" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-8 control-label" for="logo">Logo (min width 100px, min height 100px)</label>
                            <div class="col-sm-12">
                                <div id="logo_image"></div>
                                <input type="file" class="form-control-file" onchange="validateimg()" id="logo" name="file">
                            </div>
                        </div>
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="btn-save">Save changes
                            </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!-- end bootstrap model -->
    <div class="container">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <table class="table table-bordered data-table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Logo</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            let table = $('.data-table').DataTable({
                serverSide: true,
                ajax: "{{ route('company.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {
                        data: 'logo_path',
                        name: 'logo',
                        render: function(data, row){
                            if(data) {
                                return '<img src="/storage/logos/' + data + '">'
                            }
                            return '<img src="http://dummyimage.com/100">'
                        }
                    },
                    {
                        data: 'name',
                        name: 'name',
                        obj: 'id',
                        render: function(data, row, obj){
                            return '<a href="/company/' + obj.id + '">' + data + '</a>';
                        },
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'name',
                        name: 'actions',
                        obj: 'id',
                        render: function(data, row, obj){
                            return '<div class="btn-group" role="group">' +
                                '<input class="btn btn-info" type="button" onclick="editFunc(' + obj.id + ')" value="Edit"> ' +
                                '<input class="btn btn-danger" type="button" value="Delete" onclick="deleteFunc(' + obj.id + ')">' +
                                '</div>'
                        },
                    },
                ]
            });

        function add(){
            $('#CompanyForm').trigger("reset");
            $('#CompanyModal').html("Add Company");
            $('#company-modal1').modal('show');
            $('#id').val('');
        }
        function editFunc(id){
            $.ajax({
                type:"GET",
                url: "/company/" + id + "/edit",
                dataType: 'json',
                success: function(res){
                    $('#CompanyModal').html("Edit Company");
                    $('#company-modal1').modal('show');
                    $('#id').val(res.id);
                    $('#name').val(res.name);
                    $('#address').val(res.address);
                    $('#email').val(res.email);
                    if(res.logo_path){
                        $('#logo_image').html('<img src="/storage/logos/' + res.logo_path + '">');
                    }else{
                        $('#logo_image').html('<img src="http://dummyimage.com/100">');
                    }


                }
            });
        }
        function deleteFunc(id){
            if (confirm("Delete Record?") == true) {
                $.ajax({
                    type:"DELETE",
                    url: "{{ url('company') }}/" + id,
                    data: { id: id },
                    dataType: 'json',
                    success: function(res){
                        table.clear();
                        table.draw();
                    }
                });
            }
        }
        $('#CompanyForm').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let route = '{{ url('/company') }}';
            let method = 'POST';
            if($('#id').val() != ''){
                route = '{{ url('/company/update') }}/' + $('#id').val();
            }
            $.ajax({
                type:method,
                url: route,
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: (data) => {
                    $("#company-modal1").modal('hide');
                    $("#btn-save").html('Submit');
                    $("#btn-save").attr("disabled", false);
                    table.clear();
                    table.draw();
                },
                error: function(data){
                    console.log(data);
                }
            });
        });
            function validateimg() {
                let fileUpload = $('#logo')[0];
                let regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(.jpg|.png|.gif)$");
                if (regex.test(fileUpload.value.toLowerCase())) {
                    if (typeof (fileUpload.files) != "undefined") {
                        let reader = new FileReader();
                        reader.readAsDataURL(fileUpload.files[0]);
                        reader.onload = function (e) {
                            let image = new Image();
                            image.src = e.target.result;
                            image.onload = function () {
                                let height = this.height;
                                let width = this.width;
                                if (height < 100 || width < 100) {
                                    alert("The height and width of the images must be greater than 100 pixels.");
                                    $('#logo').val(null);
                                    return false;
                                }else{
                                    alert("Uploaded image has valid Height and Width.");
                                    return true;
                                }
                            };
                        }
                    } else {
                        alert("This browser does not support HTML5.");
                        return false;
                    }
                } else {
                    alert("Please select a valid Image file.");
                    $('#logo').val(null);
                    return false;
                }
            }
    </script>
</x-app-layout>
