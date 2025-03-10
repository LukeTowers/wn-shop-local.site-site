<div data-control="toolbar">
    <a
        href="<?= Backend::url('shoplocal/core/retailercategories/create') ?>"
        class="btn btn-primary wn-icon-plus">
        <?= e(trans('backend::lang.form.create_title', ['name' => trans('shoplocal.core::lang.models.retailercategory.label')])); ?>
    </a>

    <button
        class="btn btn-primary wn-icon-shuffle"
        data-request="onGenerateDummyData"
        data-request-confirm="Would you like to generate & insert dummy data?"
        data-stripe-load-indicator>
        Generate Data
    </button>

    <button
        class="btn btn-danger wn-icon-trash-o"
        disabled="disabled"
        onclick="$(this).data('request-data', { checked: $('.control-list').listWidget('getChecked') })"
        data-request="onDelete"
        data-request-confirm="<?= e(trans('backend::lang.list.delete_selected_confirm')); ?>"
        data-trigger-action="enable"
        data-trigger=".control-list input[type=checkbox]"
        data-trigger-condition="checked"
        data-request-success="$(this).prop('disabled', 'disabled')"
        data-stripe-load-indicator>
        <?= e(trans('backend::lang.list.delete_selected')); ?>
    </button>
</div>
