
<form action="#" class="card card-orange card-outline" id="formBarcode">
    <div class="card-body">
        <div class="input-group">
            <input type="text" class="form-control" id="barcode"
                placeholder="Kode / Barcode" autocomplete="off">

            <div class="input-group-append">
                <button type="reset" class="btn btn-danger" id="resetBarcode">
                    Clear
                </button>
            </div>
        </div>

        <div class="invalid-feedback" id="msgErrorBarcode"></div>
    </div>
</form>

@push('scripts')
<script>
    $(function () {
        $('#barcode').focus();

        $('#formBarcode').on('submit', function (e) {
            e.preventDefault();

            const kode = $('#barcode').val().trim();

            if (kode !== '') {
                addItem(kode);
            }
        });

        $('#resetBarcode').on('click', function () {
            $('#barcode').val('').focus();
            $('#msgErrorBarcode').removeClass('d-block').text('');
        });
    });

    function addItem(kode_produk) {
        const input = $('#barcode');

        $('#msgErrorBarcode').removeClass('d-block').text('');
        input.removeClass('is-invalid');

        $.ajax({
            url: '/cart',
            type: 'POST',
            data: {
                kode_produk: kode_produk
            },
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        .done(function (response) {
            input.val('');
            fetchCart();

            if (typeof $('#searchProduk').val() !== 'undefined') {
                $('#searchProduk').val('');
                $('#resultProduk').empty();
            }
        })
        .fail(function (xhr) {
            const pesan = xhr.responseJSON?.message ||
                'Produk gagal ditambahkan.';

            $('#msgErrorBarcode')
                .addClass('d-block')
                .text(pesan);

            input.addClass('is-invalid');
        })
        .always(function () {
            input.focus();
        });
    }
</script>
@endpush
