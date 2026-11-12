$(function () {
    const $form = $("#registrationForm");

    if (!$form.length) return;

    function showError(field, message) {
        const $input = $("#" + field);
        $input.addClass("invalid").attr("aria-invalid", "true");
        $input.closest(".field").find(".field-error").text(message);
    }

    function clearError(field) {
        const $input = $("#" + field);
        $input.removeClass("invalid").removeAttr("aria-invalid");
        $input.closest(".field").find(".field-error").text("");
    }

    $form.on("submit", function (event) {
        let valid = true;
        $("#clientError").text("");

        ["name", "email", "phone", "course", "dob"].forEach(clearError);
        $("input[name='gender']").closest(".field").find(".field-error").text("");

        const name = $("#name").val().trim();
        const email = $("#email").val().trim();
        const phone = $("#phone").val().trim();
        const course = $("#course").val();
        const gender = $("input[name='gender']:checked").val();
        const dob = $("#dob").val();

        if (name.length < 2) { showError("name", "Enter at least 2 characters."); valid = false; }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { showError("email", "Enter a valid email address."); valid = false; }
        if (!/^[0-9]{10}$/.test(phone)) { showError("phone", "Enter a 10-digit phone number."); valid = false; }
        if (!course) { showError("course", "Please select a course."); valid = false; }
        if (!gender) {
            $("input[name='gender']").closest(".field").find(".field-error").text("Please select an option.");
            valid = false;
        }
        if (!dob || dob > new Date().toISOString().slice(0, 10)) { showError("dob", "Choose a valid date."); valid = false; }

        if (!valid) {
            event.preventDefault();
            $("#clientError").text("Please correct the highlighted fields.");
            $(".invalid").first().trigger("focus");
        }
    });

    $("#phone").on("input", function () {
        this.value = this.value.replace(/\D/g, "").slice(0, 10);
    });

    ["name", "email", "phone", "course", "dob"].forEach(function (field) {
        $("#" + field).on("input change", function () { clearError(field); });
    });
    $("input[name='gender']").on("change", function () {
        $(this).closest(".field").find(".field-error").text("");
    });
});
