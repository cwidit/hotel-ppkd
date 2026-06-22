@extends('layouts.admin')

@section('title', 'Profil Saya')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Profil Saya</h3>
            <p class="text-subtitle text-muted">Kelola informasi akun dan keamanan Anda.</p>
        </div>
    </div>
</div>

<section class="section">
    <div class="row">
        <div class="col-md-6">
            {{-- Profile Information --}}
            <div class="card">
                <div class="card-header">Informasi Profil</div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Update Password --}}
            <div class="card mt-3">
                <div class="card-header">Ubah Password</div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <div class="col-md-6">
            {{-- Delete Account --}}
            @unless(auth()->user()->hasRole('Administrator'))
            <div class="card border-danger">
                <div class="card-header text-danger">Hapus Akun</div>
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
            @endunless
        </div>
    </div>
</section>
@endsection
