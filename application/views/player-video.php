<html>
<head>
    <title>YPlayer</title>
    <style>body{margin:0!important;overflow:none}</style>
</head>
<body>
    <div class='video'>
    <script src="https://use.fontawesome.com/20603b964f.js"></script>
    <script type="text/javascript" src="https://content.jwplatform.com/libraries/LJ361JYj.js"></script>
	<script type="text/javascript">jwplayer.key = '9dOyFG96QFb9AWbR+FhhislXHfV1gIhrkaxLYfLydfiYyC0s';</script>
	<div id="playerJW"></div><script type="text/javascript">
			jwplayer("playerJW").setup({
        		image: "<?= $thumb ?>",
        		width: '100%',
        		autostart: false,
        		playlist: [{
                    "sources": <?= $data ?>
                }]
			})
	</script>
  </div>
  </body>
</html>