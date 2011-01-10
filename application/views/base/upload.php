        <!-- The data encoding type, enctype, MUST be specified as below -->
        <form enctype="multipart/form-data" action="<?php echo $action ?>" method="POST">
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size ;?>" />
    <!-- Name of input element determines name in $_FILES array -->
    Send this file: <input name="book" type="file" />
    <input type="submit" value="Send File" />
</form>
