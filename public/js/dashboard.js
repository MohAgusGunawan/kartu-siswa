document.getElementById('search-dropdown').addEventListener('input', function () {
    const query = this.value.toLowerCase();
    const items = document.querySelectorAll('#dropdown-options a');

    items.forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(query) ? 'block' : 'none';
    });
});