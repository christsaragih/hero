<?=$this->head_assets->stylesheet('css/dataset.css');?>

<?=$this->load->view(branded_view('cp/header'));?>
<h1>Profile: <?=$user['username'];?></h1>
<h2 class="cat user">Member Data</h2>
<ul class="data">
	<li><span class="tag">Username</span> <?=$user['username'];?></li>
	<li><span class="tag">Full Name</span> <?=$user['last_name'];?>, <?=$user['first_name'];?></li>
	<li><span class="tag">Email</span> <?=$user['email'];?></li>
	<li><span class="tag">Member Groups</span><?=$user['show_usergroups'];?></li>
	<? if (is_array($custom_fields)) { foreach ($custom_fields as $field) { ?>
		<li><span class="tag"><?=$field['friendly_name'];?></span> <? if ($field['type'] == 'checkbox') { ?><? if (!empty($user[$field['name']])) { ?>Yes<? } else { ?>No<? } } elseif (@is_array(unserialize($user[$field['name']]))) { ?><?=implode(', ', unserialize($user[$field['name']]));?><? } else { ?><?=$user[$field['name']];?><? } ?></li>
	<? } } ?>
	<li><span class="tag">&nbsp;</span> <a href="<?=site_url('admincp/users/edit/' . $user['id']);?>">edit profile</a></li>
</ul>

<h2 class="cat user">Subscriptions</h2>
<Br />
	<table class="dataset" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<td>ID #</td>
				<td>Plan Name</td>
				<td>Recurring Charge</td>
				<td>Next Charge Date</td>
				<td>Start Date</td>
				<td>End Date</td>
				<td>Status</td>
				<td>Options</td>
			</tr>
		</thead>
		<tbody>
<? if (is_array($subscriptions)) { ?>
	<? foreach($subscriptions as $subscription) { ?>
		<tr class="<? if ($subscription['active'] == TRUE) { ?>active<? } else { ?>inactive<? } ?>">
			<td><?=$subscription['id'];?></td>
			<td><?=$subscription['plan']['name'];?></td>
			<td><?=setting('currency_symbol');?><?=$subscription['amount'];?></td>
			<td><? if ($subscription['next_charge_date']) { ?><?=date('d-M-Y',strtotime($subscription['next_charge_date']));?><? } ?></td>
			<td><?=date('d-M-Y',strtotime($subscription['start_date']));?></td>
			<td><?=date('d-M-Y',strtotime($subscription['end_date']));?></td>
			<td><? if ($subscription['active'] == TRUE) { ?>active<? } else { ?>inactive<? } ?></td>
			<td>
				<form method="post" action="<?=site_url('admincp/users/profile_actions/');?>" />
				<input type="hidden" name="subscription_id" value="<?=$subscription['id'];?>" />
				<select name="action">
					<option value="0" selected="selected"></option>
					<? if ($subscription['active'] == TRUE) { ?>
					<option value="cancel">cancel subscription</option>
						<? if (!empty($subscription['card_last_four'])) { ?>
						<option value="update_cc">update credit card</option
						<? } ?>
					<? if ((float)$subscription['amount'] != 0) { ?><option value="change_price">change recurring amount</option><? } ?>
					<option value="change_plan">change plan</option>
					<? } ?>
					<option value="view_all">report: view all related charges</option>
				</select>
				&nbsp;
				<input type="submit" class="button" name="go_action" value="Go" />
				</form>
			</td>
		</tr>
	<? } ?>
<? } else { ?>
	<tr>
		<td colspan="8">This member does not have any active or expired subscriptions.</td>
	</tr>
<? } ?>
	</tbody>
</table>
<?=$this->load->view(branded_view('cp/footer'));?>