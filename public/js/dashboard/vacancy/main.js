// eslint-disable-next-line no-undef,no-new
new FormRequest();

if (typeof ClassicEditor !== 'undefined') {
  const editorElement = document.getElementById('vacancy-description-editor');
  if (editorElement) {
    ClassicEditor.create(editorElement).catch(() => {});
  }
}
