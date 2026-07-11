// Property archive filters: auto-submit the form when a <select> changes.
export function initFilters() {
  const form = document.querySelector('.archive-filters');
  if (!form) return;
  form.querySelectorAll('select').forEach((select) => {
    select.addEventListener('change', () => form.submit());
  });
}
