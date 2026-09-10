(function () {
  function bindGalleryCard(card) {
    var file = card.querySelector('.js-gallery-file');
    var url = card.querySelector('.js-gallery-url');
    var thumb = card.querySelector('.js-gallery-thumb');
    var empty = card.querySelector('.js-gallery-thumb-empty');
    if (!file) return;

    function syncThumb() {
      var name = file.value;
      if (!name) {
        if (thumb) {
          thumb.hidden = true;
          thumb.removeAttribute('src');
        }
        if (empty) empty.hidden = false;
        return;
      }
      var src = '/assets/img/gallery/' + name;
      if (thumb) {
        thumb.src = src;
        thumb.hidden = false;
      }
      if (empty) empty.hidden = true;
    }

    file.addEventListener('change', function () {
      syncThumb();
      var name = file.value;
      var m = name.match(/-([A-Za-z0-9_-]{6,})\.(jpe?g|png|webp)$/i);
      if (m && url && !url.value) {
        url.value = 'https://www.youtube.com/watch?v=' + m[1];
      }
    });
  }

  document.querySelectorAll('.gallery-card-admin').forEach(bindGalleryCard);

  document.querySelectorAll('.js-add-row').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var target = btn.getAttribute('data-target');
      var list = document.querySelector('.repeat-list[data-repeat="' + target + '"]');
      var tpl = document.getElementById('tpl-' + target);
      if (!list || !tpl) return;
      var node = tpl.content.cloneNode(true);
      list.appendChild(node);
      var card = list.lastElementChild;
      if (card && card.classList.contains('gallery-card-admin')) {
        bindGalleryCard(card);
      }
    });
  });

  document.addEventListener('click', function (e) {
    var btn = e.target.closest('.js-remove-row');
    if (!btn) return;
    var row = btn.closest('.repeat-row, .repeat-card');
    var list = btn.closest('.repeat-list');
    if (!row || !list) return;
    if (list.children.length <= 1) {
      row.querySelectorAll('input, textarea').forEach(function (el) { el.value = ''; });
      row.querySelectorAll('select').forEach(function (el) { el.selectedIndex = 0; });
      if (row.classList.contains('gallery-card-admin')) {
        bindGalleryCard(row);
        var file = row.querySelector('.js-gallery-file');
        if (file) file.dispatchEvent(new Event('change'));
      }
      return;
    }
    row.remove();
  });
})();
