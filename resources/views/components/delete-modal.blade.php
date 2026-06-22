@props(['id' => 'deleteModal'])

<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 10px 40px rgba(0,0,0,0.15);">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title">
                    <i data-feather="alert-triangle" style="width:18px;height:18px;color:#dc3545;margin-right:8px;"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body py-3">
                <p class="mb-0">Yakin ingin menghapus <strong id="deleteLabel">data ini</strong>?</p>
                <small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-delete').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var action = this.dataset.action;
            var label  = this.dataset.label || 'data ini';
            document.getElementById('deleteForm').action  = action;
            document.getElementById('deleteLabel').textContent = label;
            $('#{{ $id }}').modal('show');
        });
    });
});
</script>
@endpush
