<!DOCTYPE html>
<html>
<head>
<title>Feedback Admin Panel</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>
  <?php
    include('header.php');
  ?>

    <div class="content">
			<div class="head-title">
				<div class="left">
					<h2>Food Details</h2>
					<ul class="breadcrumb">
						<li>
							<p>Home</p>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Food Detail</Dd></a>
						</li>
					</ul>
				</div>
			</div>
        <div class="table-data">
				<div class="order">
					<div class="head">
						<h3>Food Details</h3>
						<button class="view">View All</button>
					</div>
					<table>
						<thead>
							<tr>
                                <th>Food Name</th>
                                <th>Food Type</th>
                                <th>Category</th>
                                <th>Quantity</th>
                                <th>Date & Time</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>