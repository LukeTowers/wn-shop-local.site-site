<?php Block::put('breadcrumb') ?>
    <ul>
        <li><a href="<?= Backend::url('shoplocal/core/retailers') ?>"><?= e(trans('shoplocal.core::lang.models.retailer.label_plural')) ?></a></li>
        <li><?= e(trans($this->pageTitle)) ?></li>
    </ul>
<?php Block::endPut() ?>

<?php if (!$this->fatalError): ?>

    <?php Block::put('form-contents') ?>
        <div class="layout">

            <div class="layout-row">
                <?= $this->formRenderOutsideFields() ?>
                <?= $this->formRenderPrimaryTabs() ?>
            </div>

            <div class="form-buttons">
                <div class="loading-indicator-container">
                    <button
                        type="button"
                        data-request="onSave"
                        data-request-data="redirect:0"
                        data-hotkey="ctrl+s, cmd+s"
                        data-load-indicator="<?= e(trans('backend::lang.form.saving_name', ['name' => trans('shoplocal.core::lang.models.retailer.label')])); ?>"
                        class="btn btn-primary">
                        <?= e(trans('backend::lang.form.save')); ?>
                    </button>
                    <button
                        type="button"
                        data-request="onSave"
                        data-request-data="close:1"
                        data-hotkey="ctrl+enter, cmd+enter"
                        data-load-indicator="<?= e(trans('backend::lang.form.saving_name', ['name' => trans('shoplocal.core::lang.models.retailer.label')])); ?>"
                        class="btn btn-default">
                        <?= e(trans('backend::lang.form.save_and_close')); ?>
                    </button>
                    <button
                        type="button"
                        class="wn-icon-trash-o btn-icon danger pull-right"
                        data-request="onDelete"
                        data-load-indicator="<?= e(trans('backend::lang.form.deleting_name', ['name' => trans('shoplocal.core::lang.models.retailer.label')])); ?>"
                        data-request-confirm="<?= e(trans('backend::lang.form.confirm_delete')); ?>">
                    </button>
                    <span class="btn-text">
                        or <a href="<?= Backend::url('shoplocal/core/retailers') ?>"><?= e(trans('backend::lang.form.cancel')); ?></a>
                    </span>
                </div>
            </div>

        </div>
    <?php Block::endPut() ?>

    <?php Block::put('form-sidebar') ?>
        <div class="hide-tabs"><?= $this->formRenderSecondaryTabs() ?></div>
    <?php Block::endPut() ?>

    <?php Block::put('body') ?>
        <?= Form::open(['class'=>'layout stretch']) ?>
            <?= $this->makeLayout('form-with-sidebar') ?>
        <?= Form::close() ?>
    <?php Block::endPut() ?>

<?php else: ?>
    <div class="control-breadcrumb">
        <?= Block::placeholder('breadcrumb') ?>
    </div>
    <div class="padded-container">
        <p class="flash-message static error"><?= e(trans($this->fatalError)) ?></p>
        <p><a href="<?= Backend::url('shoplocal/core/retailers') ?>" class="btn btn-default"><?= e(trans('backend::lang.form.return_to_list')); ?></a></p>
    </div>
<?php endif ?>
