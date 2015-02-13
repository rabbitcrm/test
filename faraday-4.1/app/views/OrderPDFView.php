<!DOCTYPE html>
<html lang="en" class="no-js">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>RabbitCRM<?=($this->pageTitle ? ' | '.ucfirst($this->pageTitle) : '')?> :: </title>
    <meta name="description" content="<?=$this->pageDesc;?>">
    <meta name="author" content="Siva Durgarao">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Template Styles -->
    <link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/css/pdf-styles.css">
  </head>
  
  <body>
    <h3>
      Sales Order
    </h3>

    <table class="table" cellspacing="0">
      <tbody>
        <tr>
          <th width="50%">
            <br><br>
            <a href="<?=$organization->website?>"><img src="<?=$_SESSION['bcz_user']->org_logo?$_SESSION['bcz_user']->org_logo:($_SESSION['bcz_org_logo']?$_SESSION['bcz_org_logo']:(base_url().'assets/img/logo.jpg'))?>" width="200" height="50" border="0" title="Skyzon" alt="" /></a>
            <p>
              <?=$organization->name?><br>
              <?=$organization->address?><br>
              <?=$organization->city?>, <?=$organization->state?> - <?=$organization->pcode?><br>
              <?=$organization->country?><br><br>
              Phone: <?=$organization->phone?><br>
              Email: <?=$organization->email?><br>
             <?php /*?> Fax: <?=$organization->fax?><br><br><?php */?>
            </p>
          </th>
          <th width="50%" class="text-right">
            <h4>SO No: <?=$order->so_no?></h4>
            <strong>So Date: <?=convertDateTime($order->so_create_date)?></strong><br>
            <strong>Estimated Delivery: <?=convertDateTime($order->estimated_delivery)?></strong>
          </th>
        </tr>
        <tr class="well">
          <td width="50%">
            <br><br>
            <strong>BILLING ADDRESS:</strong><br><br>
            <strong><?=$order->first_name." ".$order->last_name?></strong><br>
            
			 <?=$order->company_name?><br>
            <?=$order->bill_addr?><br>
            <?=$order->bill_city?>, <?=$order->bill_state?><?php if(($order->bill_pcode!=0 )&&($order->bill_pcode!="" )) { ?> - <?=$order->bill_pcode; }?><br>
            <?=$order->bill_country?><br>
            Email: <?=$order->email?><br>
          </td>
          <td width="50%">
            <br><br>
            <strong>SHIPPING ADDRESS:</strong><br><br>
            <strong><?=$order->first_name." ".$order->last_name ?></strong><br>
             <?=$order->company_name?><br>
            <?=$order->ship_addr?><br>
            <?=$order->ship_city?><br>
            <?=$order->ship_state?> <?php if(($order->ship_pcode!=0 )&&($order->ship_pcode!="" )) { ?> - <?=$order->ship_pcode; }?><br>
            <?=$order->ship_country?><br>
            Email: <?=$order->email?><br>
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
        <?php foreach($order->items as $ik => $item) { ?>
        <tr>
        <?php $tax_name='item'.$ik.'_tax_type'; ?>
          <td width="5%"><br><br><?=$ik?></td>
          <td width="36%"><br><br><?=$item['product']->product_name . ' - ' . $item['product']->partno . '<br><font size="-2">' . $item['desc'] . '</font>'?></td>
          <td width="12.5%"><br><br><?=$item['price']?></td>
          <td width="10%"><br><br><?=$item['discount']?></td>
          <td width="7.5%"><br><br><?=$item['qty']?></td>
          <?php if($item['vat']!="0"){ ?>
         
          <td width="10%"><br><br><?=$order->$tax_name?></td>
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
               <?php    setlocale(LC_MONETARY, 'en_IN');
$frieght = money_format('%!i', $order->frieght); 
$install = money_format('%!i', $order->install);
?>
        <tr>
          <td width="66%" style="text-align: left;"><br><br>Currency : <?=$order->so_currency?></td>
          <td width="23%" style="text-align: right;"><br><br><strong>Frieght</strong></td>
          <td width="12.5%" style="text-align: right;"><br><br><?=$frieght?></td>
        </tr>
        <tr>

          <th width="66%" style="text-align: left;"><br><br><?php /*?>VAT No : <?=$organization->TIN?><?php */?></th>
          <th width="23%" style="text-align: right;"><br><br><strong>Installation</strong></th>
          <th width="12.5%" style="text-align: right;"><br><br><?=$install?></th>
        </tr>
        <tr>
          <th width="66%" style="text-align: left;"><br><br><?php /*?>CST : <?=$organization->CST?><?php */?></th>
          <th width="23%" style="text-align: right;"><br><br><strong>TOTAL</strong></th>
          <th width="12.5%" style="text-align: right;"><br><br><strong>      <?php 	$tot_amount=$order->install+$order->frieght+$tot_amount;	   setlocale(LC_MONETARY, 'en_IN');
$tot_amount = money_format('%!i', $tot_amount); ?>
           <?=$tot_amount?></strong></th>
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
            <?=$order->delivery?><br>
            <?=$order->carrier?><br>
            <?=$order->payment?>
          </th>
        </tr>
         <tr><td colspan="3"> <?=$order->terms?></td></tr>
      </tbody>
    </table>

  
</body>
</html>