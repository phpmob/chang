$(document).on('click', '[data-form-filter] [data-remove]', function () {
    $(this).closest('[data-form-filter]').find('[data-add]').show();
    $(this).closest('[data-row]').remove();
});

$(document).on('click', '[data-form-filter] [data-add]', function () {
    const $filter = $(this).closest('[data-form-filter]');
    const filters = $(this).closest('[data-filters]').data('filters');

    for (let name in filters) {
        let $rows = $filter.find('[data-rows]');
        let $row = $rows.find('[data-row="' + name + '"]');

        if (!$row.length) {
            $rows.append(filters[name]);
            $(document).trigger('dom-node-inserted', [$rows]);
            break;
        }
    }

    if ($filter.find('[data-row]').length === Object.keys(filters).length) {
        $(this).hide();
    }
});
