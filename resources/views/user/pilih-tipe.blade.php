@extends('layouts.app')

@section('title', 'Pilih Tipe Pendaftaran')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- Header -->
            <div class="text-center mb-4">
                <h4 class="fw-bold" style="color: #dc3545;">
                    <i class="fas fa-clipboard-list me-2"></i>Pilih Tipe Pendaftaran
                </h4>
                <p class="text-muted">Pilih jenis pendaftaran Anda</p>
            </div>

            <!-- Form Card -->
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <form action="{{ route('user.proses-pilih-tipe') }}" method="POST">
                        @csrf
                        
                        <!-- HANYA PILIHAN JENIS PENDAFTARAN -->
                        <div class="mb-4">
                            <label class="form-label fw-bold mb-3">
                                <i class="fas fa-tag me-2" style="color: #dc3545;"></i>Jenis Pendaftaran
                            </label>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check card-option p-3 border rounded-3" style="cursor: pointer;">
                                        <input class="form-check-input" type="radio" name="tipe" id="tipeIndividu" value="individu" required style="accent-color: #dc3545;">
                                        <label class="form-check-label w-100" for="tipeIndividu">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3" style="width: 40px; height: 40px; background: #fff5f5; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-user" style="color: #dc3545;"></i>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-0">Individu</h6>
                                                    <small class="text-muted">Daftar sendiri</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-check card-option p-3 border rounded-3" style="cursor: pointer;">
                                        <input class="form-check-input" type="radio" name="tipe" id="tipeKelompok" value="kelompok" style="accent-color: #dc3545;">
                                        <label class="form-check-label w-100" for="tipeKelompok">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3" style="width: 40px; height: 40px; background: #fff5f5; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-users" style="color: #dc3545;"></i>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-0">Kelompok</h6>
                                                    <small class="text-muted">Daftar bersama tim</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Tambah pesan kecil untuk membantu user -->
                            <div class="mt-3 p-3 bg-light rounded-3">
                                <small class="text-muted d-block">
                                    <i class="fas fa-info-circle me-1" style="color: #dc3545;"></i>
                                    <strong>Individu:</strong> Akan diarahkan ke form pendaftaran individu
                                </small>
                                <small class="text-muted d-block mt-1">
                                    <i class="fas fa-info-circle me-1" style="color: #dc3545;"></i>
                                    <strong>Kelompok:</strong> Akan diarahkan ke form pendaftaran kelompok (minimal 2 orang, maksimal 5 orang)
                                </small>
                            </div>
                        </div>
                        
                        <!-- Tombol Submit -->
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn text-white py-2 fw-bold" style="background-color: #dc3545; border-radius: 50px;">
                                <i class="fas fa-arrow-right me-2"></i>Lanjutkan
                            </button>
                        </div>
                        
                        <!-- Link Kembali -->
                        <div class="text-center mt-3">
                            <a href="{{ route('user.home') }}" class="text-decoration-none small" style="color: #6c757d;">
                                <i class="fas fa-chevron-left me-1"></i>Kembali ke Home
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card-option {
        transition: all 0.2s ease;
        border: 2px solid #dee2e6 !important;
    }
    .card-option:hover {
        border-color: #dc3545 !important;
        background-color: #fff5f5;
    }
    .card-option:has(input:checked) {
        border-color: #dc3545 !important;
        background-color: #fff5f5;
    }
    /* Untuk browser yang tidak support :has */
    .card-option.selected {
        border-color: #dc3545 !important;
        background-color: #fff5f5;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cardOptions = document.querySelectorAll('.card-option');
        const radioIndividu = document.getElementById('tipeIndividu');
        const radioKelompok = document.getElementById('tipeKelompok');
        
        // Fungsi untuk update class selected
        function updateSelected() {
            cardOptions.forEach(option => {
                option.classList.remove('selected');
            });
            
            if (radioIndividu.checked) {
                radioIndividu.closest('.card-option').classList.add('selected');
            } else if (radioKelompok.checked) {
                radioKelompok.closest('.card-option').classList.add('selected');
            }
        }
        
        // Event listener untuk radio buttons
        radioIndividu.addEventListener('change', updateSelected);
        radioKelompok.addEventListener('change', updateSelected);
        
        // Event listener untuk card
        cardOptions.forEach(option => {
            option.addEventListener('click', function(e) {
                // Cegah jika yang diklik adalah radio button itu sendiri
                if (e.target.type !== 'radio') {
                    const radio = this.querySelector('input[type="radio"]');
                    if (radio) {
                        radio.checked = true;
                        updateSelected();
                    }
                }
            });
        });
        
        // Initial check
        updateSelected();
    });
</script>
@endpush
@endsection