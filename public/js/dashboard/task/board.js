const boardEl = document.getElementById('kanban-board');

if (boardEl) {
  const statuses = JSON.parse(boardEl.dataset.statuses || '[]');
  const loadUrl = boardEl.dataset.loadUrl;
  const moveUrlTemplate = boardEl.dataset.moveUrlTemplate;
  const columns = {};

  const esc = (value) => (value ?? '').toString()
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#39;');

  const taskCard = (task) => `
    <div class="card mb-2 shadow-sm task-card" data-id="${task.id}">
      <div class="card-body p-2">
        <div class="fw-semibold mb-1">${esc(task.title)}</div>
        <small class="d-block text-muted">${esc(task.project?.name || '')}</small>
        <small class="d-block text-muted">${esc(task.user?.name || '')}</small>
        <a class="btn btn-sm btn-outline-primary mt-2" href="${route('dashboard.tasks.show', task.id)}">Open</a>
      </div>
    </div>
  `;

  const loadTasks = async () => {
    const response = await fetch(loadUrl, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
      },
    });
    const tasks = await response.json();

    Object.values(columns).forEach((column) => {
      column.innerHTML = '';
    });

    tasks.forEach((task) => {
      if (columns[task.status]) {
        columns[task.status].insertAdjacentHTML('beforeend', taskCard(task));
      }
    });
  };

  const moveTask = async (taskId, status) => {
    const url = moveUrlTemplate.replace(':id', taskId);
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const response = await fetch(url, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token,
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify({ status }),
    });

    if (!response.ok) {
      throw new Error(`Failed: ${response.status}`);
    }
  };

  statuses.forEach((status) => {
    const column = boardEl.querySelector(`.kanban-column[data-status="${status}"]`);
    columns[status] = column;

    // eslint-disable-next-line no-undef
    Sortable.create(column, {
      group: 'tasks-kanban',
      animation: 120,
      onEnd: async (event) => {
        const taskId = event.item.dataset.id;
        const newStatus = event.to.dataset.status;

        try {
          await moveTask(taskId, newStatus);
        } catch (e) {
          event.from.insertBefore(event.item, event.from.children[event.oldIndex] || null);
        }
      },
    });
  });

  loadTasks();
}
