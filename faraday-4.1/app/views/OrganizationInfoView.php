<div class="row">
  <?php $this->load->view('MessagesView', array(messages => $messages)); ?>

  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>settings/saveOrg" accept-charset="utf-8" enctype="multipart/form-data">
    <div class="col-sm-6">
      <div class="form-group">
        <label class="col-lg-3 control-label">Name<?=$this->mandatoryFieldIndicator?></label>
        <div class="col-lg-9">
          <input type="text" name="name" data-required="true" class="form-control" value="<?=$organization->name?>">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label">Address<?=$this->mandatoryFieldIndicator?></label>
        <div class="col-lg-9">
          <input type="text" name="address" data-required="true" class="form-control" value="<?=$organization->address?>">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label">City<?=$this->mandatoryFieldIndicator?></label>
        <div class="col-lg-9">
          <input type="text" name="city" data-required="true" class="form-control" value="<?=$organization->city?>">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label">State<?=$this->mandatoryFieldIndicator?></label>
        <div class="col-lg-9">
          <input type="text" name="state" data-required="true" class="form-control" value="<?=$organization->state?>">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label">Pincode<?=$this->mandatoryFieldIndicator?></label>
        <div class="col-lg-9">
          <input type="text" name="pcode" data-required="true" class="form-control" value="<?=$organization->pcode?>" data-type="number">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label">Country</label>
        <div class="col-lg-9">
          <div class="btn-group col-xs-12 no-padder">
           
            <select name="country" class="select2-option" >
              <option value=""><?=$this->chooseOption?></option>
              <?php $orgCountry = $organization->country ? $organization->country : 'India'; foreach($fields as $country) { if ($country->country) { ?>
                <option value="<?=$country->country?>" <? if ($country->country == $orgCountry) { ?>selected="selected"<?php } ?>><?=$country->country?></option>
              <?php } } ?>
            </select>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label">Currency</label>
        <div class="col-lg-9">
          <div class="btn-group col-xs-12 no-padder" id="currency_div">
       
               <?php if($currency_freeze['currency_freeze']==1){ ?>
              
               <input type="text" name="set_currency" data-required="true" class="form-control" value="<?=$currency_freeze['currency']?>" readonly='readonly'>
               <input type="hidden" id="hidden_crrency" value="<?=$currency_freeze['currency']?>">
               
               <?php } else { ?>
            <select name="set_currency" id="set_currency" class="select2-option"   >
              <option value=""><?=$this->chooseOption?></option>
              <?php foreach($fields as $currency) { if ($currency->currency) { ?>
                <option value="<?=$currency->currency?>" <? if ($currency->currency == $organization->currency) { ?>selected="selected"<?php } ?>><?=$currency->currency?></option>
              <?php } } ?>
            </select>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6">
      <div class="form-group">
        <label class="col-lg-3 control-label">Logo</label>
        <div class="col-lg-9 media">
          <div class="bg-light pull-left text-center media-large thumb-large">
            <?php if ($this->user->org_logo || $organization->logo_path) { ?>
              <img src="<?=$this->user->org_logo?$this->user->org_logo:$organization->logo_path?>">
            <?php } else { ?>
              <i class="icon-user inline icon-light icon-3x m-t-large m-b-large"></i>
            <?php } ?>
          </div>
          <div class="media-body">
            <input type="file" name="org_logo" title="Choose file" class="btn btn-sm btn-info m-b-small"><br>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label">Phone<?=$this->mandatoryFieldIndicator?></label>
        <div class="col-lg-9">
          <input type="text" name="phone" data-required="true" class="form-control" value="<?=$organization->phone?>" data-type="number">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label">Fax</label>
        <div class="col-lg-9">
          <input type="text" name="fax" class="form-control" value="<?=$organization->fax?>" data-type="number">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label">Web</label>
        <div class="col-lg-9">
          <input type="text" name="website" class="form-control" value="<?=$organization->website?>">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label">Email<?=$this->mandatoryFieldIndicator?></label>
        <div class="col-lg-9">
          <input type="text" name="email" data-required="true" class="form-control" value="<?=$organization->email?>">
        </div>
      </div>
      <div class="form-group m-b-small">
        <label class="col-lg-3 control-label">Date Format</label>
        <div class="col-lg-9">
          <div class="btn-group col-xs-12 no-padder">
            <select name="set_date_format" class="select2-option">
              <option value=""><?=$this->chooseOption?></option>
              <option value="m/d/Y" <?php if ($organization->date_format == 'm/d/Y') { ?>selected="selected"<?php } ?>>MM/DD/YYYY</option>
              <option value="d/m/Y" <?php if ($organization->date_format == 'd/m/Y') { ?>selected="selected"<?php } ?>>DD/MM/YYYY</option>
              <option value="d M Y" <?php if ($organization->date_format == 'd M Y') { ?>selected="selected"<?php } ?>>DD MMM YYYY</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-8">
      <input type="hidden" name="org_id" value="<?=$organization->id?$organization->id:$organization->sno?>">
      <button type="submit" class="btn btn-primary m-l" id="save_org">Save</button>
    </div>
  </form>
</div>