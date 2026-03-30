document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.custom-pack-card').forEach(function (card) {
        card.addEventListener('click', function (e) {
            if (e.target.tagName.toLowerCase() === 'input') return;

            document.querySelectorAll('.custom-pack-card').forEach(c => c.classList.remove('custom-pack-card--selected'));
            this.classList.add('custom-pack-card--selected');

            this.querySelector('input').checked = true;
            var title = this.querySelector('.custom-pack-card__title').innerText;
            var labelEl = document.getElementById('dynamic-pack-label');
            if (labelEl) {
                labelEl.innerText = title;
            }
        });
    });
});

