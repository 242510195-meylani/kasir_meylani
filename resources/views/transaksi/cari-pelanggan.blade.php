<form action="#" method="get" id="formCariPelanggan">
    <div class="input-group">
        <input type="text" class="form-control" placeholder="Nama Pelanggan" id="searchPelanggan">
        <div class="input-group-append">
            <button type="submit" class="btn btn-primary">
                Cari
            </button>
        </div>
    </div>
</form>
<table class="table table-sm mt-3">
    <thead>
        <tr>
            <th colspan="2" class="border-0">Hasil Pencarian :</th>
        </tr>
    </thead>
    <tbody id="resultPelanggan"></tbody>
</table>

@push('scripts')
<script>
    $(function() {
        $('#formCariPelanggan').submit(function(e) {
            e.preventDefault();
            const search = $('#searchPelanggan').val();

            if (search.length >= 3) {
                fetchCariPelanggan(search)
            }
        })
    })

    function fetchCariPelanggan(search) {
        $.getJSON("/transaksi/pelanggan", { search: search })
            .done(function(response) {
                $('#resultPelanggan').html('');
                response.forEach(addResultPelanggan);
            })
            .fail(function() {
                $('#resultPelanggan').html('<tr><td colspan="2">Pencarian pelanggan gagal.</td></tr>');
            });
    }

    function addResultPelanggan(item) {
        const {
            id,
            nama
        } = item

        const btn = `<button type="button" class="btn btn-xs btn-success" onclick="addPelanggan(${id})">
            Pilih
        </button>`;

        const row = `<tr>
            <td>${nama}</td>
            <td class="text-right">${btn}</td>
        </tr>`;
        $('#resultPelanggan').append(row)
    }

    function addPelanggan(id) {
        $.ajax({
            url: "/transaksi/pelanggan",
            type: "POST",
            data: { id: id },
            dataType: "json"
        }).done(function() {
            fetchCart();
        }).fail(function(xhr) {
            alert(xhr.responseJSON?.message || 'Pelanggan gagal dipilih.');
        });
    }
</script>
@endpush