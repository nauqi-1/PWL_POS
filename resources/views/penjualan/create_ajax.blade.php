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
                    <input type="text" class="form-control" value="{{ auth()->user()->nama }}" readonly>
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
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
                                <select name="items[0][barang_id]" class="form-control check-harga" required>
                                    <option value="">- Pilih Barang -</option>
                                    @foreach($barang as $item)
                                        <option data-harga="{{$item->harga_jual}}" data-image="{{$item->image}}" value="{{ $item->barang_id }}">{{ $item->barang_nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Jumlah</label>
                                <input type="number" name="items[0][jumlah]" class="form-control jumlah-barang" required>
                            </div>
                            <div class="col-md-3">
                                <label>Harga</label>
                                <input type="number" name="items[0][harga]" class="form-control harga" required>
                            </div>
                            <div class="col-md-3">
                                <label>Image</label>
                                <img src="" alt="Gambar Barang" style="max-width: 100px; height: auto;">                            </div>
                            <div class="col-md-1">
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
    
        function updateHarga() {
            $(document).on('change', '.check-harga', function() {
                let selectedHarga = $('option:selected', this).data('harga');
                $(this).closest('.item-row').find('.harga').val(selectedHarga);
                updateImage.call(this); 
            });
        }
    
        function updateImage() {
            let selectedImage = $('option:selected', this).data('image');
            $(this).closest('.item-row').find('img').attr('src', selectedImage);
            console.log(selectedImage);
            console.log(selectedHarga);

            
        }
    
        updateHarga();
    
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
    
        $('#add-item').on('click', function() {
            const newItemRow = `
                <div class="row item-row">
                    <div class="col-md-4">
                        <label>Nama Barang</label>
                        <select name="items[${itemIndex}][barang_id]" class="form-control check-harga" required>
                            <option value="" data-harga="">- Pilih Barang -</option>
                            @foreach($barang as $item)
                                        <option data-harga="{{$item->harga_jual}}" data-image="{{$item->image}}" value="{{ $item->barang_id }}">{{ $item->barang_nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Jumlah</label>
                        <input type="number" name="items[${itemIndex}][jumlah]" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label>Harga</label>
                        <input type="number" name="items[${itemIndex}][harga]" class="form-control harga" required>
                    </div>
                    <div class="col-md-3">
                        <label>Image</label>
                        <img src="" alt="Gambar Barang" style="max-width: 100px; height: auto;">
                    </div>
                    <div class="col-md-1">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-danger remove-item">Hapus</button>
                    </div>
                </div>
            `;
            $('#items-container').append(newItemRow);
            itemIndex++;
        });
    
        $(document).on('click', '.remove-item', function() {
            $(this).closest('.item-row').remove();
        });
    });
    </script>
    