<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SupplierSearchForm */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Suppliers';
$this->params['breadcrumbs'][] = $this->title;

$script = <<< JS
var ids = []
    , exportByQuery = false
    , allBtn = $('#all-check')
    , form = $('#export_form')
    , idsInput = $('#export_form input[name=ids]')
    , exportBtn = $('#export-selected')
    , actionUrl = form.attr('action')

function onReload() {
    ids = []
    exportByQuery = false
    allBtn = $('#all-check')
    hideExportHint()
}

function hasUnchecked() {
    return $('.i-check:not(:checked)').length > 0;
}

function onAllCheck(item) {
    var obj = $(item)
        , select = obj.prop('checked')
    $('.i-check').each(function(i, item) {
        changeItem(item, select)
    })
    if (select) {
        showExportHint()
    } else {
        hideExportHint()
    }
}

function changeItem(item, select) {
    var obj = $(item)
        , id = obj.val()
        , oldSelect = obj.prop('checked')
    if (oldSelect != select) {
        obj.prop('checked', select)
        updateId(id, select);
    }
}

function updateId(id, select) {
    if (select) {
        ids.push(id)
    } else {
        var idx = ids.indexOf(id)
        if (idx != -1) {
            ids.splice(idx, 1)
        }
    }

    if (ids.length == 0) {
        exportBtn.prop('disabled', true)
    } else {
        exportBtn.prop('disabled', false)
    }

    console.log(ids.length)
}

function onItemCheck(item) {
    var currentState = allBtn.prop('checked')
        , notCheckAll = hasUnchecked()
    if (!notCheckAll && currentState == false) {
        allBtn.prop('checked', true)
        showExportHint()
    } else if (notCheckAll && currentState == true) {
        allBtn.prop('checked', false)
        hideExportHint()
    }
    var obj = $(item)
        , id = obj.val()
        , select = obj.prop('checked')
    updateId(id ,select)
}

$(document).delegate('#all-check', 'change', function(e) {
    onAllCheck(this)
})
$(document).delegate('.i-check', 'change', function(e) {
    onItemCheck(this)
})
$(document).on('pjax:complete', function() {
    onReload()
})

var exportByQueryHint = 'All suppliers on this page have been selected. <a href="javascript:void(0)">Select all suppliers that match this search.</a>'
var exportBySelectHint = 'All suppliers in this search have been selected. <a href="javascript:void(0)">Clear selection</a>'

function showExportHint() {
    var hint = $('#export-hint')
    if (exportByQuery) {
        hint.html(exportBySelectHint)
    } else {
        hint.html(exportByQueryHint)
    }
}

function hideExportHint() {
    var hint = $('#export-hint')
    hint.html('')
    exportByQuery = false
}

function toggleExportByQuery() {
    exportByQuery = !exportByQuery
    showExportHint()
}

$('#export-hint').delegate('a', 'click', function(e) {
    toggleExportByQuery();
})

exportBtn.on('click', function(e) {
    if (exportByQuery) {
        var query = window.location.search
        form.attr('action', actionUrl + query)
        idsInput.val(null);
    } else {
        form.attr('action', actionUrl)
        idsInput.val(ids.join(','))
    }
    form.submit()
})

JS;
$this->registerJs($script);
?>
<div class="supplier-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::beginForm(Url::to('/site/export'), 'post', [
            'id' => 'export_form',
            'target' => '_blank'
        ]) ?>
        <?= Html::hiddenInput('ids') ?>
        <?= Html::button('Export Suppliers', [
            'id' => 'export-selected',
            'class' => 'btn btn-primary',
            'disabled' => true,
        ]) ?>
        <span id='export-hint'></span>
        <?= Html::endForm() ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'format' => 'raw',
                'attribute' => '',
                'header' => "<input type='checkbox' id='all-check'",
                'value' => function ($data) {
                    return "<input type='checkbox' class='i-check' value={$data['id']}>";
                },
            ],
            'id',
            'name',
            'code',
            [
                'attribute' => 'status',
                'filter' => [
                    'ok' => 'OK',
                    'hold' => 'Hold',
                ],
                'filterOptions' => [
                    'prompt' => 'ALL',
                    'class' => 'form-control',
                    'id' => null,
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>