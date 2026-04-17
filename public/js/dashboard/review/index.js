function renderReviewRating(data) {
  if (data === null || data === undefined || data === '') {
    return '<span class="text-muted">—</span>';
  }
  const num = Number(data);
  const rounded = Math.min(5, Math.max(0, Math.round(Number.isNaN(num) ? 0 : num)));
  let stars = '';
  for (let i = 1; i <= 5; i += 1) {
    const cls = i <= rounded ? 'fas fa-star text-warning' : 'far fa-star text-muted';
    stars += `<i class="${cls}"></i>`;
  }
  return `<span class="review-rating-stars" title="${data}">${stars}<span class="review-rating-num">${data}</span></span>`;
}

function renderReviewPeriod(data, type, row) {
  const label = data || '';
  const key = (row.review_period || '').toString().replace(/_/g, '-');
  const cls = key ? `review-period-pill review-period-pill--${key}` : 'review-period-pill';
  return `<span class="${cls}">${label}</span>`;
}

function renderReviewPerspective(data, type, row) {
  const label = data || '';
  const key = (row.review_perspective || '').toString().replace(/_/g, '-');
  const cls = key ? `review-perspective-pill review-perspective-pill--${key}` : 'review-perspective-pill';
  return `<span class="${cls}">${label}</span>`;
}

const options = {
  pathOptions: {
    searchPath: route('dashboard.reviews.getListData'),
    deletePath: route('dashboard.reviews.destroy', ':id'),
    editPath: route('dashboard.reviews.edit', ':id'),
    showPath: route('dashboard.reviews.show', ':id'),
  },

  relations: {
    user: 'name',
    reviewer: 'name',
  },

  columnsRender: {
    rating: {
      render(data) {
        return renderReviewRating(data);
      },
    },
    review_period_display: {
      render(data, type, row) {
        return renderReviewPeriod(data, type, row);
      },
    },
    review_perspective_display: {
      render(data, type, row) {
        return renderReviewPerspective(data, type, row);
      },
    },
  },

  actions: {
    show: false,
  },
};
// eslint-disable-next-line no-new,no-undef
new DataTable(options);
