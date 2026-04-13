const calendarEl = document.getElementById('meetings-calendar');

if (calendarEl && typeof FullCalendar !== 'undefined') {
  const feedUrl = calendarEl.dataset.feedUrl;
  const moveUrlTemplate = calendarEl.dataset.moveUrlTemplate;
  const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    editable: true,
    eventResizableFromStart: true,
    events: async (fetchInfo, successCallback, failureCallback) => {
      try {
        const response = await fetch(`${feedUrl}?start=${encodeURIComponent(fetchInfo.startStr)}&end=${encodeURIComponent(fetchInfo.endStr)}`);
        const data = await response.json();
        successCallback(data);
      } catch (error) {
        failureCallback(error);
      }
    },
    eventDrop: async (info) => {
      if (info.event.extendedProps?.isLeave || info.event.extendedProps?.isHoliday) {
        info.revert();
        return;
      }
      try {
        await updateMeeting(info.event);
      } catch (error) {
        info.revert();
      }
    },
    eventResize: async (info) => {
      if (info.event.extendedProps?.isLeave || info.event.extendedProps?.isHoliday) {
        info.revert();
        return;
      }
      try {
        await updateMeeting(info.event);
      } catch (error) {
        info.revert();
      }
    },
  });

  const updateMeeting = async (event) => {
    const url = moveUrlTemplate.replace(':id', event.id);
    const payload = {
      start_at: event.start ? event.start.toISOString() : null,
      end_at: event.end ? event.end.toISOString() : null,
    };
    const response = await fetch(url, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token,
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify(payload),
    });
    if (!response.ok) {
      throw new Error(`Failed ${response.status}`);
    }
  };

  calendar.render();
}
