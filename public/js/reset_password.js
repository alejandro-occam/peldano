"use strict";

// Class Definition
var KTLogin = (function () {
    var resetForm;

    function resetFormFunction() {
        var validation;
        var form = KTUtil.getById("reset-password-form");

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validation = FormValidation.formValidation(form, {
            fields: {
                email: {
                    validators: {
                        notEmpty: {
                            message: "El email es obligatorio",
                        },
                        emailAddress: {
                            message: "El email no es válido",
                        },
                    },
                },
                password: {
                    validators: {
                        notEmpty: {
                            message: "La nueva contraseña es obligatoria",
                        },
                    },
                },
                password_confirmation: {
                    validators: {
                        identical: {
                            compare: function () {
                                return form.querySelector('[name="password"]')
                                    .value;
                            },
                            message: "Las contraseñas no coinciden",
                        },
                    },
                },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap(),
            },
        });

        // Handle submit button
        $("#reset-password-submit").on("click", function (e) {
            e.preventDefault();

            validation.validate().then(function (status) {
                if (status == "Valid") {
                    // Submit form
                    form.submit();
                } else {
                    Swal.fire({
                        text: "Lo siento, debes completar todos los campos.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Aceptar",
                        customClass: {
                            confirmButton:
                                "btn font-weight-bold btn-light-primary",
                        },
                    }).then(function () {
                        KTUtil.scrollTop();
                    });
                }
            });
        });
    }

    // Public Functions
    return {
        // public functions
        init: function () {
            resetForm = $("#reset-password-form");

            resetFormFunction();
        },
    };
})();

// Class Initialization
$(() => {
    KTLogin.init();
});
