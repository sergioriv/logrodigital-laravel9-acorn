/**
 *
 * Signatures
 *
 * */
class SignaturesInput {
    constructor () {
        const form = document.getElementById("studentProfileInfoForm");
        if (!form) {
            console.log("studentProfileInfoForm is null");
            return;
        }


        this._initOpenModalSignatures();
    }

    _initOpenModalSignatures() {
        const _this = this;

        const sig_tutor = document.getElementById("sig-canvas-tutor");
        const sig_student = document.getElementById("sig-canvas-student");

        /* TUTOR */
        const btnOpenSigTutor = document.getElementById("openSigTutor");
        if (btnOpenSigTutor && btnOpenSigTutor.length != 0) {
            var withSigTutor = btnOpenSigTutor.parentElement.clientWidth;
            if (withSigTutor > 440) withSigTutor = 440;
            btnOpenSigTutor.addEventListener("click", function(e) {
                sig_tutor.setAttribute('width', withSigTutor +'px');
                _this._initSignature(sig_tutor, 'tutor');
            }, false);
        }

        /* STUDENT */
        const btnOpenSigStudent = document.getElementById("openSigStudent");
        if (btnOpenSigStudent && btnOpenSigStudent.length != 0) {
            var withSigStudent = btnOpenSigStudent.parentElement.clientWidth;
            if (withSigStudent > 440) withSigStudent = 440;
            btnOpenSigStudent.addEventListener("click", function(e) {
                sig_student.setAttribute('width', withSigStudent +'px');
                _this._initSignature(sig_student, 'student');
            }, false);
        }

    }

    _initSignature(canvas, canvaName) {


        // Set up the UI
        var sigText = document.getElementById("sig-dataUrl-" + canvaName);
        var sigImage = document.getElementById("sig-image-" + canvaName);
        var clearBtn = document.getElementById("sig-clearBtn-" + canvaName);
        var submitBtn = document.getElementById("sig-submitBtn-" + canvaName);

        var change = false;
        // var changeContentImg = $('#sig-img-' + canvaName);
        var changeContentImg = document.getElementById("sig-img-" + canvaName);
        var changeInputImg = document.getElementById("fileSigLoad-" + canvaName);

        dataUrlNull();

        function dataUrlNull() {
            sigText.value = null;
            sigImage.parentElement.classList.add("d-none");
            sigImage.setAttribute("src", "");
            changeContentImg.setAttribute("src", "");
            changeInputImg.value = '';

            canvas.classList.remove('d-none');
            changeContentImg.parentElement.classList.add('d-none');
        }

        (function() {
            window.requestAnimFrame = (function(callback) {
              return window.requestAnimationFrame ||
                window.webkitRequestAnimationFrame ||
                window.mozRequestAnimationFrame ||
                window.oRequestAnimationFrame ||
                window.msRequestAnimaitonFrame ||
                function(callback) {
                  window.setTimeout(callback, 1000 / 60);
                };
            })();

            var writing = false;
            var ctx = canvas.getContext("2d");
            ctx.strokeStyle = "#222222";
            ctx.lineWidth = 1;

            var drawing = false;
            var mousePos = {
              x: 0,
              y: 0
            };
            var lastPos = mousePos;

            jQuery('#fileSigLoad-' + canvaName).on("change", function() {
                changeContentImg.parentElement.classList.remove('d-none');
                canvas.classList.add('d-none');
                change = true;
                writing = false;
            });

            canvas.addEventListener("mousedown", function(e) {
              drawing = true;
              writing = true;
              lastPos = getMousePos(canvas, e);
            }, false);

            canvas.addEventListener("mouseup", function(e) {
              drawing = false;
            }, false);

            canvas.addEventListener("mousemove", function(e) {
              mousePos = getMousePos(canvas, e);
            }, false);

            // Add touch event support for mobile
            /* canvas.addEventListener("touchstart", function(e) {}, false); */

            canvas.addEventListener("touchmove", function(e) {
              var touch = e.touches[0];
              var me = new MouseEvent("mousemove", {
                clientX: touch.clientX,
                clientY: touch.clientY
              });
              canvas.dispatchEvent(me);
            }, false);

            canvas.addEventListener("touchstart", function(e) {
              mousePos = getTouchPos(canvas, e);
              var touch = e.touches[0];
              var me = new MouseEvent("mousedown", {
                clientX: touch.clientX,
                clientY: touch.clientY
              });
              canvas.dispatchEvent(me);
            }, false);

            canvas.addEventListener("touchend", function(e) {
              var me = new MouseEvent("mouseup", {});
              canvas.dispatchEvent(me);
            }, false);

            function getMousePos(canvasDom, mouseEvent) {
              var rect = canvasDom.getBoundingClientRect();
              return {
                x: mouseEvent.clientX - rect.left,
                y: mouseEvent.clientY - rect.top
              }
            }

            function getTouchPos(canvasDom, touchEvent) {
              var rect = canvasDom.getBoundingClientRect();
              return {
                x: touchEvent.touches[0].clientX - rect.left,
                y: touchEvent.touches[0].clientY - rect.top
              }
            }

            function renderCanvas() {
              if (drawing) {
                ctx.moveTo(lastPos.x, lastPos.y);
                ctx.lineTo(mousePos.x, mousePos.y);
                ctx.stroke();
                lastPos = mousePos;
              }
            }

            // Prevent scrolling when touching the canvas
            document.body.addEventListener("touchstart", function(e) {
              if (e.target == canvas) {
                e.preventDefault();
              }
            }, false);
            document.body.addEventListener("touchend", function(e) {
              if (e.target == canvas) {
                e.preventDefault();
              }
            }, false);
            document.body.addEventListener("touchmove", function(e) {
              if (e.target == canvas) {
                e.preventDefault();
              }
            }, false);

            (function drawLoop() {
              requestAnimFrame(drawLoop);
              renderCanvas();
            })();

            function clearCanvas() {
              canvas.width = canvas.width;
              writing = false;
              change = false;
            }


            clearBtn.addEventListener("click", function(e) {
                // changeContentImg.addClass('d-none');
                changeContentImg.parentElement.classList.add('d-none');
                // canvas.removeClass('d-none');
                canvas.classList.remove('d-none');

              dataUrlNull();
              clearCanvas();
            }, false);
            submitBtn.addEventListener("click", function(e) {
              if (writing === true) {
                  var dataUrl = canvas.toDataURL();
                  sigText.value = dataUrl;
                  sigImage.setAttribute("src", dataUrl);
                  sigImage.parentElement.classList.remove("d-none");
              } else if(change === true) {
                  var dataUrl = changeContentImg.getAttribute('src');
                  sigText.value = dataUrl;
                  sigImage.setAttribute("src", dataUrl);
                  sigImage.parentElement.classList.remove("d-none");
              } else {
                dataUrlNull();
              }
            }, false);



          })();
    }

}
