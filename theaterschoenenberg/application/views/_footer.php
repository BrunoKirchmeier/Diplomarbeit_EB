  <!-- Von Content Main -->
  </div>

  <!-- Jquery Libary laden -->
  <script type="text/javascript"
          src="<?= base_url()?>node_modules/jquery/dist/jquery.min.js">
  </script>

  <!-- Bootstrap Libary laden -->
  <script type="text/javascript"
          src="<?= base_url()?>node_modules/bootstrap/dist/js/bootstrap.min.js">
  </script>

  <!-- Aside Menü für Desktop und Mobile laden -->
  <script type="text/javascript"
          src="<?= base_url()?>assets/js/menue.js">
  </script>

  <!-- drawsvg Libary laden (zum dynamischen erstellen von svg Pfaden) -->
  <script type="text/javascript"
          src="<?= base_url()?>node_modules/drawsvg/src/jquery.drawsvg.js">
  </script>

  <!-- Individuelle Javascript Files aus den Controllern -->
  <?php foreach($this->mSeitenJs as $s_js){?>
  <script type="text/javascript"
          src="<?= base_url()?>assets/js/<?php echo $s_js?>.js">
  </script>
      <?php }?>
  </body>
</html>
