<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="form-group">
        <label for="current_password">Password Saat Ini</label>
        <input id="current_password" name="current_password" type="password"
            class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
            autocomplete="current-password">
        @error('current_password', 'updatePassword')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="password">Password Baru</label>
        <input id="password" name="password" type="password"
            class="form-control @error('password', 'updatePassword') is-invalid @enderror"
            autocomplete="new-password">
        @error('password', 'updatePassword')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="password_confirmation">Konfirmasi Password Baru</label>
        <input id="password_confirmation" name="password_confirmation" type="password"
            class="form-control" autocomplete="new-password">
    </div>

    <button type="submit" class="btn btn-warning">Update Password</button>
    @if(session('status') === 'password-updated')
        <span class="text-success ml-2"><small>Password berhasil diubah.</small></span>
    @endif
</form>
