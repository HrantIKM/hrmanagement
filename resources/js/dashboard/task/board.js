const boardEl = document.getElementById('kanban-board');

if (boardEl) {
  const statuses = JSON.parse(boardEl.dataset.statuses || '[]');
  const loadUrl = boardEl.dataset.loadUrl || '';
  const moveUrlTemplate = boardEl.dataset.moveUrlTemplate || '';
  const showUrlTemplate = boardEl.dataset.showUrlTemplate || '';
  const editUrlTemplate = boardEl.dataset.editUrlTemplate || '';
  const deleteUrlTemplate = boardEl.dataset.deleteUrlTemplate || '';
  const searchEl = document.getElementById('task-board-search');
  const reloadBtn = document.getElementById('task-board-reload');
  const counts = {};
  const columns = {};
  const tasksById = {};
  let allTasks = [];
  let selectedTaskId = null;

  const esc = (value) => (value ?? '').toString()
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#39;');

  const byId = (id) => document.getElementById(id);
  const modalEl = byId('taskBoardTaskModal');
  const modalBody = byId('task-board-modal-body');
  const modalTitle = byId('task-board-modal-title');
  const modalStatus = byId('task-board-modal-status');
  const modalActions = byId('task-board-modal-actions');
  // eslint-disable-next-line no-undef
  const taskModal = modalEl && window.bootstrap ? new window.bootstrap.Modal(modalEl) : null;

  function dueLabel(dueDate) {
    if (!dueDate) return 'No due date';
    const today = new Date();
    const due = new Date(dueDate);
    const delta = due.setHours(0, 0, 0, 0) - new Date(today.getFullYear(), today.getMonth(), today.getDate()).getTime();
    const days = Math.round(delta / (24 * 60 * 60 * 1000));
    if (days < 0) return `${Math.abs(days)}d overdue`;
    if (days === 0) return 'Due today';
    if (days === 1) return 'Due tomorrow';
    return `Due in ${days}d`;
  }

  function priorityClass(priority) {
    return `status-pill status-pill--${(priority || '').replaceAll('_', '-') || 'default'}`;
  }

  function statusClass(status) {
    return `status-pill status-pill--${(status || '').replaceAll('_', '-') || 'default'}`;
  }

  function renderCard(task) {
    const due = dueLabel(task.due_date);
    const overdue = due.includes('overdue') ? 'is-overdue' : '';
    return `
      <article class="kanban-task ${overdue}" data-id="${task.id}">
        <div class="kanban-task__head">
          <span class="${priorityClass(task.priority)}">${esc(task.priority_display || task.priority || '')}</span>
          <button type="button" class="kanban-task__open" data-open-task="${task.id}" title="Open task">
            <i class="fas fa-expand-alt"></i>
          </button>
        </div>
        <h6 class="kanban-task__title">${esc(task.title)}</h6>
        <div class="kanban-task__meta">
          ${task.project?.name ? `<span><i class="far fa-folder me-1"></i>${esc(task.project.name)}</span>` : ''}
          ${task.user?.name ? `<span><i class="far fa-user me-1"></i>${esc(task.user.name)}</span>` : ''}
        </div>
        <div class="kanban-task__foot">
          <span class="kanban-task__due ${overdue}">${esc(due)}</span>
          <span class="${statusClass(task.status)}">${esc(task.status_display || task.status || '')}</span>
        </div>
      </article>
    `;
  }

  function updateLaneCounts(tasks) {
    statuses.forEach((status) => {
      const total = tasks.filter((task) => task.status === status).length;
      const countEl = counts[status];
      if (countEl) countEl.textContent = String(total);
    });
  }

  function visibleTasks() {
    const term = (searchEl?.value || '').trim().toLowerCase();
    if (!term) return allTasks;
    return allTasks.filter((task) => {
      const text = [task.title, task.description, task.project?.name, task.user?.name].filter(Boolean).join(' ').toLowerCase();
      return text.includes(term);
    });
  }

  function renderBoard() {
    const tasks = visibleTasks();
    Object.values(columns).forEach((column) => {
      column.innerHTML = '';
    });
    tasks.forEach((task) => {
      if (columns[task.status]) {
        columns[task.status].insertAdjacentHTML('beforeend', renderCard(task));
      }
    });
    updateLaneCounts(tasks);
    bindOpenButtons();
  }

  async function loadTasks() {
    const response = await fetch(loadUrl, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        Accept: 'application/json',
      },
    });
    const tasks = await response.json();
    allTasks = Array.isArray(tasks) ? tasks : [];
    allTasks.forEach((task) => {
      tasksById[String(task.id)] = task;
    });
    renderBoard();
  }

  async function moveTask(taskId, status) {
    const task = tasksById[String(taskId)];
    if (!task || !task.can_manage_status) {
      throw new Error('You cannot move this task.');
    }
    const url = moveUrlTemplate.replace(':id', taskId);
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const response = await fetch(url, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token,
        'X-Requested-With': 'XMLHttpRequest',
        Accept: 'application/json',
      },
      body: JSON.stringify({ status }),
    });
    if (!response.ok) {
      throw new Error(`Failed: ${response.status}`);
    }
    task.status = status;
    return true;
  }

  function updateModalStatusOptions(task) {
    modalStatus.innerHTML = statuses.map((status) => `<option value="${status}" ${task.status === status ? 'selected' : ''}>${status.replaceAll('_', ' ')}</option>`).join('');
    modalStatus.disabled = !task.can_manage_status;
  }

  function fillModal(task) {
    selectedTaskId = String(task.id);
    modalTitle.textContent = task.title || 'Task details';
    updateModalStatusOptions(task);
    modalBody.innerHTML = `
      <div class="task-modal__badges mb-3">
        <span class="${priorityClass(task.priority)}">${esc(task.priority_display || task.priority || '')}</span>
        <span class="${statusClass(task.status)}">${esc(task.status_display || task.status || '')}</span>
      </div>
      <dl class="row small mb-2">
        <dt class="col-sm-3 text-muted">Project</dt><dd class="col-sm-9">${esc(task.project?.name || '—')}</dd>
        <dt class="col-sm-3 text-muted">Assignee</dt><dd class="col-sm-9">${esc(task.user?.name || '—')}</dd>
        <dt class="col-sm-3 text-muted">Due date</dt><dd class="col-sm-9">${esc(task.due_date || '—')} (${esc(dueLabel(task.due_date))})</dd>
      </dl>
      <div class="task-modal__description">
        <h6>Description</h6>
        <p class="mb-0">${esc(task.description || 'No description')}</p>
      </div>
    `;
    modalActions.innerHTML = `
      <a href="${showUrlTemplate.replace(':id', task.id)}" class="btn btn-sm btn-outline-secondary">Open page</a>
      ${task.can_edit ? `<a href="${editUrlTemplate.replace(':id', task.id)}" class="btn btn-sm btn-outline-primary">Edit</a>` : ''}
      ${task.can_delete ? `<button type="button" class="btn btn-sm btn-outline-danger" id="task-board-delete-btn">Delete</button>` : ''}
    `;
    const deleteBtn = byId('task-board-delete-btn');
    if (deleteBtn) {
      deleteBtn.addEventListener('click', async () => {
        // eslint-disable-next-line no-alert
        if (!window.confirm('Delete this task?')) return;
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const response = await fetch(deleteUrlTemplate.replace(':id', task.id), {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': token,
            'X-Requested-With': 'XMLHttpRequest',
            Accept: 'application/json',
          },
        });
        if (!response.ok) return;
        allTasks = allTasks.filter((t) => String(t.id) !== String(task.id));
        delete tasksById[String(task.id)];
        renderBoard();
        if (taskModal) taskModal.hide();
      });
    }
  }

  function bindOpenButtons() {
    boardEl.querySelectorAll('[data-open-task]').forEach((btn) => {
      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        const taskId = btn.getAttribute('data-open-task');
        const task = tasksById[String(taskId)];
        if (!task) return;
        fillModal(task);
        if (taskModal) taskModal.show();
      });
    });
    boardEl.querySelectorAll('.kanban-task').forEach((card) => {
      card.addEventListener('click', () => {
        const task = tasksById[String(card.dataset.id)];
        if (!task) return;
        fillModal(task);
        if (taskModal) taskModal.show();
      });
    });
  }

  if (modalStatus) {
    modalStatus.addEventListener('change', async () => {
      if (!selectedTaskId) return;
      const status = modalStatus.value;
      try {
        await moveTask(selectedTaskId, status);
        const task = tasksById[selectedTaskId];
        task.status = status;
        renderBoard();
        fillModal(task);
      } catch (e) {
        // eslint-disable-next-line no-alert
        alert('Could not move task.');
      }
    });
  }

  statuses.forEach((status) => {
    columns[status] = boardEl.querySelector(`.kanban-column[data-status="${status}"]`);
    counts[status] = document.querySelector(`[data-lane-count="${status}"]`);
    // eslint-disable-next-line no-undef
    Sortable.create(columns[status], {
      group: 'tasks-kanban',
      animation: 150,
      ghostClass: 'kanban-task--ghost',
      chosenClass: 'kanban-task--chosen',
      onEnd: async (event) => {
        const taskId = event.item.dataset.id;
        const oldStatus = event.from.dataset.status;
        const newStatus = event.to.dataset.status;
        if (oldStatus === newStatus) return;
        try {
          await moveTask(taskId, newStatus);
          const task = tasksById[String(taskId)];
          if (task) task.status = newStatus;
          renderBoard();
        } catch (e) {
          event.from.insertBefore(event.item, event.from.children[event.oldIndex] || null);
          // eslint-disable-next-line no-alert
          alert(e.message || 'Could not move task.');
        }
      },
    });
  });

  searchEl?.addEventListener('input', () => renderBoard());
  reloadBtn?.addEventListener('click', () => loadTasks());
  loadTasks();
}
