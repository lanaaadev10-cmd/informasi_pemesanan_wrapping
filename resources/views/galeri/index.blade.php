<h2>Galeri Hasil Pekerjaan</h2>

<div style="display:flex; flex-wrap:wrap; gap:20px;">
@foreach($galeri as $item)
    <div style="width:250px; border:1px solid #ccc; padding:10px;">

        <img src="{{ asset('storage/' . $item->foto) }}" 
             style="width:100%; height:150px; object-fit:cover;">

        <h3>{{ $item->judul }}</h3>

        <p>{{ $item->deskripsi }}</p>

        <small>{{ $item->tanggal_upload }}</small>

    </div>
@endforeach
</div>