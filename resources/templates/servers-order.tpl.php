<section class="cadre">
	<h1><?php echo Utils::t('Servers order'); ?></h1>
	<form method="post" action="?p=<?php echo USER_PAGE; ?>">
		<div class="content">
			<ul id="sortableServersList">
			<?php
				$showServerList = null;
				
				// Liste des serveurs
				if( is_array($serverList) && count($serverList) > 0 ){
					foreach($serverList as $serverName => $serverData){
						$showServerList .= '<li class="ui-state-default">'
							.'<div class="ui-icon ui-icon-arrowthick-2-n-s"></div>'
							.'<div class="order-server-name">'.$serverName.'</div>'
							.'<div class="order-server-addr-port">'.$serverData['address'].' / '.$serverData['port'].'</div>'
						.'</li>';
					}
				}
				
				echo $showServerList;
			?>
			</ul>
		</div>
		<div class="fright save">
			<input class="button light" type="button" id="reset" name="reset" value="<?php echo Utils::t('Reset'); ?>" />
			<input class="button light" type="submit" id="save" name="save" value="<?php echo Utils::t('Save'); ?>" />
			<input type="hidden" id="list" name="list" value="" />
		</div>
	</form>
</section>