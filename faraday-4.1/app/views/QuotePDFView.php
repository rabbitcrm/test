<?php $tot_amount==0;?>
<!DOCTYPE html>
<html lang="en" class="no-js">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title> RabbitCRM<?=($this->pageTitle ? ' | '.ucfirst($this->pageTitle) : '')?> </title>
    <meta name="description" content="<?=$this->pageDesc;?>">
    <meta name="author" content="Siva Durgarao">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Template Styles -->
    <link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/css/pdf-styles.css">
  </head>
  
  <body>
    <h3>
      Quotation
    </h3>

    <table class="table" cellspacing="0">
      <tbody>
        <tr>
          <th width="50%">
            <br><br>
            <a href="<?php if($organizations->website){$organizations->website;}else { echo 'http://rabbitcrm.com/'; }?>" target="_blank"><img src="<?=$_SESSION['bcz_user']->org_logo?$_SESSION['bcz_user']->org_logo:($_SESSION['bcz_org_logo']?$_SESSION['bcz_org_logo']:(base_url().'assets/img/logo.jpg'))?>" width="200" height="50" border="0" title="Skyzon" alt="" /></a>
            <p>
              <?=$organizations->name?><br>
              <?=$organizations->address?><br>
              <?=$organizations->city?>, <?=$organizations->state?> - <?=$organizations->pcode?><br>
              <?=strtoupper($organizations->country);?><br>
              Phone: <?=$organizations->phone?><br>
              Email: <?=$organizations->email?><br>
            <?php /*?>  Fax: <?=$organizations->fax?><br><br><?php */?>
            </p>
          </th>
          <th width="50%" class="text-right">
            <h4>Quote No: <?=$quote->quote_no?></h4>
            <strong>Quote Date: <?=convertDateTime($quote->quote_create_date)?></strong><br>
            <strong>Valid Till: <?=convertDateTime($quote->valid_till)?></strong>
          </th>
        </tr>
        <tr class="well">
          <td width="50%">
            <br><br>
            <strong>BILLING ADDRESS:</strong><br><br>
            <strong><?=$quote->first_name." ".$quote->last_name?></strong><br>
			 &nbsp;<?=$quote->company_name?><br>
            <?=$quote->bill_addr?><br>
            <?=$quote->bill_city?>, <?=$quote->bill_state?><?php if($quote->ship_pcode!="0") { ?> - <?=$quote->bill_pcode?><?php } ?><br>
            <?=$quote->bill_country?><br>
            Email: <?=$quote->email?><br>
          </td>
          <td width="50%">
            <br><br>
            <strong>SHIPPING ADDRESS:</strong><br><br>
            <strong><?=$quote->first_name." ".$quote->last_name?></strong><br>
            &nbsp;<?=$quote->company_name?><br>
            &nbsp;<?=$quote->ship_addr?><br>
            &nbsp;<?=$quote->ship_city?>, 
            &nbsp;<?=$quote->ship_state?> <?php if($quote->ship_pcode!="0") { ?>- <?=$quote->ship_pcode?><?php } ?><br>
            &nbsp;<?=$quote->ship_country?><br>
           &nbsp;Email: <?=$quote->email?><br>
          </td>
        </tr>
      </tbody>
    </table>

    <table cellspacing="0" class="table text-center with-border">
      <thead>
        <tr>
          <th width="5%"><br><br><br><br>No</th>
          <th width="36%"><br><br><br><br>SUMMARY</th>
          <th width="12.5%"><br><br><br><br>PRICE</th>
          <th width="10%"><br><br><br><br>DISCOUNT</th>
          <th width="7.5%"><br><br><br><br>QTY</th>
         <th width="10%"><br><br><br><br>Tax Type</th>
          <th width="8%"><br><br><br><br>Tax %</th>
         
          <th width="12.5%"><br><br><br><br>AMOUNT</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($quote->items as $ik => $item) { ?>
        <tr>
         <?php $tax_name='item'.$ik.'_tax_type'; ?>
          <td width="5%"><br><br><?=$ik?></td>
          <td width="36%"><br><br><?=$item['product']->product_name . ' - ' . $item['product']->partno . '<br><font size="-2">' . $item['desc'] . '</font>'?></td>
          <td width="12.5%"><br><br><?=$item['price']?></td>
          <td width="10%"><br><br><?=$item['discount']?></td>
          <td width="7.5%"><br><br><?=$item['qty']?></td>

          <?php if($item['vat']!="0"){ ?>
          
          <td width="10%"><br><br><?=$quote->$tax_name?></td>
          <td width="8%"><br><br><?=$item['vat']?></td>
          <?php
		  }
		  else
		  {
			  ?>
          <td width="10%"><br><br>-</td>
          <td width="8%"><br><br>-</td>
              <?php 
		  }


		 $amount= str_replace(",","",$item['amount']);
		   $amount = round ($amount); 


		   $tot_amount=$amount+$tot_amount;
		   setlocale(LC_MONETARY, 'en_IN');
$amount = money_format('%!i', $amount);

		    ?>
          <td width="12.5%" style="text-align:right;"><br><br><?=$amount?></td>
        </tr>
        <?php } ?>
        <tr>
          <td width="66%" style="text-align: left;"><br><br>Currency : <?=$quote->quote_currency?></td>
          <td width="23%" style="text-align: right;"><br><br><strong>Frieght</strong></td>
          <td width="12.5%" style="text-align:right;"><br><br><?=money_format('%!i',$quote->frieght);?></td>
        </tr>
        <?php if($quote->install!="") { ?>
        <tr>
          <th width="66%"  style="text-align: left;"><br><br><?php /*?>VAT No : <?=$organization->TIN?><?php */?></th>
          <th width="23%" style="text-align: right;"><br><br><strong>Installation</strong></th>
          <th width="12.5%" style="text-align: right;"><br><br><?=money_format('%!i',$quote->install)?></th>
        </tr>
        <?php } ?>
        <tr>
          <th width="66%" style="text-align: left;"><br><br><?php /*?>CST : <?=$organization->CST?><?php */?></th>
          <th width="23%" style="text-align: right;"><br><br><strong>TOTAL</strong></th>
          <th width="12.5%" style="text-align:right;"><br><br><strong>
          <?php 		   setlocale(LC_MONETARY, 'en_IN');
		  $tot_amount=$quote->frieght+$tot_amount+$quote->install;
$tot_amount = money_format('%!i', $tot_amount); ?>
           &nbsp;&nbsp; <?=$tot_amount?></strong></th>
        </tr>
      </tbody>
    </table>

    <br><?php /*?><br><br>
    <h5>
      Bank Account Details
    </h5>
    <table class="table" cellspacing="0">
      <tbody>
        <tr>
          <th width="30%">
            Bank Name<br>
            Account Name<br>
            Account No<br>
            IFSC Code<br>
            Bank Address<br>
          </th>
          <th width="70%">
           <?=$organization->bank_name?> <br>
            <?=$organization->bank_name?><br>
            <?=$organization->account_name?><br>
            <?=$organization->account_no?><br>
            <?=$organization->IFSC_code?><br>
            <?=$organization->bank_addr?>
          </th>
        </tr>
      </tbody>
    </table>
<?php */?>
    <h5>
      Terms and Conditions
    </h5>
    <table class="table" cellspacing="0">
      <tbody>
        <tr>
          <th width="30%">
            Delivery<br>
            Carrier<br>
            Payment<br><br>
          </th>
          <th width="70%">
            <?=$quote->delivery?><br>
            <?=$quote->carrier?><br>
            <?=$quote->payment?>
          </th>
          
          
        </tr>
        <tr><td  colspan="3"> <?=$quote->terms?></td></tr>
      </tbody>
    </table>
    
</body>
</html>