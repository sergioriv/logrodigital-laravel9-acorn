const Active = function (quill, options) {
  this.quill = quill;
  this.options = options;
  this.container = quill.container;
  this.activeClass = options.activeClass || 'active';

  const _this = this;
  this.quill.on('selection-change', (range, oldRange, source) => {
    if (range === null && oldRange !== null) {
      if (this.container.parentNode.classList.contains('editor-container')) {
        this.container.parentNode.classList.remove(this.activeClass);
      } else {
        this.container.classList.remove(this.activeClass);
      }
    } else if (range !== null && oldRange === null) {
      if (this.container.parentNode.classList.contains('editor-container')) {
        this.container.parentNode.classList.add(this.activeClass);
      } else {
        this.container.classList.add(this.activeClass);
      }
    }
  });
};

if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
  module.exports = Active;
}
