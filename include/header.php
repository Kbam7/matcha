<?php

    echo
    '

        <nav class="navbar navbar-default navbar-fixed-top">
          <div class="container-fluid">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#global-navbar" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="/matcha/index.php">Matcha</a>
            </div>

            <div class="collapse navbar-collapse" id="global-navbar">
                <ul class="nav navbar-nav">
    ';
    if (!isset($_SESSION['logged_on_user'])) {
        echo
        '
                    <li>
                        <a href="/matcha/views/create.php">Register</a>
                    </li>
                    <li>
                        <a href="/matcha/views/reset_password.php">Forgot Password</a>
                    </li>
                </ul> <!-- /Left-Nav -->

                <form class="navbar-form navbar-right animate_label" id="loginForm" method="post" action="/matcha/php/login.php">
                    <div class="form-input form-group">
                        <label class="sr-only" for="login">Username:</label>
                        <input type="text" class="form-control" name="login" id="user-login" placeholder="Username" maxlength="24" title="Username" required="true" /> <!--  pattern="^[a-zA-Z]\B[a-zA-Z0-9]{4,18}[a-zA-Z0-9]\b$"   pattern="(?!.*[\.\-\_]{2,})^[a-zA-Z0-9\.\-\_]{3,24}$"   -->
                    </div>
                    <div class="form-input form-group">
                        <label class="sr-only" for="passwd">Password:</label>
                        <input type="password" class="form-control" name="passwd" id="user-passwd" placeholder="Password" title="Password" required="true" /> <!--     pattern="^(((?=.*[a-z])(?=.*[A-Z]))|((?=.*[a-z])(?=.*[0-9]))|((?=.*[A-Z])(?=.*[0-9])))(?=.{6,})"    -->
                    </div>
                    <div class="form-input form-group">
                        <input type="submit" name="submit" value="OK" />
                    </div>
                </form>
        ';
    } elseif (isset($_SESSION['logged_on_user'])) {
        echo
        '
                </ul> <!-- /Left-Nav -->

                <form class="navbar-form navbar-left">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search">
                    </div>
                    <button type="submit" class="btn btn-default">Submit</button>
                </form>

                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="/matcha/views/dashboard.php">Dashboard</a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        Chat <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/matcha/views/view_profile.php">Chatroom</a></li>
                            <li><a href="/matcha/views/profile_settings.php">Inbox <span class="badge">42</span></a></li>
                            <li role="separator" class="divider"></li>
                            <li>If i make a private chat, dispay it here</li>
                        </ul>
                    </li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        Profile <span class="caret"></span>
                      </a>
                      <ul class="dropdown-menu">
                        <li><a href="/matcha/views/view_profile.php">View Profile</a></li>
                        <li><a href="/matcha/views/profile_settings.php">Edit Profile</a></li>
                      </ul>
                    </li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        Settings <span class="caret"></span>
                      </a>
                      <ul class="dropdown-menu">
                        <li><a href="/matcha/views/account.php">Account Settings</a></li>
                        <li><a href="/matcha/views/account.php#reportUser">Report a User</a></li>
                        <li role="separator" class="divider"></li>
                        <li class="logout-btn">
                            <a href="/matcha/php/logout.php" title="Logout of Account">LOGOUT</a>
                        </li>

                      </ul>
                    </li>
                </ul>  <!-- /Right-Nav -->

        ';
    }
    echo
    '
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>

    ';
