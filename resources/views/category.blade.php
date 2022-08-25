@include('header');


<div class="container pt-5" style="border:black">
    <div class="row col-lg-12">
        <h3>Add Category</h3>

        <form id="category">
            <div class="form-group">
                <div class="row col-lg-12">
                    <div class="col-lg-2">
                        <label for="name">Name :</label>
                    </div>
                    <div class="col-lg-3">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                    </div>
                    <div class="error col-lg-2">*</div>
                </div>
                <div class="col-md-12 pt-3">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>

    </div>
</div>

<div class="container">
    <table class="table" id="category_data">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Name</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="edit_category">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit</h4>
            </div>
            <div class="modal-body">
                <form id="classFormUpdate" method="post">
                    <div class="form-group">
                        <label for="edit-taunt">Name</label>
                        <input type="text" class="form-control" id="editname" name="editname" placeholder="Enter Name">
                        <input type="hidden" class="form-control" id="id" name="id">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="modal-save">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

@include('footer');

<script>
$(document).ready(function() {

    var table = $('#category_data').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('category_data') }}",
        columns: [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ]
    });

    $('#category').on('submit', function(e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var name = $('#name').val();
        // alert(name);
        $.ajax({
            type: "POST",
            url: '{{ route("category_sub") }}',
            cache: false,
            data: {
                name: name,
            },
            success: function(msg) {
                alert('Category Add Successfully');
                table.ajax.reload();
            }
        });
    });


    

    $(document).on("click", ".category_delete", function() {
        var action = $(this).data("action");
        confirm("Are You sure want to delete this Post!");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "DELETE",
            url: action,
            success: function(data) {
                table.ajax.reload();
            },
        });
    });

    $(document).on("click", ".category_edit", function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var id = $(this).data('id');
        $.ajax({
            type: 'post',
            url: '{{ route("category_get") }}',
            data: {
                id: id,
                _token: "{{ csrf_token() }}"
            },
           
            dataType: "json",
            success: function(data) {
                $('#id').val(data.id);
                $('#editname').val(data.name);
                // $('#classFormUpdate').attr('action', action);
                $('#edit_category').modal('show');
            }
        });
    });

    $('#modal-save').on('click', function(e) {
        // alert('ff');
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var action = $(this).data("action");
        // console.log(action);
        var name = $('#editname').val();
        var id = $('#id').val();
        $.ajax({
            type: "POST",
            url: "{{ route('category_edit') }}",
            cache: false,
            data: {
                id:id,
                name: name,
            },
            success: function(msg) {
                alert('Category Add Successfully');
                table.ajax.reload();
                $('#edit_category').modal('hide');
            }
        });
    });

});
</script>