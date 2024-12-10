<div class="container">
    <div class="row">
        <div id="rating-component">
            <h3>Оценка обращения №<?= htmlspecialchars($arParams['DEAL_ID']) ?></h3>
            <? foreach ($arResult['CRITERIA_MAP'] as $k => $criterion): ?>
                <div class="rating">
                    <span><?= $k ?></span>
                    <div class="stars" data-criterion="<?= $k ?>">
                        <? for ($i = 1; $i <= 5; $i++): ?>
                            <span class="star" data-value="<?= $i ?>">☆</span>
                        <? endfor; ?>
                    </div>
                </div>
            <? endforeach; ?>
            <div id="rating-message" style="color: green;"></div>
        </div>
    </div>
</div>
<script>
    document.querySelectorAll('.star').forEach(star => {
        star.addEventListener('click', function() {
            const starsContainer = this.parentNode;
            const value = parseInt(this.dataset.value, 10);
            const criterion = this.parentNode.dataset.criterion;

            // TODO: расскомментировать, если нужно
            // const messageContainer = document.getElementById('rating-message');

            starsContainer.querySelectorAll('.star').forEach(star => {
                star.classList.toggle('selected', parseInt(star.dataset.value, 10) <= value);
            });

            BX.ajax.runComponentAction('tau:feedbackrating', 'rate', {
                mode: 'class',
                data: {
                    DEAL_ID: <?= $arParams['DEAL_ID'] ?>,
                    criterion,
                    value
                }
            }).then(() => {
                if (messageContainer) {
                    messageContainer.textContent = 'Спасибо за вашу оценку!';
                }
            }).catch((error) => {
                if (messageContainer) {
                    messageContainer.style.color = 'red';
                    messageContainer.textContent = 'Произошла ошибка, попробуйте позже.';
                }
                console.error('Ошибка:', error);
            });
        });
    });
</script>
<style>
    .rating .star {
        cursor: pointer;
        font-size: 2.5em;
        transition: color 0.3s;
        color: gray;
    }

    .rating .star.selected,
    .rating .star:hover {
        color: gold;
    }
</style>