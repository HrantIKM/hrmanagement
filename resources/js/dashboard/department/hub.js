/* eslint-disable no-param-reassign */
(function () {
  const cfg = window.__DEPT_HUB__;
  if (!cfg || !cfg.dataUrl) return;

  const t = cfg.i18n || {};
  const treeEl = document.getElementById('dept-hub-tree');
  const emptyEl = document.getElementById('dept-hub-empty');
  const searchEl = document.getElementById('dept-hub-search');
  const detailBody = document.getElementById('dept-hub-detail-body');
  const placeholder = document.getElementById('dept-hub-placeholder');
  const tpl = document.getElementById('dept-hub-detail-template');

  let tree = [];
  /** @type {Record<string, object>} */
  let flat = {};
  let selectedId = null;
  let filterText = '';

  function escapeHtml(str) {
    if (!str) return '';
    const d = document.createElement('div');
    d.textContent = str;
    return d.innerHTML;
  }

  function initials(name) {
    const parts = (name || '').trim().split(/\s+/);
    const a = (parts[0] || '?').charAt(0);
    const b = (parts[1] || '').charAt(0);
    return (a + b).toUpperCase() || '?';
  }

  function statsLine(counts) {
    const line = t.statsLine || '';
    return line
      .replace(':members', String(counts.members))
      .replace(':positions', String(counts.positions))
      .replace(':skills', String(counts.skills));
  }

  function nodeMatches(node, q) {
    if (!q) return true;
    const n = (node.name || '').toLowerCase();
    if (n.includes(q)) return true;
    return (node.children || []).some((c) => nodeMatches(c, q));
  }

  function renderTreeNode(node, depth) {
    const open = filterText ? true : depth < 2;
    const hasKids = (node.children || []).length > 0;
    const match = nodeMatches(node, filterText);
    if (filterText && !match) return '';

    const counts = node.counts || { members: 0, positions: 0, skills: 0 };
    const id = String(node.id);
    const active = selectedId === id ? ' is-active' : '';
    const kidsHtml = (node.children || [])
      .map((c) => renderTreeNode(c, depth + 1))
      .join('');
    const expanded = open || (filterText && kidsHtml) ? ' is-open' : '';

    return `
      <div class="dept-hub__node" data-id="${id}">
        <div class="dept-hub__node-row${active}" data-dept-id="${id}" role="treeitem" aria-expanded="${hasKids ? 'true' : 'false'}">
          ${hasKids ? `<button type="button" class="dept-hub__twisty" aria-label="Toggle" data-twisty="${id}"><i class="fas fa-chevron-right"></i></button>` : '<span class="dept-hub__twisty-spacer"></span>'}
          <span class="dept-hub__node-body">
            <span class="dept-hub__node-name">${escapeHtml(node.name)}</span>
            <span class="dept-hub__node-meta">${counts.members} · ${counts.positions} · ${counts.skills}</span>
          </span>
        </div>
        ${hasKids ? `<div class="dept-hub__node-children${expanded}" data-children="${id}">${kidsHtml}</div>` : ''}
      </div>
    `;
  }

  function renderTree() {
    if (!tree.length) {
      treeEl.innerHTML = '';
      emptyEl.classList.remove('d-none');
      return;
    }
    emptyEl.classList.add('d-none');
    treeEl.innerHTML = tree.map((n) => renderTreeNode(n, 0)).join('');
    bindTreeEvents();
  }

  function bindTreeEvents() {
    treeEl.querySelectorAll('[data-twisty]').forEach((btn) => {
      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        const id = btn.getAttribute('data-twisty');
        const wrap = treeEl.querySelector(`[data-children="${id}"]`);
        if (wrap) wrap.classList.toggle('is-open');
        btn.closest('.dept-hub__node-row')?.setAttribute(
          'aria-expanded',
          wrap?.classList.contains('is-open') ? 'true' : 'false',
        );
      });
    });
    treeEl.querySelectorAll('[data-dept-id]').forEach((row) => {
      row.addEventListener('click', () => {
        const id = row.getAttribute('data-dept-id');
        selectDepartment(id);
      });
    });
  }

  function selectDepartment(id) {
    selectedId = String(id);
    treeEl.querySelectorAll('.dept-hub__node-row').forEach((el) => {
      el.classList.toggle('is-active', el.getAttribute('data-dept-id') === selectedId);
    });
    showDetail(flat[selectedId]);
  }

  function showDetail(node) {
    if (!node || !tpl) return;
    placeholder.classList.add('d-none');
    detailBody.classList.remove('d-none');

    const frag = tpl.content.cloneNode(true);
    const q = (sel) => frag.querySelector(`[data-field="${sel}"]`);

    q('name').textContent = node.name || '';
    const desc = q('description');
    desc.textContent = node.description || '';
    desc.classList.toggle('d-none', !node.description);

    const icon = q('icon');
    if (node.icon_url) {
      icon.src = node.icon_url;
      icon.classList.remove('d-none');
      q('fallback').classList.add('d-none');
    } else {
      icon.classList.add('d-none');
      const fb = q('fallback');
      fb.textContent = initials(node.name);
      fb.classList.remove('d-none');
    }

    const badgeRoot = q('badge-root');
    if (!node.parent_id) {
      badgeRoot.textContent = t.rootBadge || '';
      badgeRoot.classList.remove('d-none');
    } else {
      badgeRoot.classList.add('d-none');
    }

    q('stats').textContent = statsLine(node.counts || {});

    const edit = q('edit-link');
    if (cfg.canManage) {
      edit.href = (cfg.editUrlTemplate || '').replace('__ID__', String(node.id));
      edit.classList.remove('d-none');
    } else {
      edit.classList.add('d-none');
      edit.removeAttribute('href');
    }

    const membersEl = q('members');
    const prev = node.members_preview || [];
    if (!prev.length) {
      membersEl.innerHTML = `<p class="text-muted small mb-0">${escapeHtml(t.noMembers || '')}</p>`;
    } else {
      membersEl.innerHTML = prev
        .map(
          (m) => `
        <div class="dept-hub__member" title="${escapeHtml(m.position || '')}">
          ${m.avatar_url
    ? `<img src="${escapeHtml(m.avatar_url)}" alt="">`
    : `<span class="dept-hub__member-initials">${escapeHtml(initials(m.name))}</span>`}
          <div class="dept-hub__member-text">
            <span class="dept-hub__member-name">${escapeHtml(m.name)}</span>
            ${m.position ? `<span class="dept-hub__member-role">${escapeHtml(m.position)}</span>` : ''}
          </div>
        </div>`,
        )
        .join('');
    }

    const more = q('members-more');
    if (node.members_more > 0) {
      const txt = (t.moreMembers || '').replace(':count', String(node.members_more));
      more.textContent = txt;
      more.classList.remove('d-none');
    } else {
      more.classList.add('d-none');
    }

    const posEl = q('positions');
    const positions = node.positions || [];
    if (!positions.length) {
      posEl.innerHTML = `<p class="text-muted small mb-0">${escapeHtml(t.noPositions || '')}</p>`;
    } else {
      posEl.innerHTML = positions
        .map((p) => `<span class="dept-hub__chip">${escapeHtml(p.title)}</span>`)
        .join('');
    }

    const skillsDept = q('skills-dept');
    const byCat = node.skills_by_category || {};
    const catKeys = Object.keys(byCat);
    if (!catKeys.length) {
      skillsDept.innerHTML = `<p class="text-muted small mb-0">${escapeHtml(t.noSkills || '')}</p>`;
    } else {
      skillsDept.innerHTML = catKeys
        .map(
          (cat) => `
        <div class="dept-hub__skill-group">
          <div class="dept-hub__skill-cat">${escapeHtml(cat)}</div>
          <div class="dept-hub__chips">
            ${byCat[cat].map((s) => `<span class="dept-hub__chip dept-hub__chip--skill">${escapeHtml(s.name)}</span>`).join('')}
          </div>
        </div>`,
        )
        .join('');
    }

    const memSkills = q('skills-members');
    const ms = node.member_skills || [];
    if (!ms.length) {
      memSkills.innerHTML = `<p class="text-muted small mb-0">${escapeHtml(t.noSkills || '')}</p>`;
    } else {
      memSkills.innerHTML = ms.map((s) => `<span class="dept-hub__chip">${escapeHtml(s.name)}</span>`).join('');
    }

    detailBody.innerHTML = '';
    detailBody.appendChild(frag);

    if ((node.children || []).length) {
      const sub = document.createElement('div');
      sub.className = 'dept-hub__subsection';
      sub.innerHTML = `<h4 class="dept-hub__subsection-title">${escapeHtml(t.childTeams || '')}</h4>
        <div class="dept-hub__child-pills">${node.children
    .map(
      (c) =>
        `<button type="button" class="dept-hub__child-pill" data-jump="${c.id}">${escapeHtml(c.name)} <span class="text-muted">(${(c.counts && c.counts.members) || 0})</span></button>`,
    )
    .join('')}</div>`;
      detailBody.appendChild(sub);
      sub.querySelectorAll('[data-jump]').forEach((b) => {
        b.addEventListener('click', () => selectDepartment(b.getAttribute('data-jump')));
      });
    }
  }

  searchEl?.addEventListener('input', () => {
    filterText = (searchEl.value || '').toLowerCase().trim();
    renderTree();
    if (selectedId && flat[selectedId]) selectDepartment(selectedId);
  });

  document.getElementById('dept-hub-expand')?.addEventListener('click', () => {
    treeEl.querySelectorAll('.dept-hub__node-children').forEach((el) => el.classList.add('is-open'));
  });
  document.getElementById('dept-hub-collapse')?.addEventListener('click', () => {
    treeEl.querySelectorAll('.dept-hub__node-children').forEach((el) => el.classList.remove('is-open'));
  });

  fetch(cfg.dataUrl, {
    headers: {
      Accept: 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
    },
    credentials: 'same-origin',
  })
    .then((r) => r.json())
    .then((data) => {
      tree = data.tree || [];
      flat = data.flat || {};
      const norm = {};
      Object.keys(flat).forEach((k) => {
        norm[String(k)] = flat[k];
      });
      flat = norm;
      renderTree();
      if (tree.length && tree[0]) {
        selectDepartment(String(tree[0].id));
      }
    })
    .catch(() => {
      emptyEl.textContent = 'Could not load departments.';
      emptyEl.classList.remove('d-none');
    });
}());
