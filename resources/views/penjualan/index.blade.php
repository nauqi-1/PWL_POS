@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/penjualan/import') }}')" class="btn btn-info">Import Penjualan</button>
            <a href="{{ url('/penjualan/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Excel</a>
            <a href="{{ url('/penjualan/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export PDF </a>
            <button onclick="modalAction('{{url('penjualan/create_ajax')}}')" class="btn btn-sm btn-success mt-1">Tambah Ajax</button>

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
                                @foreach($user as $item)
                                    <option value="{{ $item->user_id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Kasir</small>
                        </div>
                    </div>
                </div>
            </div>
            
        <table class="table table-bordered table-striped table-hover table-sm" id="table_penjualan">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kasir</th>
                    <th>Pembeli</th>
                    <th>Kode Penjualan</th>
                    <th>Jumlah Jenis Barang</th>
                    <th>Total Harga</th>
                    <th>Tanggal</th>
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

    var dataPenjualan;

    $(document).ready(function() {
        dataPenjualan = $('#table_penjualan').DataTable({
            serverSide: true,
            ajax: {
                "url": "{{ url('penjualan/list') }}",
                "dataType": "json",
                "type": "POST",
                "data": function(s) {
                    s.user_id = $('#supplier_id').val();
                    console.log(s.user_id);
                }
            },
            columns: [
                {
                    data: "penjualan_id",
                    className: "",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "user.nama", 
                    className: "",
                    orderable: false,
                    searchable: true
                },
                {
                    data: "pembeli",
                    className: "",
                    orderable: false,
                    searchable: true
                },
                {
                    data: "penjualan_kode",
                    className: "",
                    orderable: false,
                    searchable: true
                },
                {
                    data: "jumlah_barang", // Aggregate the number of items sold
                    className: "",
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row){
                        return row.penjualan_detail.length; // Count the details rows
                    }
                },
                {
                    data: "total_harga", // Aggregate the total price
                    className: "",
                    orderable: true,
                    searchable: false,
                    render: function(data, type, row){
                        let total = 0;
                        row.penjualan_detail.forEach(function(detail) {
                            total += detail.harga * detail.jumlah; // Calculate total price for each item
                        });
                        return new Intl.NumberFormat('id-ID').format(total); // Format total as currency
                    }
                },
                {
                    data: "penjualan_tanggal",
                    className: "",
                    orderable: true,
                    searchable: false
                },
                {
                    data: "aksi", // Action buttons
                    className: "",
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#supplier_id').on('change', function() {
            dataPenjualan.ajax.reload(); // Reload data when filter changes
        });
    });
</script>

@endpush
