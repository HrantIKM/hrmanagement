const options = {
  pathOptions: {
    searchPath: route('dashboard.attendances.getListData'),
    deletePath: route('dashboard.attendances.destroy', ':id'),
    editPath: route('dashboard.attendances.edit', ':id'),
    showPath: route('dashboard.attendances.show', ':id'),
  },

  relations: {},

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
