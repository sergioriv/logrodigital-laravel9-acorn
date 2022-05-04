/**
 *
 * Move Content
 * A simple component that moves content from a container to another by a breakpoint. It prevents manual duplication.
 *
 *
 */

class MoveContent {
  get options() {
    return {
      targetSelector: '',
      moveBreakpoint: '',
      beforeMove: null,
      afterMove: null,
    };
  }

  constructor(element, options = {}) {
    this.settings = Object.assign(this.options, options);
    this.content = '';
    this.currentPlacement = null; //source - target
    this.sourceContainer = element;
    this.targetContainer = document.querySelector(this.settings.targetSelector);
    if (!this.targetContainer) {
      console.error('No target container for move content');
      return;
    }
    this._init();
  }

  _init() {
    this.content = this.sourceContainer.innerHTML;
    this.sourceContainer.innerHTML = '';
    this.breakpoint = parseInt(Globals[this.settings.moveBreakpoint]);
    window.addEventListener('resize', (event) => {
      this._onWindowResize(event);
    });
    this._onWindowResize(null);
  }

  _onWindowResize(event) {
    if (window.innerWidth >= this.breakpoint) {
      if (this.currentPlacement !== 'target') {
        this.currentPlacement = 'target';
        this._moveToTarget();
      }
    } else if (!this.movedToSmaller) {
      if (this.currentPlacement !== 'source') {
        this.currentPlacement = 'source';
        this._moveToSource();
      }
    }
  }

  // Source should be for smaller screens as Bootstrap is mobile first
  _moveToSource() {
    this._clearBoth();
    this.sourceContainer.insertAdjacentHTML('beforeend', this.content);
    if (this.settings.afterMove) {
      this.settings.afterMove(this.currentPlacement);
    }
  }

  // Target should be for larger screens as Bootstrap is mobile first
  _moveToTarget() {
    this._clearBoth();
    this.targetContainer.insertAdjacentHTML('beforeend', this.content);
    if (this.settings.afterMove) {
      this.settings.afterMove(this.currentPlacement);
    }
  }

  _clearBoth() {
    if (this.settings.beforeMove) {
      this.settings.beforeMove(this.currentPlacement);
    }
    this.sourceContainer.innerHTML = '';
    this.targetContainer.innerHTML = '';
  }
}
