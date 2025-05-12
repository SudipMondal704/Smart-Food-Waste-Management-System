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
					<h2>Feedbacks</h2>
					<ul class="breadcrumb">
						<li>
							<p>Home</p>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Feedback</a>
						</li>
					</ul>
				</div>
			</div>
        <div class="table-data">
				<div class="order">
					<div class="head">
						<h3>Feedbacks</h3>
						<button class="view">View All</button>
					</div>
					<table>
						<thead>
							<tr>
                <th>Username</th>
                <th>Email</th>
                <th>Phone No.</th>
                <th>Feedback</th>
                <th>Date</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>