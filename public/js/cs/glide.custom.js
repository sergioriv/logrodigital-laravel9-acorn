/**
 *
 * GlideCustom
 *
 * Small additions for Glide to create bullets on the fly and hide them based on the breakpoint
 *
 * @param {any} element Dom element to init Glide
 * @param {Object} options Glide options
 * @param {boolean} addDots Shows or hides dots
 *
 * @method destroy Destroys the instances
 * @method update Updates the instances
 * @method mount Mounts the instances
 *
 */

class GlideCustom {
  get options() {
    return {};
  }

  // Glide object
  get glide() {
    return this._glide;
  }

  constructor(element, options = {}, addDots = true) {
    this.settings = Object.assign(this.options, options);
    this._glide;
    this.addDots = addDots;
    this.element = element;
    this._init();
  }

  _init() {
    if (typeof Glide === 'undefined') {
      console.log('Glide is undefined!');
      return;
    }
    this._glide = new Glide(this.element, this.settings);
    // Appending max number of dots
    if (this.addDots) {
      this._glide.on(['mount.before'], () => {
        const bulletCount = this._glide.selector.querySelectorAll('.glide__slide').length;
        const bulletWrapper = this._glide.selector.querySelectorAll('.glide__bullets')[0];

        if (!bulletWrapper) {
          return;
        }

        while (bulletWrapper.firstChild) {
          bulletWrapper.removeChild(bulletWrapper.firstChild);
        }

        for (let index = 0; index < bulletCount; index++) {
          const button = document.createElement('button');
          button.className = 'glide__bullet';
          button.setAttribute('data-glide-dir', '=' + index);
          bulletWrapper.appendChild(button);
        }
      });
    }

    // Hiding them with d-none if it is needed
    this._glide.on(['resize', 'build.after'], () => {
      const perView = this._glide.settings.perView;
      const total = this._glide.selector.querySelectorAll('.glide__slide').length;
      const sub = total - perView;

      // Adds or removes d-none class
      this._glide.selector.querySelectorAll('.glide__bullet').forEach((el, i) => {
        if (i > sub) {
          el.classList.add('d-none');
        } else {
          el.classList.remove('d-none');
        }
      });
      // Prevents the empty last stop when resized for a larger breakpoint with more items
      if (this._glide.index > sub && sub >= 0) {
        this._glide.go('=' + sub);
      }
    });
  }

  mount() {
    this._glide.mount();
  }

  update() {
    this._glide.update();
  }

  destroy() {
    this._glide.destroy();
  }
}

/**
 *
 * GlideGallery
 * Two glide carousels working together to create thumbs and details.
 *
 *
 * @param {any} elementLarge Dom element to init Glide for large images
 * @param {any} elementThumb Dom element to init Glide for small images
 * @param {any} optionsLarge Options for large Glide
 * @param {any} optionsThumb Options for small Glide
 * @param {number} glideLength Length of the slides
 * @param {number} perView Initial thumb count
 * @param {number} thumbWidth Thumb width
 *
 * @method destroy Destroys all the instances
 * @method update Updates all the instances
 *
 */

class GlideGallery {
  // Glide details object
  get glideLarge() {
    return this._glideLarge;
  }

  // Glide thumbs object
  get glideThumb() {
    return this._glideThumb;
  }

  get optionsLarge() {
    return {};
  }

  get optionsThumb() {
    return {};
  }

  constructor(elementLarge, elementThumb, optionsLarge = {}, optionsThumb = {}, glideLength, perView, thumbWidth) {
    this.settingsLarge = Object.assign(this.optionsLarge, optionsLarge);
    this.settingsThumb = Object.assign(this.optionsThumb, optionsThumb);
    this.elementLarge = elementLarge;
    this.elementThumb = elementThumb;
    this._glideLarge;
    this._glideThumb;
    this._glideLength = glideLength;
    this._perView = perView;
    this._thumbWidth = thumbWidth;
    this._baguetteBox;

    // Preventing clicks when swiping
    this._linkAllowClick = true;
    this._linkSwipeEnd = true;

    this._init();
    this._addActiveThumbClass(0);
    this._addListeners();
    this._mount();
    this._onGlideThumbResize();
    this._initBaguetteBox();
    window.dispatchEvent(new Event('resize'));
  }

  _init() {
    this.elementThumb.style.width = this._perView * this._thumbWidth + 'px';
    this._glideLarge = new Glide(this.elementLarge, this.settingsLarge);
    this._glideThumb = new Glide(this.elementThumb, this.settingsThumb);
  }

  _mount() {
    this._glideLarge.mount().update();
    this._glideThumb.mount().update();
  }

  _addActiveThumbClass(index) {
    this.elementThumb.querySelectorAll('li').forEach((el, i) => {
      el.classList.remove('active');
      if (i === index) {
        el.classList.add('active');
      }
    });
    var gap = this.glideThumb.index + this._perView;
    if (index >= gap) {
      this.glideThumb.go('>');
    }
    if (index < this.glideThumb.index) {
      this.glideThumb.go('<');
    }
  }

  _onThumbClick(event) {
    var clickedIndex = this._getIndex(event.currentTarget);
    this.glideLarge.go('=' + clickedIndex);
    this._addActiveThumbClass(clickedIndex);
  }

  _onGlideThumbResize() {
    this._perView = Math.min(this.glideThumb.settings.perView, this._glideLength);
    this.elementThumb.style.width = this._perView * this._thumbWidth + 'px';
    if (this._perView >= this.elementThumb.querySelectorAll('li').length) {
      this.elementThumb.querySelector('.glide__arrows').style.display = 'none';
    } else {
      this.elementThumb.querySelector('.glide__arrows').style.display = 'block';
    }
  }

  _onLargeElementAnchorClick(event) {
    if (this._linkAllowClick) {
      return true;
    }
    event.preventDefault();
    event.stopPropagation();
    event.stopImmediatePropagation();
  }

  _addListeners() {
    this.elementThumb.querySelectorAll('li').forEach((el, i) => {
      el.addEventListener('click', this._onThumbClick.bind(this));
    });
    this.glideLarge.on(['swipe.end'], this._onGlideLargeSwipeEnd.bind(this));
    this.glideLarge.on(['swipe.move'], this._onGlideLargeSwipeMove.bind(this));
    this.glideLarge.on(['move.after'], this._onGlideLargeMoveAfter.bind(this));
    this.glideThumb.on(['resize'], this._onGlideThumbResize.bind(this));
    this.elementLarge.querySelectorAll('a').forEach((el) => {
      el.addEventListener('click', this._onLargeElementAnchorClick.bind(this));
    });
  }

  _getIndex(elem) {
    var i = 0;
    while ((elem = elem.previousElementSibling) != null) ++i;
    return i;
  }

  _initBaguetteBox() {
    if (this.elementLarge.querySelector('.gallery-glide-custom') && typeof baguetteBox !== 'undefined') {
      baguetteBox.run('.gallery-glide-custom');
    }
  }

  _onGlideLargeSwipeEnd(event) {
    this._addActiveThumbClass(this.glideLarge.index);
    this._linkSwipeEnd = true;
  }

  _onGlideLargeSwipeMove() {
    this._linkSwipeEnd = false;
    this._linkAllowClick = false;
  }

  _onGlideLargeMoveAfter() {
    if (this._linkSwipeEnd) {
      this._linkAllowClick = true;
    }
  }

  destroy() {
    this._glideLarge.destroy();
    this._glideThumb.destroy();
  }

  update() {
    this._glideLarge.update();
    this._glideThumb.update();
  }
}
