<div class="page-header">
	<h1>Newsletter</h1>
</div>
    
<form action="index.php?amethod=newsletter" method="post">
	
	<label for="subject">Betreff</label>
	<input type="text" class="input-xlarge" name="subject" id="subject" placeholder="Betreff" required>
	
	<label for="content">Inhalt</label>
	<textarea name="newsletter"></textarea>
	<script type="text/javascript">
		CKEDITOR.replace( 'newsletter' );
	</script>
	
	<div class="form-actions">
		<input type="submit" value="Newsletter absenden" class="btn btn-success">
		<a href="index.php?admin=newsletter" class="btn">Abbrechen</a>
	</div>
</form>