<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <button data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar" type="button">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="/asterisk" class="brand">Asterisk</a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <?php if(auth_is_logged()){ ?>
                    <li class="">
                        <a href="/asterisk_content">Content</a>
                    </li>
                    <!--
                    <li class="">
                        <a href="/asterisk_comment">Comments</a>
                    </li>
                    <li class="">
                        <a href="/asterisk_sales_order">Sales Orders</a>
                    </li>
                    -->
                    <?php if(auth_is_super_admin()){ ?>
                    <li class="dropdown ">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li ><a href="/asterisk_admin_user">Admin Users</a></li>
                            <li ><a href="/asterisk_fieldset">Fieldsets</a></li>
                            <li ><a href="/asterisk_content_type">Content Type</a></li>
                        </ul>
                    </li>
                    <?php } ?>                    
                    <li class="">
                        <a href="/asterisk/logout">Logout</a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>