<form id="formEditPenjualan">
    @csrf
    @method('PUT')
    <input type="hidden" name="penjualan_id" value="{{ $penjualan->penjualan_id }}">
    <div class="form-group">
        <label for="user_id">User</label>
        <select class="form-control" id="user_id" name="user_id" required>
            <option value="">- Pilih User -</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ $penjualan->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
        <div class="invalid-feedback" id="error-user_id"></div>
    </div>
    <div class="form-group">
        <label for="pembeli">Nama Pembeli</label>
        <input type="text" class="form-control" id="pembeli" name="pembeli" value="{{ $penjualan->pembeli }}" required>
        <div class="invalid-feedback" id="error-pembeli"></div>
    </div>
    <div class="form-group">
        <label for="penjualan_kode">Kode Penjualan</label>
        <input type="text" class="form-control" id="penjualan_kode" name="penjualan_kode" value="{{ $penjualan->penjualan_kode }}" required>
        <div class="invalid-feedback" id="error-penjualan_kode"></div>
    </div>
    <div class="form-group">
        <label for="penjualan_tanggal">Tanggal Penjualan</label>
        <input type="datetime-local" class="form-control" id="penjualan_tanggal" name="penjualan_tanggal" value="{{ $penjualan->penjualan_tanggal }}" required>
        <div class="invalid-feedback" id="error-penjualan_tanggal"></div>
    </div>
</form>