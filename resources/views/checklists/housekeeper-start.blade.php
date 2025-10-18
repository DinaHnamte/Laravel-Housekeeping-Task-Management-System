@extends("layouts.app")

@section("content")
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-clipboard-list"></i>
                            Start Cleaning Checklist - {{ $property->name }}
                        </h3>
                        <p class="text-muted mb-0">
                            Assignment Date: {{ $assignment->assignment_date->format("M d, Y") }}
                        </p>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Instructions:</strong> Complete each room's tasks in order. Upload photos for each task
                            and mark as complete before moving to the next room.
                        </div>

                        <div class="row">
                            @foreach ($rooms as $room)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="card-title mb-0">
                                                <i class="fas fa-door-open"></i>
                                                {{ $room->name }}
                                            </h5>
                                            <span class="badge bg-secondary">{{ $room->tasks->count() }} tasks</span>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <strong>Tasks to complete:</strong>
                                                <ul class="list-unstyled mt-2">
                                                    @foreach ($room->tasks as $task)
                                                        <li class="mb-1">
                                                            <i class="fas fa-check-circle text-muted"></i>
                                                            {{ $task->task }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>

                                            <div class="d-grid">
                                                <a href="{{ route("checklists.room", ["assignment" => $assignment->id, "room" => $room->id]) }}"
                                                    class="btn btn-primary">
                                                    <i class="fas fa-play"></i>
                                                    Start {{ $room->name }} Tasks
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if ($rooms->count() === 0)
                            <div class="py-5 text-center">
                                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                <h5 class="text-muted">No rooms found</h5>
                                <p class="text-muted">This property doesn't have any rooms with tasks assigned yet.</p>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route("assignments.index") }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Assignments
                            </a>

                            @if ($rooms->count() > 0)
                                <div class="text-muted">
                                    <small>
                                        <i class="fas fa-clock"></i>
                                        Estimated time: {{ $rooms->count() * 15 }} minutes
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
