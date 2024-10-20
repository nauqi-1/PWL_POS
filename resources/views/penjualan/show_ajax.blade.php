@empty($penjualan)
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                Data yang anda cari tidak ditemukan
            </div>
            <a href="{{ url('/penjualan') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/penjualan/' . $penjualan->penjualan_id . '/delete_ajax') }}" method="POST" id="form-delete">
    @csrf
    @method('DELETE')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data Transaksi Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">Kasir :</th>
                        <td class="col-9">{{ $penjualan->user->nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Pembeli :</th>
                        <td class="col-9">{{ $penjualan->pembeli }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Kode :</th>
                        <td class="col-9">{{ $penjualan->penjualan_kode }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal :</th>
                        <td class="col-9">{{ $penjualan->penjualan_tanggal }}</td>
                    </tr>
                </table>

                <div class="form-group">
                    <label>Barang yang dibeli:</label>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalPrice = 0; @endphp
                            @foreach($penjualan->penjualan_detail as $item)
                                <tr>
                                    <td>{{ $item->barang->barang_nama }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td>{{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td>{{ number_format($item->jumlah * $item->harga, 0, ',', '.') }}</td>
                                </tr>
                                @php $totalPrice += $item->jumlah * $item->harga; @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-right">Total Harga</th>
                                <th>{{ number_format($totalPrice, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Kembali</button>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    $("#form-delete").validate({
        rules: {},
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        dataPenjualan.ajax.reload();
                    } else {
                        $('.error-text').text('');
                        $.each(response.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message
                        });
                    }
                }
            });
            return false;
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});
</script>
@endempty
