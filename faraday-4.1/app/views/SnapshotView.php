<!-- breakdown chart -->
<section class="panel">
  <header class="panel-heading h5">Deals Pipeline</header>
  <div class="panel-body">
    <?php 
      $count = 0; 
      foreach ($percentages as $stage => $percentage) {
        switch ($stage) {
          case 'Presentation':
            $bgClass = 'bg-primary';
            break;
          case 'Proposal-Quote':
            $bgClass = 'bg-success';
            break;
          case 'Discussion-Meeting':
            $bgClass = 'bg-info';
            break;
          case 'Negotiation':
            $bgClass = 'bg-warning';
            break;
          case 'Purchasing':
            $bgClass = 'bg-danger';
            break;
          case 'Pending Payment':
            $bgClass = 'bg-default';
            break;
          
          default:
            $bgClass = '';
            break;
        }

        if ($bgClass) {
    ?>

        <div class="media <?=$count?'m-t-none':''?>">
          <div class="pull-right media-small col-sm-3 no-padder <?=($count==0)?'m-t-mini':''?>"><?=$stage?></div>
          <div class="progress bg-light <?=($count==0)?'m-t-mini':''?> <?=($count==5)?'m-b-mini':''?>">
            <div class="progress-bar <?=$bgClass?>" style="width: <?=$percentage?>%"><?=$amounts[$stage]?></div>
          </div>
        </div>

    <?php $count++; } } ?>
  </div>
</section>
<!-- breakdown chart end -->