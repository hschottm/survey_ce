// Make the complete matrix cell clickable without requiring jQuery or MooTools.
document.addEventListener('click', function (event) {
    const cell = event.target.closest('.matrix td');
    if (!cell || event.target.closest('input, label, a, button')) {
        return;
    }

    const input = cell.querySelector('input[type="radio"], input[type="checkbox"]');
    if (input && !input.disabled) {
        input.click();
    }
});
