document.addEventListener('DOMContentLoaded', function() {
    // Skrip JavaScript Anda di sini
    document.querySelectorAll('input[readonly]').forEach(element => {
        element.addEventListener('click', () => {
            element.select();
        });
    });

    document.querySelectorAll('textarea[readonly]').forEach(element => {
        element.addEventListener('click', () => {
            element.select();
        });
    });
});