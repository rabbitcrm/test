<div class="panel addr-info">
  <div class="panel-body">
    <div class="h2 text-left text-primary m-b-small">Address Info</div>
    <div class="h3 col-sm-6 text-left m-t-mini m-b-mini">Address</div><div class="h4 col-sm-6 text-left m-t-mini m-b"><?=($address?$address:$this->noDataChar)?></div>
    <div class="h3 col-sm-6 text-left m-b-mini">City</div><div class="h4 col-sm-6 text-left m-b"><?=($city?$city:$this->noDataChar)?></div>
    <div class="h3 col-sm-6 text-left m-b-mini">State</div><div class="h4 col-sm-6 text-left m-b"><?=($state?$state:$this->noDataChar)?></div>
    <div class="h3 col-sm-6 text-left m-b-mini">Zip code</div><div class="h4 col-sm-6 text-left m-b"><?=($zip?$zip:$this->noDataChar)?></div>
    <div class="h3 col-sm-6 text-left">Country</div><div class="h4 col-sm-6 text-left"><?=($country?$country:$this->noDataChar)?></div>
  </div>
</div>