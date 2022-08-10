/**
 *
 * Change Avatar Form
 *
 * */

 class ChangeAvatarForm {
    constructor() {
        // Initialization of the page
        if (!jQuery('#avatar')) {
            console.log("avatar is undefined!");
            return;
        }

        this._initChangeAvatar();
    }

    _initChangeAvatar() {
        jQuery('#avatar').on("change", function() {
            $("#formAvatar").submit();
        });
    }
 }
