<p class="text-muted">Setelah akun dihapus, semua data akan hilang permanen.</p>

<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteAccountModal">
    Hapus Akun Saya
</button>

<div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Konfirmasi Hapus Akun</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                <div class="modal-body">
                    <p>Masukkan password Anda untuk konfirmasi penghapusan akun.</p>
                    <div class="form-group">
                        <label for="del_password">Password</label>
                        <input id="del_password" name="password" type="password"
                            class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                            placeholder="Password">
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>
