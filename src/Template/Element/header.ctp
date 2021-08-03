<?php
// src/Template/Element/header.ctp
?>
<!-- Header Navigation -->
<div class="row">                   
    <div class="col-md-12">
                
        <nav class="navbar brb" role="navigation">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-reorder"></span>                            
                        </button>                                                
                        <a class="navbar-brand" href="<?php $this->ERPfunction->action_link('Dashboard');?>">
						<?php echo $this->Html->image('/img/logo/ERP_Banner.png',['style'=>"height: 43px;margin-top: -3px;"]); ?>
						</a>                                                                                     
                    </div>
                    <div class="collapse navbar-collapse navbar-ex1-collapse">                                     
                        <ul class="nav navbar-nav">
                            <li>
                                <a href="<?php echo $this->request->base;?>/dashboard">
                                    <span class="icon-dashboard"></span> dashboard
                                </a>
                            </li>                            
                            <li class="dropdown">
								<!-- class="dropdown-toggle" data-toggle="dropdown" -->
                                <a href="<?php  echo $this->request->base;?>/contract"><span class="icon-key"></span>Contract Admin</a>
                            </li>
                            <li class="dropdown">
                                <a href="<?php echo $this->request->base;?>/assets"><span class="icon-th-large"></span>Assets</a>
                            </li>                          
                            <li><a href="<?php echo $this->request->base;?>/purchase/"><span class="icon-shopping-cart"></span>Purchase</a></li>
                            <li class="dropdown">
                                <a href="<?php echo $this->request->base;?>/accounts/"><span class="icon-inr"></span>Account</a>
                            </li> 
							<li class="dropdown">
                                <a href="<?php echo $this->request->base;?>/humanresource"><span class="icon-group"></span>Human Resource</a>
                            </li> 
							<li class="dropdown">
                                <a href="<?php echo $this->request->base;?>/inventory"><span class="icon-truck"></span>Inventory</a>
                            </li>  
                        </ul>						
                        <ul class="nav navbar-nav" style="float:right;border-right:medium none;">
							<li class="dropdown">
                                <a href="<?php echo $this->ERPfunction->action_link('Login','logout');?>">
									<span class="icon-signout"></span> Logout
								</a>
							</li>  
						</ul>                                            
                    </div>
        </nav>               

    </div>            
 </div>