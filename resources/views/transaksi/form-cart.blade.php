
<div class="card card-orange card-outline">
    <div class="card-body">
        <h3 class="m-0 text-right">
            Rp <span id="totalJumlah">0</span>,-
        </h3>
    </div>
</div>

<form action="{{ route('transaksi.store') }}" method="POST"
    class="card card-orange card-outline" id="formTransaksi">

    @csrf

    <div class="card-body">

        <p class="text-right">
            Tanggal : {{ $tanggal }}
        </p>

        <div class="row">
            <div class="col">
                <label>Nama Pelanggan</label>

                <input type="text"
                    id="namaPelanggan"
                    class="form-control @error('pelanggan_id') is-invalid @enderror"
                    disabled>

                <input type="hidden"
                    name="pelanggan_id"
                    id="pelangganId">

                @error('pelanggan_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col">
                <label>Nama Kasir</label>

                <input type="text"
                    class="form-control"
                    value="{{ $nama_kasir ?? auth()->user()->nama ?? auth()->user()->name ?? '' }}"
                    disabled>
            </div>
        </div>

        <table class="table table-striped table-hover table-bordered mt-3">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Sub Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody id="resultCart">
                <tr>
                    <td colspan="5" class="text-center">
                        Tidak ada data.
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="row mt-3">
            <div class="col-2 offset-6">
                <p>Subtotal</p>
                <p>Pajak 10%</p>
                <p>Total Bayar</p>
            </div>

            <div class="col-4 text-right">
                <p id="subtotal">0</p>
                <p id="taxAmount">0</p>
                <p id="total">0</p>
            </div>
        </div>

        <div class="col-6 offset-6">
            <hr class="mt-0">

            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Cash</span>
                </div>

                <input type="number"
                    name="cash"
                    id="cash"
                    class="form-control @error('cash') is-invalid @enderror"
                    placeholder="Jumlah Cash"
                    value="{{ old('cash') }}"
                    min="0"
                    required>
            </div>

            <input type="hidden"
                name="total_bayar"
                id="totalBayar">

            @error('cash')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="col-12 form-inline mt-3">

            <a href="{{ route('transaksi.index') }}"
                class="btn btn-secondary mr-2">
                Ke Transaksi
            </a>

            <button type="button"
                class="btn btn-danger"
                id="btnKosongkan">
                Kosongkan
            </button>

            <button type="submit"
                class="btn btn-success ml-auto">
                <i class="fas fa-money-bill-wave mr-2"></i>
                Bayar Transaksi
            </button>

        </div>
    </div>
</form>

<form action="{{ route('cart.clear') }}" method="POST" id="formClearCart" class="d-none">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    $(function () {
        fetchCart();

        $('#btnKosongkan').on('click', function () {
            if (confirm('Yakin ingin mengosongkan keranjang?')) {
                $('#formClearCart').submit();
            }
        });
    });

    function fetchCart() {
        $.getJSON('/cart')
            .done(function (response) {
                $('#resultCart').empty();

                const items = response.items || [];
                const daftarItem = Array.isArray(items)
                    ? items
                    : Object.values(items);

                const subtotal = Number(response.subtotal || 0);
                const pajak = Number(response.tax_amount || 0);
                const total = Number(response.total || 0);

                $('#subtotal').text(rupiah(subtotal));
                $('#taxAmount').text(rupiah(pajak));
                $('#total').text(rupiah(total));
                $('#totalJumlah').text(rupiah(total));
                $('#totalBayar').val(total);

                if (daftarItem.length === 0) {
                    $('#resultCart').html(
                        '<tr><td colspan="5" class="text-center">Tidak ada data.</td></tr>'
                    );
                } else {
                    daftarItem.forEach(function (item) {
                        addRow(item);
                    });
                }

                const pelanggan = response.extra_info?.pelanggan || null;

                $('#namaPelanggan').val(pelanggan?.nama || '');
                $('#pelangganId').val(pelanggan?.id || '');
            })
            .fail(function (xhr) {
                console.error('Gagal mengambil keranjang:', xhr.responseText);
            });
    }

    function addRow(item) {
        const hash = item.hash || item.id;
        const nama = escapeHtml(item.title || '');
        const qty = Number(item.quantity || 0);
        const harga = Number(item.price || 0);
        const subtotal = Number(item.total_price || item.subtotal || harga * qty);

        const tombol = `
            <button type="button" class="btn btn-xs btn-success"
                onclick="ePut('${hash}', 1)">+</button>

            <button type="button" class="btn btn-xs btn-primary"
                onclick="ePut('${hash}', -1)">-</button>

            <button type="button" class="btn btn-xs btn-danger"
                onclick="eDel('${hash}')">×</button>
        `;

        $('#resultCart').append(`
            <tr>
                <td>${nama}</td>
                <td>${qty}</td>
                <td>${rupiah(harga)}</td>
                <td>${rupiah(subtotal)}</td>
                <td class="text-center">${tombol}</td>
            </tr>
        `);
    }

    function escapeHtml(value) {
        return String(value).replace(/[&<>"']/g, function (char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];
        });
    }

    function rupiah(value) {
        return new Intl.NumberFormat('id-ID').format(Number(value || 0));
    }

    function ePut(hash, qty) {
        $.ajax({
            url: '/cart/' + encodeURIComponent(hash),
            type: 'PATCH',
            data: { qty: qty },
            dataType: 'json'
        })
        .done(fetchCart)
        .fail(function (xhr) {
            alert(xhr.responseJSON?.message || 'Gagal memperbarui jumlah.');
        });
    }

    function eDel(hash) {
        $.ajax({
            url: '/cart/' + encodeURIComponent(hash),
            type: 'DELETE',
            dataType: 'json'
        })
        .done(fetchCart)
        .fail(function (xhr) {
            alert(xhr.responseJSON?.message || 'Gagal menghapus produk.');
        });
    }
</script>
@endpush
