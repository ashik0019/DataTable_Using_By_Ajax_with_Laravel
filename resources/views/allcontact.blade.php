<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ajax</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="container">
    <h1 class="text-info">CRUD BY AJAX & LARAVEL</h1>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <a class="btn btn-sm btn-success float-right" onclick="addForm()">Add New</a>
            <table id="contact-table" class="table table-striped table-dark">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Religion</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    @include('form')
</div>






<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.10.2/validator.min.js"></script>
<script type="text/javascript">
    var table1 = $('#contact-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('all.contact') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'religion', name: 'religion'},
            {data: 'action', name: 'action', orderable:false, serarchable: false }
        ]
    });
    // add form function
    function  addForm() {
        save_method = 'add';
        $('input[name_method]').val('POST');
        $('#modal-form').modal('show');
        $('#modal-form form')[0].reset();
        $('.modal-title').text('Add Contact');
        $('#insertbutton').text('Add Contact');
    }
    //insert data by ajax with laravel
        $(function () {
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if(save_method == 'add') url = "{{url('contact')}}";
                    else url = "{{url('contact') .'/'}}" + id;
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: new FormData($("#modal-form form")[0]),
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            $('#modal-form').modal('hide');
                            table1.ajax.reload();
                            swal({
                                title: "Data Successfully Added",
                                text: "You clicked the button!",
                                icon: "success",
                                buttons: "Great!",
                            })
                        },
                        error: function (data) {
                            swal({
                                title: "Oops!",
                                text: data.message,
                                icon: "error",
                                timer: "1500",
                            });
                        }
                    });
                    return false;
                }
            });
        });

    // edit data by ajax
    function editForm(id) {
        save_method = 'edit';
        $('input[name=_method]').val('PATCH');
        $('#modal-form form')[0].reset();
        $.ajax({
            url: "{{url('contact')}}" + '/' + id + "/edit",
            type: "GET",
            data: {id: id},
            dataType: "JSON",
            success: function (data) {
                $('#modal-form').modal("show");
                $('.modal-title').text('Edit Contact');
                $('#insertbutton').text('Update Contact');
                $('#id').val(data.id);
                $('#name').val(data.name);
                $('#email').val(data.email);
                $('#phone').val(data.phone);
                $('#religion').val(data.religion);
            },
            error: function (data) {
                alert("Not working properly.");
            }
        });
    }
    // delete method
    function deleteData(id) {
        var csrf_token = $('meta[name="csrf-token"]').attr('contact');
        swal({
            title: "Are you sure to delete?",
            text: "Once delete, you will not able to recover this imaginary file!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: "{{ url('contact') }}"+'/'+id,
                    type: "POST",
                    dataType: "JSON",
                    data: {'_method': 'DELETE', "_token": "{{ csrf_token() }}" },
                    success: function (data) {
                        table1.ajax.reload();
                        swal({
                            title: "Successfully Deleted!",
                            text: "You clicked the button!",
                            icon: "success",
                            buttons: "Done!",
                        });
                    },
                    error: function (data) {
                        swal({
                            title: "Oops!",
                            text: data.message,
                            icon: "error",
                            timer: "1500",
                        });
                    }
                });
            }else {
                swal("You imaginary file is safe ");
            }
        })
    }
</script>
</body>
</html>
