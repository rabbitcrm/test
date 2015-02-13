<div class="row">
    <div class="col-sm-6">
      <form class="form-horizontal" method="post" action="<?=base_url()?>settings/formatfiles" onsubmit="var formatTable = $('select[name=format_table]').val(); if(!formatTable) return false;">
        <div class="text-primary h4 m-t-mini m-b-mini">Format Files</div>

        <div class="export-status-msg alert ta-left hide"></div>

        <div class="form-group">
          <div class="col-lg-9 m-t-small">
            <div class="btn-group col-xs-12 no-padder">
              <select name="format_table" class="select2-option">
                <option value="">-- Choose Table --</option>
                <?php foreach($tables as $tableName => $tableLabel) { ?>
                  <option value="<?=$tableName?>"><?=$tableLabel?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-lg-3">
            <button type="submit" class="btn btn-primary" style="margin-top: 7px;">Download</button>
          </div>
        </div>
      </form>

      <form class="form-horizontal m-t" method="post" action="<?=base_url()?>settings/import" accept-charset="utf-8" enctype="multipart/form-data" onsubmit="">
        <div class="text-primary h4 m-t-large m-b-mini">Import Data</div>

        <div class="import-status-msg alert ta-left hide"></div>

        <div class="form-group m-t-large">
          <label class="col-lg-3 control-label" style="text-align: left;">Table</label>
          <div class="col-lg-9">
            <div class="btn-group col-xs-12 no-padder">
              <select name="import_table" class="select2-option" data-required="true">
                <option value=""><?=$this->chooseOption?></option>
                <?php foreach($tables as $tableName => $tableLabel) { ?>
                  <option value="<?=$tableName?>"><?=$tableLabel?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label" style="text-align: left;">File</label>
          <div class="col-lg-9 media m-t-none">
            <div class="media-body">
              <input type="file" name="import_file" title="Choose file" class="btn btn-sm btn-info m-b-small" data-required="true"><br>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="col-lg-12"><button id="bcz_import" type="submit" class="btn btn-primary">Import</button></div>
        </div>
      </form>
    </div>

    <div class="col-sm-6">
      <form class="form-horizontal" method="post" action="<?=base_url()?>settings/export" onsubmit="var expTable = $('select[name=export_table]').val(); if(!expTable) return false;">
        <div class="text-primary h4 m-t-mini m-b-mini">Export Data</div>

        <div class="export-status-msg alert ta-left hide"></div>

        <div class="form-group">
          <div class="col-lg-9 m-t-small">
            <div class="btn-group col-xs-12 no-padder">
              <select name="export_table" class="select2-option">
                <option value="">-- Choose Table --</option>
                <?php foreach($tables as $tableName => $tableLabel) { ?>
                  <option value="<?=$tableName?>"><?=$tableLabel?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-lg-3">
            <button id="bcz_export" type="submit" class="btn btn-primary" style="margin-top: 7px;">Export</button>
          </div>
        </div>
      </form>
    </div>
</div>