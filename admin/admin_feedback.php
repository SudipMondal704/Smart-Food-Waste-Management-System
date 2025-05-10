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
    <main>
      <div class="head-title">
			  <div class="left">
					<h2>Feedbacks</h2>
        </div>
      </div>

      <div class="table-data">
				<div class="order">
					<div class="head">
						<h3>Feedback Details</h3>
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
    </main>      

</body>
</html>