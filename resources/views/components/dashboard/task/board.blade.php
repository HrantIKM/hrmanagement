@php
    use App\Models\Task\Enums\TaskStatus;
@endphp
<x-dashboard.layouts.app>
    <div class="container-fluid">
        <div class="card mb-4">
            <x-dashboard.layouts.partials.card-header title="Kanban Board"/>
            <div class="card-body">
                <div class="mb-3 text-muted">
                    {{ __('Task board ordered as Backlog -> To Do -> In Progress -> Ready to Test -> Done') }}
                </div>
                <div class="row g-3" id="kanban-board"
                     data-statuses='@json($taskStatusesOrdered)'
                     data-load-url="{{ route('dashboard.tasks.boardData') }}"
                     data-move-url-template="{{ route('dashboard.tasks.move', ':id') }}">
                    @foreach($taskStatusesOrdered as $status)
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="border rounded h-100 p-2 bg-light">
                                <h6 class="mb-2">{{ __('task.status.' . $status) }}</h6>
                                <div class="kanban-column" data-status="{{ $status }}" style="min-height: 380px;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
        <script src="{{ asset('/js/dashboard/task/board.js') }}"></script>
    </x-slot>
</x-dashboard.layouts.app>
