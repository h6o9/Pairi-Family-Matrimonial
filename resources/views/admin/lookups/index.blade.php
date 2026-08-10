@extends('admin.master_layout')
@section('title')
    <title>{{ $definition['label'] }} - Piyari Family</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header d-flex justify-content-between align-items-center">
            <h1>{{ $definition['label'] }}</h1>
            <a href="{{ route('admin.lookups.create', ['type' => $type]) }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Add New
            </a>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped" id="lookupTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
<x-admin.delete-modal />
@endsection

@push('js')
<script>
    $(function () {
        $('#lookupTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: @json(route('admin.lookups.data', ['type' => $type])),
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            order: [[1, 'asc']],
            columns: [
                { data: 'row_number', name: 'id', searchable: false },
                { data: 'name', name: 'name' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            language: {
                search: '',
                searchPlaceholder: 'Search...',
                processing: 'Loading...',
                emptyTable: 'No records found.',
                zeroRecords: 'No matching records found.'
            }
        });
    });
</script>
@endpush
