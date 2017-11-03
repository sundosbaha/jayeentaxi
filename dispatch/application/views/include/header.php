<style>
.navbar-inverse .navbar-nav > .active > a
{
	background:#23374a;
}
</style>
<nav class="navbar navbar-inverse" style="background:#e03129;border-color:white;">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" style="color:white;" href="#">Taxi Appz</a>
    </div>
    <ul class="nav navbar-nav">
      <li <?php if($this->router->fetch_class() == 'Booking' && $this->router->fetch_method() == 'booking') { ?> class="active" <?php } ?>><a href="<?php echo  $base; ?>Booking/booking" style="color:white;"><span class="glyphicon glyphicon-map-marker"></span>Tracking</a></li>
      <li <?php if($this->router->fetch_class() == 'Booking' && $this->router->fetch_method() == 'booking_details') { ?> class="active" <?php } ?>><a href="<?php echo  $base; ?>Booking/booking_details" style="color:white;"><span class="glyphicon glyphicon-book"></span> Bookings</a></li>
       <li <?php if($this->router->fetch_class() == 'Booking' && $this->router->fetch_method() == 'schedule_details') { ?> class="active" <?php } ?>><a href="<?php echo  $base; ?>Booking/schedule_details" style="color:white;"><span class="glyphicon glyphicon-book"></span> Schedules</a></li>
   
      <li <?php if($this->router->fetch_class() == 'Users' && $this->router->fetch_method() == 'index') { ?> class="active" <?php } ?>><a href="<?php echo  $base; ?>Users" style="color:white;"><span class="glyphicon glyphicon-education"  ></span> Users</a></li>
    </ul>
     <ul class="nav navbar-nav navbar-right">
     
      <li><a href="#"><span class="glyphicon glyphicon-user" style="color:white;"></span> <?php echo ucwords($this->session->userdata('name')); ?></a></li>
      <li><a href="<?php echo  $base; ?>Login/logout" style="color:white;"><span class="glyphicon glyphicon-log-in"></span> Sign out</a></li>
     
    
    </ul>
  </div>
</nav>