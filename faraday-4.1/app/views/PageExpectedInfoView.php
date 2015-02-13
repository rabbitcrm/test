<div class="panel addr-info">
  <div class="panel-body">
    <div class="h2 text-left text-primary m-b-small">Expected & Current Info</div>
    <div class="h3 col-sm-6 text-left m-t-mini m-b-mini">Exp.Response</div><div class="h4 col-sm-6 text-left m-t-mini m-b"><?=($ExpectedResponse?$ExpectedResponse:$this->noDataChar)?></div>
    <div class="h3 col-sm-6 text-left m-b-mini">Exp.Response Count</div><div class="h4 col-sm-6 text-left m-b"><?=($ExpectedResponseCount?$ExpectedResponseCount:$this->noDataChar)?></div>
    <div class="h3 col-sm-6 text-left m-b-mini">Current Response</div><div class="h4 col-sm-6 text-left m-b"><?=($CurrentResponse?$CurrentResponse:$this->noDataChar)?></div>
    <div class="h3 col-sm-6 text-left m-b-mini">Exp.Sales Count</div><div class="h4 col-sm-6 text-left m-b"><?=($ExpectedSalesCount?$ExpectedSalesCount:$this->noDataChar)?></div>
    <div class="h3 col-sm-6 text-left">Exp.ROI</div><div class="h4 col-sm-6 text-left"><?=($ExpectedROI?$ExpectedROI:$this->noDataChar)?></div>
  </div>
</div>