<<<<<<< HEAD
<link rel='stylesheet' type='text/css' href='/css/calendar/index.css'  />
<link rel='stylesheet' type='text/css' href='/fullcalendar/fullcalendar.css' />
<link href='/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
<script type='text/javascript' src='/js/jquery.js'></script>
<script type='text/javascript' src='/js/jquery-ui-1.10.3.custom.min.js'></script>
<script type='text/javascript' src='/fullcalendar/fullcalendar.js'></script>
<script type='text/javascript' src='/fullcalendar/fullcalendar.min.js'></script>    
<script type='text/javascript' src='/js/calendar/index.js'></script>
<script>
    var site_url="<?php echo site_url();?>";
    var base_url="<?php echo base_url();?>";
    var cid     ="<?php echo $gid;?>";
</script>
<div id="add_events" class="fc-button fc-state-default fc-corner-left fc-corner-right">
    <p>add event</p>
</div>
<div id="add_form" class="form">
        <p>To</p>
        <div class="content">
            <select class="group">
                <?php foreach ($own as $i=>$item):?>
                    <option value="<?php echo $item['id'];?>"><?php echo $item['name'];?></option>
                <?php endforeach;?>
                <option value="0">Self</option>
            </select>
        </div>
        <p>Title</p>
        <div class="content">
            <input type="text" class="name" />
        </div>
        <p>Start Time</p>
        <div class="content">
            <select class="smonth">
                <?php for ($i=0;$i<12;$i++):?>
                    <option value="<?php echo date('M', strtotime('+'.$i.' month'));?>"><?php echo date('M', strtotime('+'.$i.' month'));?></option>
                <?php endfor;?>
            </select>
            <select class="sday">
                <?php for ($i=1;$i<32;$i++):?>
                    <option value="<?php echo $i;?>"><?php echo $i;?></option>
                <?php endfor;?>
            </select>
            <select class="syear">
                <option value="<?php echo date('Y', time());?>"><?php echo date('Y', time());?></option>
                <option value="<?php echo date('Y', strtotime('+1 year'));?>"><?php echo date('Y', strtotime('+1 year'));?></option>
            </select>
        </div>
        <p>End Time</p>
        <div class="content">
            <select class="emonth">
                <?php for ($i=0;$i<12;$i++):?>
                    <option value="<?php echo date('M', strtotime('+'.$i.' month'));?>"><?php echo date('M', strtotime('+'.$i.' month'));?></option>
                <?php endfor;?>
            </select>
            <select class="eday">
                <?php for ($i=1;$i<32;$i++):?>
                    <option value="<?php echo $i;?>"><?php echo $i;?></option>
                <?php endfor;?>
            </select>
            <select class="eyear">
                <option value="<?php echo date('Y', time());?>"><?php echo date('Y', time());?></option>
                <option value="<?php echo date('Y', strtotime('+1 year'));?>"><?php echo date('Y', strtotime('+1 year'));?></option>
            </select>
        </div>
        <p>Details</p>
        <div class="content">
            <textarea class="description"></textarea>
        </div>
        <p id="submit">Submit</p>
        <p id="close1" class="close">Close</p>
</div>
<div id="calendar"></div>
<div id="event" class="form"></div>
=======
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>calendar</title>
    <link rel='stylesheet' type='text/css' href='/css/calendar/index.css'  />
    <link rel='stylesheet' type='text/css' href='/fullcalendar/fullcalendar.css' />
	<link href='/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
    <script type='text/javascript' src='/js/jquery.js'></script>
    <script type='text/javascript' src='/js/jquery-ui-1.10.3.custom.min.js'></script>
    <script type='text/javascript' src='/fullcalendar/fullcalendar.js'></script>
    <script type='text/javascript' src='/fullcalendar/fullcalendar.min.js'></script>    
    <script type='text/javascript' src='/js/calendar/index.js'></script>
	<script>
	var site_url="<?php echo site_url();?>";
	var base_url="<?php echo base_url();?>";
	var cid     ="<?php echo $gid;?>";
    </script>
</head>
<body>
    <div id="add_events" class="fc-button fc-state-default fc-corner-left fc-corner-right">
    	<p>add event</p>
    </div>
    <div id="add_form" class="form">
            <p>To</p>
            <div class="content">
                <select class="group">
                    <?php foreach ($own as $i=>$item):?>
                        <option value="<?php echo $item['id'];?>"><?php echo $item['name'];?></option>
                    <?php endforeach;?>
                    <option value="0">Self</option>
                </select>
            </div>
            <p>Title</p>
            <div class="content">
            	<input type="text" class="name" />
            </div>
            <p>Start Time</p>
            <div class="content">
                <select class="smonth">
                    <?php for ($i=0;$i<12;$i++):?>
                        <option value="<?php echo date('M', strtotime('+'.$i.' month'));?>"><?php echo date('M', strtotime('+'.$i.' month'));?></option>
                    <?php endfor;?>
                </select>
                <select class="sday">
                    <?php for ($i=1;$i<32;$i++):?>
                        <option value="<?php echo $i;?>"><?php echo $i;?></option>
                    <?php endfor;?>
                </select>
                <select class="syear">
                    <option value="<?php echo date('Y', time());?>"><?php echo date('Y', time());?></option>
                    <option value="<?php echo date('Y', strtotime('+1 year'));?>"><?php echo date('Y', strtotime('+1 year'));?></option>
                </select>
            </div>
            <p>End Time</p>
            <div class="content">
                <select class="emonth">
                    <?php for ($i=0;$i<12;$i++):?>
                        <option value="<?php echo date('M', strtotime('+'.$i.' month'));?>"><?php echo date('M', strtotime('+'.$i.' month'));?></option>
                    <?php endfor;?>
                </select>
                <select class="eday">
                    <?php for ($i=1;$i<32;$i++):?>
                        <option value="<?php echo $i;?>"><?php echo $i;?></option>
                    <?php endfor;?>
                </select>
                <select class="eyear">
                    <option value="<?php echo date('Y', time());?>"><?php echo date('Y', time());?></option>
                    <option value="<?php echo date('Y', strtotime('+1 year'));?>"><?php echo date('Y', strtotime('+1 year'));?></option>
                </select>
            </div>
            <p>Details</p>
            <div class="content">
            	<textarea class="description"></textarea>
            </div>
            <p id="submit">Submit</p>
            <p id="close1" class="close">Close</p>
    </div>
    <div id="calendar"></div>
    <div id="event" class="form"></div>
</body>
</html>
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
