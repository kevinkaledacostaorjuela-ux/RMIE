<?php
// Partial shared: provide helper buttons/elements for "card mode" toggles
// This file is included in multiple index views. It creates hidden buttons
// with the IDs that the page-specific JS expects (e.g. rutasTableBtn, rutasCardsBtn,
// subcategoriasTableBtn, subcategoriasCardsBtn, categoriasTableBtn...).
?>
<div id="card-mode-helper" style="display:none;">
</div>
<script>
// Crear botones auxiliares para cada botón visible de toggle que empiece por "toggle"
document.addEventListener('DOMContentLoaded', function() {
    try {
        // Buscar todos los botones cuyo id comience con "toggle" (ej: toggleRutas)
        var toggles = document.querySelectorAll('[id^="toggle"]');
        toggles.forEach(function(btn) {
            var id = btn.id; // e.g. "toggleRutas"
            var suffix = id.replace(/^toggle/, '');
            if (!suffix) return;
            // normalizar: primera letra minúscula
            var key = suffix.charAt(0).toLowerCase() + suffix.slice(1);

            var tableId = key + 'TableBtn';
            var cardsId = key + 'CardsBtn';

            // Si ya existen, saltar
            if (document.getElementById(tableId) || document.getElementById(cardsId)) return;

            // Crear botones ocultos (los scripts buscan los IDs y se suscriben)
            var helper = document.getElementById('card-mode-helper');
            if (!helper) {
                helper = document.createElement('div');
                helper.id = 'card-mode-helper';
                helper.style.display = 'none';
                document.body.appendChild(helper);
            }

            var bTable = document.createElement('button');
            bTable.id = tableId;
            bTable.type = 'button';
            bTable.style.display = 'none';
            helper.appendChild(bTable);

            var bCards = document.createElement('button');
            bCards.id = cardsId;
            bCards.type = 'button';
            bCards.style.display = 'none';
            helper.appendChild(bCards);
        });
    } catch (e) {
        console.log('card_mode helper init error', e);
    }
});
</script>
