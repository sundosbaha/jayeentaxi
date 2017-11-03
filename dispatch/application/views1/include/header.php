<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Dispacho</a>
    </div>
    <ul class="nav navbar-nav">
      <li <?php if($this->router->fetch_class() == 'Booking' && $this->router->fetch_method() == 'booking') { ?> class="active" <?php } ?>><a href="<?php echo  $base; ?>Booking/booking"><span class="glyphicon glyphicon-map-marker"></span>Sequimiento
</a></li>
      <li <?php if($this->router->fetch_class() == 'Booking' && $this->router->fetch_method() == 'booking_details') { ?> class="active" <?php } ?>><a href="<?php echo  $base; ?>Booking/booking_details"><span class="glyphicon glyphicon-book"></span> Reserva
</a></li>
       <li <?php if($this->router->fetch_class() == 'Booking' && $this->router->fetch_method() == 'schedule_details') { ?> class="active" <?php } ?>><a href="<?php echo  $base; ?>Booking/schedule_details"><span class="glyphicon glyphicon-book"></span> Schedules</a></li>
   
      <li <?php if($this->router->fetch_class() == 'Users' && $this->router->fetch_method() == 'index') { ?> class="active" <?php } ?>><a href="<?php echo  $base; ?>Users"><span class="glyphicon glyphicon-education"></span> Usuarios
</a></li>
      
       <li <?php if($this->router->fetch_class() == 'Settings' && $this->router->fetch_method() == 'index') { ?> class="active" <?php } ?>><a href="<?php echo  $base; ?>Settings"><span class="glyphicon glyphicon-education"></span> Settings</a></li>
      
    </ul>
     <ul class="nav navbar-nav navbar-right">
     
      <li><a href="#"><span class="glyphicon glyphicon-user"></span> <?php echo ucwords($this->session->userdata('name')); ?></a></li>
      <li><a href="<?php echo  $base; ?>Login/logout"><span class="glyphicon glyphicon-log-in"></span> Cerrar sesión
</a></li>
     
    
    </ul>
  </div>
</nav>