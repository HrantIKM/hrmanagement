// eslint-disable-next-line no-undef,no-new
new FormRequest();

const actionItemsBtn = document.getElementById('meeting-action-items-btn');
if (actionItemsBtn) {
  actionItemsBtn.addEventListener('click', async () => {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const response = await fetch(actionItemsBtn.dataset.url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token,
        'X-Requested-With': 'XMLHttpRequest',
      },
    });
    const result = await response.json();
    // eslint-disable-next-line no-alert
    alert(result.message || 'Action completed');
  });
}
