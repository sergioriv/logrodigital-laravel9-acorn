/**
 *
 * ProgressBars
 *
 * Interface.Plugins.Progress page content scripts. Initialized from scripts.js file.
 *
 *
 */

class ProgressBars {
  constructor() {
    // Initialization of the page plugins
    if (typeof ProgressBar === 'undefined') {
      console.log('ProgressBar is null!');
      return;
    }

    this._initProgressLine();
    this._initProgressCircle();
    this._initProgressSemiCircle();
    this._initProgressSquare();
    this._initProgressColors();
  }

  // Line implementation
  _initProgressLine() {
    if (document.querySelector('#progressLineBasic')) {
      new ProgressBar.Line(document.querySelector('#progressLineBasic'), {
        color: Globals.primary,
        trailColor: Globals.separator,
        val: 75,
        max: 100,
        duration: 0,
      }).animate(75 / 100);
    }

    if (document.querySelector('#progressLineAnimated')) {
      new ProgressBar.Line(document.querySelector('#progressLineAnimated'), {
        color: Globals.primary,
        trailColor: Globals.separator,
        val: 75,
        max: 100,
        duration: 500,
      }).animate(75 / 100);
    }

    if (document.querySelector('#progressLinePercent')) {
      new ProgressBar.Line(document.querySelector('#progressLinePercent'), {
        color: Globals.primary,
        trailColor: Globals.separator,
        val: 75,
        max: 100,
        duration: 500,
        step: function (state, bar) {
          bar.setText(Math.round(bar.value() * 100) + '%');
        },
      }).animate(75 / 100);
    }

    if (document.querySelector('#progressLineValue')) {
      new ProgressBar.Line(document.querySelector('#progressLineValue'), {
        color: Globals.primary,
        trailColor: Globals.separator,
        val: 15,
        max: 20,
        duration: 500,
        step: function (state, bar) {
          bar.setText(Math.round(bar.value() * 20) + '/' + 20);
        },
      }).animate(15 / 20);
    }
  }

  // Circle implementation
  _initProgressCircle() {

    document.querySelectorAll("[logro='progress']").forEach((el, i) => {
        const val = el.getAttribute('aria-valuenow');
        const color = Globals[el.getAttribute('data-color')] || Globals.primary;
        const trailColor = Globals[el.getAttribute('data-trail-color')] || Globals.separator;
        const max = el.getAttribute('aria-valuemax') || 100;
        const showPercent = el.getAttribute('data-show-percent');
        const hideAll = el.getAttribute('data-hide-all-text');
        const strokeWidth = el.getAttribute('data-stroke-width') || 1;
        const trailWidth = el.getAttribute('data-trail-width') || 1;
        const duration = parseInt(el.getAttribute('data-duration')) || 20;
        const easing = el.getAttribute('data-easing') || 'easeInOut';
          new ProgressBar.Circle(el, {
            color: color,
            duration: duration,
            easing: easing,
            strokeWidth: strokeWidth,
            trailColor: trailColor,
            trailWidth: trailWidth,
            val: val,
            max: max,
            text: {
              autoStyleContainer: false,
            },
            step: function (state, bar) {
              if (hideAll === 'false') {
                if (showPercent === 'true') {
                  bar.setText(Math.round(bar.value() * 100) + '%');
                } else {
                  bar.setText(val + '/' + max);
                }
              }
            },
          }).animate(val / max);
      });

    if (document.querySelector('#progressCircleBasic')) {
      new ProgressBar.Circle(document.querySelector('#progressCircleBasic'), {
        color: Globals.primary,
        trailColor: Globals.separator,
        val: 75,
        max: 100,
        duration: 0,
      }).animate(75 / 100);
    }

    if (document.querySelector('#progressCircleAnimated')) {
      new ProgressBar.Circle(document.querySelector('#progressCircleAnimated'), {
        color: Globals.primary,
        trailColor: Globals.separator,
        val: 75,
        max: 100,
        duration: 500,
      }).animate(75 / 100);
    }

    if (document.querySelector('#progressCirclePercent')) {
      new ProgressBar.Circle(document.querySelector('#progressCirclePercent'), {
        color: Globals.primary,
        trailColor: Globals.separator,
        val: 75,
        max: 100,
        duration: 500,
        step: function (state, bar) {
          bar.setText(Math.round(bar.value() * 100) + '%');
        },
      }).animate(75 / 100);
    }

    if (document.querySelector('#progressCircleValue')) {
      new ProgressBar.Circle(document.querySelector('#progressCircleValue'), {
        color: Globals.primary,
        trailColor: Globals.separator,
        val: 15,
        max: 20,
        duration: 500,
        step: function (state, bar) {
          bar.setText(Math.round(bar.value() * 20) + '/' + 20);
        },
      }).animate(15 / 20);
    }
  }

  // Semi circle implementation
  _initProgressSemiCircle() {
    if (document.querySelector('#progressSemiCircleBasic')) {
      new ProgressBar.SemiCircle(document.querySelector('#progressSemiCircleBasic'), {
        color: Globals.primary,
        trailColor: Globals.separator,
        val: 75,
        max: 100,
        duration: 0,
      }).animate(75 / 100);
    }

    if (document.querySelector('#progressSemiCircleAnimated')) {
      new ProgressBar.SemiCircle(document.querySelector('#progressSemiCircleAnimated'), {
        color: Globals.primary,
        trailColor: Globals.separator,
        val: 75,
        max: 100,
        duration: 500,
      }).animate(75 / 100);
    }

    if (document.querySelector('#progressSemiCirclePercent')) {
      new ProgressBar.SemiCircle(document.querySelector('#progressSemiCirclePercent'), {
        color: Globals.primary,
        trailColor: Globals.separator,
        val: 75,
        max: 100,
        duration: 500,
        step: function (state, bar) {
          bar.setText(Math.round(bar.value() * 100) + '%');
        },
      }).animate(75 / 100);
    }

    if (document.querySelector('#progressSemiCircleValue')) {
      new ProgressBar.SemiCircle(document.querySelector('#progressSemiCircleValue'), {
        color: Globals.primary,
        trailColor: Globals.separator,
        val: 15,
        max: 20,
        duration: 500,
        step: function (state, bar) {
          bar.setText(Math.round(bar.value() * 20) + '/' + 20);
        },
      }).animate(15 / 20);
    }
  }

  // Square implementation
  _initProgressSquare() {
    if (document.querySelector('#progressSquareBasic')) {
      new ProgressBar.Square(document.querySelector('#progressSquareBasic'), {
        color: Globals.primary,
        trailColor: Globals.separator,
        val: 75,
        max: 100,
        duration: 0,
      }).animate(75 / 100);
    }

    if (document.querySelector('#progressSquareAnimated')) {
      new ProgressBar.Square(document.querySelector('#progressSquareAnimated'), {
        color: Globals.primary,
        trailColor: Globals.separator,
        val: 75,
        max: 100,
        duration: 500,
      }).animate(75 / 100);
    }

    if (document.querySelector('#progressSquarePercent')) {
      new ProgressBar.Square(document.querySelector('#progressSquarePercent'), {
        color: Globals.primary,
        trailColor: Globals.separator,
        val: 75,
        max: 100,
        duration: 500,
        step: function (state, bar) {
          bar.setText(Math.round(bar.value() * 100) + '%');
        },
      }).animate(75 / 100);
    }

    if (document.querySelector('#progressSquareValue')) {
      new ProgressBar.Square(document.querySelector('#progressSquareValue'), {
        color: Globals.primary,
        trailColor: Globals.separator,
        val: 15,
        max: 20,
        duration: 500,
        step: function (state, bar) {
          bar.setText(Math.round(bar.value() * 20) + '/' + 20);
        },
      }).animate(15 / 20);
    }
  }

  // Colors
  _initProgressColors() {
    if (document.querySelector('#progressColorsPrimary')) {
      new ProgressBar.SemiCircle(document.querySelector('#progressColorsPrimary'), {
        color: Globals.primary,
        trailColor: Globals.separator,
        val: 75,
        max: 100,
        duration: 0,
      }).animate(75 / 100);
    }

    if (document.querySelector('#progressColorsSecondary')) {
      new ProgressBar.SemiCircle(document.querySelector('#progressColorsSecondary'), {
        color: Globals.secondary,
        trailColor: Globals.separator,
        val: 75,
        max: 100,
        duration: 500,
      }).animate(75 / 100);
    }

    if (document.querySelector('#progressColorsTertiary')) {
      new ProgressBar.SemiCircle(document.querySelector('#progressColorsTertiary'), {
        color: Globals.tertiary,
        trailColor: Globals.separator,
        val: 75,
        max: 100,
        duration: 500,
        step: function (state, bar) {
          bar.setText(Math.round(bar.value() * 100) + '%');
        },
      }).animate(75 / 100);
    }

    if (document.querySelector('#progressColorsQuaternary')) {
      new ProgressBar.SemiCircle(document.querySelector('#progressColorsQuaternary'), {
        color: Globals.quaternary,
        trailColor: Globals.separator,
        val: 15,
        max: 20,
        duration: 500,
        step: function (state, bar) {
          bar.setText(Math.round(bar.value() * 20) + '/' + 20);
        },
      }).animate(15 / 20);
    }
  }
}
