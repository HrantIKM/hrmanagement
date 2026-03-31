// eslint-disable-next-line no-underscore-dangle,no-unused-vars
// const cropperModal = new Modal('cropImageModal');
const cropperModal = new Modal('cropImageModal');

class FileUploader {
  constructor() {
    this.init();
  }

  init() {
    this.deleteFileEvent();
    this.$_functions();
  }

  $_functions() {
    $('body').on('click', '.__delete__file__btn', this.deleteFileHandler.bind(this));
    $('input[type=file].temp-file-type').change(this.fileChangeHandle.bind(this));
  }

  getAttribute(qualifiedDataName) {
    return this.$el.getAttribute(`data-${qualifiedDataName}`);
  }

  fileChangeHandle() {
    this.$el = event.target;
    this.$fileListEl = $(this.$el).siblings('div.__file__list__default');
    this.$fileErrorBox = $(this.$el).siblings('div.error-messages-block');
    this.$fileHiddenInputsBox = $(this.$el).siblings('.hidden-file-inputs');
    this.resetUploader();
    this.fileData = this.$el.files;
    this.filename = '';

    if ($(this.$el).data('has-crop')) {
      if (typeof this.cropContainer == 'undefined') {
        this.initCroppie();
        this.cropModalActions();
      }

      this.cropperImageChange();
    } else {
      this.removeOldUploadedFiles();
      this.generateFormDataForFiles();
    }

    this.$el.value = '';
  }

  removeOldUploadedFiles() {
    this.is_multiple = this.$el.getAttribute('multiple') !== null;

    if (!this.is_multiple) {
      $(this.$el).siblings('.__uploaded__files').remove();
    }
  }

  initCroppie() {
    this.cropContainer = $('.crop-img-container').croppie({
      enableExif: true,
      viewport: {
        width: 200,
        height: 200,
        type: 'circle',
      },
      boundary: {
        width: 300,
        height: 300,
      },
    });
  }

  cropperImageChange() {
    cropperModal.show();

    const reader = new FileReader();
    reader.onload = (e) => {
      this.cropContainer.croppie('bind', {
        url: e.target.result,
      });
    };
    reader.readAsDataURL(this.fileData[0]);
    this.filename = this.fileData[0].name;
  }

  cropModalActions() {
    cropperModal.save(() => {
      this.cropContainer.croppie('result', {
        type: 'canvas',
        size: 'viewport',
      }).then((resp) => {
        //
        this.removeOldUploadedFiles();

        const formData = new FormData();
        formData.append('config_key', `${this.getAttribute('config-key')}.${this.getAttribute('name')}`);
        formData.append('file', resp);
        formData.append('name', this.filename);
        this.sendData(formData, 0);
        this.inputName = this.getAttribute('name');

        cropperModal.hide();
      });
    });
  }

  resetUploader() {
    this.$fileListEl.html('');
    this.$fileErrorBox.find('ul').html('');
    this.$fileErrorBox.hide();
    this.$fileHiddenInputsBox.html('');
  }

  generateFormDataForFiles() {
    if (this.fileData) {
      this.inputName = this.getAttribute('name');
      const configKey = this.getAttribute('config-key');
      // eslint-disable-next-line no-restricted-syntax
      for (const [key, file] of Object.entries(this.fileData)) {
        const formData = new FormData();
        formData.append('config_key', `${configKey}.${this.inputName}`);
        formData.append('file', file);
        this.sendData(formData, key);
      }
    }
  }

  async sendData(formData, fileIndex) {
    // eslint-disable-next-line no-undef
    this.createPreviewBlock(fileIndex);
    // eslint-disable-next-line no-undef
    await axios.post(route('dashboard.files.storeTempFile'), formData, {
      onUploadProgress: (progressEvent) => {
        const totalLength = progressEvent.lengthComputable ? progressEvent.total : progressEvent.target.getResponseHeader('content-length') || progressEvent.target.getResponseHeader('x-decompressed-content-length');
        if (totalLength !== null) {
          const progress = Math.round((progressEvent.loaded * 100) / totalLength);
          this.progressBar(progress, fileIndex);
        }
      },
    })
      .then((resp) => {
        resp = resp.data;
        this.setPreviewValue(resp, fileIndex);
        this.appendDeleteBtn(fileIndex);
        this.createHiddenInputs(resp);
      })
      .catch((error) => {
        this.errorHandler(error, fileIndex);
        this.appendDeleteBtn(fileIndex, true);
      });
  }

  setPreviewValue(fileInfo, fileIndex) {
    const fileItem = $(this.$fileListEl)
      .find(`.file__item[data-index=${fileIndex}]`);

    fileItem.addClass(`file__item__type__${fileInfo.file_type}`);
    fileItem.html(this.createHtmlForFile(fileInfo));
  }

  appendDeleteBtn(fileIndex, is_error = false) {
    const fileItem = $(this.$fileListEl).find(`.file__item[data-index=${fileIndex}]`);
    const view = ` <button class="position-absolute __delete__file __delete__file__btn"
                    data-index="${fileIndex}"
                    data-event-name="deleteFileItemEvent"
                    type="button"><i class="fas fa-times"></i></button>
                    `;
    fileItem.append(view);
  }

  createHiddenInputs(file) {
    let arraySymbol = '';

    if (this.is_multiple) {
      arraySymbol = '[]';
    }

    this.$fileHiddenInputsBox.append(`<input type="hidden" name="${this.inputName}${arraySymbol}" value="${file.name}">`);
  }

  errorHandler(error, fileIndex) {
    const fileItem = $(this.$fileListEl).find(`.file__item[data-index=${fileIndex}]`);
    fileItem.find('.progress-bar').addClass('bg-danger');

    if (error.response && error.response.data.errors) {
      this.$fileErrorBox.show();
      error = error.response.data;
      // eslint-disable-next-line no-restricted-syntax
      for (const value of Object.entries(error.errors)) {
        this.$fileErrorBox.find('ul').append(`<li data-index="${fileIndex}">File :${fileIndex} ${value}</li>`);
      }
    }
  }

  progressBar(progress, fileIndex) {
    $(this.$fileListEl)
      .find(`.file__item[data-index=${fileIndex}] .file__progress .progress-bar`)
      .css({ width: `${progress}%` })
      .attr('aria-valuenow', progress)
      .html(`${progress}%`);
  }

  createPreviewBlock(fileIndex) {
    const preview = `
            <div class="file__item d-flex justify-content-center position-relative mt-2" data-index="${fileIndex}">
                <div class="progress file__progress ">
                    <div class="progress-bar"
                         role="progressbar"
                         style="width: 0%;"
                         aria-valuenow="0"
                         aria-valuemin="0"
                         aria-valuemax="100">
                        0%
                    </div>
                </div>
            </div>
    `;

    this.$fileListEl.append(preview);
  }

  // eslint-disable-next-line class-methods-use-this
  createHtmlForFile(fileInfo) {
    switch (fileInfo.file_type) {
      case 'image':
        return `<img src="${fileInfo.file_url}" alt="${fileInfo.original_name}" class="upload-file-img">`;
      default:
        return `<span class="mr-5 text-primary p-2">${fileInfo.original_name}</span>`;
    }
  }

  // Delete functions start
  deleteFileHandler(event) {
    const el = event.target;
    const index = $(el).data('index');

    this.$fileErrorBox.find(`ul li[data-index="${index}"]`).remove();
    if (!this.$fileErrorBox.find('ul li').length) {
      this.$fileErrorBox.hide();
    }

    $(el).closest('.file__item').remove();
    this.$fileHiddenInputsBox.html('');
    this.deleteFileEvent();
  }

  deleteFileEvent() {
    window.addEventListener('deleteFileItemInDbEvent', (event) => {
      const el = event.detail.element;
      const boxLength = $(el).parents('.__uploaded__files').find('.file__item').length;
      if (boxLength < 2) {
        $(el).parents('.__uploaded__files').remove();
      }
      $(el).parents('.file__item').remove();
    });
  }
  // Delete functions end
}

// eslint-disable-next-line no-new
new FileUploader();
