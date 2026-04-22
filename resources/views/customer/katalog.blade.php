<h2>Katalog Layanan</h2>

@foreach($layanan as $item)
    <div style="border:1px solid #ccc; padding:5px; margin:5px;">
        <h3>{{ $item->nama_layanan }}</h3>
        <p>{{ $item->deskripsi }}</p>
// oke baik
        @if($item->tipe_layanan == 'fix')
            <p>Rp {{ number_format($item->harga) }}</p>
        @else
            <p>Custom</p>
        @endif
    </div>
@endforeach