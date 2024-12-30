document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const fields = ['name', 'surname', 'email', 'phone', 'password'].filter(field => document.getElementById(field));

    document.querySelectorAll('.invalid-feedback').forEach(function(feedback) {
        feedback.style.display = 'none';
    });

    fields.forEach(function(field) {
        const input = document.getElementById(field);
        if (input) {
            input.addEventListener('focus', function() {
                document.querySelectorAll('.invalid-feedback').forEach(function(feedback) {
                    feedback.style.display = 'none';
                });
                const errorFeedback = input.nextElementSibling;
                if (errorFeedback && errorFeedback.classList.contains('invalid-feedback')) {
                    errorFeedback.style.display = 'block';
                }
            });
        }
    });
});
