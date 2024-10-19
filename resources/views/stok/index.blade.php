@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/stok/import') }}')" class="btn btn-info">Import Barang</button>
            <a href="{{ url('/stok/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Excel</a>
            <a href="{{ url('/stok/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export PDF </a>
            <button onclick="modalAction('{{url('stok/create_ajax')}}')" class="btn btn-sm btn-success mt-1">Tambah Ajax</button>

        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{session('success')}}</div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger">{{session('error')}}</div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="supplier_id" name="supplier_id" required>
                                <option value="">- Semua -</option>
                                @foreach($supplier as $item)
                                    <option value="{{ $item->supplier_id }}">{{ $item->supplier_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Supplier Stok</small>
                        </div>
                        <div class="col-3">
                            <select class="form-control" id="user_id" name="user_id" required>
                                <option value="">- Semua -</option>
                                @foreach($user as $item)
                                    <option value="{{ $item->user_id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Penerima Stok</small>
                        </div>
                    </div>
                </div>
            </div>
            
        <table class="table table-bordered table-striped table-hover table-sm" id="table_stok">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Supplier</th>
                    <th>Nama Barang</th>
                    <th>Penerima</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>

@endsection

@push('css')
@endpush

@push('js')
<script>
        function modalAction(url = '') {
        $('#myModal').load(url,function() {
            $('#myModal').modal('show');
        });
    }

    var dataStok;

    $(document).ready(function() {
        dataStok = $('#table_stok').DataTable({
            // serverSide: true, jika ingin menggunakan server side processing
            serverSide: true,
            ajax: {
                "url": "{{ url('stok/list') }}",
                "dataType": "json",
                "type": "POST",

                "data": function(s) {
                    s.supplier_id = $('#supplier_id').val();
                    s.user_id = $('#user_id').val();
                    console.log(s.supplier_id); 
                    console.log(s.user_id);
                }
            },
            columns: [
                {
                    data: "DT_RowIndex",
                    className: "",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "supplier.supplier_nama", //mengambil kolom foreign key dari tabel supplier
                    className: "",
                    orderable: false,
                    searchable: true
                },
                {
                    data: "barang.barang_nama",
                    className: "",
                    orderable: false,
                    searchable: true
                },
                {
                    data: "user.nama",
                    className: "",
                    orderable: false,
                    searchable: true
                },
                {
                    data: "stok_tanggal",
                    className: "",
                    orderable: true,
                    searchable: false
                },
                {
                    data: "stok_jumlah",
                    className: "",
                    width: "10%",
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row){
                        return new Intl.NumberFormat('id-ID').format(data);
                    }
                },
                {
                    data: "aksi",
                    className: "",
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#table-stok_filter input').unbind().bind().on('keyup', function(e){
            if(e.keyCode == 13){ // enter key
                tableStok.search(this.value).draw();
            }
        }); 

        $('#supplier_id, #user_id').on('change',function() {
            dataStok.ajax.reload();
        })

    });
</script>
@endpush
