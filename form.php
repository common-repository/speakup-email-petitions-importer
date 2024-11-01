<form action="#" method="post" enctype="multipart/form-data" id="speakupimportform">
	<p>
		<label>
			<?_e('Select your CSV file','speakupimport')?>
			<input type="file" name="csv" />
			<legend>
				 
				<?_e('File format:','speakupimport')?>
				<table>
					<tr>
						<td colspan="16">							
							<?_e('Tab separated values, 16 not empty columns (from A to P)','speakupimport')?>
						</td>
					</tr>
					<tr>
						<td><?php echo __( 'First Name', 'dk_speakup' ); ?></td>
						<td><?php echo __( 'Last Name', 'dk_speakup' );?></td>
						<td><?php echo __( 'Email Address', 'dk_speakup' );?></td>
						<td><?php echo __( 'Street Address', 'dk_speakup' );?></td>
						<td><?php echo __( 'City', 'dk_speakup' );?></td>
						<td><?php echo __( 'State', 'dk_speakup' );?></td>
						<td><?php echo __( 'Post Code', 'dk_speakup' );?></td>
						<td><?php echo __( 'Country', 'dk_speakup' );?></td>
						<td><?php echo __( 'Date Signed', 'dk_speakup' );?></td>
						<td><?php echo __( 'Confirmed', 'dk_speakup' );?></td>
						<td><?php echo __( 'Petition Title', 'dk_speakup' );?></td>
						<td><?php echo __( 'Petition ID', 'dk_speakup' );?></td>
						<td><?php echo __( 'Email Opt-in', 'dk_speakup' );?></td>
						<td><?php echo __( 'Custom Message', 'dk_speakup' );?></td>
						<td><?php echo __( 'Language', 'dk_speakup' );?></td>
					</tr>
				</table>
			</legend>
		</label>
	</p>
	<p>
		<label>
			<input type="checkbox" name="notfirst" checked="checked"/>
			<?_e('First line contents columns title','speakupimport')?>
		</label>
	</p>
	<p>
		<label>
			<input type="checkbox" name="erase" />
			<?_e('Remove signatures before importing','speakupimport')?>
		</label>
		<?_e('(cannot be undone)','speakupimport')?>
	</p>
	<p>
		<input type="submit" value="<?_e('Import','speakupimport')?>"/>
	</p>
</form>
