<x-app-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Employees') }}
        </h2>
        <div class="pull-right mb-2">
            <a class="btn btn-success" onClick="add()" href="javascript:void(0)">Add employee</a>
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
                    <form action="javascript:void(0)" id="CompanyForm" name="CompanyForm" class="form-horizontal">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="name" class="col-sm-6 control-label">Employee Name</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Employee Name" maxlength="50" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-6 control-label">Employee Email</label>
                            <div class="col-sm-12">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter Employee Email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phone" class="col-sm-6 control-label">Employee Phone</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Employee Phone">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="company" class="col-sm-6 control-label">Employee Company</label>
                            <div class="col-sm-12">
                                <select class="form-control" id="company" name="company_id" required>
                                    @foreach($companies as $company)
                                        <option value="{{ $company['id'] }}">{{ $company['name'] }}</option>
                                    @endforeach
                                </select>
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
                        <table  class="table table-striped table-bordered" id="employee_table" style="width: 100%;">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Company</th>
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
        let table = $('#employee_table').DataTable({
            serverSide: true,
            ajax: "{{ route('employee.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {
                    data: 'name',
                    name: 'name',
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'company',
                    name: 'company',
                    obj: 'id',
                    render: function(row, data, obj){
                        return obj.company['name']
                    },
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
            $('#CompanyModal').html("Add Employee");
            $('#company-modal1').modal('show');
            $('#id').val('');
        }

            function editFunc(id){
                $.ajax({
                    type:"GET",
                    url: "/employee/" + id + "/edit",
                    dataType: 'json',
                    success: function(res){
                        $('#CompanyModal').html("Edit Employee");
                        $('#company-modal1').modal('show');
                        $('#id').val(res.id);
                        $('#name').val(res.name);
                        $('#phone').val(res.phone);
                        $('#email').val(res.email);
                        $('#company').val(res.company_id);
                    }
                });
            }
            function deleteFunc(id){
                if (confirm("Delete Record?") == true) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ url('/employee') }}/" + id,
                        success: function (res) {
                            table.clear();
                            table.draw();
                        }
                    });
                }
            }
            $('#CompanyForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                let route = "{{ url('/employee') }}";
                let method = 'POST';
                if($('#id').val() != ''){
                    route = "{{ url('/employee/update') }}/" + $('#id').val();
                    method = 'POST'
                }
                $.ajax({
                    type: method,
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

    </script>
</x-app-layout>
