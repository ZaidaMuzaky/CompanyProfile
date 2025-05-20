@extends('layouts.logapp')

@section('title', 'Formulir Backlog')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Backlog</a></li>
    <li class="breadcrumb-item active" aria-current="page">Formulir Backlog</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold text-dark">Formulir Backlog After Service</h2>
                <p class="text-muted mb-0">Formulir untuk pelaporan backlog setelah service di Section DT, SDT, dan A2B,
                    Site Kendawangan, Kalimantan Barat.</p>
                <hr class="mt-3 mb-0">
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4">
                        <!-- Tujuan -->
                        <div class="bg-light border rounded-3 p-4 mb-4">
                            <h6 class="fw-bold mb-3">Tujuan</h6>
                            <ul class="mb-0 small text-muted">
                                <li>Memantau efektivitas pelaporan backlog setelah service secara online.</li>
                                <li>Memungkinkan approval oleh pengawas dan pemantauan langsung oleh MDV/Planner.</li>
                                <li>Memantau breakdown minor/mayor untuk backup cepat.</li>
                            </ul>
                        </div>

                        <!-- Jadwal Schedule Service -->
                        <h5 class="fw-bold text-center mb-4">Jadwal Schedule Service</h5>
                        @php
                            $header = \App\Models\BacklogHeader::first(); // atau disesuaikan
                        @endphp

                        @if ($header && $header->header_image)
                            <div class="text-center mb-4">
                                <img src="{{ Storage::url($header->header_image) }}" class="img-fluid rounded-3 shadow-sm"
                                    style="max-height: 400px; cursor: pointer;" alt="Header Backlog" data-bs-toggle="modal"
                                    data-bs-target="#imageModal">
                            </div>
                        @else
                            <div class="card border-0 shadow-sm rounded-3 text-center py-5 mb-4">
                                <div class="card-body">
                                    <i class="fas fa-image fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada jadwal service yang diunggah.</p>
                                </div>
                            </div>
                        @endif

                        <!-- Modal Gambar -->
                        @if ($header && $header->header_image)
                            <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                    <div class="modal-content bg-transparent border-0">
                                        <div class="modal-body text-center">
                                            <img src="{{ Storage::url($header->header_image) }}"
                                                class="img-fluid rounded-3 shadow-sm" alt="Gambar Besar">
                                        </div>
                                        <div class="modal-footer border-0 justify-content-center">
                                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                                                data-bs-dismiss="modal">
                                                Tutup
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Form -->
                        <form action="" method="POST" enctype="multipart/form-data">
                            @csrf
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif


                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="email" class="form-label fw-semibold">Email *</label>
                                    <input type="email" name="email" id="email"
                                        class="form-control rounded-3 @error('email') is-invalid @enderror" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="tanggal_service" class="form-label fw-semibold">Tanggal Service *</label>
                                    <input type="date" name="tanggal_service" id="tanggal_service"
                                        class="form-control rounded-3 @error('tanggal_service') is-invalid @enderror"
                                        required>
                                    @error('tanggal_service')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="nama_mekanik" class="form-label fw-semibold">Nama Mekanik *</label>
                                    <input type="text" name="nama_mekanik" id="nama_mekanik"
                                        class="form-control rounded-3 @error('nama_mekanik') is-invalid @enderror" required>
                                    @error('nama_mekanik')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="waktu_serah_terima" class="form-label fw-semibold">Waktu Serah Terima
                                        *</label>
                                    <input type="time" name="waktu_serah_terima" id="waktu_serah_terima"
                                        class="form-control rounded-3 @error('waktu_serah_terima') is-invalid @enderror"
                                        required>
                                    @error('waktu_serah_terima')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="nik" class="form-label fw-semibold">NIK *</label>
                                    <input type="text" name="nik" id="nik"
                                        class="form-control rounded-3 @error('nik') is-invalid @enderror" required>
                                    @error('nik')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="section" class="form-label fw-semibold">Section *</label>
                                    <select name="section" id="section"
                                        class="form-select rounded-3 @error('section') is-invalid @enderror" required>
                                        <option value="">-- Pilih Section --</option>
                                        <option value="Section DT">Section DT</option>
                                        <option value="Section SDT">Section SDT</option>
                                        <option value="Section A2B">Section A2B</option>
                                    </select>
                                    @error('section')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="supervisor" class="form-label fw-semibold">Supervisor Approval *</label>
                                <select name="supervisor" id="supervisor"
                                    class="form-select rounded-3 @error('supervisor') is-invalid @enderror" required>
                                    <option value="">-- Pilih Supervisor --</option>
                                    <option value="Ari Handoko">Ari Handoko</option>
                                    <option value="Teo Hermansyah">Teo Hermansyah</option>
                                    <option value="Herri Setiawan">Herri Setiawan</option>
                                    <option value="Budi Wahono">Budi Wahono</option>
                                </select>
                                @error('supervisor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="model_unit" class="form-label fw-semibold">Model Unit *</label>
                                <select name="model_unit" id="model_unit_select"
                                    class="form-select rounded-3 @error('model_unit') is-invalid @enderror" required
                                    onchange="toggleOtherInput()">
                                    <option value="">-- Pilih Model Unit --</option>
                                    @php
                                        $models = [
                                            'SL500LC-V',
                                            'PC400LC-8',
                                            'PC300SE-8M0',
                                            'PC200-8',
                                            'PC210-10M0',
                                            'PC200-8 Long Arm',
                                            'D85ESS-2',
                                            'GD825A-2',
                                            'GD535-5',
                                            'GD511A',
                                            'GD705A-4',
                                            'BW211D-40',
                                            'ACTROSS 4043s 6X4',
                                            'FD460TH-E5',
                                            'FM260JD',
                                            'FM280JD',
                                            'GIGA FVZ34',
                                            'XGA3250D2WC',
                                            'SBDT 45',
                                            'SDT 41M3',
                                        ];
                                    @endphp
                                    @foreach ($models as $model)
                                        <option value="{{ $model }}">{{ $model }}</option>
                                    @endforeach
                                    <option value="Other">Other</option>
                                </select>
                                @error('model_unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4 d-none" id="other_model_unit_div">
                                <label for="other_model_unit" class="form-label fw-semibold">Masukkan Model Unit
                                    Lainnya</label>
                                <input type="text" class="form-control rounded-3" name="other_model_unit"
                                    id="other_model_unit" placeholder="Tulis model unit lainnya">
                            </div>
                            <div class="mb-4 d-none">
                                <script>
                                    function toggleOtherInput() {
                                        const modelSelect = document.getElementById('model_unit_select');
                                        const otherInputDiv = document.getElementById('other_model_unit_div');

                                        if (modelSelect.value === 'Other') {
                                            otherInputDiv.classList.remove('d-none');
                                        } else {
                                            otherInputDiv.classList.add('d-none');
                                        }
                                    }

                                    document.addEventListener('DOMContentLoaded', function() {
                                        toggleOtherInput();
                                    });
                                </script>

                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="cn_unit" class="form-label fw-semibold">CN Unit *</label>
                                    <input type="text" name="cn_unit" id="cn_unit"
                                        class="form-control rounded-3 @error('cn_unit') is-invalid @enderror" required>
                                    @error('cn_unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="periodical_service" class="form-label fw-semibold">Periodical Service
                                        *</label>
                                    <select name="periodical_service" id="periodical_service"
                                        class="form-select rounded-3 @error('periodical_service') is-invalid @enderror"
                                        required>
                                        <option value="">-- Pilih Interval --</option>
                                        <option value="250">250</option>
                                        <option value="500">500</option>
                                        <option value="1000">1000</option>
                                        <option value="1500">1500</option>
                                        <option value="2000">2000</option>
                                    </select>
                                    @error('periodical_service')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="hour_meter" class="form-label fw-semibold">Hour Meter (HM) *</label>
                                <input type="number" name="hour_meter" id="hour_meter"
                                    class="form-control rounded-3 @error('hour_meter') is-invalid @enderror" required>
                                @error('hour_meter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Temuan / Backlog / Inspection Description</label>
                                <p class="text-muted small mb-3">MASUKAN EVIDENCE JIKA TERDAPAT TEMUAN MAYOR, LAPOR KEPADA
                                    PENGAWAS JIKA MENGALAMI KEBINGUNGAN</p>
                                <div class="mb-4">
                                    <div id="inspection-container">
                                        <div class="input-group mb-2">
                                            <input type="text" name="temuanFields[]" class="form-control"
                                                placeholder="Masukkan deskripsi inspeksi">
                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                onclick="removeInspection(this)" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                        onclick="addInspection()">
                                        <i class="bi bi-plus"></i> Tambah Deskripsi
                                    </button>
                                </div>

                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Evidence</label>
                                <p class="text-muted small mb-3">Masukkan evidence untuk temuan mayor. Hubungi pengawas
                                    jika bingung.</p>
                                <div class="border rounded-3 p-4 text-center bg-light">
                                    <input class="form-control rounded-3 mb-3" type="file" name="evidence[]"
                                        id="evidence_files" multiple
                                        accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx,.mp4,.avi">
                                    <small class="text-muted d-block">Upload hingga 10 file (maks. 100MB per file).</small>
                                    <div id="evidence-preview" class="mt-3"></div>
                                </div>
                                @error('evidence.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary rounded-pill px-4" data-bs-toggle="tooltip"
                                    title="Kirim formulir backlog">
                                    <i class="fas fa-paper-plane me-1"></i> Kirim Form
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000
                });
            @endif
        });
    </script>
    <script>
        function addInspection() {
            const container = document.getElementById('inspection-container');
            const div = document.createElement('div');
            div.classList.add('input-group', 'mb-2');
            div.innerHTML = `
        <input type="text" name="temuanFields[]" class="form-control" placeholder="Masukkan deskripsi inspeksi">
        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeInspection(this)" title="Hapus">
            <i class="bi bi-trash"></i>
        </button>`;
            container.appendChild(div);
        }

        function removeInspection(button) {
            button.parentElement.remove();
        }
    </script>

@endsection

@push('styles')
    <style>
        /* General Styling */
        body {
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .form-control,
        .form-select {
            border-radius: 8px;
        }

        .form-label {
            font-size: 0.9rem;
            color: #495057;
        }

        .img-fluid {
            transition: transform 0.2s ease;
        }

        .img-fluid:hover {
            transform: scale(1.02);
        }

        .modal-content {
            border-radius: 12px;
        }

        .modal-header {
            border-bottom: none;
        }

        .modal-footer {
            border-top: none;
        }

        .bg-light {
            border-radius: 8px;
        }

        /* File Preview */
        .preview-item {
            max-width: 100px;
            margin: 5px;
            border-radius: 4px;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {

            .form-control,
            .form-select,
            .btn {
                font-size: 0.9rem;
            }

            .img-fluid {
                max-width: 100%;
            }

            .form-label {
                font-size: 0.85rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });


        // File Preview
        document.getElementById('evidence_files').addEventListener('change', function(e) {
            const previewContainer = document.getElementById('evidence-preview');
            previewContainer.innerHTML = '';
            const files = e.target.files;
            for (const file of files) {
                const div = document.createElement('div');
                div.className = 'd-inline-block text-center';
                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.className = 'preview-item shadow-sm';
                    img.alt = file.name;
                    div.appendChild(img);
                } else {
                    const icon = document.createElement('i');
                    icon.className = 'fas fa-file fa-3x text-muted';
                    div.appendChild(icon);
                }
                const name = document.createElement('small');
                name.className = 'd-block text-muted mt-1';
                name.textContent = file.name.slice(0, 10) + (file.name.length > 10 ? '...' : '');
                div.appendChild(name);
                previewContainer.appendChild(div);
            }
        });
    </script>
@endpush
