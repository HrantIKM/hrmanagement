@php
    use App\Models\Task\Enums\TaskStatus;
@endphp
<x-dashboard.layouts.app>
    <div class="container-fluid task-board-page">
        <div class="task-board-hero mb-4">
            <div class="task-board-hero__title-wrap">
                <h2 class="task-board-hero__title mb-1">Kanban Workspace</h2>
                <p class="task-board-hero__subtitle mb-0">{{ __('Task board ordered as Backlog -> To Do -> In Progress -> Ready to Test -> Done') }}</p>
            </div>
            <div class="task-board-hero__actions">
                <a href="{{ route('dashboard.tasks.index') }}" class="btn btn-light btn-sm">Table view</a>
                @if($canAdminManage)
                    <a href="{{ route('dashboard.tasks.create') }}" class="btn btn-primary btn-sm">Create task</a>
                @endif
            </div>
        </div>
        <div class="card mb-4 task-board-card">
            <div class="card-body p-3 p-md-4">
                <div class="task-board-toolbar">
                    <input type="search" class="form-control form-control-sm task-board-toolbar__search" id="task-board-search" placeholder="Search by title, project, assignee...">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="task-board-reload">Refresh</button>
                </div>
                <div class="task-board-grid mt-1" id="kanban-board"
                     data-statuses='@json($taskStatusesOrdered)'
                     data-load-url="{{ route('dashboard.tasks.boardData') }}"
                     data-move-url-template="{{ route('dashboard.tasks.move', ':id') }}"
                     data-show-url-template="{{ route('dashboard.tasks.show', ':id') }}"
                     data-edit-url-template="{{ route('dashboard.tasks.edit', ':id') }}"
                     data-delete-url-template="{{ route('dashboard.tasks.destroy', ':id') }}">
                    @foreach($taskStatusesOrdered as $status)
                        <div class="task-board-grid__lane">
                            <div class="kanban-lane">
                                <div class="kanban-lane__head">
                                    <h6 class="mb-0">{{ __('task.status.' . $status) }}</h6>
                                    <span class="kanban-lane__count" data-lane-count="{{ $status }}">0</span>
                                </div>
                                <div class="kanban-column" data-status="{{ $status }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="taskBoardTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="task-board-modal-title">Task details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="task-board-modal-body"></div>
                <div class="modal-footer justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <label for="task-board-modal-status" class="small text-muted mb-0">Move to</label>
                        <select id="task-board-modal-status" class="form-select form-select-sm"></select>
                    </div>
                    <div class="d-flex align-items-center gap-2" id="task-board-modal-actions"></div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
        <script src="{{ mix('js/dashboard/task/board.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>
