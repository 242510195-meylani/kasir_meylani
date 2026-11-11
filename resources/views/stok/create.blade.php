@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Tambah Stok Barang</h3>
        </div>
        
        <form action="{{ route('stok.store') }}" method="POST">
            @csrf
            <div class="card-body">
                
                <!-- Input Nama Produk dengan Modal Trigger -->
                <div class="form-group">
                    <label>Nama Produk</label>
                    <div class="input-group">
                        <input type="hidden" name="produk_id" id="produk_id" value="{{ old('produk_id') }}">
                        <input type="text" id="nama_produk" class="form-control @error('produk_id') is-invalid @enderror" placeholder="Pilih Produk" readonly data-toggle="modal" data-target="#modalCariProduk">
                        <span class="input-group-append">
                            <button type="button" id="btn-buka-modal-produk" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </span>
                        @error('produk_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Input Jumlah -->
                <div class="form-group">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror" value="{{ old('jumlah') }}" placeholder="Masukkan jumlah stok">
                    @error('jumlah')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Input Nama Suplier -->
                <div class="form-group">
                    <label for="nama_suplier">Nama Suplier</label>
                    <input type="text" name="nama_suplier" class="form-control @error('nama_suplier') is-invalid @enderror" value="{{ old('nama_suplier') }}" placeholder="Masukkan nama suplier">
                    @error('nama_suplier')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan Stok</button>
                <a href="{{ route('stok.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<!-- Modal Cari Produk -->
<div class="modal fade" id="modalCariProduk" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cari Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <div class="input-group mb-3">
                    <input type="text" id="keyword" class="form-control" placeholder="Cari nama produk...">
                    <div class="input-group-append">
                        <button type="button" id="btn-search" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="10%">#</th>
                            <th>Nama Produk</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="hasil-pencarian">
                        <!-- Data akan dimuat lewat AJAX -->
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fungsi untuk memuat data produk ke dalam modal
    function loadProduk(search = '') {
        $.ajax({
            url: "{{ route('stok.produk') }}",
            type: "GET",
            data: { search: search },
            success: function(data) {
                let rows = '';
                if(data.length > 0) {
                    $.each(data, function(index, produk) {
                        rows += `<tr>
                            <td>${index + 1}</td>
                            <td>${produk.nama_produk}</td>
                            <td>
                                <button type="button" class="btn btn-success btn-sm pilih-produk" data-id="${produk.id}" data-nama="${produk.nama_produk}">
                                    Add
                                </button>
                            </td>
                        </tr>`;
                    });
                } else {
                    rows = `<tr><td colspan="3" class="text-center">Produk tidak ditemukan</td></tr>`;
                }
                $('#hasil-pencarian').html(rows);
            }
        });
    }

    // Saat modal dibuka, muat semua produk
    $(function () {
        $('#btn-buka-modal-produk, #nama_produk').on('click', function(e) {
            e.preventDefault();
            $('#modalCariProduk').modal('show');
            loadProduk();
        });

        $('#btn-search').on('click', function(e) {
            e.preventDefault();
            let keyword = $('#keyword').val();
            loadProduk(keyword);
        });

        $('#keyword').on('keyup', function() {
            let keyword = $(this).val();
            loadProduk(keyword);
        });

        $(document).on('click', '.pilih-produk', function() {
            let id = $(this).data('id');
            let nama = $(this).data('nama');

            $('#produk_id').val(id);
            $('#nama_produk').val(nama);
            $('#modalCariProduk').modal('hide');
        });
    });
</script>
@endpush