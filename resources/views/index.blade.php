@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Sekolah</h1>
    <a href="{{ route('sekolah.create') }}" class="btn btn-primary">Tambah Sekolah</a>
    <table class="table mt-3">
        <tr>
            <th>Nama</th><th>Alamat</th><th>Aksi</th>
        </tr>
        @foreach($sekolahs as $s)
        <tr>
            <td>{{ $s->nama }}</td>
            <td>{{ $s->alamat }}</td>
            <td>
                <a href="{{ route('sekolah.edit', $s->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('sekolah.destroy', $s->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Yakin?')" class="btn btn-danger btn-sm">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
