(function () {
  document.querySelectorAll('.js-add-row').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var target = btn.getAttribute('data-target');
      var list = document.querySelector('.repeat-list[data-repeat="' + target + '"]');
      var tpl = document.getElementById('tpl-' + target);
      if (!list || !tpl) return;
      list.appendChild(tpl.content.cloneNode(true));
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
      return;
    }
    row.remove();
  });
})();
