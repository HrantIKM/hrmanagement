function formatAttendanceDateTime(raw) {
  if (raw === null || raw === undefined || raw === '') {
    return '<span class="text-muted">—</span>';
  }
  const s = String(raw);
  const d = new Date(s);
  if (Number.isNaN(d.getTime())) {
    return `<span class="ts-time-cell">${s}</span>`;
  }
  return `<span class="ts-time-cell">${new Intl.DateTimeFormat(undefined, {
    dateStyle: 'medium',
    timeStyle: 'short',
  }).format(d)}</span>`;
}

const options = {
  pathOptions: {
    searchPath: route('dashboard.attendances.getListData'),
    deletePath: route('dashboard.attendances.destroy', ':id'),
    editPath: route('dashboard.attendances.edit', ':id'),
    showPath: route('dashboard.attendances.show', ':id'),
  },

  relations: {},

  columnsRender: {
    user_id: {
      render(data, type, row) {
        if (row.user) {
          const u = row.user;
          const full = [u.first_name, u.last_name].filter(Boolean).join(' ').trim();
          return full || u.name || String(data ?? '');
        }
        return data ?? '';
      },
    },
    clock_in: {
      render(data) {
        return formatAttendanceDateTime(data);
      },
    },
    clock_out: {
      render(data) {
        return formatAttendanceDateTime(data);
      },
    },
  },

  actions: {
      show: false,
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);

const attendanceCalendarEl = document.getElementById('attendance-calendar');
if (attendanceCalendarEl && typeof FullCalendar !== 'undefined') {
  const feedUrl = attendanceCalendarEl.dataset.feedUrl;
  const attendanceCalendar = new FullCalendar.Calendar(attendanceCalendarEl, {
    initialView: 'dayGridMonth',
    height: 'auto',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,listWeek',
    },
    dayMaxEventRows: 3,
    displayEventTime: false,
    eventClassNames: 'attendance-event',
    events: async (fetchInfo, successCallback, failureCallback) => {
      try {
        const response = await fetch(`${feedUrl}?start=${encodeURIComponent(fetchInfo.startStr)}&end=${encodeURIComponent(fetchInfo.endStr)}`);
        const data = await response.json();
        successCallback(data);
      } catch (error) {
        failureCallback(error);
      }
    },
  });

  attendanceCalendar.render();
}
