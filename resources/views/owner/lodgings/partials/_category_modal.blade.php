<!-- Modal Pilihan Kategori Usaha -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 bg-primary text-white p-4">
                <div>
                    <h5 class="modal-title fw-bold" id="categoryModalLabel"><i class="fa-solid fa-layer-group me-2"></i>Pilih Kategori Tempat Usaha</h5>
                    <p class="small mb-0 text-white-50">Pilih jenis tempat usaha yang ingin Anda daftarkan ke sistem Lokavino</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="row g-3">
                    <!-- Option 1: Penginapan -->
                    <div class="col-md-4">
                        <a href="{{ route('owner.lodgings.create', ['category' => 'penginapan']) }}" class="text-decoration-none">
                            <div class="card border-0 shadow-sm rounded-4 h-100 p-3 text-center category-card hover-lift transition-all">
                                <div class="icon-circle bg-primary bg-opacity-10 text-primary mx-auto mb-3" style="width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                    🏨
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Penginapan</h6>
                                <p class="small text-muted mb-0">Hotel, Homestay, Villa, Kost, Resort, dsb.</p>
                                <span class="badge bg-primary rounded-pill px-3 py-1 mt-3">Pilih Kategori</span>
                            </div>
                        </a>
                    </div>

                    <!-- Option 2: Nongkrong / Kafe -->
                    <div class="col-md-4">
                        <a href="{{ route('owner.lodgings.create', ['category' => 'nongkrong']) }}" class="text-decoration-none">
                            <div class="card border-0 shadow-sm rounded-4 h-100 p-3 text-center category-card hover-lift transition-all">
                                <div class="icon-circle bg-warning bg-opacity-10 text-warning mx-auto mb-3" style="width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                    ☕
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Kafe & Nongkrong</h6>
                                <p class="small text-muted mb-0">Coffee shop, Resto, Angkringan, Cafe, dsb.</p>
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1 mt-3">Pilih Kategori</span>
                            </div>
                        </a>
                    </div>

                    <!-- Option 3: Tempat Wisata -->
                    <div class="col-md-4">
                        <a href="{{ route('owner.lodgings.create', ['category' => 'wisata']) }}" class="text-decoration-none">
                            <div class="card border-0 shadow-sm rounded-4 h-100 p-3 text-center category-card hover-lift transition-all">
                                <div class="icon-circle bg-info bg-opacity-10 text-info mx-auto mb-3" style="width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                    🏖️
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Tempat Wisata</h6>
                                <p class="small text-muted mb-0">Wisata Alam, Sejarah, Religius, Buatan, dsb.</p>
                                <span class="badge bg-info text-dark rounded-pill px-3 py-1 mt-3">Pilih Kategori</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
