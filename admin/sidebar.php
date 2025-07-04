<?php
require_once('adminSession.php');
?>
<div class="container">
	    <div class="sidebar">
		    <div class="brand">
			    <i class="fi fi-ss-admin-alt"></i>
			    <p class="name">ADMIN</p>
		    </div>

			<div class="menu">
				<ul>
					<li>
						<a href="admin.php?type=default" class="active">
							<i class='bx bxs-dashboard' ></i>
							<span class="text">Dashboard</span>
						</a>
					</li>

					<li>
						<a href="#">
							<i class="fi fi-ss-users"></i>
							<span class="text">Donors</span>
							<i class='arrow bx bx-chevron-down'></i>	
						</a>
								
						<ul class="sub-menu">
							<li>
								<a href="admin.php?type=create_donor">
									<span class="text">Create</span>
									<i class="sub-fi fi-ss-plus"></i>
								</a>
							</li>
							<li>
								<a href="admin.php?type=donors">
									<span class="text">List</span>
									<i class="sub-fi fi-ss-list"></i>
								</a>
							</li>
						</ul>
					</li>

					<li>
						<a href="#">
							<i class="bx bxs-home"></i>
							<span class="text">NGOs</span>
							<i class='arrow bx bx-chevron-down'></i>	
						</a>
								
						<ul class="sub-menu">
							<li>
								<a href="admin.php?type=create_ngo">
									<span class="text">Create</span>
									<i class="sub-fi fi-ss-plus"></i>
								</a>
							</li>
					
							<li>
								<a href="admin.php?type=ngos">
									<span class="text">List</span>
									<i class="sub-fi fi-ss-list"></i>
								</a>
							</li>
						</ul>
					</li>



					<li>
						<a href="admin.php?type=feedback">
							<i class='fas fa-comments' ></i>
							<span class="text">Feedbacks</span>
						</a>
					</li>

					<li>
						<a href="admin.php?type=pending">
							<i class='fas fa-clock' ></i>
							<span class="text">Pending Donation Requests</span>
						</a>
					</li>
					<li>
						<a href="admin.php?type=assign">
							<i class='fas fa-tasks ngo-icon' ></i>
							<span class="text">Assigned Donation Requests</span>
						</a>
					</li>


					<li>
						<a href="admin.php?type=status">
							<i class='fas fa-chart-line status ngo-icon' ></i>
							<span class="text">Donation Status</span>
						</a>
					</li>
                        <li>
						<a href="AdminLogout.php" class="logout">
							<i class="fi fi-ss-sign-out-alt"></i>
							<span class="text">Logout</span>
						</a>
					</li>
				</ul>
			</div>	
		</div>