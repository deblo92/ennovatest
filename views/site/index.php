<?php

/** @var yii\web\View $this */

$this->title = 'Ennova Homepage';
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use app\models\Pratica;
use app\models\Cliente;
?>
<div class="site-index">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-title">Ricerca pratica</div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin(['id' => 'search-pratiche-form']); ?>
                        <?= $form->field(new Pratica(), 'id_pratica')->textInput(['autofocus' => true, 'value' => $request ? $request['Pratica']['id_pratica'] : ""]) ?>

                        <?= $form->field(new Cliente(), 'codicefiscale')->textInput(['value' => $request ? $request['Cliente']['codicefiscale'] : ""]) ?>

                        <div class="form-group">
                            <?= Html::submitButton('Ricerca', ['class' => 'btn btn-primary btn-sm', 'name' => 'search-pratiche-button']) ?>
                        </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <button class="btn btn-primary btn-sm" id="exportTableRows">Esporta risultati</button>

            <?php $form = ActiveForm::begin(['id' => 'export-database']); ?>
                <input type="hidden" name="export-database" value="1">
                <button class="btn btn-sm btn-success">Esporta Database</button>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered mt-3">
            <thead>
            <tr>
                <th>Id</th>
                <th>Id pratica</th>
                <th>Stato</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($pratiche as $key => $pratica){ ?>
                <tr>
                    <td> <?=$pratica->id?>  </td>
                    <td> <?=$pratica->id_pratica?>  </td>
                    <td> <?=$pratica->stato_pratica?>  </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

    </div>
</div>

<script>
    function downloadCSVFile(csv, filename) {
        var csv_file, download_link;

        csv_file = new Blob([csv], {type: "text/csv"});

        download_link = document.createElement("a");

        download_link.download = filename;

        download_link.href = window.URL.createObjectURL(csv_file);

        download_link.style.display = "none";

        document.body.appendChild(download_link);

        download_link.click();
    }
    function htmlToCSV(html, filename) {
        var data = [];
        var rows = document.querySelectorAll("table tr");

        for (var i = 0; i < rows.length; i++) {
            var row = [], cols = rows[i].querySelectorAll("td, th");

            for (var j = 0; j < cols.length; j++) {
                row.push(cols[j].innerText);
            }

            data.push(row.join(","));
        }

        downloadCSVFile(data.join("\n"), filename);
    }
    document.getElementById("exportTableRows").addEventListener("click", function () {
        var html = document.querySelector("table").outerHTML;
        htmlToCSV(html, "Export.csv");
    });
</script>
