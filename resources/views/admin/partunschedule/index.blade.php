@extends('layouts.logapp')

@section('title', 'Manage Parts Unscheduled')

@section('breadcrumb')
    <li class="breadcrumb-item active">Part Unschedule</li>
@endsection

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <!-- Add Part Unschedule Button -->
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPartUnscheduleModal" style="margin-right: 1%">
                <i class="bi bi-folder-plus"></i> Add Part Unschedule
            </button>
            <!-- Import Button -->
            <button class="btn btn-info d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#importPartModal">
                <i class="bi bi-file-earmark-excel"></i>
                <span class="d-none d-sm-inline ms-1">Import Parts</span>
            </button>

            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.partunschedule.index') }}" class="d-flex mx-auto"
                style="width: 50%;">
                <input type="text" name="search" class="form-control" placeholder="Search Part Unschedule..."
                    value="{{ request()->query('search') }}">
                <button type="submit" class="btn btn-primary ms-2">
                    <i class="bi bi-search"></i>
                </button>
            </form>


        </div>
    </div>

    <!-- Part Unschedules Table -->
    <table class="table mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Sparepart</th>
                <th>Tanggal</th>
                <th>Type</th>
                <th>Model</th>
                <th>No Orderan</th>
                <th>Keterangan</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($partunschedules as $index => $partunschedule)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $partunschedule->nama_sparepart }}</td>
                    <td>{{ $partunschedule->tanggal }}</td>
                    <td>{{ $partunschedule->type }}</td>
                    <td>{{ $partunschedule->model }}</td>
                    <td>{{ $partunschedule->no_orderan }}</td>
                    <td>{{ $partunschedule->keterangan }}</td>
                    <td>
                        <!-- Edit Button -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editPartUnscheduleModal"
                            onclick="editPartUnschedule('{{ $partunschedule->id }}', '{{ $partunschedule->nama_sparepart }}', '{{ $partunschedule->tanggal }}', '{{ $partunschedule->type }}', '{{ $partunschedule->model }}', '{{ $partunschedule->no_orderan }}', '{{ $partunschedule->keterangan }}')">
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        <!-- Delete Button -->
                        <form action="{{ route('admin.partunschedule.destroy', $partunschedule->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Anda yakin akan menghapus part unschedule ini?');">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Add Modal -->
    <div class="modal fade" id="addPartUnscheduleModal" tabindex="-1" aria-labelledby="addPartUnscheduleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.partunschedule.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPartUnscheduleModalLabel">Add Part Unschedule</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @foreach (['nama_sparepart', 'tanggal', 'type', 'model', 'no_orderan', 'keterangan'] as $field)
                            <div class="mb-3">
                                <label for="{{ $field }}"
                                    class="form-label">{{ ucwords(str_replace('_', ' ', $field)) }}</label>
                                @if ($field == 'tanggal')
                                    <input type="date" class="form-control" id="{{ $field }}" name="{{ $field }}" required>
                                @elseif ($field == 'keterangan')
                                    <textarea class="form-control" id="{{ $field }}" name="{{ $field }}"></textarea>
                                @else
                                    <input type="text" class="form-control" id="{{ $field }}" name="{{ $field }}" required>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Part Unschedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editPartUnscheduleModal" tabindex="-1" aria-labelledby="editPartUnscheduleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="editPartUnscheduleForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPartUnscheduleModalLabel">Edit Part Unschedule</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editPartUnscheduleId" name="id">
                        @foreach (['nama_sparepart', 'tanggal', 'type', 'model', 'no_orderan', 'keterangan'] as $field)
                            <div class="mb-3">
                                <label for="edit{{ ucfirst($field) }}"
                                    class="form-label">{{ ucwords(str_replace('_', ' ', $field)) }}</label>
                                @if ($field == 'tanggal')
                                    <input type="date" class="form-control" id="edit{{ ucfirst($field) }}" name="{{ $field }}"
                                        required>
                                @elseif ($field == 'keterangan')
                                    <textarea class="form-control" id="edit{{ ucfirst($field) }}" name="{{ $field }}"></textarea>
                                @else
                                    <input type="text" class="form-control" id="edit{{ ucfirst($field) }}" name="{{ $field }}"
                                        required>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update Part Unschedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importPartModal" tabindex="-1" aria-labelledby="importPartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importPartModalLabel">Import Parts</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Pastikan file Excel yang Anda upload sesuai dengan format yang ditentukan.</p>
                    <p>Anda dapat mengunduh template Excel di bawah ini:</p>
                    <a href="{{ asset('templates/partunschedule_import_template.xlsx') }}" class="btn btn-outline-success mb-3"
                        download>
                        <i class="bi bi-download"></i> Download Template
                    </a>

                    <form id="importPartForm" method="POST" action="{{ route('admin.parts.import') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="importPartFile" class="form-label">Upload Excel File</label>
                            <input type="file" class="form-control" id="importPartFile" name="file" accept=".xlsx, .xls"
                                required>
                        </div>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert Success Notification -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
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
        function editPartUnschedule(id, nama_sparepart, tanggal, type, model, no_orderan, keterangan) {
            document.getElementById('editPartUnscheduleId').value = id;
            document.getElementById('editNamaSparepart').value = nama_sparepart;
            document.getElementById('editTanggal').value = tanggal;
            document.getElementById('editType').value = type;
            document.getElementById('editModel').value = model;
            document.getElementById('editNoOrderan').value = no_orderan;
            document.getElementById('editKeterangan').value = keterangan;
            document.getElementById('editPartUnscheduleForm').action = "/admin/partunschedule/" + id + "/update";
        }
    </script>
@endsection