<?php
require_once('../classes/database.php');
$con = new database();


$bookcount = $con->countBooks();
$copycount = $con->countCopies();
$loancount = $con->countLoans();
$overduecount = $con->countOverdueItems();
$allLoans = $con->viewLoans();









?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Dashboard — Library</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">

  <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="admin-dashboard.php">Library Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navAdmin">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="navAdmin" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto gap-lg-1">
        <li class="nav-item"><a class="nav-link active" href="admin-dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="books.php">Books</a></li>
        <li class="nav-item"><a class="nav-link" href="borrowers.php">Borrowers</a></li>
        <li class="nav-item"><a class="nav-link active" href="authors-genres.php">Authors &amp; Genres</a></li>
        <li class="nav-item"><a class="nav-link" href="checkout.php">Checkout</a></li>
        <li class="nav-item"><a class="nav-link" href="return.php">Return</a></li>
        <li class="nav-item"><a class="nav-link" href="catalog.php">Catalog</a></li>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <span class="badge badge-soft">Role: ADMIN</span>
        <a class="btn btn-sm btn-outline-secondary" href="login.html">Logout</a>
      </div>
    </div>
  </div>
</nav>

<!-- ANNOY ? -->
<main class="container py-4">
  <div class="row g-3">
    <div class="col-12 col-lg-8">
      <div class="card p-4">
        <h5 class="mb-1">Quick Overview</h5>
        <p class="small-muted mb-4">These are placeholder values—connect to PHP later.</p>

        <div class="row g-3">
          <div class="col-6 col-md-3">
            <div class="border rounded p-3 bg-white">
              <div class="small-muted">Total Books</div>

              <div class="fs-4 fw-semibold"><?php echo $bookcount; ?></div>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="border rounded p-3 bg-white">
              <div class="small-muted">Total Copies</div>
              <div class="fs-4 fw-semibold"><?php echo $copycount; ?></div>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="border rounded p-3 bg-white">
              <div class="small-muted">Open Loans</div>
              <div class="fs-4 fw-semibold"><?php echo $loancount; ?></div>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="border rounded p-3 bg-white">
              <div class="small-muted">Overdue Items</div>
              
              <div class="fs-4 fw-semibold"><?php echo $overduecount; ?></div>
            </div>
          </div>
        </div>

        <hr class="my-4">

        <h6 class="mb-2">Recent Loans (with Processor)</h6>
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead class="table-light">
              <tr>
                <th>Loan ID</th>
                <th>Borrower</th>
                <th>Status</th>
                <th>Loan Date</th>
                <th>Processed By (User)</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($allLoans as $loan) {
                
                 if ($loan['loan_status'] == 'OPEN') {
                  $class = 'text-bg-warning';
                } else {
                  $class = 'text-bg-success';
                }
                ?>
               
              <tr>
                <td><?php echo $loan['loan_id'] ?></td>
                <td><?php echo $loan['borrower_name'] ?></td>
              
 
                <td><span class="badge <?php echo $class; ?>"><?php echo $loan['loan_status'] ?></span></td>
 
                <td><?php echo $loan['loan_date'] ?></td>
                
                <td><?php echo $loan['username'] ?></td>
              </tr>
 
            <?php } ?>
              
              <!-- <tr>
                <td>1002</td>
                <td>Maria Santos</td>
                <td><span class="badge text-bg-success">CLOSED</span></td>
                <td>2025-12-12</td>
                <td>admin.library@samplemail.com</td>
              </tr> -->
             
 
 
              
            </tbody>
          </table>
        </div>

      
    </div>

    <div class="col-12 col-lg-4">
      <div class="card p-4">
        <h6 class="mb-3">Admin Shortcuts</h6>
        <div class="d-grid gap-2">
          <a class="btn btn-primary" href="checkout.html">Process Checkout</a>
          <a class="btn btn-outline-primary" href="return.html">Process Return</a>
          <a class="btn btn-outline-secondary" href="books.html">Manage Books</a>
          <a class="btn btn-outline-secondary" href="borrowers.html">Manage Borrowers</a>
        </div>
        <hr class="my-4">
        <div class="small-muted">
          Reminder: every checkout must record <b>processed_by_user_id</b> (ADMIN).
        </div>
      </div>
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>