    <form enctype="multipart/form-data" action="register.php" method="post">
    	<div class="form-group">
    		<label for="reason">Reason</label>
    		<textarea id="reason" class="form-control" name="reason_text" rows="4"></textarea>
    	</div>
    	<div class="form-group">
    		<label for="reason_file">Attachment</label>
    		<input type="file" id="reason_file" name="reason_file">
    	</div>
    	<div class="form-group">
    		<label for="amount">Score</label>
    		<input type="number" id="amount" class="form-control" name="amount">
    	</div>
    	<input type="submit" class="btn btn-primary" name="register" value="Register">
    </form>