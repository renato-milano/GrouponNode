{*
* 2007-2023 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2023 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="panel" style="display: flex;padding:50px !important;flex-direction: column;align-items: center;">
	<h3><i class="icon icon-credit-card"></i> {l s='GrouponNode' mod='grouponnode'}</h3>
	<p>
		<strong>{l s='Welcome to GrouponNode' mod='grouponnode'}</strong><br />
		{l s='Use this module to connect your E-commerce to Groupon!' mod='grouponnode'}<br />
	</p>
	<p>Made with &#10084; by <a target="_blank" href="https://github.com/renato-milano">Renato Milano</a> </p>
	<br />
	<p>
		{l s='Import your orders now, click below!' mod='grouponnode'}
	</p>
	<button type="submit" value="1" id="ImportButton" name="submitGrouponNodeModule" class="btn btn-default pull-right">
							<i class="process-icon-save"></i> Import Orders
						</button>
</div>
<!-- Modal Avviso Importato -->
<div class="modal fade" id="ModalBind" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div style="background-color:rgb(0 173 23);justify-content: space-around" class="modal-header">
        <h5 class="modal-title" style="color:white;font-size:x-large;-webkit-text-stroke: thin;" id="exampleModalLabel">ORDERS IMPORTED</h5>
      </div>
      <div class="modal-body">
        <p id="ModalInfoBody"></p>
        <div style="border-top: 0px;" class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Chiudi</button>
      </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
{literal}
//var mt_ajax = prestashop.urls.base_url + 
//'modules/grouponnode/controllers/ImportOrders.php';
$(document).ready(function() {
  $('#ImportButton').click(function () {
	console.log("Click")
    $.ajax({
        url:'https://www.iglm.store/modules/grouponnode/controllers/ImportOrders.php',
        data: 'method=ImportOrdini',
        method:'GET',
        success:function(data) {
            $("#ModalInfoBody").html(data);
			$("#ModalBind").modal('show');
        },
    });
  });
})
{/literal}
</script>