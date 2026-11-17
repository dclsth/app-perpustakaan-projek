@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
    <h1>Profil Saya</h1>
    <div class="table-wrap"><table class="info">
        <tr><th>Nama akun</th><td>{{ auth()->user()->name }}</td></tr>
        <tr><th>Email</th><td>{{ auth()->user()->email }}</td></tr>
        <tr><th>Peran</th><td>Member</td></tr>
        @if ($member)
            <tr><th>NIM</th><td>{{ $member->nim }}</td></tr>
            <tr><th>Nomor telepon</th><td>{{ $member->nomor_telepon }}</td></tr>
            <tr><th>Alamat</th><td>{{ $member->alamat }}</td></tr>
            <tr><th>Status keanggotaan</th><td>{{ ucfirst($member->status) }}</td></tr>
        @else
            <tr><th>Data anggota</th><td>Belum ada data anggota yang terhubung ke email akun ini.</td></tr>
        @endif
    </table></div>
@endsection
