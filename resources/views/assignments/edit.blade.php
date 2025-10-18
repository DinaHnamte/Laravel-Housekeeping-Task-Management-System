@extends("layouts.app")

@section("content")
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Edit Assignment</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route("assignments.update", $assignment) }}" method="POST">
                            @csrf
                            @method("PUT")

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="property_id" class="form-label">Property <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error("property_id") is-invalid @enderror"
                                            id="property_id" name="property_id" required>
                                            <option value="">Select a property</option>
                                            @foreach ($properties as $property)
                                                <option value="{{ $property->id }}"
                                                    {{ old("property_id", $assignment->property_id) == $property->id ? "selected" : "" }}>
                                                    {{ $property->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error("property_id")
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="user_id" class="form-label">Housekeeper <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error("user_id") is-invalid @enderror" id="user_id"
                                            name="user_id" required>
                                            <option value="">Select a housekeeper</option>
                                            @foreach ($housekeepers as $housekeeper)
                                                <option value="{{ $housekeeper->id }}"
                                                    {{ old("user_id", $assignment->user_id) == $housekeeper->id ? "selected" : "" }}>
                                                    {{ $housekeeper->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error("user_id")
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="assignment_date" class="form-label">Assignment Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date"
                                            class="form-control @error("assignment_date") is-invalid @enderror"
                                            id="assignment_date" name="assignment_date"
                                            value="{{ old("assignment_date", $assignment->assignment_date->format("Y-m-d")) }}"
                                            required>
                                        @error("assignment_date")
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="start_time" class="form-label">Start Time</label>
                                        <input type="time" class="form-control @error("start_time") is-invalid @enderror"
                                            id="start_time" name="start_time"
                                            value="{{ old("start_time", $assignment->start_time ? $assignment->start_time->format("H:i") : "") }}">
                                        @error("start_time")
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="end_time" class="form-label">End Time</label>
                                        <input type="time" class="form-control @error("end_time") is-invalid @enderror"
                                            id="end_time" name="end_time"
                                            value="{{ old("end_time", $assignment->end_time ? $assignment->end_time->format("H:i") : "") }}">
                                        @error("end_time")
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error("status") is-invalid @enderror" id="status"
                                            name="status" required>
                                            <option value="pending"
                                                {{ old("status", $assignment->status) == "pending" ? "selected" : "" }}>
                                                Pending</option>
                                            <option value="in_progress"
                                                {{ old("status", $assignment->status) == "in_progress" ? "selected" : "" }}>
                                                In Progress</option>
                                            <option value="completed"
                                                {{ old("status", $assignment->status) == "completed" ? "selected" : "" }}>
                                                Completed</option>
                                            <option value="cancelled"
                                                {{ old("status", $assignment->status) == "cancelled" ? "selected" : "" }}>
                                                Cancelled</option>
                                        </select>
                                        @error("status")
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Notes</label>
                                        <textarea class="form-control @error("notes") is-invalid @enderror" id="notes" name="notes" rows="3"
                                            placeholder="Add any notes about this assignment...">{{ old("notes", $assignment->notes) }}</textarea>
                                        @error("notes")
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route("assignments.show", $assignment) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Assignment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
