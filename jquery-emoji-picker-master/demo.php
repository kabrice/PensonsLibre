<!doctype html>
<html>
<head>
  <title>jQuery Emoji Picker Demo</title>
  <link rel="stylesheet" type="text/css" href="css/jquery.emojipicker.css">
  <link rel="stylesheet" type="text/css" href="css/jquery.emojipicker.a.css">

</head>
<body>
  <!-- &#x1F335; -->


  <textarea placeholder='Entre ton commentaire :)' id='libelle'  name='libelle' class='input-custom-size col-md-10'   required autofocus ></textarea>

  <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
  <script type="text/javascript" src="js/jquery.emojipicker.js"></script>
  <script type="text/javascript" src="js/jquery.emojis.js"></script>
  <script >
    $(document).ready(function(e) {


      $('.input-custom-size').emojiPicker({
        width: '200px',
        height: '300px'
      });

    });
  </script>


</body>
</html>
