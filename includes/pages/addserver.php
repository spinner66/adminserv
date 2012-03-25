<?php
	AdminServUI::getHeader();
?>
<section class="cadre">
	<h1>Ajouter un serveur</h1>
	<form method="post" action="?p=<?php echo USER_PAGE; ?>">
		<div class="content">
			<fieldset>
				<legend>Informations de connexion</legend>
				<table>
					<tr>
						<td class="key"><label for="">Nom du serveur</label></td>
						<td class="value">
							<input class="text width3" type="text" name="" id="" value="" />
						</td>
						<td class="help">
							Nom du serveur sans couleur
						</td>
					</tr>
					<tr>
						<td class="key"><label for="">Adresse</label></td>
						<td class="value">
							<input class="text width3" type="text" name="" id="" value="" />
						</td>
						<td class="help">
							Adresse IP ou nom de domaine
						</td>
					</tr>
					<tr>
						<td class="key"><label for="">Port XMLRPC</label></td>
						<td class="value">
							<input class="text width3" type="text" name="" id="" value="" />
						</td>
						<td class="help">
							Port permettant le controle à distance
						</td>
					</tr>
				</table>
			</fieldset>
			
			<fieldset>
				<legend>Informations optionnelles</legend>
				<table>
					<tr>
						<td class="key"><label for="">MatchSettings du serveur</label></td>
						<td class="value">
							<input class="text width3" type="text" name="" id="" value="" />
						</td>
						<td class="help">
							
						</td>
					</tr>
					<tr>
						<td class="key"><label for="">Niveau "SuperAdmin"</label></td>
						<td class="value">
							<input class="text width3" type="text" name="" id="" value="" />
						</td>
						<td class="help">
							
						</td>
					</tr>
					<tr>
						<td class="key"><label for="">Niveau "Admin"</label></td>
						<td class="value">
							<input class="text width3" type="text" name="" id="" value="" />
						</td>
						<td class="help">
							
						</td>
					</tr>
					<tr>
						<td class="key"><label for="">Niveau "User"</label></td>
						<td class="value">
							<input class="text width3" type="text" name="" id="" value="" />
						</td>
						<td class="help">
							
						</td>
					</tr>
				</table>
			</fieldset>
		</div>
		
		<div class="fright save">
			<input class="button light" type="submit" name="saveserver" id="saveserver" value="Enregistrer" />
		</div>
	</form>
</section>
<?php
	AdminServUI::getFooter();
?>