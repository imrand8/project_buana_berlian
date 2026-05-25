@extends('admin.partials.master')

@section('page_title', 'Pengaturan & Profil')
@section('page_subtitle', 'Kelola informasi akun admin dan keamanan sistem.')

@section('content')

<style>
    /* Form Inputs */
    .form-label-custom {
        font-size: 0.75rem; font-weight: 800; color: var(--text-muted);
        text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;
    }
    .form-control-custom {
        background: var(--input-bg); border: 1px solid var(--border-color); color: var(--text-main);
        padding: 12px 18px; border-radius: 12px; font-size: 0.9rem; width: 100%; transition: 0.3s;
    }
    .form-control-custom:focus { border-color: var(--p-color); outline: none; box-shadow: 0 0 0 4px rgba(72, 61, 139, 0.1); }
    [data-theme="dark"] .form-control-custom:focus { box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1); border-color: #d4af37; }

    /* Profile Avatar Upload */
    .profile-upload { position: relative; width: 150px; height: 150px; margin: 0 auto 20px; }
    .profile-upload img { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 4px solid var(--bg-body); box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    .upload-btn {
        position: absolute; bottom: 5px; right: 5px; width: 40px; height: 40px; border-radius: 50%;
        background: var(--p-color); color: white; border: 3px solid var(--card-bg);
        display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.3s;
    }
    .upload-btn:hover { transform: scale(1.1); }
    [data-theme="dark"] .upload-btn { background: #d4af37; color: black; }

    /* Buttons */
    .btn-save { background: var(--p-color); color: white; border: none; padding: 12px 25px; border-radius: 12px; font-weight: 700; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; }
    .btn-save:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(72, 61, 139, 0.2); color: white; }
    [data-theme="dark"] .btn-save { background: #d4af37; color: black; }
    [data-theme="dark"] .btn-save:hover { box-shadow: 0 10px 20px rgba(212, 175, 55, 0.2); }

    .toggle-pwd { position: absolute; right: 15px; top: 40px; color: var(--text-muted); cursor: pointer; font-size: 1.2rem; transition: 0.2s;}
    .toggle-pwd:hover { color: var(--p-color); }
    [data-theme="dark"] .toggle-pwd:hover { color: #d4af37; }

    /* ========================================================
       FIX NOTIFIKASI (ALERT) BIAR ELEGAN DI DARK MODE
       ======================================================== */
    [data-theme="dark"] .alert-success {
        background-color: rgba(25, 135, 84, 0.15) !important;
        color: #75b798 !important;
        border: 1px solid rgba(25, 135, 84, 0.3) !important;
    }
    [data-theme="dark"] .alert-danger {
        background-color: rgba(220, 53, 69, 0.15) !important;
        color: #ea868f !important;
        border: 1px solid rgba(220, 53, 69, 0.3) !important;
    }
</style>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="content-card text-center h-100">
            <h5 class="fw-bold mb-4">Foto Profil</h5>
            
            <div class="profile-upload">
                @php
                    $words = explode(' ', Auth::user()->name ?? 'Admin');
                    $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                    $colors = ['bg-primary', 'bg-success', 'bg-danger', 'bg-warning text-dark', 'bg-info text-dark', 'bg-secondary'];
                    $avatarColor = $colors[(Auth::user()->id ?? 1) % count($colors)];
                @endphp

                @if(Auth::user()->avatar)
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Foto Admin" id="preview-image">
                @else
                    <div class="{{ $avatarColor }}" id="preview-avatar-initial" style="width: 100%; height: 100%; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 3.5rem; font-weight: 800; color: white; border: 4px solid var(--bg-body); box-shadow: 0 10px 30px rgba(0,0,0,0.1);">{{ $initials }}</div>
                    <img src="" alt="Foto Admin" id="preview-image" style="display: none;">
                @endif

                <label for="file-upload" class="upload-btn" title="Ganti Foto">
                    <i class="bi bi-camera-fill"></i>
                </label>
                <input id="file-upload" type="file" name="avatar" style="display: none;" accept="image/*" form="form-profil" onchange="
                    document.getElementById('preview-image').src = window.URL.createObjectURL(this.files[0]);
                    document.getElementById('preview-image').style.display = 'block';
                    if(document.getElementById('preview-avatar-initial')) { document.getElementById('preview-avatar-initial').style.display = 'none'; }
                ">
            </div>

            <h5 class="fw-bold mb-1">{{ Auth::user()->name }}</h5>
            <span class="status-badge bg-process mb-3">Super Administrator</span>
            
            <hr class="opacity-10 my-4 border-secondary">
            
            <div class="text-start">
                <div class="text-muted small mb-2"><i class="bi bi-envelope me-2"></i> {{ Auth::user()->email }}</div>
                <div class="text-muted small"><i class="bi bi-shield-check me-2"></i> Akun Aktif & Terverifikasi</div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="content-card">
            
            @if(session('success'))
                <div class="alert alert-success small fw-bold">{{ session('success') }}</div>
            @endif

            <h5 class="fw-bold mb-4">Informasi Pribadi</h5>
            <form id="form-profil" action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label-custom">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control-custom" value="{{ Auth::user()->name }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Nomor WhatsApp</label>
                        <input type="text" name="phone" class="form-control-custom" value="{{ Auth::user()->phone }}" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label-custom">Alamat Email (Login)</label>
                        <input type="email" class="form-control-custom" value="{{ Auth::user()->email }}" disabled style="opacity: 0.7;">
                    </div>
                </div>

                <div class="d-flex justify-content-end mb-5">
                    <button type="submit" class="btn-save"><i class="bi bi-check2-circle"></i> Simpan Perubahan</button>
                </div>
            </form>

            <hr class="opacity-10 my-4 border-secondary">

            @if(session('success_password'))
                <div class="alert alert-success small fw-bold">{{ session('success_password') }}</div>
            @endif
            @if($errors->has('old_password') || $errors->has('password'))
                <div class="alert alert-danger small fw-bold">{{ $errors->first('old_password') }} {{ $errors->first('password') }}</div>
            @endif

            <h5 class="fw-bold mb-4 text-danger"><i class="bi bi-lock-fill me-2"></i>Keamanan Akun</h5>
            <form action="{{ route('admin.password.update') }}" method="POST">
                @csrf
                <div class="row g-3 mb-4">
                    <div class="col-md-12" style="position: relative;">
                        <label class="form-label-custom">Password Saat Ini</label>
                        <input type="password" name="old_password" id="old-pwd" class="form-control-custom" required>
                        <i class="bi bi-eye-slash toggle-pwd" onclick="togglePassword('old-pwd', this)"></i>
                    </div>
                    <div class="col-md-6" style="position: relative;">
                        <label class="form-label-custom">Password Baru</label>
                        <input type="password" name="password" id="new-pwd" class="form-control-custom" required minlength="8">
                        <i class="bi bi-eye-slash toggle-pwd" onclick="togglePassword('new-pwd', this)"></i>
                    </div>
                    <div class="col-md-6" style="position: relative;">
                        <label class="form-label-custom">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="conf-pwd" class="form-control-custom" required minlength="8">
                        <i class="bi bi-eye-slash toggle-pwd" onclick="togglePassword('conf-pwd', this)"></i>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn-save" style="background: var(--text-main); color: var(--card-bg);"><i class="bi bi-shield-lock"></i> Update Password</button>
                </div>
            </form>

        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePassword(inputId, iconEl) {
        const input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            iconEl.classList.replace("bi-eye-slash", "bi-eye");
            iconEl.style.color = "var(--p-color)";
        } else {
            input.type = "password";
            iconEl.classList.replace("bi-eye", "bi-eye-slash");
            iconEl.style.color = "var(--text-muted)";
        }
    }
</script>
@endpush

@endsection