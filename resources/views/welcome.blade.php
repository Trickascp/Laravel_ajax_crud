<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>CRUD AJAX LARAVEL</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">

        <script src="{{ asset('js/app.js') }}"></script>

        <!-- Styles -->
    </head>
    <body>
        
        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    
                    <div class="card">
                        <div class="card-body">
                            <h3 align="center" class="mb-4">LARAVEL CRUD MENGGUNAKAN AJAX</h3>
                            <div>
                                <button style="width: 100%;" class="btn btn-success mb-1" id="create">Add</button>
                            </div>
                            <table class="table table-striped text-center">
                                <thead class="bg-primary text-light">
                                    <th>Nama</th>
                                    <th>Jurusan</th>
                                    <th>Sekolah</th>
                                    <th>Modify</th>
                                </thead>
                                <tbody id="all-list">
                                    @forelse($get as $d)
                                    <tr class="d{{ $d->id }}">
                                        <td>{{ $d->nama }}</td>
                                        <td>{{ $d->jurusan }}</td>
                                        <td>{{ $d->sekolah }}</td>
                                        <td><button value="{{ $d->id }}" class="btn btn-warning edit">Edit</button>
                                            <button value="{{ $d->id }}" class="btn btn-danger delete">Delete</button></td>
                                    </tr>

                                    @empty
                                    <tr>
                                        <td colspan="4">No Data Found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="modal fade" id="modaljax" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Siswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                
                <form id="formModal">
                    <div class="form-group">
                        <input type="text" name="" class="form-control" placeholder="Nama Siswa" id="nama">
                    </div>
                    <div class="form-group">
                        <input type="text" name="" class="form-control" placeholder="Jurusan" id="jurusan">
                    </div>
                    <div class="form-group">
                        <input type="text" name="" class="form-control" placeholder="Sekolah" id="sekolah">
                    </div>
                    <div class="form-group text-right">
                        <input type="submit" id="btn-action" name="" class="btn btn-primary" value="Save">
                        <input type="hidden" name="id" id="idSiswa" value="0">
                    </div>
                </form>

              </div>
            </div>
          </div>
        </div>



        <script>
            
            $(document).ready(function(){

                // $('body').on('click', '.edit', function () {
                //        var link_id = $(this).val();
                //        $.get('siswa-update/' + link_id, function (data) {
                //            $('#btn-action').val("update");
                //            $('#modaljax').modal('show');
                //        })

                $('#create').click(function(){
                    $('#btn-action').val("Add")
                    $('#formModal').trigger("reset");
                    $('#modaljax').modal('show')
                })

                $('.edit').click(function(e){
                    e.preventDefault();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $('#btn-action').val("Edit")
                    $('#modaljax').modal('show')

                    var id = $(this).val();
                    $.ajax({

                        url: "/siswa/"+id,
                        dataType: "json",
                        success: function(data){
                            $('#nama').val(data.nama)
                            $('#jurusan').val(data.jurusan)
                            $('#sekolah').val(data.sekolah)
                            $('#idSiswa').val(data.id)
                        }

                    })

                })

                $('.delete').click(function(e){
                    e.preventDefault();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    var id = $(this).val();
                    $.ajax({
                        url: "/siswa/delete/"+id,
                        dataType: "json",
                        success: function(data){

                            $(".d" + data.id).fadeOut('slow');

                        }
                    })

                })

                $('#btn-action').click(function(e){

                    e.preventDefault();

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    var type = 'POST';
                    var state = $('#btn-action').val();
                    var data1 = {
                        nama: $('#nama').val(),
                        jurusan: $('#jurusan').val(),
                        sekolah: $('#sekolah').val(),
                    };
                    
                    var urls = "{{ route('si.create') }}"

                    if(state == 'Edit'){
                        var data1 = {
                            id: $('#idSiswa').val(),
                            nama: $('#nama').val(),
                            jurusan: $('#jurusan').val(),
                            sekolah: $('#sekolah').val(),
                        };  
                        urls = "{{ route('si.update') }}"
                    }

                    $.ajax({
                        url: urls,
                        method:"POST",
                        data: data1,
                        dataType:"json",
                        success: function(data){

                            
                            var apdn = '<tr><td>'+ data.nama +'</td>';
                            apdn += '<td>'+ data.jurusan +'</td>';
                            apdn += '<td>'+ data.sekolah +'</td>';
                            apdn += '<td><button value="'+ data.id +'" class="edit btn btn-warning">Edit</button>'
                            apdn += '<button value="'+ data.id +'" class="delete btn btn-danger">Delete</button></td></tr>'
                            if(state == "Add"){
                                $('#all-list').append(apdn);
                            }else{
                                $('.d'+data.id).replaceWith(apdn);
                            }
                            

                            $('#formModal').trigger("reset");
                            $('#modaljax').modal('hide')
                        }

                    })
                    

                })  


            })

        </script>

    </body>
</html>
