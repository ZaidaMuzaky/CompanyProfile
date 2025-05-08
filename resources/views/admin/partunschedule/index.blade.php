@extends('layouts.logapp')

@section('title', 'Manage Parts Unscheduled')

@section('breadcrumb')
    <li class="breadcrumb-item active">Part Unschedule</li>
@endsection

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPartUnscheduleModal">
                <i class="bi bi-folder-plus"></i> Add Part Unschedule
            </button>
            <form method="GET" action="{{ route('admin.partunschedule.index') }}" class="d-flex mx-auto"
                style="width: 50%;">
                <input type="text" name="search" class="form-control" placeholder="Search Part Unschedule..."
                    value="{{ request()->query('search') }}">
                <button type="submit" class="btn btn-primary ms-2">
                    <i class="bi bi-search"></i>
                </button>
            </form>
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
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editPartUnscheduleModal"
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
    </div>

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
                        <div class="mb-3">
                            <label for="nama_sparepart" class="form-label">Nama Sparepart</label>
                            <input type="text" class="form-control" id="nama_sparepart" name="nama_sparepart" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <input type="text" class="form-control" id="type" name="type" required>
                        </div>
                        <div class="mb-3">
                            <label for="model" class="form-label">Model</label>
                            <input type="text" class="form-control" id="model" name="model" required>
                        </div>
                        <div class="mb-3">
                            <label for="no_orderan" class="form-label">No Orderan</label>
                            <input type="text" class="form-control" id="no_orderan" name="no_orderan" required>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
                        </div>
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
                        <div class="mb-3">
                            <label for="editNamaSparepart" class="form-label">Nama Sparepart</label>
                            <input type="text" class="form-control" id="editNamaSparepart" name="nama_sparepart" required>
                        </div>
                        <div class="mb-3">
                            <label for="editTanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="editTanggal" name="tanggal" required>
                        </div>
                        <div class="mb-3">
                            <label for="editType" class="form-label">Type</label>
                            <input type="text" class="form-control" id="editType" name="type" required>
                        </div>
                        <div class="mb-3">
                            <label for="editModel" class="form-label">Model</label>
                            <input type="text" class="form-control" id="editModel" name="model" required>
                        </div>
                        <div class="mb-3">
                            <label for="editNoOrderan" class="form-label">No Orderan</label>
                            <input type="text" class="form-control" id="editNoOrderan" name="no_orderan" required>
                        </div>
                        <div class="mb-3">
                            <label for="editKeterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="editKeterangan" name="keterangan"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update Part Unschedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
@endsection