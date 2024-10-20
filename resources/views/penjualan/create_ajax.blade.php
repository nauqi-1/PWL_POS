<form action="{{ url('/penjualan/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>User</label>
                    <select name="user_id" id="user" class="form-control" required>
                        <option value="">- Pilih User -</option>
                        @foreach($user as $s)
                            <option value="{{ $s->user_id }}">{{ $s->nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-user_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Pembeli</label>
                    <input type="text" name="pembeli" id="pembeli" class="form-control" required>
                    <small id="error-pembeli" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Kode Penjualan</label>
                    <input type="text" name="penjualan_kode" id="penjualan_kode" class="form-control" required>
                    <small id="error-penjualan_kode" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Items Dibeli</label>
                    <div id="items-container">
                        <div class="row item-row">
                            <div class="col-md-4">
                                <label>Nama Barang</label>
                                <select name="items[0][barang_id]" class="form-control" required>
                                    <option value="">- Pilih Barang -</option>
                                    @foreach($barang as $item)
                                        <option value="{{ $item->barang_id }}">{{ $item->barang_nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Jumlah</label>
                                <input type="number" name="items[0][jumlah]" class="form-control jumlah-barang" required>
                            </div>
                            <div class="col-md-3">
                                <label>Harga</label>
                                <input type="number" name="items[0][harga]" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-danger remove-item">Hapus</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success" id="add-item">Tambah Barang</button>
                    <small class="form-text text-muted">Tambah barang yang dibeli oleh pembeli</small>
                </div>
                <div class="form-group">
                    <label>Tanggal Penjualan</label>
                    <input type="datetime-local" name="penjualan_tanggal" id="penjualan_tanggal" class="form-control" required>
                    <small id="error-penjualan_tanggal" class="error-text form-text text-danger"></small>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        let itemIndex = 1; 
        let validator = $("#form-tambah").validate({
            rules: {
                pembeli: { required: true },
                penjualan_kode: { required: true, minlength: 3 },
                penjualan_tanggal: { required: true },
                user_id: { required: true, number: true },
                "items[][jumlah]": { required: true, number: true, min: 1 },
                "items[][harga]": { required: true, number: true, min: 1 }
            },
            messages: {
                "items[][jumlah]": {
                    required: "Jumlah barang harus diisi",
                    number: "Jumlah harus berupa angka",
                    min: "Jumlah minimal 1"
                },
                "items[][harga]": {
                    required: "Harga harus diisi",
                    number: "Harga harus berupa angka",
                    min: "Harga minimal 1"
                }
            },
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
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        // Adding new item row
        $('#add-item').on('click', function() {
            const newItemRow = `
                <div class="row item-row">
                    <div class="col-md-4">
                        <label>Nama Barang</label>
                        <select name="items[${itemIndex}][barang_id]" class="form-control" required>
                            <option value="">- Pilih Barang -</option>
                            @foreach($barang as $item)
                                <option value="{{ $item->barang_id }}">{{ $item->barang_nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Jumlah</label>
                        <input type="number" name="items[${itemIndex}][jumlah]" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label>Harga</label>
                        <input type="number" name="items[${itemIndex}][harga]" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-danger remove-item">Hapus</button>
                    </div>
                </div>
            `;
            $('#items-container').append(newItemRow);
            itemIndex++; // Increment item index

            $('.remove-item').off('click').on('click', function() {
                $(this).closest('.item-row').remove();
            });
        });

        $(document).on('click', '.remove-item', function() {
            $(this).closest('.item-row').remove();
        });
    });
</script>
