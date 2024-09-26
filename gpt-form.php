<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>ChatGPT Form</title>
  </head>
  <body>
    <form action="gpt-run.php" method="post">
      <div>
        <div><label for="prompt">input ask.</label></div>
        <textarea id="prompt" name="prompt" cols="40" rows="3" required><?php echo $_POST['prompt']; ?></textarea>
      </div>
      <div><?php echo $_POST['response']; ?></div>
      <div>
        <input type="submit" value="Submit">
      </div>
    </form>
    <script>
      document.getElementById("prompt").select();
    //   document.getElementById("prompt").focus();
    </script>
  </body>
</html>
