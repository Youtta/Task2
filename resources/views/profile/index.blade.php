@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Profile

                    <a href="{{ route('profile.create') }}">
                    <button type="button" class="btn btn-success" style="float:right">Add Profile</button>
                    </a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="custom_datatable">

                        <table id="datatable" class="table table-bordered table-striped" width="100%" cellspacing="0" cellpadding="0">
                          <thead>
                            <tr>
                              <th class="no-sort text-center" width="1%">S.No</th>
                              <th width="15%">Image</th>
                              <th width="10%">Name</th>
                              <th width="5%">Gender</th>
                              <th width="10%">Age</th>
                              <th class="no-sort text-center" width="20%">Actions</th>
                            </tr>
                          </thead>
                        </table>
                      </div>
                </div>
            </div>
        </div>
    </div>
</div>



@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function () {
      var table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("profile.datatable") }}',
        "columns": [
            { data: 'id' },
            { data: 'image'},
            { data: 'name'},
            { data: 'age'},
            { data: 'gender'},
            { data: 'id' },

        ],
        "columnDefs": [{
          "targets": 'no-sort',
          "orderable": false,
        },
        {
          "targets": 0,
          "render": function (data, type, row, meta) {
            return meta.row + 1;
          },
        },

        {
          "targets": 1,
          "render": function (data, type, row, meta) {
            // console.log(data);
            return '<img src="{{config("task.file_url")}}'+data+'" width="50" height="50">';
          },
        },


        {
          "targets": 5,
          "render": function (data, type, row, meta) {
            var edit = '{{route("profile.edit",[":id"])}}';
            edit = edit.replace(':id', row.id);
            // console.log(data);
            var checked = row.status == 1 ? 'checked' : null;
            return `
            <div class="text-center">
            <a href="` + edit + `" class="p-1" data-original-title="Edit" title="" data-placement="top" data-toggle="tooltip">
                <button class="btn btn-primary">Edit</button>
            </a>

            <a href="javascript:;" class="delete" data-original-title="Delete" title="" data-placement="top" data-toggle="tooltip" data-id="`+ row.id + `">
                <button class="btn btn-danger">Delete</button>
            </a>

            <input class="status" type="checkbox" data-plugin="switchery" data-color="#005CA3" data-size="small" ` + checked + ` value="` + row.id + `">
            </div>
            `;
          },
        },
        ],
        "drawCallback": function (settings) {
          var elems = Array.prototype.slice.call(document.querySelectorAll('.status'));
          if (elems) {
            elems.forEach(function (html) {
              var switchery = new Switchery(html, {
                color: '#005CA3'
                , secondaryColor: '#dfdfdf'
                , jackColor: '#fff'
                , jackSecondaryColor: null
                , className: 'switchery'
                , disabled: false
                , disabledOpacity: 0.5
                , speed: '0.1s'
                , size: 'small'
              });

            });
          }

          $('.status').change(function () {
            var $this = $(this);
            var id = $this.val();
            var status = this.checked;

            if (status) {
              status = 1;
            } else {
              status = 0;
            }

            axios
              .post('{{route("profile.status")}}', {
                _token: '{{csrf_token()}}',
                _method: 'patch',
                id: id,
                status: status,
              })
              .then(function (responsive) {
                console.log(responsive);
              })
              .catch(function (error) {
                console.log(error);
              });
          });

          $('.delete').click(function () {
            var deleteId = $(this).data('id');
            console.log(deleteId)
            var $this = $(this);

            swal({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
            }).then(function (result) {
              axios
                .post('{{route("profile.destroy")}}', {
                  _method: 'delete',
                  _token: '{{csrf_token()}}',
                  id: deleteId,
                })
                .then(function (response) {
                  console.log(response);

                  swal(
                    'Deleted!',
                    'Your profile has been deleted.',
                    'success'
                  )

                  table
                    .row($this.parents('tr'))
                    .remove()
                    .draw();
                })
                .catch(function (error) {
                  console.log(error);
                  swal(
                    'Failed!',
                    error.response.data.error,
                    'error'
                  )
                });
            })
          });
        },
        //scrollX:true,
      });

    });
  </script>
@endsection

@endsection
