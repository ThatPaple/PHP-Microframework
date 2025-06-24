<div class="d-none d-md-block">
    <form method="get" class="d-inline-block">
        <select id="lang-select" name="lang"
                class="form-select form-select-sm custom-lang-select fw-semibold p-1 ps-2 pe-4 rounded-pill"
                style="min-width: 120px;"
                onchange="this.form.submit()">
            <?php foreach (\App\Core\Lang::getAvailableLocales() as $locale): 
                $meta = \App\Core\Lang::getMeta($locale);               
                ?>
                <option value="<?= $locale ?>" <?= ($_SESSION['lang'] ?? 'en') === $locale ? 'selected' : '' ?>>
                    <?= $meta['emoji'] ?> <?= $meta['name'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>
