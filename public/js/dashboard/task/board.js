/******/ (() => { // webpackBootstrap
/*!**********************************************!*\
  !*** ./resources/js/dashboard/task/board.js ***!
  \**********************************************/
function _regenerator() { /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/babel/babel/blob/main/packages/babel-helpers/LICENSE */ var e, t, r = "function" == typeof Symbol ? Symbol : {}, n = r.iterator || "@@iterator", o = r.toStringTag || "@@toStringTag"; function i(r, n, o, i) { var c = n && n.prototype instanceof Generator ? n : Generator, u = Object.create(c.prototype); return _regeneratorDefine2(u, "_invoke", function (r, n, o) { var i, c, u, f = 0, p = o || [], y = !1, G = { p: 0, n: 0, v: e, a: d, f: d.bind(e, 4), d: function d(t, r) { return i = t, c = 0, u = e, G.n = r, a; } }; function d(r, n) { for (c = r, u = n, t = 0; !y && f && !o && t < p.length; t++) { var o, i = p[t], d = G.p, l = i[2]; r > 3 ? (o = l === n) && (u = i[(c = i[4]) ? 5 : (c = 3, 3)], i[4] = i[5] = e) : i[0] <= d && ((o = r < 2 && d < i[1]) ? (c = 0, G.v = n, G.n = i[1]) : d < l && (o = r < 3 || i[0] > n || n > l) && (i[4] = r, i[5] = n, G.n = l, c = 0)); } if (o || r > 1) return a; throw y = !0, n; } return function (o, p, l) { if (f > 1) throw TypeError("Generator is already running"); for (y && 1 === p && d(p, l), c = p, u = l; (t = c < 2 ? e : u) || !y;) { i || (c ? c < 3 ? (c > 1 && (G.n = -1), d(c, u)) : G.n = u : G.v = u); try { if (f = 2, i) { if (c || (o = "next"), t = i[o]) { if (!(t = t.call(i, u))) throw TypeError("iterator result is not an object"); if (!t.done) return t; u = t.value, c < 2 && (c = 0); } else 1 === c && (t = i["return"]) && t.call(i), c < 2 && (u = TypeError("The iterator does not provide a '" + o + "' method"), c = 1); i = e; } else if ((t = (y = G.n < 0) ? u : r.call(n, G)) !== a) break; } catch (t) { i = e, c = 1, u = t; } finally { f = 1; } } return { value: t, done: y }; }; }(r, o, i), !0), u; } var a = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} t = Object.getPrototypeOf; var c = [][n] ? t(t([][n]())) : (_regeneratorDefine2(t = {}, n, function () { return this; }), t), u = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(c); function f(e) { return Object.setPrototypeOf ? Object.setPrototypeOf(e, GeneratorFunctionPrototype) : (e.__proto__ = GeneratorFunctionPrototype, _regeneratorDefine2(e, o, "GeneratorFunction")), e.prototype = Object.create(u), e; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, _regeneratorDefine2(u, "constructor", GeneratorFunctionPrototype), _regeneratorDefine2(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = "GeneratorFunction", _regeneratorDefine2(GeneratorFunctionPrototype, o, "GeneratorFunction"), _regeneratorDefine2(u), _regeneratorDefine2(u, o, "Generator"), _regeneratorDefine2(u, n, function () { return this; }), _regeneratorDefine2(u, "toString", function () { return "[object Generator]"; }), (_regenerator = function _regenerator() { return { w: i, m: f }; })(); }
function _regeneratorDefine2(e, r, n, t) { var i = Object.defineProperty; try { i({}, "", {}); } catch (e) { i = 0; } _regeneratorDefine2 = function _regeneratorDefine(e, r, n, t) { function o(r, n) { _regeneratorDefine2(e, r, function (e) { return this._invoke(r, n, e); }); } r ? i ? i(e, r, { value: n, enumerable: !t, configurable: !t, writable: !t }) : e[r] = n : (o("next", 0), o("throw", 1), o("return", 2)); }, _regeneratorDefine2(e, r, n, t); }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
var boardEl = document.getElementById('kanban-board');
if (boardEl) {
  var dueLabel = function dueLabel(dueDate) {
    if (!dueDate) return 'No due date';
    var today = new Date();
    var due = new Date(dueDate);
    var delta = due.setHours(0, 0, 0, 0) - new Date(today.getFullYear(), today.getMonth(), today.getDate()).getTime();
    var days = Math.round(delta / (24 * 60 * 60 * 1000));
    if (days < 0) return "".concat(Math.abs(days), "d overdue");
    if (days === 0) return 'Due today';
    if (days === 1) return 'Due tomorrow';
    return "Due in ".concat(days, "d");
  };
  var priorityClass = function priorityClass(priority) {
    return "status-pill status-pill--".concat((priority || '').replaceAll('_', '-') || 'default');
  };
  var statusClass = function statusClass(status) {
    return "status-pill status-pill--".concat((status || '').replaceAll('_', '-') || 'default');
  };
  var renderCard = function renderCard(task) {
    var _task$project, _task$user;
    var due = dueLabel(task.due_date);
    var overdue = due.includes('overdue') ? 'is-overdue' : '';
    return "\n      <article class=\"kanban-task ".concat(overdue, "\" data-id=\"").concat(task.id, "\">\n        <div class=\"kanban-task__head\">\n          <span class=\"").concat(priorityClass(task.priority), "\">").concat(esc(task.priority_display || task.priority || ''), "</span>\n          <button type=\"button\" class=\"kanban-task__open\" data-open-task=\"").concat(task.id, "\" title=\"Open task\">\n            <i class=\"fas fa-expand-alt\"></i>\n          </button>\n        </div>\n        <h6 class=\"kanban-task__title\">").concat(esc(task.title), "</h6>\n        <div class=\"kanban-task__meta\">\n          ").concat((_task$project = task.project) !== null && _task$project !== void 0 && _task$project.name ? "<span><i class=\"far fa-folder me-1\"></i>".concat(esc(task.project.name), "</span>") : '', "\n          ").concat((_task$user = task.user) !== null && _task$user !== void 0 && _task$user.name ? "<span><i class=\"far fa-user me-1\"></i>".concat(esc(task.user.name), "</span>") : '', "\n        </div>\n        <div class=\"kanban-task__foot\">\n          <span class=\"kanban-task__due ").concat(overdue, "\">").concat(esc(due), "</span>\n          <span class=\"").concat(statusClass(task.status), "\">").concat(esc(task.status_display || task.status || ''), "</span>\n        </div>\n      </article>\n    ");
  };
  var updateLaneCounts = function updateLaneCounts(tasks) {
    statuses.forEach(function (status) {
      var total = tasks.filter(function (task) {
        return task.status === status;
      }).length;
      var countEl = counts[status];
      if (countEl) countEl.textContent = String(total);
    });
  };
  var visibleTasks = function visibleTasks() {
    var term = ((searchEl === null || searchEl === void 0 ? void 0 : searchEl.value) || '').trim().toLowerCase();
    if (!term) return allTasks;
    return allTasks.filter(function (task) {
      var _task$project2, _task$user2;
      var text = [task.title, task.description, (_task$project2 = task.project) === null || _task$project2 === void 0 ? void 0 : _task$project2.name, (_task$user2 = task.user) === null || _task$user2 === void 0 ? void 0 : _task$user2.name].filter(Boolean).join(' ').toLowerCase();
      return text.includes(term);
    });
  };
  var renderBoard = function renderBoard() {
    var tasks = visibleTasks();
    Object.values(columns).forEach(function (column) {
      column.innerHTML = '';
    });
    tasks.forEach(function (task) {
      if (columns[task.status]) {
        columns[task.status].insertAdjacentHTML('beforeend', renderCard(task));
      }
    });
    updateLaneCounts(tasks);
    bindOpenButtons();
  };
  var loadTasks = /*#__PURE__*/function () {
    var _ref = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee() {
      var response, tasks;
      return _regenerator().w(function (_context) {
        while (1) switch (_context.n) {
          case 0:
            _context.n = 1;
            return fetch(loadUrl, {
              headers: {
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json'
              }
            });
          case 1:
            response = _context.v;
            _context.n = 2;
            return response.json();
          case 2:
            tasks = _context.v;
            allTasks = Array.isArray(tasks) ? tasks : [];
            allTasks.forEach(function (task) {
              tasksById[String(task.id)] = task;
            });
            renderBoard();
          case 3:
            return _context.a(2);
        }
      }, _callee);
    }));
    return function loadTasks() {
      return _ref.apply(this, arguments);
    };
  }();
  var moveTask = /*#__PURE__*/function () {
    var _ref2 = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee2(taskId, status) {
      var _document$querySelect;
      var task, url, token, response;
      return _regenerator().w(function (_context2) {
        while (1) switch (_context2.n) {
          case 0:
            task = tasksById[String(taskId)];
            if (!(!task || !task.can_manage_status)) {
              _context2.n = 1;
              break;
            }
            throw new Error('You cannot move this task.');
          case 1:
            url = moveUrlTemplate.replace(':id', taskId);
            token = (_document$querySelect = document.querySelector('meta[name="csrf-token"]')) === null || _document$querySelect === void 0 ? void 0 : _document$querySelect.getAttribute('content');
            _context2.n = 2;
            return fetch(url, {
              method: 'PUT',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json'
              },
              body: JSON.stringify({
                status: status
              })
            });
          case 2:
            response = _context2.v;
            if (response.ok) {
              _context2.n = 3;
              break;
            }
            throw new Error("Failed: ".concat(response.status));
          case 3:
            task.status = status;
            return _context2.a(2, true);
        }
      }, _callee2);
    }));
    return function moveTask(_x, _x2) {
      return _ref2.apply(this, arguments);
    };
  }();
  var updateModalStatusOptions = function updateModalStatusOptions(task) {
    modalStatus.innerHTML = statuses.map(function (status) {
      return "<option value=\"".concat(status, "\" ").concat(task.status === status ? 'selected' : '', ">").concat(status.replaceAll('_', ' '), "</option>");
    }).join('');
    modalStatus.disabled = !task.can_manage_status;
  };
  var fillModal = function fillModal(task) {
    var _task$project3, _task$user3;
    selectedTaskId = String(task.id);
    modalTitle.textContent = task.title || 'Task details';
    updateModalStatusOptions(task);
    modalBody.innerHTML = "\n      <div class=\"task-modal__badges mb-3\">\n        <span class=\"".concat(priorityClass(task.priority), "\">").concat(esc(task.priority_display || task.priority || ''), "</span>\n        <span class=\"").concat(statusClass(task.status), "\">").concat(esc(task.status_display || task.status || ''), "</span>\n      </div>\n      <dl class=\"row small mb-2\">\n        <dt class=\"col-sm-3 text-muted\">Project</dt><dd class=\"col-sm-9\">").concat(esc(((_task$project3 = task.project) === null || _task$project3 === void 0 ? void 0 : _task$project3.name) || '—'), "</dd>\n        <dt class=\"col-sm-3 text-muted\">Assignee</dt><dd class=\"col-sm-9\">").concat(esc(((_task$user3 = task.user) === null || _task$user3 === void 0 ? void 0 : _task$user3.name) || '—'), "</dd>\n        <dt class=\"col-sm-3 text-muted\">Due date</dt><dd class=\"col-sm-9\">").concat(esc(task.due_date || '—'), " (").concat(esc(dueLabel(task.due_date)), ")</dd>\n      </dl>\n      <div class=\"task-modal__description\">\n        <h6>Description</h6>\n        <p class=\"mb-0\">").concat(esc(task.description || 'No description'), "</p>\n      </div>\n    ");
    modalActions.innerHTML = "\n      <a href=\"".concat(showUrlTemplate.replace(':id', task.id), "\" class=\"btn btn-sm btn-outline-secondary\">Open page</a>\n      ").concat(task.can_edit ? "<a href=\"".concat(editUrlTemplate.replace(':id', task.id), "\" class=\"btn btn-sm btn-outline-primary\">Edit</a>") : '', "\n      ").concat(task.can_delete ? "<button type=\"button\" class=\"btn btn-sm btn-outline-danger\" id=\"task-board-delete-btn\">Delete</button>" : '', "\n    ");
    var deleteBtn = byId('task-board-delete-btn');
    if (deleteBtn) {
      deleteBtn.addEventListener('click', /*#__PURE__*/_asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee3() {
        var _document$querySelect2;
        var token, response;
        return _regenerator().w(function (_context3) {
          while (1) switch (_context3.n) {
            case 0:
              if (window.confirm('Delete this task?')) {
                _context3.n = 1;
                break;
              }
              return _context3.a(2);
            case 1:
              token = (_document$querySelect2 = document.querySelector('meta[name="csrf-token"]')) === null || _document$querySelect2 === void 0 ? void 0 : _document$querySelect2.getAttribute('content');
              _context3.n = 2;
              return fetch(deleteUrlTemplate.replace(':id', task.id), {
                method: 'DELETE',
                headers: {
                  'X-CSRF-TOKEN': token,
                  'X-Requested-With': 'XMLHttpRequest',
                  Accept: 'application/json'
                }
              });
            case 2:
              response = _context3.v;
              if (response.ok) {
                _context3.n = 3;
                break;
              }
              return _context3.a(2);
            case 3:
              allTasks = allTasks.filter(function (t) {
                return String(t.id) !== String(task.id);
              });
              delete tasksById[String(task.id)];
              renderBoard();
              if (taskModal) taskModal.hide();
            case 4:
              return _context3.a(2);
          }
        }, _callee3);
      })));
    }
  };
  var bindOpenButtons = function bindOpenButtons() {
    boardEl.querySelectorAll('[data-open-task]').forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.stopPropagation();
        var taskId = btn.getAttribute('data-open-task');
        var task = tasksById[String(taskId)];
        if (!task) return;
        fillModal(task);
        if (taskModal) taskModal.show();
      });
    });
    boardEl.querySelectorAll('.kanban-task').forEach(function (card) {
      card.addEventListener('click', function () {
        var task = tasksById[String(card.dataset.id)];
        if (!task) return;
        fillModal(task);
        if (taskModal) taskModal.show();
      });
    });
  };
  var statuses = JSON.parse(boardEl.dataset.statuses || '[]');
  var loadUrl = boardEl.dataset.loadUrl || '';
  var moveUrlTemplate = boardEl.dataset.moveUrlTemplate || '';
  var showUrlTemplate = boardEl.dataset.showUrlTemplate || '';
  var editUrlTemplate = boardEl.dataset.editUrlTemplate || '';
  var deleteUrlTemplate = boardEl.dataset.deleteUrlTemplate || '';
  var searchEl = document.getElementById('task-board-search');
  var reloadBtn = document.getElementById('task-board-reload');
  var counts = {};
  var columns = {};
  var tasksById = {};
  var allTasks = [];
  var selectedTaskId = null;
  var esc = function esc(value) {
    return (value !== null && value !== void 0 ? value : '').toString().replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;').replaceAll('"', '&quot;').replaceAll("'", '&#39;');
  };
  var byId = function byId(id) {
    return document.getElementById(id);
  };
  var modalEl = byId('taskBoardTaskModal');
  var modalBody = byId('task-board-modal-body');
  var modalTitle = byId('task-board-modal-title');
  var modalStatus = byId('task-board-modal-status');
  var modalActions = byId('task-board-modal-actions');
  // eslint-disable-next-line no-undef
  var taskModal = modalEl && window.bootstrap ? new window.bootstrap.Modal(modalEl) : null;
  if (modalStatus) {
    modalStatus.addEventListener('change', /*#__PURE__*/_asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee4() {
      var status, task, _t;
      return _regenerator().w(function (_context4) {
        while (1) switch (_context4.p = _context4.n) {
          case 0:
            if (selectedTaskId) {
              _context4.n = 1;
              break;
            }
            return _context4.a(2);
          case 1:
            status = modalStatus.value;
            _context4.p = 2;
            _context4.n = 3;
            return moveTask(selectedTaskId, status);
          case 3:
            task = tasksById[selectedTaskId];
            task.status = status;
            renderBoard();
            fillModal(task);
            _context4.n = 5;
            break;
          case 4:
            _context4.p = 4;
            _t = _context4.v;
            // eslint-disable-next-line no-alert
            alert('Could not move task.');
          case 5:
            return _context4.a(2);
        }
      }, _callee4, null, [[2, 4]]);
    })));
  }
  statuses.forEach(function (status) {
    columns[status] = boardEl.querySelector(".kanban-column[data-status=\"".concat(status, "\"]"));
    counts[status] = document.querySelector("[data-lane-count=\"".concat(status, "\"]"));
    // eslint-disable-next-line no-undef
    Sortable.create(columns[status], {
      group: 'tasks-kanban',
      animation: 150,
      ghostClass: 'kanban-task--ghost',
      chosenClass: 'kanban-task--chosen',
      onEnd: function () {
        var _onEnd = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee5(event) {
          var taskId, oldStatus, newStatus, task, _t2;
          return _regenerator().w(function (_context5) {
            while (1) switch (_context5.p = _context5.n) {
              case 0:
                taskId = event.item.dataset.id;
                oldStatus = event.from.dataset.status;
                newStatus = event.to.dataset.status;
                if (!(oldStatus === newStatus)) {
                  _context5.n = 1;
                  break;
                }
                return _context5.a(2);
              case 1:
                _context5.p = 1;
                _context5.n = 2;
                return moveTask(taskId, newStatus);
              case 2:
                task = tasksById[String(taskId)];
                if (task) task.status = newStatus;
                renderBoard();
                _context5.n = 4;
                break;
              case 3:
                _context5.p = 3;
                _t2 = _context5.v;
                event.from.insertBefore(event.item, event.from.children[event.oldIndex] || null);
                // eslint-disable-next-line no-alert
                alert(_t2.message || 'Could not move task.');
              case 4:
                return _context5.a(2);
            }
          }, _callee5, null, [[1, 3]]);
        }));
        function onEnd(_x3) {
          return _onEnd.apply(this, arguments);
        }
        return onEnd;
      }()
    });
  });
  searchEl === null || searchEl === void 0 || searchEl.addEventListener('input', function () {
    return renderBoard();
  });
  reloadBtn === null || reloadBtn === void 0 || reloadBtn.addEventListener('click', function () {
    return loadTasks();
  });
  loadTasks();
}
/******/ })()
;