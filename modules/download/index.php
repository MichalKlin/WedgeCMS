<?php
if (!isset($_GET[download])){
	$result = $this->baza->select("*","mod_download","","ORDER BY mdow_id DESC");
	$size_result = $this->baza->size_result($result);
	if ($size_result>0){
		?>
		<center>
		<table border="0" cellpadding="0" cellspacing="0" width="98%" >
		<tr>
		<td class="art_group_title" style="color: #660405; font-size: 12px;">Pliki do pobrania</td>
		</tr>
		<tr>
		<td>
		
		<table border="0" width="98%" cellpadding="0" cellspacing="0">
		<?
		for ($i=0; $i<$size_result; $i++){
			$lista_plików = $this->baza->row($result);
			?>
			<tr>
				<td style="text-align: center;">
					<table border="0" width="100%" class="download_table" cellpadding="0" cellspacing="0">
						<tr>
							<td style="text-align: left;">
								<strong>Plik:</strong> 
								<a href="download/<?=$lista_plików[mdow_file]?>" style="font-size:14px; font-weight : bold;">
									<?=$lista_plików[mdow_file]?>
								</a>
							</td>
							<td style="text-align: right;">
								<strong>Dodano:</strong>
								<?=$lista_plików[mdow_created]?>
							</td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: justify;">
								<strong>Opis:</strong>
								<?=$lista_plików[mdow_description]?>
							</td>
						</tr>
						<tr>
							<td style="text-align: left;">
								<strong>Wielkość pliku:</strong>
								<?=$lista_plików[mdow_size]?>
							</td>
							<td style="text-align: right;">
								<strong>Liczba pobrań:</strong>
								<?=$lista_plików[mdow_counter]?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="text-align: center;">
					<hr color="yellow" height="1" style="height: 1px;" />
				</td>
			</tr>
			<?
		}
		?>
		</table>

		</td>
		</tr>
		</table>
		</center>
		<?
	}
	else{
		?><p class="message">Obecnie nie ma żadnych plików do pobrania.</p><?
	}
}

?>
