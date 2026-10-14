@extends('admin.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card col-md-10 mx-auto">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Mail Template</h5>
            <a href="{{ route('admin.mailtemplates.index') }}" class="btn btn-sm btn-secondary">Back to List</a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.mailtemplates.update', $mailTemplate->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Template Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $mailTemplate->name }}"
                        required>
                </div>

                <div class="mb-3">
                    <label for="subject" class="form-label">Subject</label>
                    <input type="text" name="subject" id="subject" class="form-control"
                        value="{{ $mailTemplate->subject }}" required>
                </div>

                <div class="mb-3">
                    <label for="summernote" class="form-label">Template Content</label>
                    <div class="alert alert-info small">
                        <strong>Available placeholders:</strong><br>
                        <code>{username}</code> - User's name<br>
                        <code>{email}</code> - User's email address<br>
                        <code>{password}</code> - User's plain password<br>
                    </div>
                    <textarea name="template" id="summernote" rows="8"
                        class="form-control">{{ $mailTemplate->template }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="1" {{ $mailTemplate->status ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$mailTemplate->status ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update Template</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- jQuery and Summernote -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

<script>
$(document).ready(function() {
    $('#summernote').summernote({
        height: 300,
        placeholder: 'Edit your email template...'
    });
});
</script>
@endsection