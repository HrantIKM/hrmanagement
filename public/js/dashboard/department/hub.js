/******/ (() => { // webpackBootstrap
/*!**************************************************!*\
  !*** ./resources/js/dashboard/department/hub.js ***!
  \**************************************************/
/* eslint-disable no-param-reassign */
(function (_document$getElementB, _document$getElementB2) {
  var cfg = window.__DEPT_HUB__;
  if (!cfg || !cfg.dataUrl) return;
  var t = cfg.i18n || {};
  var treeEl = document.getElementById('dept-hub-tree');
  var emptyEl = document.getElementById('dept-hub-empty');
  var searchEl = document.getElementById('dept-hub-search');
  var detailBody = document.getElementById('dept-hub-detail-body');
  var placeholder = document.getElementById('dept-hub-placeholder');
  var tpl = document.getElementById('dept-hub-detail-template');
  var tree = [];
  /** @type {Record<string, object>} */
  var flat = {};
  var selectedId = null;
  var filterText = '';
  function escapeHtml(str) {
    if (!str) return '';
    var d = document.createElement('div');
    d.textContent = str;
    return d.innerHTML;
  }
  function initials(name) {
    var parts = (name || '').trim().split(/\s+/);
    var a = (parts[0] || '?').charAt(0);
    var b = (parts[1] || '').charAt(0);
    return (a + b).toUpperCase() || '?';
  }
  function statsLine(counts) {
    var line = t.statsLine || '';
    return line.replace(':members', String(counts.members)).replace(':positions', String(counts.positions)).replace(':skills', String(counts.skills));
  }
  function nodeMatches(node, q) {
    if (!q) return true;
    var n = (node.name || '').toLowerCase();
    if (n.includes(q)) return true;
    return (node.children || []).some(function (c) {
      return nodeMatches(c, q);
    });
  }
  function renderTreeNode(node, depth) {
    var open = filterText ? true : depth < 2;
    var hasKids = (node.children || []).length > 0;
    var match = nodeMatches(node, filterText);
    if (filterText && !match) return '';
    var counts = node.counts || {
      members: 0,
      positions: 0,
      skills: 0
    };
    var id = String(node.id);
    var active = selectedId === id ? ' is-active' : '';
    var kidsHtml = (node.children || []).map(function (c) {
      return renderTreeNode(c, depth + 1);
    }).join('');
    var expanded = open || filterText && kidsHtml ? ' is-open' : '';
    return "\n      <div class=\"dept-hub__node\" data-id=\"".concat(id, "\">\n        <div class=\"dept-hub__node-row").concat(active, "\" data-dept-id=\"").concat(id, "\" role=\"treeitem\" aria-expanded=\"").concat(hasKids ? 'true' : 'false', "\">\n          ").concat(hasKids ? "<button type=\"button\" class=\"dept-hub__twisty\" aria-label=\"Toggle\" data-twisty=\"".concat(id, "\"><i class=\"fas fa-chevron-right\"></i></button>") : '<span class="dept-hub__twisty-spacer"></span>', "\n          <span class=\"dept-hub__node-body\">\n            <span class=\"dept-hub__node-name\">").concat(escapeHtml(node.name), "</span>\n            <span class=\"dept-hub__node-meta\">").concat(counts.members, " \xB7 ").concat(counts.positions, " \xB7 ").concat(counts.skills, "</span>\n          </span>\n        </div>\n        ").concat(hasKids ? "<div class=\"dept-hub__node-children".concat(expanded, "\" data-children=\"").concat(id, "\">").concat(kidsHtml, "</div>") : '', "\n      </div>\n    ");
  }
  function renderTree() {
    if (!tree.length) {
      treeEl.innerHTML = '';
      emptyEl.classList.remove('d-none');
      return;
    }
    emptyEl.classList.add('d-none');
    treeEl.innerHTML = tree.map(function (n) {
      return renderTreeNode(n, 0);
    }).join('');
    bindTreeEvents();
  }
  function bindTreeEvents() {
    treeEl.querySelectorAll('[data-twisty]').forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        var _btn$closest;
        e.stopPropagation();
        var id = btn.getAttribute('data-twisty');
        var wrap = treeEl.querySelector("[data-children=\"".concat(id, "\"]"));
        if (wrap) wrap.classList.toggle('is-open');
        (_btn$closest = btn.closest('.dept-hub__node-row')) === null || _btn$closest === void 0 || _btn$closest.setAttribute('aria-expanded', wrap !== null && wrap !== void 0 && wrap.classList.contains('is-open') ? 'true' : 'false');
      });
    });
    treeEl.querySelectorAll('[data-dept-id]').forEach(function (row) {
      row.addEventListener('click', function () {
        var id = row.getAttribute('data-dept-id');
        selectDepartment(id);
      });
    });
  }
  function selectDepartment(id) {
    selectedId = String(id);
    treeEl.querySelectorAll('.dept-hub__node-row').forEach(function (el) {
      el.classList.toggle('is-active', el.getAttribute('data-dept-id') === selectedId);
    });
    showDetail(flat[selectedId]);
  }
  function showDetail(node) {
    if (!node || !tpl) return;
    placeholder.classList.add('d-none');
    detailBody.classList.remove('d-none');
    var frag = tpl.content.cloneNode(true);
    var q = function q(sel) {
      return frag.querySelector("[data-field=\"".concat(sel, "\"]"));
    };
    q('name').textContent = node.name || '';
    var desc = q('description');
    desc.textContent = node.description || '';
    desc.classList.toggle('d-none', !node.description);
    var icon = q('icon');
    if (node.icon_url) {
      icon.src = node.icon_url;
      icon.classList.remove('d-none');
      q('fallback').classList.add('d-none');
    } else {
      icon.classList.add('d-none');
      var fb = q('fallback');
      fb.textContent = initials(node.name);
      fb.classList.remove('d-none');
    }
    var badgeRoot = q('badge-root');
    if (!node.parent_id) {
      badgeRoot.textContent = t.rootBadge || '';
      badgeRoot.classList.remove('d-none');
    } else {
      badgeRoot.classList.add('d-none');
    }
    q('stats').textContent = statsLine(node.counts || {});
    var edit = q('edit-link');
    if (cfg.canManage) {
      edit.href = (cfg.editUrlTemplate || '').replace('__ID__', String(node.id));
      edit.classList.remove('d-none');
    } else {
      edit.classList.add('d-none');
      edit.removeAttribute('href');
    }
    var membersEl = q('members');
    var prev = node.members_preview || [];
    if (!prev.length) {
      membersEl.innerHTML = "<p class=\"text-muted small mb-0\">".concat(escapeHtml(t.noMembers || ''), "</p>");
    } else {
      membersEl.innerHTML = prev.map(function (m) {
        return "\n        <div class=\"dept-hub__member\" title=\"".concat(escapeHtml(m.position || ''), "\">\n          ").concat(m.avatar_url ? "<img src=\"".concat(escapeHtml(m.avatar_url), "\" alt=\"\">") : "<span class=\"dept-hub__member-initials\">".concat(escapeHtml(initials(m.name)), "</span>"), "\n          <div class=\"dept-hub__member-text\">\n            <span class=\"dept-hub__member-name\">").concat(escapeHtml(m.name), "</span>\n            ").concat(m.position ? "<span class=\"dept-hub__member-role\">".concat(escapeHtml(m.position), "</span>") : '', "\n          </div>\n        </div>");
      }).join('');
    }
    var more = q('members-more');
    if (node.members_more > 0) {
      var txt = (t.moreMembers || '').replace(':count', String(node.members_more));
      more.textContent = txt;
      more.classList.remove('d-none');
    } else {
      more.classList.add('d-none');
    }
    var posEl = q('positions');
    var positions = node.positions || [];
    if (!positions.length) {
      posEl.innerHTML = "<p class=\"text-muted small mb-0\">".concat(escapeHtml(t.noPositions || ''), "</p>");
    } else {
      posEl.innerHTML = positions.map(function (p) {
        return "<span class=\"dept-hub__chip\">".concat(escapeHtml(p.title), "</span>");
      }).join('');
    }
    var skillsDept = q('skills-dept');
    var byCat = node.skills_by_category || {};
    var catKeys = Object.keys(byCat);
    if (!catKeys.length) {
      skillsDept.innerHTML = "<p class=\"text-muted small mb-0\">".concat(escapeHtml(t.noSkills || ''), "</p>");
    } else {
      skillsDept.innerHTML = catKeys.map(function (cat) {
        return "\n        <div class=\"dept-hub__skill-group\">\n          <div class=\"dept-hub__skill-cat\">".concat(escapeHtml(cat), "</div>\n          <div class=\"dept-hub__chips\">\n            ").concat(byCat[cat].map(function (s) {
          return "<span class=\"dept-hub__chip dept-hub__chip--skill\">".concat(escapeHtml(s.name), "</span>");
        }).join(''), "\n          </div>\n        </div>");
      }).join('');
    }
    var memSkills = q('skills-members');
    var ms = node.member_skills || [];
    if (!ms.length) {
      memSkills.innerHTML = "<p class=\"text-muted small mb-0\">".concat(escapeHtml(t.noSkills || ''), "</p>");
    } else {
      memSkills.innerHTML = ms.map(function (s) {
        return "<span class=\"dept-hub__chip\">".concat(escapeHtml(s.name), "</span>");
      }).join('');
    }
    detailBody.innerHTML = '';
    detailBody.appendChild(frag);
    if ((node.children || []).length) {
      var sub = document.createElement('div');
      sub.className = 'dept-hub__subsection';
      sub.innerHTML = "<h4 class=\"dept-hub__subsection-title\">".concat(escapeHtml(t.childTeams || ''), "</h4>\n        <div class=\"dept-hub__child-pills\">").concat(node.children.map(function (c) {
        return "<button type=\"button\" class=\"dept-hub__child-pill\" data-jump=\"".concat(c.id, "\">").concat(escapeHtml(c.name), " <span class=\"text-muted\">(").concat(c.counts && c.counts.members || 0, ")</span></button>");
      }).join(''), "</div>");
      detailBody.appendChild(sub);
      sub.querySelectorAll('[data-jump]').forEach(function (b) {
        b.addEventListener('click', function () {
          return selectDepartment(b.getAttribute('data-jump'));
        });
      });
    }
  }
  searchEl === null || searchEl === void 0 || searchEl.addEventListener('input', function () {
    filterText = (searchEl.value || '').toLowerCase().trim();
    renderTree();
    if (selectedId && flat[selectedId]) selectDepartment(selectedId);
  });
  (_document$getElementB = document.getElementById('dept-hub-expand')) === null || _document$getElementB === void 0 || _document$getElementB.addEventListener('click', function () {
    treeEl.querySelectorAll('.dept-hub__node-children').forEach(function (el) {
      return el.classList.add('is-open');
    });
  });
  (_document$getElementB2 = document.getElementById('dept-hub-collapse')) === null || _document$getElementB2 === void 0 || _document$getElementB2.addEventListener('click', function () {
    treeEl.querySelectorAll('.dept-hub__node-children').forEach(function (el) {
      return el.classList.remove('is-open');
    });
  });
  fetch(cfg.dataUrl, {
    headers: {
      Accept: 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    },
    credentials: 'same-origin'
  }).then(function (r) {
    return r.json();
  }).then(function (data) {
    tree = data.tree || [];
    flat = data.flat || {};
    var norm = {};
    Object.keys(flat).forEach(function (k) {
      norm[String(k)] = flat[k];
    });
    flat = norm;
    renderTree();
    if (tree.length && tree[0]) {
      selectDepartment(String(tree[0].id));
    }
  })["catch"](function () {
    emptyEl.textContent = 'Could not load departments.';
    emptyEl.classList.remove('d-none');
  });
})();
/******/ })()
;