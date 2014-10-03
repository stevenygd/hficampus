<link rel="stylesheet" href="/css/chatroom/channel.css"/>
<div class="container">
    <div class="row">
        
        <div id="chatroom-list" class="todo mrm col-sm-4 col-md-4 col-lg-4 col-xs-12">
            <ul>
                <li class>
                    <div class="todo-icon"><i class="icon-group"></i></div>
                    <div class="todo-value">
                        <h4 class="todo-name">
                            <strong>Steven</strong>
                            <span class="navbar-unread">1</span>
                            Yang
                        </h4>
                            I am testing!
                    </div>
                </li>
                <li class="todo-done">
                    <div class="todo-icon fui-user"></div>
                    <div class="todo-value">
                        <h4 class="todo-name">
                            <strong>Steven</strong>
                            <span class="navbar-unread green"></span>
                            Yang
                        </h4>
                            I am testing!
                    </div>
                </li>
                 <li class>
                    <div class="todo-icon fui-user"></div>
                    <div class="todo-value">
                        <h4 class="todo-name">
                            <strong>Steven</strong>
                            <span class="navbar-unread white"></span>
                            Yang
                        </h4>
                            I am testing!
                    </div>
                </li>
                <li class>
                    <div class="todo-icon"><i class="icon-group"></i></div>
                    <div class="todo-value">
                        <h4 class="todo-name">
                            <strong>Steven</strong>
                            <span class="navbar-unread">2</span>
                            Yang
                        </h4>
                            I am testing!
                    </div>
                </li>
				                <li class>
                    <div class="todo-icon"><i class="icon-group"></i></div>
                    <div class="todo-value">
                        <h4 class="todo-name">
                            <strong>Steven</strong>
                            <span class="navbar-unread white"></span>
                            Yang
                        </h4>
                            I am testing!
                    </div>
                </li>
                <li>
                    <div class="delete-button"><i class="icon-minus"></i></div>
                    <div class="create-button"><i class="icon-plus"></i></div>
                </li>
            </ul>
        </div>
        
        <div id="chatroom-msg" class="col-sm-4 col-md-4 col-lg-4 hidden-xs">
        	<div>
                <ul class="pager">
                    <li>
                        <a href="#" style="float: left;"><i class="icon-angle-left"></i>Back</a>
                    </li>
                    <li>
                    	<a href="#" class="user-name"><i class="icon-user"></i>Steven Yang</a>
                    </li>
                    <li>
                    	<a href="#" style="float: right;"><i class="icon-info-sign"></i>Info</a>
                    </li>
                </ul>
            </div>
            <div id="conver">
                <div class="receive">
                    <div class="tooltip fade right in">
                        <div class="tooltip-arrow"></div>
                        <div class="tooltip-inner">I am testing.</div>
                    </div>
                </div>
                <div class="send">
                    <a href="#" class="button button-rounded button-flat-caution"><i class="icon-trash"></i>Delete</a>
                    <div class="tooltip fade left in">
                        <div class="tooltip-arrow"></div>
                        <div class="tooltip-inner">keep typing.keep typing.keep typing.keep typing.keep typing.</div>
                    </div>
                </div>
                <div class="receive">
                    <div class="tooltip fade right in">
                        <div class="tooltip-arrow"></div>
                        <div class="tooltip-inner">keep typing.keep typing.keep typing.keep typing.keep typing.</div>
                    </div>
                </div>
                <div class="send">
                    <div class="tooltip fade left in">
                        <div class="tooltip-arrow"></div>
                        <div class="tooltip-inner">I am testing.</div>
                    </div>
                    <div class="delete-button">
                    </div>
                </div>
            </div>
            <div id="send-msg">
            	<textarea class="form-control" rows="3"></textarea>
                <a href="#" class="button glow button-rounded button-flat"><i class="icon-comments-alt"></i>send</a>
            </div>
        </div>
    </div>
    <div class="row">
            
            <div id="create-chatroom" class="col-sm-4 col-md-4 col-lg-4 hidden-xs">
                  <form class="form-horizontal login-form">
                    <fieldset>
                      <div id="legend" class="">
                        <legend class="">New Chatroom</legend>
                      </div>
                    <div class="control-group">
                
                          <!-- Text input-->
                          <label class="control-label" for="input01">Name</label>
                          <div class="controls">
                            <input type="text" placeholder="name" class="input-xlarge form-control">
                            <p class="help-block">Chatroom's name</p>
                          </div>
                        </div>
                
                    <div class="control-group">
                
                          <!-- Text input-->
                          <label class="control-label" for="input01">Topic</label>
                          <div class="controls">
                            <input type="text" placeholder="topic" class="input-xlarge form-control">
                            <p class="help-block">Topic of conversations</p>
                          </div>
                        </div>
                        
                    <div class="control-group">
                
                          <!-- Text input-->
                          <label class="control-label" for="input01">Type</label>
                          <div class="controls">
                          	<div class="toggle toggle-off">
                              <label class="toggle-radio" for="toggleOption2"><i class="icon-group"></i>Group</label>
                              <input type="radio" name="toggleOptions" id="toggleOption1" value="group" checked="checked">
                              <label class="toggle-radio" for="toggleOption1"><i class="icon-user"></i>Single</label>
                              <input type="radio" name="toggleOptions" id="toggleOption2" value="single">
                            </div>
                            <p class="help-block">Single or Group</p>
                          </div>
                        </div>
                	
                    <div class="control-group">
                
                          <!-- Text input-->
                          <label class="control-label" for="input01">Capacity</label>
                          <div class="controls">
                          	<input type="text" placeholder="capacity" class="input-xlarge form-control">
                            <p class="help-block">Number of people allowed (3~100)</p>
                          </div>
                        </div>

                    <div class="control-group">
                          <label class="control-label"></label>
                
                          <!-- Button -->
                          <div class="controls">
                            <button class="btn btn-inverse">Create</button>
                          </div>
                        </div>
                
                    </fieldset>
                  </form>
            </div>
            
            <div id="search" class="todo mrm col-sm-4 col-md-4 col-lg-4 hidden-xs">
            	<div class="todo-search">
                	<input type="search" class="todo-search-field form-control" placeholder="Search"/>
                </div>
                <ul>
                	<li class>
                        <div class="todo-icon"><i class="icon-group"></i></div>
                        <div class="todo-value">
                            <h4 class="todo-name">
                                <strong>Steven</strong>
                                Yang
                            </h4>
                                Student
                        </div>
                    </li>
                                    	<li class>
                        <div class="todo-icon"><i class="icon-group"></i></div>
                        <div class="todo-value">
                            <h4 class="todo-name">
                                <strong>Steven</strong>
                                Yang
                            </h4>
                                Student
                        </div>
                    </li>
                	<li class>
                        <div class="todo-icon"><i class="icon-group"></i></div>
                        <div class="todo-value">
                            <h4 class="todo-name">
                                <strong>Steven</strong>
                                Yang
                            </h4>
                                Student
                        </div>
                    </li>
                	<li class>
                        <div class="todo-icon"><i class="icon-group"></i></div>
                        <div class="todo-value">
                            <h4 class="todo-name">
                                <strong>Steven</strong>
                                Yang
                            </h4>
                                Student
                        </div>
                    </li>
                	<li class>
                        <div class="todo-icon"><i class="icon-group"></i></div>
                        <div class="todo-value">
                            <h4 class="todo-name">
                                <strong>Steven</strong>
                                Yang
                            </h4>
                                Student
                        </div>
                    </li>
                </ul>
            </div>
    </div>
</div>