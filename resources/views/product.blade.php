@include('header');


<div class="container pt-5" style="border:black">
    <div class="row col-lg-12">
        <h3>Add Product</h3>

        <form id="productform" action="{{ route('product_sub') }}" method="post">
            @csrf
            <div class="form-group">

                <div class="row col-lg-12">
                    <div class="col-lg-2">
                        <label for="name">Category :</label>
                    </div>
                    <div class="col-lg-3">
                        <select id='sel_emp' name="sel_emp" style='width: 200px;'>
                            <option value='0'>-- Select Category --</option>
                            @foreach($Category as $value)
                            <option value="{{ $value->id  }}">{{ $value->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- <div class="error col-lg-2">*</div> -->
                </div>
                <div class="row col-lg-12 pt-3">
                    <div class="col-lg-2">
                        <label for="name">Name :</label>
                    </div>
                    <div class="col-lg-3">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                    </div>
                    <div class="error col-lg-2">*</div>
                </div>
                <div class="row col-lg-12 pt-3">
                    <div class="col-lg-2">
                        <label for="name">Description :</label>
                    </div>
                    <div class="col-lg-3">
                        <input type="text" class="form-control" id="description" name="description"
                            placeholder="Enter description">
                    </div>
                    <div class="error col-lg-2">*</div>
                </div>
                <div class="row col-lg-12 pt-3">
                    <div class="col-lg-2">
                        <label for="name">price :</label>
                    </div>
                    <div class="col-lg-3">
                        <input type="number" class="form-control" id="price" name="price" placeholder="Enter price">
                    </div>
                    <div class="error col-lg-2">*</div>
                </div>
                <div class="row col-lg-12 pt-3">
                    <div class="col-lg-2">
                        <label for="name">Image :</label>
                    </div>
                    <div class="col-lg-3">
                        <input type="file" name="files[]" id="files" placeholder="Choose files" multiple>
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
    <table class="table" id="datatable">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">category_id </th>
                <th scope="col">Name</th>
                <th scope="col">description</th>
                <th scope="col">Price</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>


<div class="modal fade" tabindex="-1" role="dialog" id="edit_product">
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
                        <label for="edit-taunt">Category</label>
                        <div class="col-lg-3">
                            <select id='editsel_emp' name="editsel_emp" style='width: 200px;'>
                                <option value='0'>-- Select Category --</option>
                                @foreach($Category as $value)
                                <option value="{{ $value->id  }}">{{ $value->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" class="form-control" id="id" name="id">
                    </div>
                    <div class="form-group">
                        <label for="edit-taunt">Name</label>
                        <input type="text" class="form-control" id="editname" name="editname" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label for="edit-taunt">Description :</label>
                        <input type="text" class="form-control" id="editdescription" name="editdescription"
                            placeholder="Enter Description">
                    </div>
                    <div class="form-group">
                        <label for="edit-taunt">Prize :</label>
                        <input type="text" class="form-control" id="editprize" name="editprize"
                            placeholder="Enter Prize">
                    </div>
                    <div class="form-group pt-2">
                        <label for="edit-taunt">Image :</label>
                        <input type="file" name="files[]" id="files" placeholder="Choose files" multiple>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editmodal-save">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

@include('footer');

<script>
$(document).ready(function() {




    var producttable = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('datatable') }}",
        columns: [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'category_id',
                name: 'category_id'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'description',
                name: 'description'
            },
            {
                data: 'price',
                name: 'price'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ]
    });

    $('#productform').on('submit', function(e) {
        e.preventDefault();
        var postData = new FormData(this);
        // alert(postData);


        $.ajax({
            url: $(this).attr('action'),
            type: "POST",
            processData: false,
            contentType: false,
            cache: false,
            data: postData,
            success: function(msg) {
                alert('Product Add Successfully');
                producttable.ajax.reload();
            }
        });
    });

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(function() {

        $("#sel_emp").select2({
            width: '100%',
            ajax: {
                url: "{{route('category-search')}}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page || 1,
                        _token: CSRF_TOKEN,
                    };
                },
            }
        });

    });

    $(document).on("click", ".product_delete", function() {
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
                producttable.ajax.reload();
            },
        });
    });


    $(document).on("click", ".product_edit", function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var id = $(this).data('id');
        $.ajax({
            type: 'post',
            url: '{{ route("product_get") }}',
            data: {
                id: id,
                _token: "{{ csrf_token() }}"
            },

            dataType: "json",
            success: function(data) {
                console.log(data);
                $('#id').val(data.product.id);
                $('#editsel_emp').val(data.product.category_id);
                // $('[name=editsel_emp]').val(data.sel_emp);
                $('#editname').val(data.product.name);
                $('#editdescription').val(data.product.description);
                $('#editprize').val(data.product.price);
                $('#files').val(data.images.name);

               

                $('#edit_product').modal('show');
            }
        });
    });

    $('#editmodal-save').on('click', function(e) {
        // alert('ff');
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var action = $(this).data("action");
        // console.log(action);
        var id = $('#id').val();
        var sel_emp = $('#editsel_emp').val();
        var name = $('#editname').val();
        var description = $('#editdescription').val();
        var prize = $('#editprize').val();

        $.ajax({
            type: "POST",
            url: "{{ route('product_edit') }}",
            cache: false,
            data: {
                id: id,
                sel_emp: sel_emp,
                name: name,
                description: description,
                prize: prize,
            },
            success: function(msg) {
                alert('Category Add Successfully');
                $('#edit_product').modal('hide');
                producttable.ajax.reload();
            }
        });
    });
});
</script>