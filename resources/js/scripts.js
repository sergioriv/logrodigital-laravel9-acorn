/**
 *
 * Scripts
 *
 * Initialization of the template base and page scripts.
 *
 *
 */

class Scripts {
    constructor() {
        this._initSettings();
        this._initVariables();
        this._addListeners();
        this._init();
    }

    // Showing the template after waiting for a bit so that the css variables are all set
    // Initialization of the common scripts and page specific ones
    _init() {
        setTimeout(() => {
            document.documentElement.setAttribute("data-show", "true");
            document.body.classList.remove("spinner");
            this._initBase();
            this._initCommon();
            this._initPages();
            this._initForms();
            this._initPlugins();
        }, 100);
    }

    // Base scripts initialization
    _initBase() {
        // Navigation
        if ("undefined" !== typeof Nav) {
            const nav = new Nav(document.getElementById("nav"));
        }

        // Search implementation
        if ("undefined" !== typeof Search) {
            const search = new Search();
        }

        // AcornIcons initialization
        if ("undefined" !== typeof AcornIcons) {
            new AcornIcons().replace();
        }
    }

    // Common plugins and overrides initialization
    _initCommon() {
        // common.js initialization
        if ("undefined" !== typeof Common) {
            let common = new Common();
        }
    }

    // Pages initialization
    _initPages() {
        if ("undefined" !== typeof ResponsiveTab) {
            document.querySelector(".responsive-tabs") !== null &&
                new ResponsiveTab(document.querySelector(".responsive-tabs"));
        }
    }

    // Form and form controls pages initialization
    _initForms() {
      // controls.autocomplete.js initialization
      if ("undefined" !== typeof GenericForms) {
        new GenericForms;
      }
      if ("undefined" !== typeof GenericUserForms) {
        new GenericUserForms;
      }
      if ("undefined" !== typeof AuthLogin) {
        new AuthLogin;
      }
      if ("undefined" !== typeof AuthForgotPassword) {
        new AuthForgotPassword;
      }
      if ("undefined" !== typeof AuthResetPassword) {
        new AuthResetPassword;
      }
      if (typeof SingleImageUpload !== 'undefined' && document.getElementById('imageProfile')) {
        const singleImageUpload = new SingleImageUpload(document.getElementById('imageProfile'));
      }
      if ("undefined" !== typeof ChangeAvatarForm) {
        new ChangeAvatarForm;
      }
      if ("undefined" !== typeof Select2Form) {
        new Select2Form;
      }
      if ("undefined" !== typeof StudentProfileInfoForm) {
        new StudentProfileInfoForm;
      }
      if ("undefined" !== typeof StudentPersonChargeForm) {
        new StudentPersonChargeForm;
      }
      if ("undefined" !== typeof SignaturesInput) {
        new SignaturesInput;
      }
      if ("undefined" !== typeof StudentTransferForm) {
        new StudentTransferForm;
      }
      if ("undefined" !== typeof MyInstitutionForm) {
        new MyInstitutionForm;
      }
      if ("undefined" !== typeof StudentAdviceForm) {
        new StudentAdviceForm;
      }
      if ("undefined" !== typeof StudentDeleteForm) {
        new StudentDeleteForm;
      }
      if ("undefined" !== typeof StudyTimeCreateForm) {
        new StudyTimeCreateForm;
      }
      if ("undefined" !== typeof TeacherCreateForm) {
        new TeacherCreateForm;
      }
      if ("undefined" !== typeof TeacherPermitCreateForm) {
        new TeacherPermitCreateForm;
      }
      if ("undefined" !== typeof PasteGrades) {
        new PasteGrades;
      }
    }

    // Plugin pages initialization
    _initPlugins() {
        // datatable.editablerows.js initialization
        if ("undefined" !== typeof RowsAjaxUsers) {
            new RowsAjaxUsers;
        }
        if ("undefined" !== typeof RowsAjaxRoles) {
            new RowsAjaxRoles;
        }
        if ("undefined" !== typeof DatatableStudyTimes) {
            new DatatableStudyTimes;
        }
        if ("undefined" !== typeof DatatableHeadquarters) {
            new DatatableHeadquarters;
        }
        if ("undefined" !== typeof DatatableAreas) {
            new DatatableAreas;
        }
        if ("undefined" !== typeof DatatableSubjects) {
            new DatatableSubjects;
        }
        if ("undefined" !== typeof DatatablesMyInstitution) {
            new DatatablesMyInstitution;
        }
        if ("undefined" !== typeof DatatableTeacherSubjects) {
            new DatatableTeacherSubjects;
        }
        if ("undefined" !== typeof DatatableStandard) {
            new DatatableStandard;
        }
        if ("undefined" !== typeof DatatablesBoxed) {
            new DatatablesBoxed;
        }
        if ("undefined" !== typeof boxedStudentsMatriculate) {
            new boxedStudentsMatriculate;
        }
        if ("undefined" !== typeof ProgressBars) {
            new ProgressBars;
        }

    }

    // Settings initialization
    _initSettings() {
        if (typeof Settings !== "undefined") {
            const settings = new Settings({
                attributes: { placement: "vertical" },
                showSettings: false,
                storagePrefix: "acorn-classic-dashboard-",
            });
            //   const settings = new Settings({attributes: {placement: 'vertical', color: 'light-lime', layout: 'fluid', radius: 'rounded', behaviour: 'unpinned' }, showSettings: false, storagePrefix: 'acorn-starter-project-'});
        }
    }

    // Variables initialization of Globals.js file which contains valus from css
    _initVariables() {
        if (typeof Variables !== "undefined") {
            const variables = new Variables();
        }
    }

    // Listeners of menu and layout changes which fires a resize event
    _addListeners() {
        document.documentElement.addEventListener(
            Globals.menuPlacementChange,
            (event) => {
                setTimeout(() => {
                    window.dispatchEvent(new Event("resize"));
                }, 25);
            }
        );

        document.documentElement.addEventListener(
            Globals.layoutChange,
            (event) => {
                setTimeout(() => {
                    window.dispatchEvent(new Event("resize"));
                }, 25);
            }
        );

        document.documentElement.addEventListener(
            Globals.menuBehaviourChange,
            (event) => {
                setTimeout(() => {
                    window.dispatchEvent(new Event("resize"));
                }, 25);
            }
        );
    }
}

// Shows the template after initialization of the settings, nav, variables and common plugins.
(function () {
    window.addEventListener("DOMContentLoaded", () => {
        // Initializing of the Scripts
        if (typeof Scripts !== "undefined") {
            const scripts = new Scripts();
        }
    });
})();

// Disabling dropzone auto discover before DOMContentLoaded
(function () {
    if (typeof Dropzone !== "undefined") {
        Dropzone.autoDiscover = false;
    }
})();
